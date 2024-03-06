<template>
    <div class="container-fluid">
      <div class="row justify-content-center align-items-center" style="height: 100vh;">
        <div class="col-md-4">
          <div class="card shadow rounded">
            <div class="card-body">
              <h1 class="text-center mb-4" style="color: #B0E0E6;">Login</h1>
  
              <form @submit.prevent="login">
                <div class="mb-3">
                  <input type="email" class="form-control rounded-pill" id="email" aria-describedby="emailHelp" placeholder="Email" v-model="email" required>
                </div>
                <div class="mb-3">
                  <input type="password" class="form-control rounded-pill" id="password" placeholder="Password" v-model="password" required>
                </div>
                <div class="d-grid gap-2">
                  <button type="submit" class="btn btn-primary btn-lg rounded-pill">Login</button>
                </div>
              </form>
  
              <div class="text-center mt-3">
                <router-link style="color: #808080;" to="/forgot">Forgot Password?</router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from '@/axios-config';
  import { useRouter } from 'vue-router';
  
  export default {
    data() {
      return {
        email: '',
        password: ''
      };
    },
    methods: {
      getToken() {
        axios.get('/sanctum/csrf-cookie');
      },
      login() {
        axios.post('/user/login', {
          email: this.email,
          password: this.password,
        })
        .then(response => {
          this.getToken();
          localStorage.setItem('token', response.data.token);
          localStorage.setItem('currentUser', JSON.stringify(response.data.user));
          // Redirection vers HomeComponent
          this.$router.push({ name: 'Home' });
        })
        .catch(error => {
          console.error(error.response);
        });
      },
    }
  };
  </script>
  
  <style scoped>
  .card {
    background-color: #F8F9FA;
  }
  </style>
  