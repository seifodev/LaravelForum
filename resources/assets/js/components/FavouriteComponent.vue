<template>
    <button :class="classes" @click="toggle">
        <span class="glyphicon glyphicon-heart"></span>
        <span v-text="count"></span>
    </button>
</template>

<script>
    export default {
        props: ['reply'],
        data () {
            return {
                count: this.reply.favourites_count,
                active: this.reply.isFavourited
            };
        },
        computed: {
            classes () {
                return [
                    'btn',
                    'btn-xs',
                    this.active ? 'btn-primary' : 'btn-default'
                ]
            },
            endpoint () {
                return '/replies/' + this.reply.id + '/favourites';
            }
        },
        methods: {
            toggle () {
                this.active ? this.create() : this.destroy();
            },
            create () {
                axios
                    .delete(this.endpoint);

                this.active = false;
                this.count--
            },
            destroy () {
                axios
                    .post(this.endpoint);

                this.active = true;
                this.count++
            }
        }
    }
</script>

<style scoped>

</style>