<template>
    <div :class="'alert alert-'+this.type+' alert-flash'" role="alert" v-show="show">
        <strong v-if="type === 'success'">Success!</strong>
        <strong v-if="type === 'danger'">Opps!</strong>
        {{ body }}
    </div>
</template>

<script>
    export default {
        props: ['message'],
        data() {
            return {
                body: '',
                show: false,
                type: 'success'
            }
        },
        created() {
            if (this.message) {
                this.flash(this.message);
            }
            this.eventHub.$on('flash', message => this.flash(message.message, message.type));
        },
        methods: {
            flash(message, type = "success") {
                this.body = message;
                this.show = true;
                this.type = type;
                this.hide();
            },
            hide() {
                setTimeout(() => {
                    this.show = false;
                }, 3000);
            }
        }
    };
</script>

<style>
    .alert-flash {
        position: fixed;
        right: 25px;
        bottom: 25px;
        border-left: 8px solid;
        border-right: 8px solid;
        z-index: 9999;
    }
</style>
