<template>
    <div>
      <h1>Reset Password</h1>
      <form @submit.prevent="resetPassword">
        <div>
          <label for="email">Email:</label>
          <input type="email" id="email" v-model="email" readonly>
        </div>
        <div>
          <label for="newPassword">New Password:</label>
          <input type="password" id="newPassword" v-model="newPassword">
        </div>
        <div>
          <label for="passwordConfirmation">Confirm Password:</label>
          <input type="password" id="passwordConfirmation" v-model="passwordConfirmation">
        </div>
        <button type="submit">Reset Password</button>
      </form>
    </div>
  </template>
  
  <script>
  import axios from '@/axios-config'; // Importez axios si ce n'est pas déjà fait
import { useRouter } from 'vue-router'
const router = useRouter();
  export default {
    data() {
      return {
        email: '',
        newPassword: '',
        passwordConfirmation: '',
        message: '', // Message pour afficher le résultat de la réinitialisation du mot de passe
      };
    },
    mounted() {
      // Récupérer l'email depuis localStorage et l'affecter à la propriété email
      this.email = localStorage.getItem('email') || '';
    },
    methods: {
      async resetPassword() {
        // Valider que les champs du formulaire ne sont pas vides et que le mot de passe est identique à la confirmation
        if (!this.newPassword || !this.passwordConfirmation) {
          this.message = 'Veuillez remplir tous les champs.';
          return;
        }
        if (this.newPassword !== this.passwordConfirmation) {
          this.message = 'Les mots de passe ne correspondent pas.';
          return;
        }
  
        try {
            console.log(this.newPassword);
          // Effectuer une requête HTTP POST vers votre API pour réinitialiser le mot de passe
          const response = await axios.post('/user/reset-password', {
            email: this.email,
            
            password: this.newPassword
          })
          console.log(response.data)
          // Traiter la réponse de l'API
          this.$router.push({ name: 'Login' });
          
        } catch (error) {
          // En cas d'erreur, afficher un message d'erreur
          console.error(error);
          this.message = 'Une erreur s\'est produite lors de la réinitialisation du mot de passe.';
        }
      }
    }
  };
  </script>
  
  