<template>
    <div class="modal fade in"
         :id="name"
         tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <slot name="title">Modal Title</slot>
                    </h5>
                    <slot name="modal-header">

                    </slot>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <slot>
                        This will only be displayed if there is no content
                        to be distributed.
                    </slot>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {

        props:['name', 'close'],

        mounted() {
            let vm = this;
            let modal = $('#'+ this.name);

            modal.modal({ keyboard: false });

            modal.on('hidden.bs.modal', function (e) {
                vm.$emit('closed');
            });
        },
        watch:{
            'close'(){
                if(this.close){
                    let modal = $('#'+ this.name);
                    modal.modal('hide');
                }
            }
        }
    }
</script>
