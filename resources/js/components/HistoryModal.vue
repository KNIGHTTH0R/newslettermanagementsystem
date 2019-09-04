<template>
    <div class="modal fade show" tabindex="-1" role="dialog"
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
                            <td>{{h.createdAt}}</td>
                            <td>{{h.driver}}</td>
                            <td style="word-break: break-word;">{{h.details}}</td>
                        </tr>
                        </tbody>
                    </table>
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
        props: ['history'],
        methods: {
            close: function () {
                this.$emit('close');
            }
        }
    }
</script>
