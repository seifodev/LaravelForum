<template>

    <div :id="'reply-' + id" class="panel panel-default">
        <div class="panel-heading clearfix">
            <strong>
                <a :href="'/profiles/' + data.owner.name">{{data.owner.name}}</a>
            </strong>
            <span v-text="ago" class="small text-muted"></span>
            <div class="pull-right">


                <favourite :reply="data" v-if="signedIn"></favourite>
                <span class="text-warning" v-else>
                    {{ data.favourites_count}} Favourites
                </span>


            </div>
        </div>

        <div class="panel-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea rows="5" class="form-control" v-model="body"></textarea>
                </div>
                <div class="form-group">
                    <button class="btn btn-sm" @click="cancel">Cancel</button>
                    <button class="btn btn-sm btn-success" @click="update">Save</button>
                </div>
            </div>
            <div v-else v-text="body"></div>
        </div>


        <div class="panel-footer level" v-if="canUpdate">
            <button class="btn btn-primary btn-xs" @click="editing = !editing">Edit</button>
            <button class="btn btn-danger btn-xs" @click="deleteReply">Delete</button>
        </div>


    </div>


</template>

<script>
    import Favourite from './FavouriteComponent.vue';
    import moment from 'moment';

    export default {
        components: {
            favourite: Favourite,
        },
        props: ['data'],
        data () {
            return {
                editing: false,
                body: '',
                response: '',
                id: this.data.id,
            };
        },
        methods: {
            cancel () {
                this.editing = false;
                this.body = this.response;
            },
            update () {
                axios
                    .patch('/replies/' + this.data.id, {body: this.body})
                    .then(function (response) {
                        window.flash('Reply has been updated successfully');
                        this.editing = false;
                        this.response = response.data;
                        this.body = this.response;
                    }.bind(this))
                    .catch(function (error) {
                        flash(error.response.data, 'danger');
                    }.bind(this));
            },
            deleteReply () {
                axios
                    .delete('/replies/' + this.data.id);

                this.$emit('deleted', this.data.id)
            }
        },
        computed: {
            signedIn () {
                return window.App.signedIn;
            },
            canUpdate () {
                return this.authorize(function (user) {
                    return user.id === this.data.owner.id;
                }.bind(this));
            },
            ago () {
                return moment(this.data.created_at).fromNow();
            }
        },
        created () {
            this.body = this.response = this.data.body;
        }
    }
</script>

<style scoped>

</style>