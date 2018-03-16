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
                            :class="{ loader : isLoading}"
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
        props: ['comments', 'taskId'],
        data() {
            return {
                isLoading: false,
                newComment: {
                    body: "",
                    type: "Task",
                    type_id: ''
                }
            }
        },
        methods: {
            async addComment() {
                this.isLoading = true;
                this.newComment.type_id = this.taskId;
                try {
                    const response = await axios.post("/api/task/" + this.taskId + "/comment", this.newComment);
                    this.isLoading = false;
                    this.comments.unshift(response.data);
                    swal({title: "Comment Added!", icon: "success", timer: 1000});
                    this.newComment.body = "";
                } catch (error) {
                    this.isLoading = false;
                    console.log(error);
                }
            }
        }
    }
</script>
