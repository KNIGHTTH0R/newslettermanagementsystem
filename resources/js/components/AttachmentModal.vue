<template>
    <div class="modal fade show" tabindex="-1" role="dialog"
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
                            v-on:click="close">Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['attachments'],
        methods: {
            close: function () {
                this.$emit('close');
            }
        }
    }
</script>
