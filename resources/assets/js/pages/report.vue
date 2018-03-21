<template>
    <div class="container">
        <div class="report" v-if="report.total">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            Task Wise - <small>(Total {{ report.total}})</small>
                        </div>
                        <div class="card-body">
                            <div v-for="stat in report.status" >
                                <h6 class="report-item">
                                    {{ stat.name }}
                                    <strong class="text-primary">{{ stat.count}}</strong>
                                </h6>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            User Wise
                        </div>
                        <div class="card-body">
                            <div v-for="user in report.users">
                                <h6 class="report-item">
                                    {{ user.email == '' ? 'Un Assigned' : user.email }}
                                    <strong class="text-primary">{{ user.count}}</strong>
                                </h6>
                                <hr>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <div>
                        <div class="card">
                            <div class="card-body">
                                <canvas id="task-wise"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div>
                        <div class="card">
                            <div class="card-body">
                                <canvas id="user-wise"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="dataempty" class="no-report">
            <i class="fa fa-pie-chart" aria-hidden="true"></i>
            <h4>It Seems you don't have any task</h4>
            <h5 class="mt-2">Reports will be shown here once task are added</h5>
        </div>
    </div>
</template>
<style scoped>
    h6.report-item strong{
        float: right;
    }
    .no-report{
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin-top:100px;
    }
    .no-report .fa{
        font-size: 120px;
        color: #5868e2;
        margin-bottom: 40px;
    }
</style>
<script>
    export default {
        mounted() {
            this.getReport();
        },
        data() {
            return {
                report: {},
                dataempty: false,
                projectId: this.$route.params.id
            }
        },
        methods: {
            renderReport() {
                try {
                    let config1 = {
                        type: 'doughnut',
                        data: {
                            datasets: [
                                {
                                    data: this.pluck(this.report.status, 'count'),
                                    backgroundColor: this.pluck(this.report.status, 'color'),
                                    label: 'Task Wise'
                                }
                            ],
                            labels: this.pluck(this.report.status, 'name')
                        },
                        options: {
                            responsive: true,
                            legend: {
                                position: 'top',
                            },
                            animation: {
                                animateScale: false,
                                animateRotate: true
                            }
                        }
                    };
                    let config2 = {
                        type: 'doughnut',
                        data: {
                            datasets: [
                                {
                                    data: this.pluck(this.report.users, 'count'),
                                    backgroundColor: this.pluck(this.report.users, 'color'),
                                    label: 'User Wise'
                                }
                            ],
                            labels: this.pluck(this.report.users, 'email')
                        },
                        options: {
                            responsive: true,
                            legend: {
                                position: 'top',
                            },
                            animation: {
                                animateScale: false,
                                animateRotate: true
                            }
                        }
                    };

                    let ctx1 = document.getElementById('task-wise');
                    if(ctx1){
                        new Chart(ctx1.getContext('2d'), config1);
                    }

                    let ctx2 = document.getElementById('user-wise');
                    if(ctx2){
                        new Chart(ctx2.getContext('2d'), config2);
                    }

                } catch (e) {
                    console.log(e);
                }
            },
            getReport() {
                let vm = this;
                axios.get("/api/project/" + vm.projectId + "/report")
                    .then(function (success) {
                        vm.report = success.data;
                        if (vm.report.total) {
                            vm.$nextTick(() => {
                                vm.renderReport();
                            });
                        }else{
                            vm.dataempty = true
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                        alert("Unable to load report")
                    })
            }
        }
    }
</script>
