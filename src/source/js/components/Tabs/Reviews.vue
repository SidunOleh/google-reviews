<template>
    <div class="reviews">
        <div class="btn" @click="downloadReviews">
            Download reviews
        </div>

        <TableLite
            :is-loading="table.isLoading"
            :columns="table.columns"
            :rows="table.rows"
            :total="table.totalRecordCount"
            :sortable="table.sortable"
            @do-search="getReviews"
            @is-finished="table.isLoading = false"/>
    </div>
</template>

<script>
import axios from 'axios'
import TableLite from 'vue3-table-lite'
import { reactive } from 'vue'
export default {
    components: {
        TableLite,
    },
    data() {
        return {
            table: reactive({
                isLoading: false,
                columns: [
                    {
                        label: "Photo",
                        field: "author_photo_url",
                        display: (row) => `<img class="author-photo" src="${row.author_photo_url}">`,
                    },
                    {
                        label: "Name",
                        field: "author_name",
                        width: '10%',
                    },
                    {
                        label: "Comment",
                        field: "comment",
                        width: '100%',
                    },
                    {
                        label: "Rating",
                        field: "rating",
                        sortable: true,
                    },
                    {
                        label: "Created",
                        field: "created_at",
                        sortable: true,
                        display: (row) => {
                            const date = new Date(row.created_at)
                                .toLocaleDateString(
                                    'en-US', 
                                    {
                                        year: 'numeric', 
                                        month: 'long', 
                                        day: 'numeric', 
                                    }
                                )
                            return `${date}`
                        },
                    },
                ],
                rows: [],
                totalRecordCount: 0,
                sortable: {
                    order: "created_at",
                    sort: "desc",
                },
            }),
        }
    },  
    methods: {
        downloadReviews() {
            this.$emit('loading', true)
            axios.get('/wp-admin/admin-ajax.php?action=download_reviews')
                .then(res => {
                    this.$emit('loading', false)
                    this.getReviews(0, 10, 'created_at', 'desc')
                }).catch(err => {
                    alert(err.response.data.error)
                    this.$emit('loading', false)
                })
        },
        getReviews(offset, limit, order, sort) {
            this.table.isLoading = true;
            axios.get('/wp-admin/admin-ajax.php', {
                params: {
                    action: 'get_reviews', 
                    offset, 
                    limit, 
                    order, 
                    sort,
                },
            }).then(res => {
                this.table.rows = res.data.reviews
                this.table.totalRecordCount = res.data.total
            })
        },
    },
    mounted() {
        this.getReviews(0, 10, 'created_at', 'desc')
    },
}
</script>

<style>
.btn {
    display: inline-block;
    padding: 10px 15px;
    border-radius: 5px;
    background: #343a40;
    cursor: pointer;
    color: white;
    font-weight: 600;
}
.btn:hover {
    background-color: #000000;
}
.reviews .btn {
    margin-bottom: 10px;
}
.vtl-paging-count-dropdown {
    background: none !important;
}
.author-photo {
    border-radius: 50%;
}
</style>