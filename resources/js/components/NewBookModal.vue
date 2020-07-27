<template>
    <div>
        <b-modal id="new-book-modal" size="xl"
            :no-close-on-backdrop="true"
            :no-close-on-esc="true"
            centered
            hide-footer
        >
            <template v-slot:modal-title>
                Add a new book to my collection
            </template>
           <div class="row">
                <div class="col">
                    <div v-if="errorBag.length > 0" class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Holy guacamole!</strong> You should check in on some of those fields below.
                        <ul>
                            <li v-for="error in errorBag">{{ error }}</li>
                        </ul>
                        <button @click="clearErrorBag" type="button" class="close" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
           </div>
            <div class="row">
                <div class="col">
                    <b-form-group id="title-label" label="Title:" label-for="title">
                        <b-form-input id="title" v-model="book.title" type="text" :required="true">
                        </b-form-input>
                    </b-form-group>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <b-form-group id="author-label" label="Author:" label-for="author">
                        <b-form-input id="author" v-model="book.author" type="text" :required="true">
                        </b-form-input>
                    </b-form-group>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <b-form-group id="published-at-label" label="Publish Date:" label-for="published-at">
                        <b-form-input id="published-at" v-model="book.published_at" type="text">
                        </b-form-input>
                    </b-form-group>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <b-form-group id="isbn-10-label" label="ISBN-10:" label-for="isbn-10">
                        <b-form-input id="isbn-10" v-model="book.isbn_10" type="text">
                        </b-form-input>
                    </b-form-group>
                </div>
                <div class="col-lg-6">
                    <b-form-group id="isbn-13-label" label="ISBN-13:" label-for="isbn-13">
                        <b-form-input id="isbn-13" v-model="book.isbn_13" type="text">
                        </b-form-input>
                    </b-form-group>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <b-form-group id="summary-label" label="Summary:" label-for="summary">
                        <b-form-textarea id="summary" v-model="book.summary" type="text">
                        </b-form-textarea>
                    </b-form-group>
                </div>
            </div>
            <div class="row">
                <div class="col-6 col-md-4 col-lg-2">
                    <b-form-group id="priority-label" label="Priority:" label-for="priority">
                        <b-form-input id="priority" v-model="book.priority" type="number" :min="1">
                        </b-form-input>
                    </b-form-group>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col text-right">
                    <button class="btn btn-primary" @click="createBook">Submit</button>
                </div>
            </div>
        </b-modal>
    </div>
</template>
<script>
    import 'bootstrap-vue/dist/bootstrap-vue.css'
    import {
        ModalPlugin,
        FormGroupPlugin,
        FormInputPlugin,
        FormTextareaPlugin
    } from 'bootstrap-vue'

    Vue.use(ModalPlugin)
    Vue.use(FormGroupPlugin)
    Vue.use(FormInputPlugin)
    Vue.use(FormTextareaPlugin)

    export default {
        data() {
            return {
                book: {
                    title: '',
                    author: '',
                    isbn_10: '',
                    isbn_13: '',
                    published_at: '',
                    summary: '',
                    priority: ''
                },
                errorBag: []
            }
        },
        methods: {
            createBook() {
                this.clearErrorBag()
                axios.post('/api/v1/books', _.omitBy(this.book, _.isEmpty))
                    .then(response => {
                        this.$root.$emit('newBookCreated')
                        this.close()
                    })
                    .catch(({ response }) => {
                        this.errorBag = _.flatten(_.values(response.data.errors))
                    })
            },
            clearErrorBag() {
                this.errorBag = []
            },
            resetData() {
                this.book.title        = ''
                this.book.author       = ''
                this.book.isbn_10      = ''
                this.book.isbn_13      = ''
                this.book.published_at = ''
                this.book.summary      = ''
                this.book.priority     = ''
            },
            close() {
                this.$bvModal.hide('new-book-modal')
                this.resetData()
            }
        },
        mounted() {
            this.$root.$on('bv::modal::hide', (bvEvent, modalId) => {
                this.resetData()
            })
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
