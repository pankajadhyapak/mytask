<template>
    <div class="container">
        <h4>{{ project.name}} Project</h4>
        <div class="row">
            <div class="col-md-4">
                <div class="card card-default">
                    <div class="card-header">Modules</div>

                    <div class="card-body">
                        <router-link
                                class="nav-link"
                                v-for="module in project.modules" :key="module.id"
                                :to="{path: '/dashboard/project/' + project.id + '/module/' + module.id}"
                        >
                            {{ module.name }}
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Tasks</div>

                    <div class="card-body">
                        <router-view></router-view>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            let vm = this;
            axios.get("/api/project/"+this.$route.params.id).then(function (response) {
                vm.project = response.data;
                vm.dataLoded = true;
            }, function (error) {
                vm.dataLoded = false;
                //vm.$router.go('/dashboard/404');
            });
        },
        data(){
            return {
                project: {},
                dataLoaded: false
            }
        }
    }
</script>
