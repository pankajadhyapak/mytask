<template xmlns:v-tooltip="http://www.w3.org/1999/xhtml">
    <div class="task-item" :class="task.is_completed ? 'completed-task': ''">
        <div class="left-item" @click.stop="$emit('clicked')">
            <div class="task-icon">
                <i v-if="!task.is_completed"
                   @click.stop="markComplete(task)"
                   class="fa fa-check-circle-o mr-2 complete-icon float-left"
                   v-tooltip:bottom="'Mark Complete'"
                   data-original-title="Mark Complete"
                   data-placement="bottom">
                </i>

                <i v-if="task.is_completed" class="fa fa-check-circle-o mr-2 complete-icon float-left"></i>
            </div>

            <div class="task-title">
                <div class="task-body" v-if="taskEditing">
                    <input
                        type="text"
                        v-model="task.name"
                        @click.stop=""
                        class="TaskName-input override-focus-border"
                        @keyup.enter="saveTask()">
                </div>

                <div class="task-text" v-if="!taskEditing">
                    {{task.name}}
                </div>

                <span class="edit-icon text-muted ml-1" @click.stop="editTask(task)" v-if="!taskEditing">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                </span>

            </div>

        </div>
        <div class="right-item">
            <!-- TODO Add popovers instead of tooltip -->
            <span class="badge badge-dark mr-2">{{ task.status.name}}</span>
            <span class="badge badge-light mr-2" v-if="task.estimated_time">
                <i class="fa fa-clock-o" aria-hidden="true">&nbsp;{{ task.estimated_time }} Hrs</i>
            </span>
            <div v-tooltip:bottom="task.assigned.email" class="avatar" v-if="task.assigned">
                {{ task.assigned.name[0]}}
            </div>
            <div v-tooltip:bottom="'Assign Task'" class="avatar not-assigned" v-else>
                UA
            </div>
        </div>
    </div>
</template>
<style>
    .task-title{
        overflow: hidden;
        margin-right: 20px;
    }
    .TaskName-input {
        /*background: transparent;*/
        border-radius: 0;
        display: block;
        height: 100%;
        outline: 0;
        overflow: hidden;
        position: absolute;
        resize: none;
        top: 0;
        width: 95%;
    }
    .TaskName-input {
        border: 0;
        margin: 0;
        padding: 5px;
        text-rendering: optimizeSpeed;
    }
    .task-item::after {
        border-bottom: 1px solid #e0e6e8;
        bottom: -2px;
        content: "";
        display: block;
        left: 2px;
        pointer-events: none;
        position: absolute;
        right: 2px;
    }
    .task-item{
        border-bottom: 1px solid transparent;
        border-top: 1px solid transparent;
        height: 40px;
        -webkit-box-align: center;
        -webkit-align-items: center;
        -ms-flex-align: center;
        align-items: center;
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: justify;
        -webkit-justify-content: space-between;
        -ms-flex-pack: justify;
        justify-content: space-between;
        overflow: visible;
        padding: 0 2px;
        position: relative;
        -webkit-transition: box-shadow 0s ease-in;
        transition: box-shadow 0s ease-in;
        white-space: nowrap;
    }
    .left-item{
        -webkit-box-align: center;
        -webkit-align-items: center;
        -ms-flex-align: center;
        align-items: center;
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-flex: 1;
        -webkit-flex: 1 1000 auto;
        -ms-flex: 1 1000 auto;
        flex: 1 1000 auto;
        height: 100%;
        -webkit-box-pack: start;
        -webkit-justify-content: flex-start;
        -ms-flex-pack: start;
        justify-content: flex-start;
        min-width: 1px;
        position: relative;
    }
    .right-item{
        -webkit-box-align: center;
        -webkit-align-items: center;
        -ms-flex-align: center;
        align-items: center;
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-flex: 0;
        -webkit-flex: 0 1 auto;
        -ms-flex: 0 1 auto;
        flex: 0 1 auto;
        height: 100%;
        -webkit-box-pack: end;
        -webkit-justify-content: flex-end;
        -ms-flex-pack: end;
        justify-content: flex-end;
        max-width: 75%;
        overflow: hidden;
    }
    span.edit-icon {
        display:none;
        font-size: 12px;
    }
    .task-title:hover span.edit-icon{
        display: inline-block !important;
        /*position: absolute;*/
        /*right: 6px;*/
        /*top: 10px;*/
        font-size: 12px;
    }
</style>
<script>
    export default {
        props: ['task', 'module', 'project'],

        mounted() {
            console.log('Component mounted.')
        },

        methods: {
            saveTask() {
                let vm = this;
                //make ajax call and save data
                axios.patch("/api/task/"+this.task.id, { name: vm.task.name}).then(function(success){
                    flash("Task Updated");
                })
                .catch(function(error){
                    flash("Unable to update", "danger");
                });
                this.taskEditing = false;
            },
            editTask(task) {
                this.taskEditing = true;
            },
            markComplete(task) {
                let vm = this;
                task.is_completed = true;
                //projectId, moduleId, taskId
                axios.patch("/api/task/" + task.id + "/complete", { "project_id": vm.project})
                    .then(function (success) {
                        flash("Marked as complete");
                    }).catch(function (error) {
                        flash("Unable to update", "danger");
                    });
            },
        },
        data() {
            return {
                taskEditing: false
            }
        }
    }
</script>
