<script>
    export default {
        mounted() {

        },
        data(){
            return {
                searchKey: ''
            }
        },
        computed:{
            showProjectNav(){
                return {
                    show: this.pathIs(this.$route.fullPath, "project"),
                    currentPath: "/project/" + this.$route.params.id
                }
            }
        },
        watch:{
            "searchKey"(){
                this.eventHub.$emit("searchKeyChanged", this.searchKey);
            },
            '$route' (to, from) {
                // console.log(this.$route.fullPath);
            }
        }
    }
</script>

<style scoped>
    a.nav-link.router-link-exact-active {
        border-bottom: 2px solid;
    }
    .bgsame {
        background: #e7ebf2 !important;
        background-color: #e7ebf2 !important;
    }
</style>
