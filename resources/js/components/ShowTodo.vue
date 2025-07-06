<template>
    <div class="container py-4">
        <h1 class="mb-4">Todo Details</h1>
        <div class="card" v-if="todo.id">
            <div class="card-header bg-primary text-white">
                <h2 class="card-title mb-0">{{ todo.title }}</h2>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Description:</dt>
                    <dd class="col-sm-9">{{ todo.description }}</dd>

                    <dt class="col-sm-3">Due Date:</dt>
                    <dd class="col-sm-9">{{ todo.due_date ? new Date(todo.due_date).toLocaleDateString() : 'N/A' }}</dd>

                    <dt class="col-sm-3">Status:</dt>
                    <dd class="col-sm-9">{{ capitalizeFirstLetter(todo.status) }}</dd>

                    <dt class="col-sm-3">Category:</dt>
                    <dd class="col-sm-9">{{ todo.category_type }}</dd>
                </dl>

                <div v-if="todo.extra_data && Object.keys(JSON.parse(todo.extra_data)).length > 0">
                    <h4 class="mt-4">Additional Details:</h4>
                    <dl class="row">
                        <div v-for="(value, key) in JSON.parse(todo.extra_data)" :key="key" class="row g-0">
                            <dt class="col-sm-3">{{ capitalizeFirstLetter(key.replace('_', ' ')) }}:</dt>
                            <dd class="col-sm-9">{{ value }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <a :href="`/todos/${todo.id}/edit`" class="btn btn-warning">Edit</a>
                <button @click="deleteTodo" class="btn btn-danger">Delete</button>
                <a href="/todos" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: ['todoId'],
    data() {
        return {
            todo: {}
        };
    },
    created() {
        this.fetchTodo();
    },
    methods: {
        fetchTodo() {
            axios.get(`/api/todos/${this.todoId}`)
                .then(response => {
                    this.todo = response.data;
                })
                .catch(error => {
                    console.error('Error fetching todo:', error);
                    alert('Error fetching todo. Please check the console for details.');
                });
        },
        deleteTodo() {
            if (confirm('Are you sure you want to delete this todo?')) {
                axios.delete(`/api/todos/${this.todo.id}`)
                    .then(response => {
                        console.log('Todo deleted:', response.data);
                        window.location.href = '/todos'; // Redirect to todo list page
                    })
                    .catch(error => {
                        console.error('Error deleting todo:', error);
                        alert('Error deleting todo. Please check the console for details.');
                    });
            }
        },
        capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
    }
}
</script>

<style scoped>
/* Add any specific styles for this component here */
</style>