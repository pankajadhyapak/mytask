<template>
    <div class="container">
        <h4>
            {{ project.name}} Project - <small class="text-muted">{{ project.modules.length}} Modules</small>
            <button class="btn btn-outline-dark btn-sm float-right">New Module</button>
        </h4>

        <div class="module-container mb-3" v-for="module in project.modules" :key="module.id">

            <div class="card card-header module-heading">

                <div class="row">
                    <div class="col-md-8"
                         style="cursor:pointer"
                         data-toggle="collapse"
                         :data-target="'#module'+module.id">
                        {{ module.name}} - <small>{{ module.tasks.length }} Tasks</small>
                    </div>
                    <div class="col-md-4">
                        Tasks <small> - <i class="fa fa-clock-o mr-1" aria-hidden="true"></i>{{ module.total_estimated }} Hrs</small>
                        <button class="btn btn-outline-primary btn-sm float-right" @click="showNewTaskModalf(module.id)">New Task</button>
                    </div>
                </div>

            </div>

            <div class="collapse show" :id="'module'+module.id">
                <div class="card card-body">
                    <div class="">
                        <input type="text"
                               @keyup.enter="addQuickTask(module.id)"
                               v-model="quickTask"
                               class="form-control mb-2"
                               placeholder="Add Quick Task">
                        <div class="task mb-2"
                             v-for="task in module.tasks.slice().reverse()"
                             :class=" task.is_completed ? 'completed-task' : ''">

                            <i @click.prevent="markCompleted(module.id, task.id)"
                               class="fa fa-check-circle-o mr-2 complete-icon float-left"
                               v-tooltip:bottom="'{task.is_completed ? '/markInCompleteMsg/' : '/markCompleteMsg/'}'"
                               :data-original-title="task.is_completed ? markInCompleteMsg : markCompleteMsg"
                               data-placement="bottom">
                            </i>

                            <span @click="showViewTaskModalf(task.id)" style="display: block">
                            {{ task.name }}
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

                        <div class="empty_list text-center mb-5 mt-5" v-if="!module.tasks.length">
                            <i class="fa fa-list mb-3" aria-hidden="true" style="font-size:100px;color: #7c7c7d;"></i>
                            <h4>You Dont have any Task!!</h4>
                            <button class="btn btn-outline-primary mt-2"
                                    @click="showNewTaskModalf(module.id)">Create New Task</button>

                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!--<div class="row">-->
            <!--<div class="col-md-4">-->
                <!--<div class="card card-default">-->
                    <!--<div class="card-header">Modules</div>-->

                    <!--<div class="card-body">-->
                        <!--<router-link-->
                                <!--class="nav-link"-->
                                <!--v-for="module in project.modules" :key="module.id"-->
                                <!--:to="{path: '/dashboard/project/' + project.id + '/module/' + module.id}"-->
                        <!--&gt;-->
                            <!--{{ module.name }}-->
                        <!--</router-link>-->
                    <!--</div>-->
                <!--</div>-->
            <!--</div>-->
            <!--<div class="col-md-8">-->
                <!--<router-view></router-view>-->
            <!--</div>-->
        <!--</div>-->

        <modal-task-new v-if="showNewTaskModal" :module_id="currentModuleId"></modal-task-new>
        <modal-task-view v-if="showViewTaskModal" :task_id="currentTaskId"></modal-task-view>
    </div>
</template>
<style>
    .complete-icon{
        color:#333;
        font-size: 18px;
    }
    .complete-icon:hover{
        color:green;
    }
    .completed-task .complete-icon{
        color:green;
    }
    .completed-task .complete-icon:hover{
        color:#333;
    }
    .task {
        padding: 10px;
        border: 1px dashed #b1afaf;
        cursor: pointer;
    }
    .module-heading{
        box-shadow: none;
        border-radius: 0;
        background: #dcdcdc;
    }

    .avatar.float-right {
        position: relative;
        right: 0;
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

            vm.fetchProject(this.$route.params.id);

            vm.eventHub.$on('newTaskModalClosed', function(e) {
                vm.showNewTaskModal = false;
            });
            vm.eventHub.$on('viewTaskModalClosed', function(e) {
                vm.showViewTaskModal = false;
            });

        },
        data(){
            return {
                project:{},
                dataLoaded: false,
                showNewTaskModal: false,
                currentModuleId: null,
                showViewTaskModal: false,
                currentTaskId: null,
                quickTask: "",
                markCompleteMsg:"Mark Done",
                markInCompleteMsg: "Mark InComplete"
            }
        },
        watch: {
            '$route' (to, from) {
                this.fetchProject(to.params.id);
            }
        },
        methods:{
            addQuickTask(moduleId){
                let vm = this;
                console.log(moduleId);

                axios.post("/api/task", {
                    name: vm.quickTask,
                    project_id: vm.project.id,
                    module_id: moduleId
                }).then(function (success) {
                    let module = _.find(vm.project.modules, ['id', success.data.module_id]);
                    module.tasks.push(success.data);
                    swal({ title :"Great", text:"Task Added!", icon:"success", timer:1000});

                    console.log(module);
                }, function (error) {
                    
                });
                this.quickTask = "";
            },
            markCompleted(moduleId, taskId){
                let module = _.find(this.project.modules, ['id', moduleId]);
                let task = _.find(module.tasks, ['id', taskId]);
                //axios call
                task.is_completed = !task.is_completed;
            },
            toolTipText(task){
                if(task.is_completed) {
                    return "Mark Incomplete";
                }
                return "Mark Complete";
            },
            test(){
                console.log("Hello");
            },
            showNewTaskModalf(module_id) {
                this.currentModuleId = module_id;
                this.showNewTaskModal = true;
            },
            showViewTaskModalf(task_id) {
                this.currentTaskId = task_id;
                this.showViewTaskModal = true;
            },
            fetchProject(id){
                let vm = this;
                axios.get("/api/project/"+id).then(function (response) {
                    vm.project = response.data;
                    vm.dataLoded = true;
                }, function (error) {
                    vm.dataLoded = false;
                    //vm.$router.go('/dashboard/404');
                });
            }
        }
    }
</script>
