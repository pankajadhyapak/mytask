<template>
    <div class="modal docked docked-right in" id="viewTask" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header ">
                    <div class="d-flex align-items-center" style="width:100%">
                        <div class="project-title">
                            <h4 v-if="!editTask">{{ _dis(task.name) }}</h4>
                            <input type="text" class="form-control" v-model="task.name" v-else>
                        </div>
                        <div class="filter-btns">
                            <button
                                    @click="editTask = true"
                                    v-if="!editTask"
                                    class="btn btn-outline-info btn-sm ml-3" type="button">
                                Edit Task
                            </button>
                            <button
                                    @click="updateTask()"
                                    v-if="editTask"
                                    class="btn btn-outline-success btn-sm ml-3" type="button">
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
                            <p v-if="!editTask">
                                {{ _dis(task.description)}}
                            </p>
                            <textarea class="form-control" v-model="task.description" v-else></textarea>
                            <hr>
                            <h5>Tags</h5>
                            <div class="tags" v-if="!editTask">
                                <span
                                        v-for="tag in task.tags"
                                        class="badge badge-info mr-2">
                                    {{tag.name}}
                                </span>
                            </div>
                            <div class="tags" v-else>
                                <vselect
                                        multiple
                                        taggable
                                        push-tags
                                        :options="alltags"
                                        v-model="task.tags"
                                        label="name"
                                />
                            </div>
                            <hr>
                            <activity
                                    v-if="dataLoaded"
                                    :taskId="task.id"
                                    :comments="task.comments"/>
                        </div>
                        <div class="col-md-4" v-if="dataLoaded">
                            <!--<div class="">Details</div>-->
                            <h5>Assigned to</h5>
                            <p v-if="task.assigned && !editTask">
							<span class="avatar">
							{{ task.assigned.name[0]}}
							</span>
                                {{task.assigned.display_name}} ({{task.assigned.email}})
                            </p>
                            <p v-if="!editTask && !task.assigned">
                                Not Assigned
                            </p>
                            <p v-if="editTask">
                                <vselect :options="options" label="email" v-model="task.assigned">
                                    <template slot="option" slot-scope="option">
                                        <div class="pb-2">
                                            <div class="avatar">
                                                {{option.display_name[0]}}
                                            </div>
                                            {{ option.email }}
                                            <span v-if="authUser.id == option.id">
                                                (You)
                                            </span>
                                        </div>
                                    </template>
                                </vselect>
                            </p>
                            <hr>
                            <h5>Status</h5>

                            <p v-if="!editTask">
                                {{task.status.name}}
                            </p>

                            <select class="form-control" v-else v-model="task.status_id">
                                <option
                                        v-for="status in statues"
                                        :value="status.id">

                                    {{ status.name }}
                                    <span v-if="status.default">
                                        (Default)
                                    </span>
                                    <span v-if="status.defines_complete">
                                        (completed)
                                    </span>
                                </option>
                            </select>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Due Date</h5>
                                    <p v-if="!editTask">
                                        {{ _dis(task.due_date) }}
                                    </p>
                                    <input type="date" class="form-control" v-else v-model="task.due_date">
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
                            <p v-if="!editTask">
                                {{ _dis(task.estimated_time) }}
                            </p>
                            <input type="number" class="form-control" v-model="task.estimated_time" v-else>
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
                            <worklog
                                    v-if="dataLoaded"
                                    :hrsLogged="totalHoursLogged"
                                    :taskId="task.id"
                                    :logs="task.worklogs"
                            />
                            <button class="btn btn-outline-danger btn-block mt-2" @click="deleteTask(task)">Delete
                                Task
                            </button>
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
    import worklog from './worklog';
    import activity from './activity';

    export default {
        components: {worklog, activity},
        mounted() {
            let vm = this;
            $('#viewTask').on('hidden.bs.modal', function (e) {
                vm.$router.push({path: `/project/${vm.$route.params.id}`});
            });
            vm.fetchTask();
            vm.fetchUsers();
            axios.get("/api/all-tags").then(function (success) {
                vm.alltags = success.data;
            })
        },
        watch: {
            '$route'(to, from) {
                this.fetchTask();
            }
        },
        data() {
            return {
                alltags:[],
                editTask: false,
                task: {},
                statues: [],
                dataLoaded: false,
                showWorkLogModal: false,
                options: [],
            }
        },
        methods: {
            //TODO update only if task is changed
            async updateTask() {
                try {
                    this.task.statues = this.statues;
                    const willUpdate = await swal({
                        title: "Are you sure?",
                        text: "To update this task ?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    });
                    if (willUpdate) {
                        try {
                            const response = await axios.put("/api/task/" + this.task.id, this.task);
                            console.log(response);
                            this.eventHub.$emit("taskUpdated", response.data.task);
                            this.task = response.data.task;
                            this.editTask = false;
                            swal({
                                title: "Updated",
                                text: "Your Task is updated",
                                icon: "success",
                                timer: 1200
                            })
                        } catch (error) {
                            console.error(error);
                            swal({
                                title: "Oops",
                                text: "There was Some Error in updating you Task!",
                                icon: "warning",
                                timer: 1200
                            })
                        }
                    }
                } catch (error) {
                    console.error(error);
                }
            },
            async fetchTask() {
                try {
                    const response = await axios.get("/api/task/" + this.$route.params.task_id);
                    this.task = response.data.task;
                    this.statues = response.data.status;
                    this.dataLoaded = true;
                    $('#viewTask').modal();
                } catch (error) {

                }
            },
            fetchUsers() {
                let vm = this;
                axios.get("/api/users?me=true&team=" + vm.$route.params.id).then(function (response) {
                    vm.options = response.data;
                });
            },
            async deleteTask(task) {
                try {
                    const willDelete = await swal({
                        title: "Are you sure?",
                        text: "Once deleted, you will not be able to recover this task!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    });
                    if (willDelete) {
                        try {
                            const response = await axios.delete("/api/task/" + task.id);
                            this.eventHub.$emit('taskDeleted', task);
                            $('#viewTask').modal('hide');
                            swal({
                                title: "Deleted",
                                text: "Your Task is deleted",
                                icon: "success",
                                timer: 1500
                            })
                        } catch (error) {
                            console.error(error);
                            swal({
                                title: "Opps",
                                text: "There was Some Error in deleting you Task!",
                                icon: "warning",
                                timer: 1500
                            })
                        }
                    }
                } catch (error) {
                    console.error(error);
                }
            }
        },
        computed: {
            progressBarStyle() {
                if (this.task.estimated_time) {
                    let completed = this.totalHoursLogged / this.task.estimated_time * 100;
                    return 'width:' + completed + '%'
                } else {
                    return 'width:0%';
                }
            },
            totalHoursLogged() {
                let hrs = 0;
                this.task.worklogs.forEach(function (log) {
                    hrs = hrs + parseInt(log.hours);
                });
                return hrs;
            }
        },
    }
</script>
