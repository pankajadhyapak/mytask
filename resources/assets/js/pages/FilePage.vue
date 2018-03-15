<template>
    <div class="container">
        <div class="empty-msg mt-lg-5">
            <img
                    style="width: 160px;"
                    class="img mt-lg-5"
                    src="/images/attachment.svg">

            <h4 class="text-muted">All attachments in this project will appear here.</h4>
        </div>
    </div>
</template>
<style scoped>
    .empty-msg{
        display: flex;
        flex-direction: column;
        align-items: center;
    }
</style>
<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data(){
            return {
                projectId: this.$route.params.id
            }
        }
    }
</script>
