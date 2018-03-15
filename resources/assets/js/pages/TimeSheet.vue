<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Time Sheet </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5">
                                <flat-pickr
                                        v-model="date"
                                        :config="config"
                                        class="form-control"
                                        placeholder="Select date"
                                        name="date">
                                </flat-pickr>
                            </div>
                            <div class="col-md-5">
                                <vselect :options="users" label="email" v-model="currentUsers" multiple>
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
                            </div>
                            <div class="col-md-2">
                                <button
                                        :disabled="!date" @click="getReports()"
                                        class="btn btn-primary"
                                        :class="{ loader : isLoading}"
                                >Get Reports</button>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card mb-4" v-for="result in data">
                    <div class="card-header">
                        {{ result.email }}
                    </div>
                    <div class="card-body" style="padding: 0px;">
                        <table class="table table-striped bg-white"
                               style="box-shadow: 0 2px 3px rgba(0,0,0,.16);margin-bottom:0px">
                            <thead>
                            <tr>
                                <th class="text-center">Hours</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Task</th>
                                <th class="text-center">Module</th>
                                <th class="text-center">Project</th>
                                <th class="text-center">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="res in result.data">
                                <td class="text-center">
                                    {{ res.hours }}
                                </td>
                                <td class="text-center">
                                    {{ res.date }}
                                </td>
                                <td class="text-center">
                                    {{ res.task.name }}
                                </td>
                                <td class="text-center">
                                    {{ res.module.name }}
                                </td>
                                <td class="text-center">
                                    {{ res.project.name }}
                                </td>
                                <td class="text-center">
                                    {{ res.status.name }}
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    th{
        color: #7746ec;
    }
</style>
<script>
    import flatPickr from 'vue-flatpickr-component';
    import 'flatpickr/dist/flatpickr.css';
    export default {
        components: {
            flatPickr
        },
        mounted() {
            let vm = this;
            axios.get("/api/users?me=true").then(function (response) {
                vm.users = response.data;
            });
        },
        methods:{
            getReports(){
                let vm = this;
                vm.isLoading = true;
                let dates = vm.date.split("to ");
                    let params = {
                        from_date: dates[0],
                        to_date: dates[1],
                        currentUsers: vm.pluck(vm.currentUsers, "id")
                    };
                    axios.get("/api/time-sheet", {params :params})
                        .then(function (success) {
                            vm.data = success.data;
                            vm.isLoading = false;
                        })
                        .catch(function (error) {
                            vm.isLoading = false;
                            alert("Error");
                        })
            }
        },
        data(){
            return {
                currentUsers:[],
                users:[],
                data:[],
                date: '',
                isLoading: false,
                config: {
                    mode: "range",
                    altInput: true,
                    dateFormat: 'Y-m-d',
                },
            }
        }
    }
</script>
