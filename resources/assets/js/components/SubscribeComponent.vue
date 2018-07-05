<template>
    <div>
        <button :class="classes" @click="subscribe" v-text="btnText"></button>
    </div>
</template>

<script>
    export default {
        props: ['active'],
        computed: {
            classes () {
                return [
                    'btn',
                    this.active ? 'btn-primary' : 'btn-default'
                ]
            },
            btnText () {
                return this.active ? 'Unsubscribe' : 'subscribe';
            }
        },
        methods: {
            subscribe () {
                let requestType = this.active ? 'delete' : 'post';
                axios[requestType](`${location.pathname}/subscriptions`);

                this.active = !this.active;
            }
        }
    }
</script>

<style scoped>
div{
    padding: 10px 0;
}
</style>