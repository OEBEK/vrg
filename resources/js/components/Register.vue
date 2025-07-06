<template>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center fs-4">Register</div>
                    <div class="card-body p-4">
                        <form @submit.prevent="register">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input id="name" type="text" class="form-control form-control-lg" :class="{ 'is-invalid': errors.name }" v-model="name" required autocomplete="name" autofocus>
                                <div class="invalid-feedback" v-if="errors.name">
                                    {{ errors.name[0] }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input id="email" type="email" class="form-control form-control-lg" :class="{ 'is-invalid': errors.email }" v-model="email" required autocomplete="email">
                                <div class="invalid-feedback" v-if="errors.email">
                                    {{ errors.email[0] }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" type="password" class="form-control form-control-lg" :class="{ 'is-invalid': errors.password }" v-model="password" required autocomplete="new-password">
                                <div class="invalid-feedback" v-if="errors.password">
                                    {{ errors.password[0] }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password-confirm" class="form-label">Confirm Password</label>
                                <input id="password-confirm" type="password" class="form-control form-control-lg" v-model="password_confirmation" required autocomplete="new-password">
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Register
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
            errors: {}
        };
    },
    methods: {
        async register() {
            this.errors = {};
            try {
                await axios.post('/register', {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation
                });
                window.location.href = '/todos'; // Redirect to todos page on successful registration
            } catch (error) {
                this.handleRegistrationError(error);
            }
        },
        handleRegistrationError(error) {
            if (error.response && error.response.data.errors) {
                this.errors = error.response.data.errors;
            } else {
                console.error('Registration error:', error);
            }
        }
    }
}
</script>