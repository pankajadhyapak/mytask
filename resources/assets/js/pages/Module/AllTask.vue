<template>
    <div class="container">
            All Tasks shown here for {{ $route.params.module_id ? $route.params.module_id : '1' }}
    </div>
</template>

<script>
    export default {
        mounted() {
            this.$http.get("/api/module/" + this.$route.params.module_id ? this.$route.params.module_id : '1' )
                .then(function (response) {

                }, function (error) {

                });
            console.log("Hello from All Task");
        },
        data(){
            return {
                project: {},
                dataLoaded: false
            }
        }
    }
</script>
