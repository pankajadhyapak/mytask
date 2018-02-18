<template>
    <div class="card card-default">
        <div class="card-header">
            Tasks <small> - <i class="fa fa-clock-o mr-1" aria-hidden="true"></i>{{ module.total_estimated }} Hrs</small>

            <span class="float-right">
                <button class="btn btn-outline-primary btn-sm" @click="showNewTaskModalf(module.id)">New Task</button>
            </span>
        </div>

        <div class="card-body" style="padding: 8px;">
            <div class="">
                <div class="task mb-2"
                     v-for="task in module.tasks"
                     :class=" task.is_completed ? 'completed-task' : ''"
                     @click="showViewTaskModalf(task.id)">
                    {{ task.name }}
                    <div class="avatar float-right" v-if="task.assigned">
                        {{ task.assigned.name[0]}}
                    </div>

                    <span v-else class="badge badge-secondary float-right">Un Assigned</span>
                    <span class="badge badge-dark float-right mr-2">{{ task.status.name}}</span>
                    <span class="badge badge-light float-right mr-2">
                <i class="fa fa-clock-o" aria-hidden="true"></i>
                {{ task.estimated_time }} Hrs
            </span>


                </div>
            </div>
        </div>

        <modal-task-new v-if="showNewTaskModal" :module_id="currentModuleId"></modal-task-new>
        <modal-task-view v-if="showViewTaskModal" :task_id="currentTaskId"></modal-task-view>
    </div>

</template>
<style scoped>
    .task{
        padding: 10px;
        border: 1px dashed #b1afaf;
        cursor: pointer;
    }
    .avatar.float-right{
        position: relative;
        right: 0px;
        top: -4px;
    }
    .completed-task {
        border: 2px solid green!important;
    }
</style>
<script>
    export default {
        mounted() {
            let vm = this;
            let module_id = this.$route.params.module_id ? this.$route.params.module_id : '1';
            this.getAllTasksForModule(module_id);
            console.log("Hello from All Task");

            vm.eventHub.$on('newTaskModalClosed', function (e) {
                vm.showNewTaskModal = false;
            });

            vm.eventHub.$on('viewTaskModalClosed', function (e) {
                vm.showViewTaskModal = false;
            });


        },
        watch: {
            '$route' (to, from) {
                console.log(to.params.module_id);
                this.getAllTasksForModule(to.params.module_id);
            }
        },
        data(){
            return {
                module: {},
                dataLoaded: false,
                showNewTaskModal: false,
                currentModuleId: null,
                showViewTaskModal: false,
                currentTaskId: null
            }
        },
        methods:{
            showNewTaskModalf(module_id){
              this.currentModuleId = module_id;
              this.showNewTaskModal = true;
            },
            showViewTaskModalf(task_id){
                this.currentTaskId = task_id;
                this.showViewTaskModal = true;
            },
            getAllTasksForModule(module_id){
                let vm = this;
                axios.get("/api/module/"+ module_id + "/tasks").then(function (response) {
                    vm.module = response.data;
                    vm.dataLoded = true;
                }, function (error) {
                    vm.dataLoded = false;
                    //vm.$router.go('/dashboard/page-404');
                });
            }
        }
    }
</script>
