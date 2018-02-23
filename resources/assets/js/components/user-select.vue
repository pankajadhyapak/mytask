<template>
    <vselect multiple label="email"
             :filterable="false"
             :options="options"
             @search="onSearch"
             v-model="selectedUsers"
             @onChange="$emit('input', selectedUsers)">

        <template slot="no-options">
            type to search users..
        </template>
        <template  slot="option" slot-scope="option">
            <div class="pb-2">
                <div class="avatar">
                    {{option.display_name[0]}}
                </div>
                {{ option.email }}
            </div>
        </template>
    </vselect>

</template>

<script>
    import _ from 'lodash';

    export default {
        data(){
            return {
                options:[],
                selectedUsers:[]
            }
        },
        methods:{
            onSearch(search, loading) {
                loading(true);
                this.search(loading, search, this);
            },
            search:_.debounce(function (loading, search, vm) {
                console.log("Hello inside");
                axios.get("/api/user/search?q="+escape(search)).then(function (response) {
                    vm.options = response.data;
                    loading(false);
                })
            }, 350)
        }
    }
</script>
