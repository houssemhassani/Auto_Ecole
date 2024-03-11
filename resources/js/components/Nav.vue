<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <router-link class="navbar-brand" to="/">Navbar</router-link>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <router-link class="nav-link" to="/">Home</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/my-courses">Manage Course</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/admin/gestioncandidat/">Manage Candidat</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/admin/gestionmonitor/">Manage Monitor</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/admin/gestionUser/listUsers">Manage User</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/admin/role">Manage Role</router-link>
            </li>
            
            <li class="nav-item">
                  <button class="dropdown-item" @click="logout">Logout</button>
                </li>
          </ul>
          <div class="navbar-nav">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                {{ user ? user.name : '' }}
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li>
                  <router-link class="dropdown-item" to="/user/profile">Profile</router-link>
                </li>
                <li>
                  <button class="dropdown-item" @click="logout">Logout</button>
                </li>
              </ul>
            </li>
          </div>
        </div>
      </div>
    </nav>
  </template>
  
  <script setup>
  import { useRouter } from 'vue-router';
  import axios from '@/axios-config';
  import { ref, onMounted } from 'vue';
  
  const router = useRouter();
  const user = ref(null);
  
  async function logout() {
    try {
      await axios.get('/user/logout');
      localStorage.setItem('token', null);
      router.push('/login');
    } catch (error) {
      console.error('Logout failed:', error);
    }
  }
  
  onMounted(async () => {
    const { data } = await axios.get('/user');
    user.value = data;
  });
  </script>
  