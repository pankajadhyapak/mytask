<template>
    <div>
        <h5>Activity</h5>
        <div class="add-comment">
            <form method="post" @submit.prevent="addComment()">
                <div class="form-group">
				<textarea name="body" v-model="newComment.body"
                          placeholder="Write Your comment here..."
                          class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <button
                            type="submit"
                            :disabled="!newComment.body"
                            class="btn btn-primary">Add Comment
                    </button>
                </div>
            </form>
        </div>
        <div class="comment-list">
            <div v-for="comment in comments">
                <div class="comment-item mb-4 row">
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
                <hr>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        props:['comments', 'taskId'],
        data(){
            return {
                newComment: {
                    body: "",
                    type: "Task",
                    type_id: ''
                }
            }
        },
        methods:{
            addComment() {
                let vm = this;
                vm.newComment.type_id = this.taskId;
                axios.post("/api/task/" + vm.taskId + "/comment", vm.newComment)
                    .then(function (response) {
                        vm.comments.unshift(response.data);
                        swal({title: "Comment Added!", icon: "success", timer: 1000});
                        vm.newComment.body = "";
                    }, function (error) {
                        console.log(error);
                    })
            },
        }
    }
</script>
