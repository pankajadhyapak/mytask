<template>
    <div>
        <div class="card card-header module-heading">
            <div class="d-flex align-items-center"
                 style="cursor:pointer"
                 data-toggle="collapse"
                 :data-target="'#module'+module.id">

                <div class="project-title">
                    {{ module.name}}&nbsp;
                    <small style="margin-top:2px;">
                        <span class="text-muted">
                            ({{ module.tasks.length }} Tasks)
                        </span>
                    </small>
                </div>

                <div class="filter-btns">
                    <span class="mr-2" v-if="moduleEstimate">
                        Total <small> - <i class="fa fa-clock-o mr-1" aria-hidden="true">
                        </i>{{ moduleEstimate }} Hrs</small>
                    </span>
                    <!--<button-->
                            <!--class="btn btn-outline-primary btn-sm float-right"-->
                            <!--@click.stop="showNewTaskModalf(module.id)">New Task</button>-->
                </div>
            </div>
        </div>
        <div class="collapse show" :id="'module'+module.id">
            <div class="card card-body">
                <div>
                    <input type="text"
                           @keyup.enter="addQuickTask(module.id)"
                           v-model="quickTask"
                           class="form-control mb-2"
                           placeholder="Add Quick Task">

                    <project-task
                            v-if="filteredTask.length"
                            :task="task"
                            :module="module.id"
                            :project="module.project_id"
                            v-for="(task, index) in filteredTask"
                            :key="index"
                            @clicked="showViewTaskModalf(task.id)">

                    </project-task>

                    <div v-if="!filteredTask.length">
                        <div class="empty_list text-center mb-5 mt-5">
                            <!--<i class="fa fa-list mb-3" aria-hidden="true" style="font-size:100px;color: #7c7c7d;"></i>-->
                            <h4 v-if="!searchKey">You Don't have any Task!!<br><br>
                                <small class="text-muted">Create Quick Task</small>
                            </h4>
                            <h4 v-if="searchKey">
                                No tasks found for the search !!
                            </h4>
                            <!--<button class="btn btn-outline-primary mt-2"-->
                                    <!--@click="showNewTaskModalf(module.id)">Create New Task</button>-->
                        </div>
                    </div>

                    <!--<div class="empty_list text-center mb-5 mt-5" v-if="!module.tasks.length">-->
                        <!--<i class="fa fa-list mb-3" aria-hidden="true" style="font-size:100px;color: #7c7c7d;"></i>-->
                        <!--<h4>You Dont have any Task!!</h4>-->
                        <!--<button class="btn btn-outline-primary mt-2"-->
                                <!--@click="showNewTaskModalf(module.id)">Create New Task</button>-->
                    <!--</div>-->
                </div>
            </div>
        </div>
        <modal-task-new v-if="showNewTaskModal" :module_id="currentModuleId"/>
        <modal-task-view v-if="showViewTaskModal" :task_id="currentTaskId" @closed="showViewTaskModal = false"/>

    </div>
</template>

<script>
    export default {
        props:['module'],

        mounted() {
            let vm = this;
            vm.eventHub.$on('newTaskModalClosed', function(e) {
                vm.showNewTaskModal = false;
            });
            vm.eventHub.$on('viewTaskModalClosed', function(e) {
                vm.showViewTaskModal = false;
            });
            vm.eventHub.$on('filterBy', function(e) {
                vm.currentFilter = e;
            });
            vm.eventHub.$on('searchKeyChanged', function(e) {
                vm.searchKey  = e;
            });
            vm.eventHub.$on("taskDeleted", function (e) {
                vm.tasks = _.reject(vm.tasks, function(o) { return o.id == e.id });
            });
            vm.eventHub.$on("taskUpdated", function (e) {
                let task = _.find(vm.tasks, ['id', e.id]);
                if(task){
                    for (let attr in e) {
                        task[attr] = e[attr];
                    }
                }
            })
        },
        data(){
          return {
              quickTask:'',
              showNewTaskModal: false,
              currentModuleId: null,
              showViewTaskModal: false,
              currentTaskId: null,
              currentFilter: 'all',
              searchKey:'',
              tasks: this.module.tasks ? this.module.tasks : []
          }
        },
        computed:{
            moduleEstimate(){
                return _.sumBy(this.tasks, function(o) {
                    if(o.estimated_time){
                        return parseInt(o.estimated_time);
                    }
                     return 0;
                });
            },
            filteredTask(){
                let vm = this;
                let taskLists = [];

                if(vm.currentFilter === 'all'){
                    taskLists = vm.tasks
                }
                if(vm.currentFilter === 'complete'){
                    taskLists =  _.filter(vm.tasks, function(o) { return o.is_completed; });
                }
                if(vm.currentFilter === 'in complete'){
                    taskLists =  _.filter(vm.tasks, function(o) { return !o.is_completed; });
                }
                if(vm.searchKey){
                    taskLists = taskLists.filter(task => { return task.name.toLowerCase().includes(vm.searchKey.toLowerCase())});
                }
                return taskLists.slice().reverse();
            }
        },
        methods:{
            showNewTaskModalf(module_id) {
                this.currentModuleId = module_id;
                this.showNewTaskModal = true;
            },
            showViewTaskModalf(task_id) {
                // this.currentTaskId = task_id;
                // this.showViewTaskModal = true;
                this.$router.push({ path: `/project/${this.module.project_id}/tasks/${task_id}` })
            },
            reverse(tasks) {
                return tasks.slice().reverse();
            },

            addQuickTask(moduleId){
                let vm = this;
                console.log(moduleId);
                axios.post("/api/task", {
                    name: vm.quickTask,
                    project_id: vm.module.project_id,
                    module_id: moduleId
                }).then(function (success) {
                    vm.module.tasks.push(success.data);
                    flash("Great !! Task Added");
                }, function (error) {
                    flash("Unable to Create task", "danger");
                });
                this.quickTask = "";
            },
        }
    }
</script>
