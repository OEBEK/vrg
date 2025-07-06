<template>
    <tr>
        <td>{{ todo.title }}</td>
        <td>{{ todo.description }}</td>
        <td>{{ todo.due_date ? new Date(todo.due_date).toLocaleString() : 'N/A' }}</td>
        <td>{{ capitalizeFirstLetter(todo.status) }}</td>
        <td :class="categoryClass">{{ todo.category_type }}</td>
        <td>
            <div class="d-flex flex-column flex-md-row gap-2">
                <a :href="`/todos/${todo.id}`" class="btn btn-info btn-sm">View</a>
                <a :href="`/todos/${todo.id}/edit`" class="btn btn-warning btn-sm">Edit</a>
                <form @submit.prevent="deleteTodo(todo.id)" class="d-inline">
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div>
        </td>
    </tr>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        todo: {
            type: Object,
            required: true
        }
    },
    computed: {
        categoryClass() {
            if (this.todo.category_type) {
                return `category-${this.slugify(this.todo.category_type)}`;
            }
            return '';
        }
    },
    methods: {
        capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        },
        slugify(text) {
            return text.toString().toLowerCase()
                .replace(/\s+/g, '-')           // Replace spaces with -
                .replace(/[^\w-]+/g, '')       // Remove all non-word chars
                .replace(/--+/g, '-')          // Replace multiple - with single -
                .replace(/^-+/, '')            // Trim - from start of text
                .replace(/-+$/, '');           // Trim - from end of text
        },
        deleteTodo(id) {
            if (confirm('Are you sure you want to delete this todo?')) {
                axios.delete(`/todos/${id}`)
                    .then(response => {
                        console.log('Todo deleted:', response.data);
                        this.$emit('todo-deleted'); // Emit event to parent
                    })
                    .catch(error => {
                        console.error('Error deleting todo:', error);
                        alert('Error deleting todo. Please check the console for details.');
                    });
            }
        }
    }
}
</script>

<style scoped>
/* Styles are defined globally in app.css, but can be overridden here if needed */
</style>