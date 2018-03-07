<template>
    <div class="modal docked docked-right in" @click.stop="closeModal()"
         id="viewTask">
        <div class="modal-dialog modal-lg" role="document" @click.stop="">
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
                                    <div class="comment-item mb-4" v-for="comment in task.comments">
                                        <div class="avatar">
                                            {{comment.owner.name[0]}}
                                        </div>
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
                                        :style=" 'width:'+(totalHoursLogged/task.estimated_time*100)+'%'">
                                </div>
                            </div>
                            <h5 class="mb-3">
                                Work Log - <small class="text-muted">{{totalHoursLogged}} Hrs Logged</small>
                                <button @click="showWorkLogModal = true" class="btn btn-primary btn-sm float-right">Log</button>
                            </h5>
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
        <worklogmodal v-if="showWorkLogModal"></worklogmodal>
    </div>
</template>
<style scoped>
    .modal{
        display: block;
        background: rgba(0, 0, 0, 0.52);
    }
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
            //modal.modal();
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
                newComment: {
                    body: "",
                    type: "Task",
                    type_id: this.task_id ? this.task_id : ''
                }
            }
        },
        methods:{
            closeModal(){
                this.$emit("closed");
            },
            addComment(){
                let vm = this;
                axios.post("/api/task/"+this.task_id+"/comment", vm.newComment)
                    .then(function (response) {
                        vm.task.comments.unshift(response.data);
                        swal({ title :"Comment Added!", icon:"success" ,timer:1000});
                        vm.newComment.body = "";

                    }, function (error) {
                        console.log(error);
                    })
            },
            deleteTask(task){
                let vm = this;

                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this task!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            axios.delete("/api/task/"+task.id).then((response)=> {
                                swal({
                                    title :"Deleted",
                                    text:"Your Task is deleted",
                                    icon:"success",
                                    timer:1500
                                })
                            }, (error) =>{
                                swal({
                                    title :"Opps",
                                    text:"There was Some Error in deleting you Task!",
                                    icon:"warning",
                                    timer:1500
                                })

                            });
                        }
                    });
            }
        },
        computed:{
            totalHoursLogged(){
                let hrs = 0;
                this.task.worklogs.forEach(function(log){
                    hrs = hrs+log.hours;
                });
                return hrs;
            }
        },
    }
</script>
