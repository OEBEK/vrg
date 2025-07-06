<template>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">My Todos</h1>
            <a href="/todos/create" class="btn btn-primary">Create New Todo</a>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                Filter Todos
            </div>
            <div class="card-body">
                <form @submit.prevent="applyFilters">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="status" class="form-label">Filter by Status:</label>
                            <select v-model="filters.status" id="status" class="form-select">
                                <option value="">All</option>
                                <option value="offen">Offen</option>
                                <option value="erledigt">Erledigt</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="category" class="form-label">Filter by Category:</label>
                            <select v-model="filters.category" id="category" class="form-select">
                                <option value="">All</option>
                                <option v-for="category in categories" :key="category" :value="category">{{ category }}</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-info w-100">Apply Filters</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div v-if="todos.length === 0" class="alert alert-info" role="alert">
            No todos found.
        </div>
        <div v-else>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <todo-list-item v-for="todo in todos" :key="todo.id" :todo="todo" @todo-deleted="fetchTodos"></todo-list-item>
                    </tbody>
                </table>
            </div>

            <nav v-if="pagination.last_page > 1" aria-label="Todo Pagination">
                <ul class="pagination justify-content-center">
                    <li class="page-item" :class="{ 'disabled': pagination.current_page === 1 }">
                        <a class="page-link" href="#" @click.prevent="fetchTodos(pagination.current_page - 1)">Previous</a>
                    </li>
                    <li class="page-item" v-for="page in pagination.last_page" :key="page" :class="{ 'active': page === pagination.current_page }">
                        <a class="page-link" href="#" @click.prevent="fetchTodos(page)">{{ page }}</a>
                    </li>
                    <li class="page-item" :class="{ 'disabled': pagination.current_page === pagination.last_page }">
                        <a class="page-link" href="#" @click.prevent="fetchTodos(pagination.current_page + 1)">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script>
import TodoListItem from './TodoListItem.vue';
import axios from 'axios';

export default {
    components: {
        TodoListItem
    },
    data() {
        return {
            todos: [],
            categories: [],
            filters: {
                status: '',
                category: ''
            },
            pagination: {
                current_page: 1,
                last_page: 1,
                from: 1,
                to: 1,
                total: 0
            }
        };
    },
    created() {
        this.fetchTodos();
        this.fetchCategories();
    },
    methods: {
/**
         * Fetches data from a given URL and updates the component's state.
         * @param {string} url - The API endpoint URL.
         * @param {object} params - Query parameters for the request.
         * @param {function} successCallback - Function to call on successful response.
         * @param {string} errorMessage - Message to log on error.
         */
        async fetchData(url, params, successCallback, errorMessage) {
            try {
                const response = await axios.get(url, { params });
                successCallback(response.data);
            } catch (error) {
                console.error(errorMessage, error);
                // Optionally, add user-facing error notification here
            }
        },

        /**
         * Fetches the list of todos with optional pagination and filters.
         * @param {number} page - The page number to fetch.
         */
        fetchTodos(page = 1) {
            const params = {
                page: page,
                status: this.filters.status,
                category: this.filters.category
            };
            this.fetchData(
                '/api/todos',
                params,
                data => {
                    this.todos = data.data;
                    this.pagination = {
                        current_page: data.current_page,
                        last_page: data.last_page,
                        from: data.from,
                        to: data.to,
                        total: data.total
                    };
                },
                'Error fetching todos:'
            );
        },

        /**
         * Fetches the list of available categories.
         */
        fetchCategories() {
            this.fetchData(
                '/api/categories',
                {}, // No params needed for categories
                data => {
                    this.categories = data;
                },
                'Error fetching categories:'
            );
        },

        /**
         * Applies the current filters and resets pagination to the first page.
         */
        applyFilters() {
            this.fetchTodos(1);
        }
    }
}
</script>
