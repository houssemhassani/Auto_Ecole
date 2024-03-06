<template>
  <div>
    <h1>Register</h1>
    <form @submit.prevent="submitForm">
      <div>
        <label for="name">Name:</label>
        <input type="text" id="name" v-model="userData.name" required>
      </div>
      <div>
        <label for="email">Email:</label>
        <input type="email" id="email" v-model="userData.email" required>
      </div>
      <div>
        <label for="password">Password:</label>
        <input type="password" id="password" v-model="userData.password" required>
      </div>
      <button type="submit">Register</button>
    </form>
  </div>
</template>

<script>
import axios from '@/axios-config';

export default {
  data() {
    return {
      userData: {
        name: '',
        email: '',
        password: ''
      }
    };
  },
  methods: {
    async submitForm() {
      try {
        const response = await axios.post('/user/store', this.userData);
        console.log(response.data); // Log successful registration response
        // Optionally, you can redirect the user to a success page or perform other actions here
      } catch (error) {
        console.error('Error registering user:', error);
        // Handle registration error here
      }
    }
  }
};
</script>

<style scoped>
/* Styles for this component */
</style>
