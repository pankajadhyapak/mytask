<template>
    <div>
        <h5 class="mb-3">
            Work Log -
            <small class="text-muted">{{hrsLogged}} Hrs Logged</small>
            <button class="btn btn-primary btn-sm float-right" data-toggle="collapse"
                    data-target="#addWorkLog" aria-expanded="false" aria-controls="collapseExample">
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
        <div class="log-list mb-3" v-for="log in logs">
            <div class="avatar">
                {{ log.owner.name[0] }}
            </div>
            <span>{{ log.owner.display_name }} logged</span>
            <strong class="text-success">{{log.hours}} Hrs </strong>
            on <strong>{{ formatDate(log.created_at, "D MMM") }}</strong>
        </div>
        <hr>
    </div>
</template>

<script>
    export default {

        mounted() {
            console.log('Component mounted.')
        },
        props:['hrsLogged', 'logs', 'taskId'],
        data(){
            return {
                newWorkLog: {
                    hours: '',
                    date: ''
                }
            }
        },
        methods:{
            addWorkLog() {
                let vm = this;
                axios.post("/api/task/" + vm.taskId + "/work-log", vm.newWorkLog)
                    .then(function (success) {
                        vm.logs.push(success.data);
                        flash("Work Log Added");
                        $('#addWorkLog').collapse('hide');
                        vm.newWorkLog.date = "";
                        vm.newWorkLog.hours = "";
                    })
                    .catch(function (error) {
                        flash("Error Adding Work Log", "danger")
                    })
            },
        }
    }
</script>