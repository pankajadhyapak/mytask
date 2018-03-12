<template>
    <div class="card card-default">
        <div class="card-header">
            Tasks <small> - <i class="fa fa-clock-o mr-1" aria-hidden="true"></i>{{ module.total_estimated }} Hrs</small>

            <span class="float-right">
                <button class="btn btn-outline-primary btn-sm" @click="showNewTaskModalf(module.id)">New Task</button>
            </span>
        </div>

        <div class="card-body" style="padding: 8px;" v-if="dataLoaded">
            <div class="">
                <div class="task mb-2"
                     v-for="task in module.tasks"
                     :class=" task.is_completed ? 'completed-tasknew' : ''"
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

                <div class="empty_list text-center mb-5 mt-5" v-if="!module.tasks.length">
                    <i class="fa fa-list mb-3" aria-hidden="true" style="font-size:100px;color: #7c7c7d;"></i>
                    <h4>You Dont have any Task!!</h4>
                    <button class="btn btn-outline-primary mt-2"
                            @click="showNewTaskModalf(module.id)">Create New Task</button>

                </div>
            </div>
        </div>

        <modal-task-new v-if="showNewTaskModal" :module_id="currentModuleId"></modal-task-new>
        <modal-task-view v-if="showViewTaskModal" :task_id="currentTaskId"></modal-task-view>
    </div>

</template>
<style scoped>
    .task {
        padding: 10px;
        border: 1px dashed #b1afaf;
        cursor: pointer;
    }

    .avatar.float-right {
        position: relative;
        right: 0px;
        top: -4px;
    }

    .completed-task {
        border: 2px solid green !important;
        color: #969696 !important;
    }
</style>
<script>
    export default {
        mounted() {
            let vm = this;
            let module_id = this.$route.params.module_id ? this.$route.params.module_id : '1';
            this.getAllTasksForModule(module_id);
            console.log("Hello from All Task");

            vm.eventHub.$on('newTaskModalClosed', function(e) {
                vm.showNewTaskModal = false;
            });

            vm.eventHub.$on('viewTaskModalClosed', function(e) {
                vm.showViewTaskModal = false;
            });


        },
        watch: {
            '$route' (to, from) {
                this.getAllTasksForModule(to.params.module_id);
            }
        },
        data() {
            return {
                module: {},
                dataLoaded: false,
                showNewTaskModal: false,
                currentModuleId: null,
                showViewTaskModal: false,
                currentTaskId: null
            }
        },
        methods: {
            showNewTaskModalf(module_id) {
                this.currentModuleId = module_id;
                this.showNewTaskModal = true;
            },
            showViewTaskModalf(task_id) {
                // this.currentTaskId = task_id;
                // this.showViewTaskModal = true;
                this.$router.push({ path: `task/${task_id}` })
            },
            getAllTasksForModule(module_id) {
                let vm = this;
                axios.get("/api/module/" + module_id + "/tasks").then(function(response) {
                    vm.module = response.data;
                    vm.dataLoaded = true;
                }, function(error) {
                    vm.dataLoaded = false;
                    //vm.$router.go('/dashboard/page-404');
                });
            }
        }
    }
</script>
