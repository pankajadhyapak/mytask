<script>
    export default {
        mounted() {
            console.log('Component mounted.')
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
            },
            currentProjectNav(){
                return this.pathIs(this.$route.fullPath, "project");
            }
        },
        watch:{
            "searchKey"(){
                this.eventHub.$emit("searchKeyChanged", this.searchKey);
            },
            '$route' (to, from) {
                console.log(this.$route.fullPath);
            }
        }
    }
</script>

<style scoped>
    a.nav-link.router-link-exact-active {
        border-bottom: 2px solid;
    }
</style>
