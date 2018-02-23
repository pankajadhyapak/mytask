<template>
    <my-modal @closed="$emit('closed')">

    </my-modal>
</template>
<style scoped>

</style>
<script>
    export default {
        mounted() {
            let vm = this;
            axios.get("/api/users").then(function (response) {
                vm.options =  response.data;
            });

        },
        data(){
            return {
                options: [],
                newTeam: {
                    name:'',
                    description:'',
                    members:[]
                }
            }
        },
        methods:{
            addNewTeam(){
                let vm = this;
                axios.post("/api/team", vm.newTeam).then(function(response){
                    console.log(response.data);
                });
            }
        }
    }
</script>
