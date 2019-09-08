<template>
    <div>
        <h2>Send a new e-mail</h2>

        <form v-on:submit.prevent="addDelivery" class="mb-3">
            <div class="form-group">
                <label for="fromInput" class="col-form-label">*From</label>
                <input id="fromInput" type="email" class="form-control" placeholder="e.g. example@example.com"
                       v-model="mail.from.email" required>
            </div>
            <div class="form-group">
                <label for="replyToInput" class="col-form-label">Reply To</label>
                <input id="replyToInput" type="email" class="form-control" placeholder="e.g. example@example.com"
                       v-model="replyToEmail" required>
            </div>
            <div class="form-group">
                <label for="toInput" class="col-form-label">*To(s)</label>
                <ul v-if="mail.to.length">
                    <li v-for="t in mail.to">{{t.email}}
                        <button type="button" class="btn btn-link btn-sm" title="Remove" v-on:click="removeToEmail(t)">
                            <i class="fas fa-times"></i></button>
                    </li>
                </ul>
                <div class="input-group mb-3">
                    <input id="toInput" type="email" class="form-control" placeholder="e.g. example@example.com"
                           v-model="toEmail" v-on:keydown.enter.prevent="addToEmail">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" v-on:click="addToEmail"><i
                            class="fas fa-plus"></i> Add
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="subjectInput" class="col-form-label">*Subject</label>
                <input id="subjectInput" type="text" class="form-control" placeholder="Subject"
                       v-model="mail.subject" required>
            </div>
            <div class="form-group">
                <label for="contentInput" class="col-form-label">*Content</label>
                <tinymce id="contentInput" v-model="content" required :plugins="tinymcePlugins"
                         :other_options="tinymceOptions"></tinymce>
            </div>
            <div class="form-group">
                <label for="attachmentField" class="col-form-label">Attachments</label>
                <ul v-if="mail.attachments.length">
                    <li v-for="att in mail.attachments"><a
                        v-bind:href="'data:'+att.contentType+';base64,'+att.base64Content"
                        v-bind:download="att.filename">{{att.filename}}</a>
                        <button type="button" class="btn btn-link btn-sm" title="Remove"
                                v-on:click="removeAttachment(att)"><i class="fas fa-times"></i></button>
                    </li>
                </ul>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="attachmentField" ref="attachmentField"
                           v-on:change="onChangeFileUpload">
                    <label class="custom-file-label" for="attachmentField">Choose a file</label>
                </div>
            </div>
            <button v-on:click="createMail" type="button"
                    class="btn btn-success btn-block mt-3">Send
            </button>
            <button v-on:click="clearForm" type="button"
                    class="btn btn-danger btn-block mt-1">Cancel
            </button>
        </form>

        <pager v-on:page="fetchDeliveries" :pagination="pagination" v-if="deliveries.length"></pager>

        <div class="card card-body mb-2" v-for="delivery in deliveries" v-bind:key="delivery.id">
            <h4>{{ delivery.subject }}</h4>
            <p>{{ delivery.textContent }}</p>
            <p style="font-size: 70%;">
                <strong>From:</strong> {{ delivery.fromEmail }}<br>
                <strong>Reply To:</strong> {{ delivery.replyToEmail }}<br>
                <strong>To:</strong> {{ delivery.toEmail }}<br>
                <strong>Status:</strong> {{ delivery.status }}<br>
                <strong>Created At:</strong> {{ delivery.createdAt }}<br>
                <strong>Delivery ID:</strong> {{ delivery.id }}<br>
                <strong>Mail ID:</strong> {{ delivery.mailId }}<br>
            </p>
            <hr>
            <div class="btn-group">
                <button class="btn btn-info mb-2" v-bind:disabled="!delivery.hasAttachment"
                        v-on:click="showAttachments(delivery.mailId)">Show attachments
                </button>
                <button class="btn btn-warning mb-2" v-on:click="showStatuses(delivery.id)">Show status history</button>
            </div>
        </div>


        <attachment-modal v-if="attachmentModalOpen" :attachments="attachments"
                          v-on:close="attachmentModalOpen = false"></attachment-modal>
        <history-modal v-if="historyModalOpen" :history="history" v-on:close="historyModalOpen = false"></history-modal>


        <div v-if="historyModalOpen | attachmentModalOpen | waiting" id="overlay"></div>


        <div v-if="waiting" class="spinner-border loading" role="status">
            <span class="sr-only">Loading...</span>
        </div>


    </div>
</template>

<script>
    export default {
        data() {
            return {
                mail: {
                    from: {
                        email: ''
                    },
                    replyTo: '',
                    to: [],
                    subject: '',
                    text: '',
                    html: '',
                    attachments: []
                },
                content: '',
                replyToEmail: '',
                toEmail: '',
                toEmails: [],
                deliveries: [],
                delivery: {
                    id: '',
                    title: '',
                    body: ''
                },
                deliveryId: '',
                pagination: {},
                currentPageUrl: null,
                statusCode: null,
                attachmentModalOpen: false,
                attachments: [],
                historyModalOpen: false,
                history: [],
                waiting: false,
                tinymcePlugins: ['image', 'textpattern', 'code', 'link', 'preview'],
                tinymceOptions: {
                    height: "300",
                    statusbar: false,
                }
            };
        },
        created() {
            // fetch deliveries if any at startup
            this.fetchDeliveries();

            // here we listen websocket for delivery status changes
            Echo.channel('DeliveryChannel')
                .listen('WebsocketDeliveryStatusChangeEvent', (e) => {
                    $.each(this.deliveries, function(key, value) {
                        if(value.id == e.newDeliveryStatus.delivery_id) {
                            value.status = e.newDeliveryStatus.status;
                        }
                    });
                });


        },
        watch: {
            // here we convert html mail content to text content
            content: function () {
                this.mail.html = this.content;
                this.mail.text = this.strip(this.content);
            },

            replyToEmail: function () {
                if (this.replyToEmail.length === 0) {
                    this.mail.replyTo = '';
                } else {
                    this.mail.replyTo = {
                        'email': this.replyToEmail
                    };
                }
            }
        },
        methods: {
            strip(html) {
                let doc = new DOMParser().parseFromString(html, 'text/html');
                return doc.body.textContent || "";
            },
            onChangeFileUpload() {
                let file = this.$refs.attachmentField.files[0];
                if (file) {
                    if (file.size > 1024 * 1024) {
                        alert("File size must be lower than 1MB!")
                    } else {
                        this.getBase64(file)
                    }
                }
                this.$refs.attachmentField.value = '';

            },
            removeAttachment(attachment) {
                let index = this.mail.attachments.indexOf(attachment);
                if (index !== -1) this.mail.attachments.splice(index, 1);
            },
            getBase64(file) {
                let reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = (e => {
                    this.addAttachment(reader.result, file);
                });
                reader.onerror = error => {
                    alert('Error: ', error);
                };
            },
            addAttachment(base64, file) {
                let att = {
                    contentType: file.type,
                    filename: file.name,
                    base64Content: base64.split(',')[1]
                };

                this.mail.attachments.push(att);

            },
            removeToEmail(email) {
                let index = this.mail.to.indexOf(email);
                if (index !== -1) this.mail.to.splice(index, 1);

                index = this.toEmails.indexOf(email.email);
                if (index !== -1) this.toEmails.splice(index, 1);
            },
            addToEmail() {
                if (this.validEmail(this.toEmail)) {
                    if (!this.toEmails.includes(this.toEmail)) {
                        this.mail.to.push({'email': this.toEmail});
                        this.toEmails.push(this.toEmail);
                    }
                    this.toEmail = '';
                } else {
                    alert("Please enter a valid e-mail address!");
                }
            },
            validEmail: email => {
                let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            },
            fetchDeliveries(pageUrl) {
                pageUrl = pageUrl || '/api/deliveries';
                this.currentPageUrl = pageUrl;
                fetch(pageUrl)
                    .then(res => res.json())
                    .then(res => {
                        this.deliveries = res.data;
                        this.makePagination(res.meta, res.links);
                    })
                    .catch(err => console.log(err));
            },
            makePagination(meta, links) {
                this.pagination = {
                    currentPage: meta.current_page,
                    lastPage: meta.last_page,
                    nextPageUrl: links.next,
                    prevPageUrl: links.prev
                };
            },
            checkForm() {
                if (!this.validEmail(this.mail.from.email)) {
                    alert("Please enter a valid 'From' e-mail address!");
                    return false;
                }
                if (this.mail.replyTo && !this.validEmail(this.mail.replyTo.email)) {
                    alert("Please enter a valid 'Reply To' e-mail address!");
                    return false;
                }
                if (this.mail.to.length === 0) {
                    alert("Please enter at least one valid 'To' e-mail address!");
                    return false;
                }
                if (this.mail.subject.length === 0) {
                    alert("Subject must be entered!");
                    return false;
                }
                if (this.content.length === 0) {
                    alert("Content must be entered!");
                    return false;
                }
                return true;

            },
            createMail() {
                if (this.checkForm()) {
                    if (confirm('Are You Sure?')) {
                        this.waiting = true;
                        fetch('api/mail', {
                            method: 'post',
                            body: JSON.stringify(this.mail),
                            headers: {
                                'content-type': 'application/json'
                            }
                        })
                            .then(res => {
                                this.statusCode = res.status;
                                return res.json();
                            })
                            .then(data => {
                                if (this.statusCode === 201) {
                                    alert('Mail sent!');
                                    this.clearForm();
                                    this.fetchDeliveries();
                                } else if (this.statusCode === 500) {
                                    alert("Internal Server Error!");
                                } else {
                                    alert(JSON.stringify(data));
                                }
                                this.statusCode = null;
                                this.waiting = false;
                            })
                            .catch(err => alert(err));
                    }
                }

            },
            clearForm() {
                this.mail = {
                    from: {
                        email: ''
                    },
                    replyTo: '',
                    to: [],
                    subject: '',
                    text: '',
                    html: '',
                    attachments: []
                };
                this.content = '';
                this.replyToEmail = '';
                this.toEmail = '';
                this.toEmails = [];
            },
            showStatuses(id) {
                this.waiting = true;
                fetch(`api/delivery/${id}/status`, {
                    method: 'get'
                })
                    .then(res => res.json())
                    .then(data => {
                        this.history = data.data;
                        this.historyModalOpen = true;
                    })
                    .catch(err => alert(err))
                    .finally(() => this.waiting = false);

            },
            showAttachments(id) {
                this.waiting = true;
                fetch(`api/mail/${id}/attachment`, {
                    method: 'get'
                })
                    .then(res => res.json())
                    .then(data => {
                        this.attachments = data.data;
                        this.attachmentModalOpen = true;
                    })
                    .catch(err => alert(err))
                    .finally(() => this.waiting = false);

            }
        }
    };
</script>

<style>
    .form-group {
        margin-bottom: 0 !important;
    }

    #overlay {
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 2;
    }

    .loading {
        position: fixed;
        top: 50%;
        right: 50%;
        z-index: 3;
        width: 3rem;
        height: 3rem;
    }
</style>
