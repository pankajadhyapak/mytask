<template>
    <div class="task mb-2"

         :class=" task.is_completed ? 'completed-task' : ''">

        <i v-if="!task.is_completed"
           @click.prevent="markComplete(project.id, module.id, task.id)"
           class="fa fa-check-circle-o mr-2 complete-icon float-left"
           v-tooltip:bottom="'Mark Complete'"
           data-original-title="Mark Complete"
           data-placement="bottom">
        </i>
        <i v-if="task.is_completed" class="fa fa-check-circle-o mr-2 complete-icon float-left">
        </i>

        <span style="display: block">

                                <span class="task-body" v-if="taskEditing">
                                    <input type="text" class="form-control" v-model="task.name" @keyup.enter="saveTask()">
                                </span>

                                <span class="task-body" v-else>
                                    {{ task.name }}
                                    <span class="edit-icon text-muted ml-1" @click.stop="editTask(task)">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </span>
                                </span>



                            <div v-tooltip:bottom="task.assigned.email" class="avatar float-right" v-if="task.assigned">
                                {{ task.assigned.name[0]}}
                            </div>

                            <span v-else class="badge badge-secondary float-right">Un Assigned</span>
                            <span class="badge badge-dark float-right mr-2">{{ task.status.name}}</span>
                            <span class="badge badge-light float-right mr-2" v-if="task.estimated_time">
                                <i class="fa fa-clock-o" aria-hidden="true"></i>
                                    {{ task.estimated_time }} Hrs
                            </span>
                            </span>
    </div>
</template>

<script>
    export default {
        props:['task', 'module', 'project'],
        mounted() {
            console.log('Component mounted.')
        },
        methods:{
            saveTask(){
              //make ajax call and save data
              this.taskEditing = false;
            },
            editTask(task){
                this.taskEditing = true;
            },
            markComplete(projectId, moduleId, taskId){
                axios.patch("/api/task/"+taskId+"/complete", {project_id:projectId, module_id:moduleId}).then(function (response) {
                    consolge.log(response.data);
                }).catch(function (error) {
                    consolge.log(error.data);
                });
                let module = _.find(this.project.modules, ['id', moduleId]);
                let task = _.find(module.tasks, ['id', taskId]);
                //axios call
                task.is_completed = !task.is_completed;
            },
        },
        data(){
            return {
                taskEditing: false
            }
        }
    }
</script>