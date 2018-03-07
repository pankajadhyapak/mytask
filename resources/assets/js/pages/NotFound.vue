<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <img class="img notfound mt-5" :src="path" alt="Page Not Found">
                <h3 class="mt-4 text-muted"> Hmm... Nothing Matches Your Search.</h3>
            </div>
        </div>
    </div>
</template>
<style scoped>
    img.notfound{
        width: 70%;
    }
</style>
<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data(){
            return {
                path:"https://d3ki9tyy5l5ruj.cloudfront.net/obj/fd26df25c2988aa92a94dcbe457c2ffbf46ca553/empty_search_v2.svg"
            }
        }
    }
</script>
