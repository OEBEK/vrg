<template>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center fs-4">Login</div>
                    <div class="card-body p-4">
                        <form @submit.prevent="login">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input id="email" type="email" class="form-control form-control-lg" :class="{ 'is-invalid': errors.email }" v-model="email" required autocomplete="email" autofocus>
                                <div class="invalid-feedback" v-if="errors.email">
                                    {{ errors.email[0] }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" type="password" class="form-control form-control-lg" :class="{ 'is-invalid': errors.password }" v-model="password" required autocomplete="current-password">
                                <div class="invalid-feedback" v-if="errors.password">
                                    {{ errors.password[0] }}
                                </div>
                            </div>

                            <div class="mb-3 form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" v-model="remember">
                                <label class="form-check-label" for="remember">
                                    Remember Me
                                </label>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Login
                                </button>
                                <a class="btn btn-link text-center" href="/forgot-password">
                                    Forgot Your Password?
                                </a>
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
            email: '',
            password: '',
            remember: false,
            errors: {}
        };
    },
    methods: {
        async login() {
            this.errors = {};
            try {
                await axios.post('/login', {
                    email: this.email,
                    password: this.password,
                    remember: this.remember
                });
                window.location.href = '/todos'; // Redirect to todos page on successful login
            } catch (error) {
                this.handleLoginError(error);
            }
        },
        handleLoginError(error) {
            if (error.response && error.response.data.errors) {
                this.errors = error.response.data.errors;
            } else if (error.response && error.response.data.message) {
                this.errors = { email: [error.response.data.message] }; // General error message
            } else {
                console.error('Login error:', error);
            }
        }
    }
}
</script>