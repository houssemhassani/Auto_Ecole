<template>
    <div>
      <h1>Check Code</h1>
      <p>Temps restant avant expiration: {{ timeRemaining }}</p>
      <form @submit.prevent="submitForm">
        <div>
          <label for="email">Email:</label>
          <input type="email" id="email" v-model="email">
        </div>
        <div>
          <label for="code">Code:</label>
          <input type="text" id="code" v-model="code">
        </div>
        <button type="submit">Submit</button>
      </form>
      <p v-if="message">{{ message }}</p>
    </div>
  </template>
  
  <script>
import axios from '@/axios-config';
  export default {
    data() {
      return {
        email: localStorage.getItem('email'),
        code: '',
        message: '',
        expirationTime: null
      };
    },
    computed: {
      timeRemaining() {
        if (!this.expirationTime) return '';
        const now = new Date();
        const diff = this.expirationTime - now;
        const hours = Math.floor(diff / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
        return `${hours}h ${minutes}m ${seconds}s`;
      }
    },
    methods: {
      async submitForm() {
        try {
          const response = await axios.post('/user/check-code', {
            email: this.email,
            code: this.code
          });
          this.message = response.data.message;
          this.$router.push({ name: 'ResetPassword' });
        } catch (error) {
          console.error(error);
          this.message = 'An error occurred while checking the code.';
        }
      },
      updateTimeRemaining() {
        // Mettre à jour le temps restant à chaque seconde
        setInterval(() => {
          this.expirationTime = new Date(this.expirationTime.getTime() - 1000);
        }, 1000);
      }
    },
    mounted() {
      // Lorsque le composant est monté, démarrer la mise à jour du temps restant
      this.expirationTime = new Date(Date.now() + 60 * 60 * 1000); // 1 heure à partir de maintenant
      this.updateTimeRemaining();
    }
  };
  </script>
  