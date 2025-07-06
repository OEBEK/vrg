<template>
    <div class="container py-4">
        <h1 class="mb-4">Edit Todo</h1>
        <div class="card p-4">
            <form @submit.prevent="updateTodo">
                <div class="mb-3">
                    <label for="title" class="form-label">Title:</label>
                    <input type="text" id="title" v-model="todo.title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description:</label>
                    <textarea id="description" v-model="todo.description" class="form-control" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="due_date" class="form-label">Due Date:</label>
                    <input type="date" id="due_date" v-model="todo.due_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status:</label>
                    <select id="status" v-model="todo.status" class="form-select" required>
                        <option value="offen">Offen</option>
                        <option value="erledigt">Erledigt</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="category_type" class="form-label">Category:</label>
                    <select id="category_type" v-model="todo.category_type" class="form-select" required @change="handleCategoryChange">
                        <option v-for="category in categories" :key="category" :value="category">{{ category }}</option>
                    </select>
                </div>

                <!-- Conditional fields based on category_type -->
                <div v-if="todo.category_type === 'WorkTask'" class="mb-3">
                    <label for="priority" class="form-label">Priority:</label>
                    <input type="text" id="priority" v-model="todo.priority" class="form-control" required>
                </div>

                <div v-if="todo.category_type === 'PersonalTask'" class="mb-3">
                    <label for="mood" class="form-label">Mood:</label>
                    <input type="text" id="mood" v-model="todo.mood" class="form-control" required>
                </div>

                <div v-if="todo.category_type === 'ShoppingTask'" class="mb-3">
                    <label for="estimated_cost" class="form-label">Estimated Cost:</label>
                    <input type="number" id="estimated_cost" v-model="todo.estimated_cost" class="form-control" required step="0.01">
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Update Todo</button>
                    <a :href="`/todos/${todo.id}`" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: ['todoId'],
    data() {
        return {
            todo: {
                id: null,
                title: '',
                description: '',
                due_date: '',
                status: 'offen',
                category_type: '',
                priority: '',
                mood: '',
                estimated_cost: null,
                extra_data: {}
            },
            categories: []
        };
    },
    created() {
        this.fetchCategories();
        this.fetchTodo();
    },
    methods: {
        fetchCategories() {
            axios.get('/api/categories')
                .then(response => {
                    this.categories = response.data;
                })
                .catch(error => {
                    console.error('Error fetching categories:', error);
                });
        },
        fetchTodo() {
            axios.get(`/api/todos/${this.todoId}`)
                .then(response => {
                    const todoData = response.data;
                    this.todo.id = todoData.id;
                    this.todo.title = todoData.title;
                    this.todo.description = todoData.description;
                    this.todo.due_date = todoData.due_date ? todoData.due_date.split('T')[0] : '';
                    this.todo.status = todoData.status;
                    this.todo.category_type = todoData.category_type;
                    this.todo.extra_data = todoData.extra_data ? (typeof todoData.extra_data === 'object' ? todoData.extra_data : JSON.parse(todoData.extra_data)) : {};

                    // Populate conditional fields
                    if (this.todo.category_type === 'WorkTask' && this.todo.extra_data.priority) {
                        this.todo.priority = this.todo.extra_data.priority;
                    } else {
                        this.todo.priority = '';
                    }
                    if (this.todo.category_type === 'PersonalTask' && this.todo.extra_data.mood) {
                        this.todo.mood = this.todo.extra_data.mood;
                    } else {
                        this.todo.mood = '';
                    }
                    if (this.todo.category_type === 'ShoppingTask' && this.todo.extra_data.estimated_cost) {
                        this.todo.estimated_cost = this.todo.extra_data.estimated_cost;
                    } else {
                        this.todo.estimated_cost = null;
                    }
                })
                .catch(error => {
                    console.error('Error fetching todo:', error);
                    alert('Error fetching todo. Please check the console for details.');
                });
        },
        handleCategoryChange() {
            // Reset conditional fields when category changes
            this.todo.priority = '';
            this.todo.mood = '';
            this.todo.estimated_cost = null;
        },
        updateTodo() {
            const payload = {
                title: this.todo.title,
                description: this.todo.description,
                due_date: this.todo.due_date,
                status: this.todo.status,
                category_type: this.todo.category_type,
            };

            if (this.todo.category_type === 'WorkTask') {
                payload.priority = this.todo.priority;
            } else if (this.todo.category_type === 'PersonalTask') {
                payload.mood = this.todo.mood;
            } else if (this.todo.category_type === 'ShoppingTask') {
                payload.estimated_cost = this.todo.estimated_cost;
            }
 
            axios.put(`/api/todos/${this.todo.id}`, payload)
                .then(response => {
                    window.location.href = `/todos/${this.todo.id}`; // Redirect to todo detail page
                })
                .catch(error => {
                    console.error('Error updating todo:', error);
                    alert('Error updating todo. Please check the console for details.');
                });
        }
    }
}
</script>

<style scoped>
/* Add any specific styles for this component here */
</style>