<template>
    <div>
        <!--TODO:: check :key-->
        <div v-for="(reply, index) in items" :key="reply.id">
            <reply :data="reply" @deleted="remove(index)"></reply>
        </div>
        <paginator :dataSet="dataSet" @changed="fetch"></paginator>
        <new-reply @created="add"></new-reply>
    </div>
</template>

<script>

    import Reply from './ReplyComponent.vue';
    import NewReply from '../components/NewReplyComponent.vue';
    import collection from '../mixins/collection';

    export default {
        components: {
            reply: Reply,
            'new-reply': NewReply,
        },
        mixins: [collection],
        data () {
            return {
                dataSet: false,
                items: [],
            }
        },
        methods: {
            fetch (page) {
                axios
                    .get(this.url(page))
                    .then(this.refresh);
            },
            url (page) {
                if(!page) {
                    page = window.location.search ? location.search.match(/page=(\d+)/)[1] : 1;
                }
                return `${location.pathname}/replies?page=${page}`;
            },
            refresh (response) {
                this.dataSet = response.data;
                this.items = response.data.data;

                window.scrollTo(0, 0);
            },
        },
        created () {
            this.fetch();
        }
    }
</script>

<style scoped>

</style>