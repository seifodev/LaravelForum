<template>
    <div class="alert alert-flash" :class="'alert-'+status" v-show="show" v-text="body"></div>

</template>

<script>
    export default {
        props: ['message'],
        data () {
            return {
                body: '',
                show: false,
                status: 'success',
            };
        },
        created () {
            if(this.message)
            {
                this.flash(this.message);
            }

            window.events.$on('flash', function (message, status) {
                this.status = status;
                this.flash(message);
            }.bind(this));

        },
        methods: {
            flash (message) {
                this.body = message;
                this.show = true;
                this.hide();
            },
            hide () {
                setTimeout(function () {
                    this.show = false
                }.bind(this), 5000)
            },
        }
    }
</script>

<style>
    .alert-flash
    {
        position: fixed;
        right: 25px;
        bottom: 40px;
        /*transition: all 1s ease-in-out;*/
    }
</style>