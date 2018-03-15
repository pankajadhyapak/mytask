<template>
    <div class="container">
        <div class="not-found">
            <img
                    class="img notfound mt-5 mb-5"
                    src="/images/not_found.svg" alt="Page Not Found">
            <h3 class="mt-lg-4 text-muted"> Hmm... Nothing Matches Your Search.</h3>
        </div>
    </div>
</template>
<style scoped>
    .not-found{
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .not-found img{
        width: 500px;
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
