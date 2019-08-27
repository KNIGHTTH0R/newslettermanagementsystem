<template>
    <div>
        <h2>Send a new e-mail</h2>
        <form v-on:submit.prevent="addDelivery" class="mb-3">
            <div class="form-group">
                <label for="from_input" class="col-form-label">*From</label>
                <input id="from_input" type="email" class="form-control" placeholder="e.g. example@example.com"
                       v-model="mail.from.email" required>
            </div>
            <div class="form-group">
                <label for="reply_to_input" class="col-form-label">Reply To</label>
                <input id="reply_to_input" type="email" class="form-control" placeholder="e.g. example@example.com"
                       v-model="reply_to_email" required>
            </div>
            <div class="form-group">
                <label for="to_input" class="col-form-label">*To(s)</label>
                <ul v-if="mail.to.length">
                    <li v-for="t in mail.to">{{t.email}}
                        <button type="button" class="btn btn-link btn-sm" title="Remove" v-on:click="removeToEmail(t)">
                            <i class="fas fa-times"></i></button>
                    </li>
                </ul>
                <div class="input-group mb-3">
                    <input id="to_input" type="email" class="form-control" placeholder="e.g. example@example.com"
                           v-model="to_email" v-on:keydown.enter.prevent="addToEmail">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" v-on:click="addToEmail"><i
                            class="fas fa-plus"></i> Add
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="subject_input" class="col-form-label">*Subject</label>
                <input id="subject_input" type="text" class="form-control" placeholder="Subject"
                       v-model="mail.subject" required>
            </div>
            <div class="form-group">
                <label for="content_input" class="col-form-label">*Content</label>
                <tinymce id="content_input" v-model="content" required :plugins="tinymce_plugins"
                         :other_options="tinymce_options"></tinymce>
            </div>
            <div class="form-group">
                <label for="attachment_field" class="col-form-label">Attachments</label>
                <ul v-if="mail.attachments.length">
                    <li v-for="att in mail.attachments"><a
                        v-bind:href="'data:'+att.contentType+';base64,'+att.base64Content"
                        v-bind:download="att.filename">{{att.filename}}</a>
                        <button type="button" class="btn btn-link btn-sm" title="Remove"
                                v-on:click="removeAttachment(att)"><i class="fas fa-times"></i></button>
                    </li>
                </ul>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="attachment_field" ref="attachment_field"
                           v-on:change="onChangeFileUpload">
                    <label class="custom-file-label" for="attachment_field">Choose a file</label>
                </div>
            </div>
            <button v-on:click="createMail" type="button"
                    class="btn btn-success btn-block mt-3">Send
            </button>
            <button v-on:click="clearForm" type="button"
                    class="btn btn-danger btn-block mt-1">Cancel
            </button>
        </form>
        <nav aria-label="Page navigation example" class="mt-2" v-if="deliveries.length">
            <ul class="pagination">
                <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link"
                                                                                                href="#"
                                                                                                v-on:click="fetchDeliveries(pagination.prev_page_url)">Previous</a>
                </li>

                <li class="page-item disabled"><a class="page-link text-dark" href="#">Page {{ pagination.current_page
                    }} of {{ pagination.last_page }}</a></li>

                <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a class="page-link"
                                                                                                href="#"
                                                                                                v-on:click="fetchDeliveries(pagination.next_page_url)">Next</a>
                </li>
            </ul>
        </nav>
        <div class="card card-body mb-2" v-for="delivery in deliveries" v-bind:key="delivery.id">
            <h4>{{ delivery.subject }}</h4>
            <p>{{ delivery.text_content }}</p>
            <p style="font-size: 70%;">
                <strong>From:</strong> {{ delivery.from_email }}<br>
                <strong>Reply To:</strong> {{ delivery.reply_to_email }}<br>
                <strong>To:</strong> {{ delivery.to_email }}<br>
                <strong>Status:</strong> {{ delivery.status }}<br>
                <strong>Created At:</strong> {{ delivery.created_at }}<br>
                <strong>Delivery ID:</strong> {{ delivery.id }}<br>
                <strong>Mail ID:</strong> {{ delivery.mail_id }}<br>
            </p>
            <hr>
            <div class="btn-group">
                <button class="btn btn-info mb-2" v-bind:disabled="!delivery.has_attachment"
                        v-on:click="showAttachments(delivery.mail_id)">Show attachments
                </button>
                <button class="btn btn-warning mb-2" v-on:click="showStatuses(delivery.id)">Show status history</button>
            </div>
        </div>
        <div v-if="attachment_modal_open" class="modal fade show" tabindex="-1" role="dialog"
             style="display: block; padding-right: 17px;">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Attachments</h5>
                    </div>
                    <div class="modal-body">
                        <li v-for="att in attachments"><a
                            v-bind:href="'data:'+att.type+';base64,'+att.content"
                            v-bind:download="att.filename">{{att.filename}}</a>
                        </li>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                v-on:click="attachment_modal_open=false">Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="history_modal_open" class="modal fade show" tabindex="-1" role="dialog"
             style="display: block; padding-right: 17px;">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delivery Status History</h5>
                    </div>
                    <div class="modal-body">
                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th scope="col">Status</th>
                                <th scope="col">Date</th>
                                <th scope="col">Driver</th>
                                <th scope="col">Details</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr style="font-size: 8pt;" v-for="h in history">
                                <td style="font-weight: bold;">{{h.status}}</td>
                                <td>{{h.created_at}}</td>
                                <td>{{h.driver}}</td>
                                <td style="word-break: break-word;">{{h.details}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                v-on:click="history_modal_open=false">Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="history_modal_open | attachment_modal_open | waiting" id="overlay"></div>
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
                reply_to_email: '',
                to_email: '',
                to_emails: [],
                deliveries: [],
                delivery: {
                    id: '',
                    title: '',
                    body: ''
                },
                delivery_id: '',
                pagination: {},
                current_page_url: null,
                status_code: null,
                attachment_modal_open: false,
                attachments: [],
                history_modal_open: false,
                history: [],
                waiting: false,
                tinymce_plugins: ['image', 'textpattern', 'code', 'link', 'preview'],
                tinymce_options: {
                    height: "300",
                    statusbar: false,
                }
            };
        },
        created() {
            // fetch deliveries if any at startup
            this.fetchDeliveries();

            //refresh delivery information in every 3 seconds
            setInterval(function () {
                this.fetchDeliveries(this.current_page_url);
            }.bind(this), 3000);
        },
        watch: {
            // here we convert html mail content to text content
            content: function () {
                this.mail.html = this.content;
                this.mail.text = this.strip(this.content);
            },

            reply_to_email: function () {
                if (this.reply_to_email.length === 0) {
                    this.mail.replyTo = '';
                } else {
                    this.mail.replyTo = {
                        'email': this.reply_to_email
                    };
                }
            }
        },
        methods: {
            strip(html) {
                var doc = new DOMParser().parseFromString(html, 'text/html');
                return doc.body.textContent || "";
            },
            onChangeFileUpload() {
                var file = this.$refs.attachment_field.files[0];
                if (file) {
                    if (file.size > 1024 * 1024) {
                        alert("File size must be lower than 1MB!")
                    } else {
                        this.getBase64(file)
                    }
                }
                this.$refs.attachment_field.value = '';

            },
            removeAttachment(attachment) {
                var index = this.mail.attachments.indexOf(attachment);
                if (index !== -1) this.mail.attachments.splice(index, 1);
            },
            getBase64(file) {
                var reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = (e => {
                    this.addAttachment(reader.result, file);
                });
                reader.onerror = function (error) {
                    alert('Error: ', error);
                };
            },
            addAttachment(base64, file) {
                var att = {
                    contentType: file.type,
                    filename: file.name,
                    base64Content: base64.split(',')[1]
                };

                this.mail.attachments.push(att);

            },
            removeToEmail(email) {
                var index = this.mail.to.indexOf(email);
                if (index !== -1) this.mail.to.splice(index, 1);

                index = this.to_emails.indexOf(email.email);
                if (index !== -1) this.to_emails.splice(index, 1);
            },
            addToEmail() {
                if (this.validEmail(this.to_email)) {
                    if (!this.to_emails.includes(this.to_email)) {
                        this.mail.to.push({'email': this.to_email});
                        this.to_emails.push(this.to_email);
                    }
                    this.to_email = '';
                } else {
                    alert("Please enter a valid e-mail address!");
                }
            },
            validEmail: function (email) {
                var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            },
            fetchDeliveries(page_url) {
                let vm = this;
                page_url = page_url || '/api/deliveries';
                this.current_page_url = page_url;
                fetch(page_url)
                    .then(res => res.json())
                    .then(res => {
                        this.deliveries = res.data;
                        vm.makePagination(res.meta, res.links);
                    })
                    .catch(err => console.log(err));
            },
            makePagination(meta, links) {
                let pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev
                };
                this.pagination = pagination;
            },
            checkForm() {
                if (!this.validEmail(this.mail.from.email))
                    return false;
                if (this.mail.replyTo && !this.validEmail(this.mail.replyTo.email))
                    return false;
                if (this.mail.to.length === 0)
                    return false;
                if (this.mail.subject.length === 0)
                    return false;
                return this.content.length !== 0;

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
                                this.status_code = res.status;
                                return res.json();
                            })
                            .then(data => {
                                if (this.status_code === 200) {
                                    alert('Mail sent!');
                                    this.clearForm();
                                    this.fetchDeliveries();
                                } else if (this.status_code === 500) {
                                    alert("Internal Server Error!");
                                } else {
                                    alert(JSON.stringify(data));
                                }
                                this.status_code = null;
                                this.waiting = false;
                            })
                            .catch(err => alert(err));
                    }
                } else {
                    alert("Please check form values!");
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
                this.reply_to_email = '';
                this.to_email = '';
                this.to_emails = [];
            },
            showStatuses(id) {
                this.waiting = true;
                fetch(`api/delivery/${id}/status`, {
                    method: 'get'
                })
                    .then(res => res.json())
                    .then(data => {
                        this.history = data.data;
                        this.history_modal_open = true;
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
                        this.attachment_modal_open = true;
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
