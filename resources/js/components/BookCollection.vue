<template>
    <div>
        <div class="row my-4 mx-n1">
            <div class="col-md-6 px-md-4">
                <b-form-group id="title-filter-label" label="Search By Title:" label-for="title-filter">
                    <b-form-input id="title-filter" v-model="titleFilter" type="search" placeholder="Type To Search:">
                    </b-form-input>
                </b-form-group>
            </div>
            <div class="col-md-6 px-md-4">
                <b-form-group id="author-filter-label" label="Search By Author:" label-for="author-filter">
                    <b-form-select id="author-filter" v-model="authorFilter" :options="authors" @change="filterByAuthor">
                        <template slot="first"><option value="">All</option></template>
                    </b-form-select>
                </b-form-group>
            </div>
        </div>

        <b-table striped hover bordered dark
            :fields="fields"
            :items="items"
            :filter="titleFilter"
            :filter-included-fields="filterBy"
            responsive="md"
        >
            <template v-slot:cell(id)="data">
                <a class="d-block" :href="'/books/' + data.value" tabindex="0">{{ data.value }}</a>
            </template>
            <template v-slot:cell(priority)="data">
                <input v-model="data.item.priority" @change="bumpPriority(data.item)" type="number" class="form-control number-input" min="0"/>
            </template>
            <template v-slot:cell(read)="data">
                <b-form-checkbox
                    v-model="data.item.read"
                    :value="true"
                    :unchecked-value="false"
                    @change="toggleReadStatus(data.item)"
                >
                </b-form-checkbox>
            </template>
            <template v-slot:cell(actions)="data">
                <div style="height: 0px; width: 100px">&nbsp;</div>
                <a href="#" class="btn btn-primary btn-sm">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M11.293 1.293a1 1 0 0 1 1.414 0l2 2a1 1 0 0 1 0 1.414l-9 9a1 1 0 0 1-.39.242l-3 1a1 1 0 0 1-1.266-1.265l1-3a1 1 0 0 1 .242-.391l9-9zM12 2l2 2-9 9-3 1 1-3 9-9z"/>
                        <path fill-rule="evenodd" d="M12.146 6.354l-2.5-2.5.708-.708 2.5 2.5-.707.708zM3 10v.5a.5.5 0 0 0 .5.5H4v.5a.5.5 0 0 0 .5.5H5v.5a.5.5 0 0 0 .5.5H6v-1.5a.5.5 0 0 0-.5-.5H5v-.5a.5.5 0 0 0-.5-.5H3z"/>
                    </svg>
                </a>
                <button class="btn btn-danger btn-sm" @click="deleteBook(data.item)">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                    </svg>
                </button>
            </template>
        </b-table>
    </div>
</template>
<script>
    import 'bootstrap-vue/dist/bootstrap-vue.css'
    import {
        TablePlugin,
        FormGroupPlugin,
        FormInputPlugin,
        FormSelectPlugin,
        FormCheckboxPlugin,
        ToastPlugin
    } from 'bootstrap-vue'

    Vue.use(TablePlugin)
    Vue.use(FormGroupPlugin)
    Vue.use(FormInputPlugin)
    Vue.use(FormSelectPlugin)
    Vue.use(FormCheckboxPlugin)
    Vue.use(ToastPlugin)

    export default {
        props: {
            books: Array,
            user: Object
        },
        data() {
            return {
                items: this.books,
                authors: null,
                fields: [
                    { key: 'id', sortable: true, class: 'book-id' },
                    { key: 'priority', sortable: true },
                    { key: 'title', sortable: true },
                    { key: 'author', sortable: true },
                    { key: 'isbn_13', sortable: false },
                    { key: 'read', sortable: true },
                    { key: 'actions', sortable: false, class: 'actions' }
                ],
                titleFilter: '',
                authorFilter: '',
                filterBy: ['title'],
                sortBy: 'priority'
            }
        },
        methods: {
            filterByAuthor() {
                axios.get('/api/v1/books')
                    .then(({ data }) => {
                        if (this.authorFilter.length > 0) {
                            this.items = data.data.filter(book => {
                                return book.author == this.authorFilter
                            })
                        } else {
                            this.items = data.data
                            this.updateAuthors()
                        }
                    })
                    .catch(error => {
                        this.showErrorNotification(
                            'Failed to refresh books.'
                        )
                    })
            },
            bumpPriority: _.debounce(function(book) {
                var self = this
                axios.put(`/api/v1/books/${book.id}`, { priority: book.priority })
                    .then(function(response) {
                        self.sortBooks()
                    })
                    .catch(function(error) {
                        self.showErrorNotification(
                            'Failed to update priority. Please Try again.'
                        )
                    })
            }, 750),
            sortBooks() {
                this.items = _.orderBy(this.items, ['priority'], ['asc'])
            },
            toggleReadStatus(book) {
                axios.put(`/api/v1/books/${book.id}`, { read: ! book.read })
                    .catch(error => {
                        book.read = ! book.read
                        this.showErrorNotification(
                            'Failed to update read status. Please Try again.'
                        )
                    })
            },
            deleteBook(book) {
                axios.delete(`/api/v1/books/${book.id}`)
                    .then(response => {
                        this.items = this.items.filter(item => {
                            return item.id != book.id
                        })
                    })
                    .catch(error => {
                        this.showErrorNotification(
                            'Failed to delete book. Please Try again.'
                        )
                    })
            },
            showErrorNotification(message, timeout = 5000) {
                this.$bvToast.toast(message, {
                    title: 'Error',
                    variant: 'danger',
                    autoHideDelay: 5000
                })
            },
            updateAuthors() {
                this.authors = _.uniq(this.items.map(book => book.author))
            }
        },
        mounted() {
            this.updateAuthors()
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.user.api_token
        }
    }
</script>

<style lang="scss" scoped>
    table td {
        vertical-align: middle;

        .number-input {
            width: 100px;
        }

        &.actions {
            a, button {
                padding: 2px 6px;
                font-size: 1rem;
                color: #EEE;
            }
        }
    }
</style>
