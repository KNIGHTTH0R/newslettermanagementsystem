<template>
    <nav aria-label="Page navigation example" class="mt-2">
        <ul class="pagination">
            <li v-bind:class="[{disabled: !pagination.prevPageUrl}]" class="page-item">
                <a class="page-link"
                   href="#prev"
                   v-on:click="page(pagination.prevPageUrl)">Previous</a>
            </li>

            <li class="page-item disabled">
                <a class="page-link text-dark" href="#">Page {{ pagination.currentPage
                    }} of {{ pagination.lastPage }}</a>
            </li>

            <li v-bind:class="[{disabled: !pagination.nextPageUrl}]" class="page-item">
                <a class="page-link"
                   href="#next"
                   v-on:click="page(pagination.nextPageUrl)">Next</a>
            </li>
        </ul>
    </nav>
</template>

<script>
    export default {
        props: ['pagination'],
        methods: {
            page: function (pageUrl) {
                this.$emit('page', pageUrl);
            }
        }
    }
</script>
