<template>
    <div>
        <h2>Users</h2>
        <form @submit.prevent="addUser" class="mb-3">
            <div class="form-group">
                <input type="text" class="form-control" ref="search" placeholder="Name" v-model="user.name">
            </div>
            <div class="form-group">
                <textarea class="form-control" placeholder="Hobby" v-model="user.hobby"></textarea>
            </div>
            <button type="submit" class="btn btn-success btn-block">Save</button>
        </form>
        <button @click="clearForm()" class="btn btn-info btn-block">Cancel</button>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#"
                        @click="fetchUser(pagination.prev_page_url)">Previous</a></li>

                <li class="page-item disabled"><a class="page-link text-dark" href="#">Page {{ pagination.current_page
                        }} of {{ pagination.last_page }}</a></li>

                <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a class="page-link" href="#"
                        @click="fetchUsers(pagination.next_page_url)">Next</a></li>
            </ul>
        </nav>
        <div class="container">
            <div class="row">
                <div class="card card-body mb-2 mr-1 col-md-3" v-for="user in users" v-bind:key="user.id">
                    <div style="height: 300px;">
                      <h3>{{ user.name }}</h3>
                    <p>{{ user.hobby }}</p>
                    
                    </div><hr>
                    <button @click="editUser(user); setFocus();" class="btn btn-warning mb-2">Edit</button>
                    <button @click="deleteUser(user.id)" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                users: [],
                user: {
                    id: '',
                    name: '',
                    hobby: ''
                },
                user_id: '',
                pagination: {},
                edit: false
            };
        },
        created() {
            this.fetchUsers();
        },
        methods: {
            fetchUsers(page_url) {
                let vm = this;
                page_url = page_url || 'http://127.0.0.1:8000/api/users';
                fetch(page_url)
                    .then(res => res.json())
                    .then(res => {
                        this.users = res.data;
                        vm.makePagination(res.meta, res.links);
                    })
                    .catch(err => console.log(err));
            },
            makePagination(meta, links) {
                let pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev
                };
                this.pagination = pagination;
            },
            deleteUser(id) {
                if (confirm('Are You Sure?')) {
                    fetch(`http://127.0.0.1:8000/api/user/${id}`, {
                            method: 'delete'
                        })
                        .then(res => res.json())
                        .then(data => {
                            alert('User Removed');
                            this.fetchUsers();
                        })
                        .catch(err => console.log(err));
                }
            },
            addUser() {
                if (this.edit === false) {
                    // Add
                    alert('Disabled this funcionality. Click edit button if you want to edit a user');
                    // fetch('http://127.0.0.1:8000/api/user', {
                    //         method: 'post',
                    //         body: JSON.stringify(this.user),
                    //         headers: {
                    //             'content-type': 'application/json'
                    //         }
                    //     })
                    //     .then(res => res.json())
                    //     .then(data => {
                    //         this.clearForm();
                    //         alert('User Added');
                    //         this.fetchUsers();
                    //     })
                    //     .catch(err => console.log(err));
                } else {
                    // Update
                    fetch('http://127.0.0.1:8000/api/user', {
                            method: 'put',
                            body: JSON.stringify(this.user),
                            headers: {
                                'content-type': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            this.clearForm();
                            this.edit = false;
                            alert('User Updated');
                            this.fetchUsers();
                        })
                        .catch(err => console.log(err));
                }
            },
            editUser(user) {
                this.edit = true;
                this.user.id = user.id;
                this.user.user_id = user.id;
                this.user.name = user.name;
                this.user.hobby = user.hobby;
            },
            clearForm() {
                this.edit = false;
                this.user.id = null;
                this.user.user_id = null;
                this.user.name = '';
                this.user.hobby = '';
            },
            setFocus: function () {
                // Note, you need to add a ref="search" attribute to your input.
                this.$refs.search.focus();
            }
        }
    };

</script>
