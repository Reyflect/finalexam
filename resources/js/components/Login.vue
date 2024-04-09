<script setup>
import { Head } from "@inertiajs/vue3";
</script>

<script>
import axios from "axios";

export default {
    data() {
        return {
            credentials: {
                login_name: "",
                login_password: "",
                _token: String,
                remember: false,
            },
        };
    },
    methods: {
        loginToPage() {
            axios
                .post(`/loginAuthentication`, this.credentials)
                .then((response) => {
                    console.log("logged in");
                });
        },
    },
};
</script>

<template>
    <Head title="Login" />
    <div class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <b>LOGIN</b>
            </div>

            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Sign in to start your session</p>
                    <form action="/loginAuthentication" method="post">
                        <div class="input-group mb-3">
                            <input
                                type="hidden"
                                name="_token"
                                :value="$page.props.csrf_token"
                            />

                            <input
                                v-model="credentials.username"
                                type="text"
                                name="login_name"
                                class="form-control"
                                placeholder="Username/Email"
                            />
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input
                                v-model="credentials.password"
                                type="password"
                                name="login_password"
                                class="form-control"
                                placeholder="Password"
                            />
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <div class="icheck-primary">
                                    <input
                                        type="checkbox"
                                        id="remember"
                                        name="remember"
                                        v-model="credentials.remember"
                                    />
                                    <label for="remember">Remember Me</label>
                                </div>
                            </div>

                            <div class="col-4">
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-block"
                                >
                                    Sign In
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
