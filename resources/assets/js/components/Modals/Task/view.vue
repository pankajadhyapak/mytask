<template>
    <div class="modal docked docked-right in" id="viewTask" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header ">
                    <div class="d-flex align-items-center" style="width:100%">

                        <div class="project-title">
                            <h4>{{ _dis(task.name) }}</h4>
                        </div>

                        <div class="filter-btns">
                            <button class="btn btn-outline-success btn-sm ml-3" type="button">
                                Update Task
                            </button>
                        </div>
                    </div>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5>Description</h5>
                            <p>
                                {{ _dis(task.description)}}
                            </p>
                            <hr>
                            <h5>Tags</h5>
                            <p>
                                  coming soon...

                            </p>
                            <hr>
                            <div>
                                <h5>Activity</h5>
                                <div class="add-comment">
                                    <form action="http://mytask.test/comment" method="post" @submit.prevent="addComment()">
                                        <input type="hidden" name="type" value="Task">
                                        <input type="hidden" name="type_id" value="1">
                                        <div class="form-group">
                                            <textarea name="body" v-model="newComment.body" placeholder="Write Your comment here..." class="form-control"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Add Comment</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="comment-list" v-if="dataLoaded">
                                    <div class="comment-item mb-4 row" v-for="comment in task.comments">

                                            <div class="col-md-1">
                                                <div class="avatar">
                                                    {{comment.owner.name[0]}}
                                                </div>
                                            </div>
                                            <div class="col-md-11">
                                                <div>
                                                    <div class="comment-meta">
                                                        <span class="comment-author text-dark">{{comment.owner.display_name}}</span>
                                                        <small>
                                                            <span class="comment-date text-muted">{{formNow(comment.created_at)}} <em>said</em></span>
                                                        </small>
                                                    </div>
                                                    <div class="comment-body text-secondary">
                                                        {{comment.body}}
                                                    </div>
                                                </div>
                                            </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4" v-if="dataLoaded">
                            <!--<div class="">Details</div>-->
                            <h5>Assigned to</h5>
                            <p v-if="task.assigned">
                                <span class="avatar">
                                {{ task.assigned.name[0]}}
                                </span>
                                {{task.assigned.display_name}} ({{task.assigned.email}})
                            </p>
                            <p v-else>

                                <vselect :options="options" label="email">
                                    <template slot="option" slot-scope="option">
                                        <div class="pb-2">
                                            <div class="avatar">
                                                {{option.display_name[0]}}
                                            </div>
                                            {{ option.email }}
                                        </div>
                                    </template>
                                </vselect>
                            </p>
                            <hr>
                            <h5>Status</h5>
                            <p>
                                {{task.status.name}}
                            </p>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">

                                    <h5>Due Date</h5>
                            <p>
                                {{ _dis(task.due_date) }}
                            </p>
                                </div>
                                <div class="col-md-6">
                                    <h5>Created Date</h5>
                            <p>
                                {{ _dis(formatDate(task.created_at)) }}
                            </p>
                                </div>
                            </div>
                            <hr>
                            <h5>Estimated Time</h5>
                            <p>
                                {{ _dis(task.estimated_time) }}
                            </p>
                            <hr>
                            <div class="progress mt-2 mb-2">
                                <div
                                    class="progress-bar progress-bar-striped"
                                    role="progressbar"
                                    :aria-valuenow="(totalHoursLogged/task.estimated_time*100)"
                                    aria-valuemin="0"
                                    :aria-valuemax="task.estimated_time"
                                    :style="progressBarStyle">
                                </div>
                            </div>
                            <h5 class="mb-3">
                                Work Log - <small class="text-muted">{{totalHoursLogged}} Hrs Logged</small>
                                <button class="btn btn-primary btn-sm float-right" data-toggle="collapse" data-target="#addWorkLog" aria-expanded="false" aria-controls="collapseExample">
                                    Log
                                </button>
                            </h5>
                            <div class="collapse" id="addWorkLog">
                                <div class="">
                                    <form @submit.prevent="addWorkLog()" style="padding: 10px">
                                        <div class="row">
                                            <div class="col-md-3" style="padding:0px">
                                                <input type="text"
                                                       v-model="newWorkLog.hours"
                                                       class="form-control form-control-sm"
                                                       placeholder="Hrs">
                                            </div>
                                            <div class="col-md-7" style="padding:0px">
                                                <input type="date"
                                                       v-model="newWorkLog.date"
                                                       class="form-control form-control-sm">
                                            </div>
                                            <div class="col-md-2" style="padding:0px">
                                                <button type="submit" class="btn-outline-success btn btn-sm">
                                                    +
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="log-list mb-3" v-for="log in task.worklogs">
                                <div class="avatar">
                                    {{ log.owner.name[0] }}
                                </div>
                                <span>{{ log.owner.display_name }} logged</span>
                                <strong class="text-success">{{log.hours}} Hrs </strong>
                                on <strong>{{ formatDate(log.created_at, "D MMM") }}</strong>
                            </div>
                            <hr>
                            <button class="btn btn-outline-danger btn-block mt-2" @click="deleteTask(task)">Delete Task</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--<worklogmodal v-if="showWorkLogModal"></worklogmodal>-->
    </div>
</template>
<style scoped>
    .modal-lg {
        max-width: 75%;
    }
    .modal.docked .modal-header {
        padding: 8px 8px 8px 18px;
    }
</style>
<script>
    import worklogmodal from './addworklog';
    export default {
        components:{worklogmodal},
        props:['task_id'],
        mounted() {
            let vm = this;
            let modal = $('#viewTask');
            modal.modal();
            modal.on('hidden.bs.modal', function (e) {
                vm.eventHub.$emit('viewTaskModalClosed');
            });

            axios.get("/api/users").then(function (response) {
               vm.options =  response.data;
            });

            axios.get("/api/task/"+this.task_id).then(function (response) {
                vm.task = response.data;
                vm.dataLoaded = true;
            }, function (error) {

            })
        },
        data(){
            return {
                task: {},
                dataLoaded: false,
                showWorkLogModal: false,
                options: [],
                newWorkLog:{
                    hours:'',
                    date:''
                },
                newComment: {
                    body: "",
                    type: "Task",
                    type_id: this.task_id ? this.task_id : ''
                }
            }
        },
        methods:{
            async addWorkLog(){
                try{
                    const response = await axios.post("/api/task/"+this.task_id+"/work-log", this.newWorkLog);
                    this.task.worklogs.push(success.data);
                    flash("Work Log Added")
                    $('#addWorkLog').collapse('hide')
                    this.newWorkLog.date = "";
                    this.newWorkLog.hours = "";
                }catch (error){
                    flash("Error Adding Work Log", "danger")
                }
            },
            async addComment(){
                try{
                    const response = await axios.post("/api/task/"+this.task_id+"/comment", this.newComment);
                    this.task.comments.unshift(response.data);
                    swal({ title :"Comment Added!", icon:"success" ,timer:1000});
                    this.newComment.body = "";
                }catch (error){
                    flash("Error adding comment", "danger");
                }
            },
            async deleteTask(task){
                try{
                    await swal({
                        title: "Are you sure?",
                        text: "Once deleted, you will not be able to recover this task!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    });
                    try {
                        const response = await axios.delete("/api/task/" + task.id);
                        swal({
                            title: "Deleted",
                            text: "Your Task is deleted",
                            icon: "success",
                            timer: 1500
                        })
                    } catch (error) {
                        swal({
                            title: "Opps",
                            text: "There was Some Error in deleting you Task!",
                            icon: "warning",
                            timer: 1500
                        })
                    }
                }catch (error){

                }
            }
        },
        computed:{
            progressBarStyle(){
                if(this.task.estimated_time){
                    let completed = this.totalHoursLogged/this.task.estimated_time*100;
                    return 'width:'+completed+'%'
                }else{
                    return 'width:0%';
                }
            },
            totalHoursLogged(){
                let hrs = 0;
                this.task.worklogs.forEach(function(log){
                    hrs = hrs+ parseInt(log.hours);
                });
                return hrs;
            }
        },
    }
</script>
