<template>
    <div class="modal docked docked-right in" id="newTask" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Create New Task
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h1>Create Task for {{module_id}}</h1>
                </div>

            </div>
        </div>
    </div>
</template>
<style scoped>
    .modal-lg {
        max-width: 75%;
    }
</style>
<script>
    export default {
        props:['module_id'],
        mounted() {
            let vm = this;
            let modal = $('#newTask');
            modal.modal({
                keyboard: false
            });

            modal.on('hidden.bs.modal', function (e) {
                vm.eventHub.$emit('newTaskModalClosed');
            })
        }
    }
</script>
