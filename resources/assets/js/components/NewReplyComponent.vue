<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group" @keyup.enter="addReply">
                <textarea v-model="body" :rows="rows" @keyup.ctrl.enter="rows++" class="form-control" placeholder="Write a reply..."></textarea>
            </div>
        </div>
        <div v-else>
            <p>Please <a href="/login">sign in</a> to participate in this discussion.</p>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                body: '',
                rows: 1
            }
        },
        methods: {
            addReply () {
                axios
                    .post(location.pathname + '/replies', {body: this.body})
                    .then(function (response) {
                        this.body = '';
                        this.$emit('created', response.data);
                        flash('Your reply has been added');
                    }.bind(this))
                    .catch(function (error) {
                        flash(error.response.data, 'danger');
                    }.bind(this));

            },
        },
        computed: {
            signedIn () {
                return window.App.signedIn;
            }
        }
    }
</script>

<style scoped>
    textarea{
        resize: none;
        overflow: hidden;
    }
</style>