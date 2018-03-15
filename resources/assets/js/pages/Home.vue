<template>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card card-default">
                    <div class="card-header">Teams</div>
                    <div class="card-body">
                        I'm an example component.
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-default">
                    <div class="card-header">Projects</div>
                    <div class="card-body">
                        I'm an example component.
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-default">
                    <div class="card-header">My Tasks</div>
                    <div class="card-body">
                        I'm an example component.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

    export default {
        mounted() {
            console.log('Component mounted.');
            this.getInitData();

        },
        data(){
            return {
                teams: [],
                tasks:[],
                selectedUsers:[],
                options: [],
                isLoading: false
            }
        },
        methods:{
            test(){

            },
            getInitData(){
                //axios.get("")
            },
        }
    }
</script>
