<script setup>
import Nav from './components/Nav.vue';

</script>
<template>
    <div >
      <Nav v-if="user" />
      <RouterView/>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

      
      <!-- Votre contenu principal ici -->
      
    </div>
  </template>
  <style scoped>
</style>
<script>
const user=localStorage.getItem('token');
console.log(user)
</script>
  
 
  