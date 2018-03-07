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
        watch:{
            "searchKey"(){
                this.eventHub.$emit("searchKeyChanged", this.searchKey);
            }
        }
    }
</script>
