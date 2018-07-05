<script>
    import Replies from '../components/RepliesComponent.vue';
    import Subscribe from '../components/SubscribeComponent.vue';
    export default {
        components: {
            replies: Replies,
            subscribe: Subscribe
        },
        props: ['initialRepliesCount'],
        data () {
            return {
                countReplies: '',
                repliesCount: this.initialRepliesCount,
            }
        }
    }
</script>

<style scoped>

</style>