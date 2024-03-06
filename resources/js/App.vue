<script setup>
import Nav from './components/Nav.vue';
</script>
<template>
    <div >
      <Nav v-if="user" />
      <RouterView/>
      
      <!-- Votre contenu principal ici -->
      
    </div>
  </template>
  <style scoped>
</style>
<script>
const user=localStorage.getItem('token');
console.log(user)
</script>
  
 
  