<template>
    <div id="app" class="container">
      <div class="row justify-content-between align-items-center mb-4">
        <h1 style="color: #40E0D0;">Liste des candidats</h1>
        <router-link to="/admin/gestioncandidat/create">
          <button class="btn btn-primary" style="background-color: #40E0D0;">Ajouter</button>
        </router-link>
      </div>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-striped table-hover" style="background-color: #F8F9FA;">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Nom</th>
                <th scope="col">Email</th>
                <th scope="col">Date de naissance</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(candidat, index) in candidats" :key="candidat.id">
                <th scope="row">{{ index + 1 }}</th>
                <td>{{ candidat.user_name }}</td>
                <td>{{ candidat.user_email }}</td>
                <td>{{ formatDate(candidat.date_of_birth) }}</td>
                <td>
                  <button class="btn btn-danger" @click="clickDelete(candidat.id, index)">Supprimer</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from '@/axios-config';
  import { ref, onMounted } from 'vue';
  import Swal from 'sweetalert2';
  
  export default {
    setup() {
      const candidats = ref([]);
  
      onMounted(async () => {
        try {
          const response = await axios.get('/gestioncandidat/allcandidats');
          candidats.value = response.data.candidats;
        } catch (error) {
          console.error('Erreur lors de la récupération des candidats :', error);
        }
      });
  
      const formatDate = (dateString) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('fr-FR', { year: 'numeric', month: 'long', day: 'numeric' });
      };
  
      const clickDelete = async (id, index) => {
        try {
          const result = await Swal.fire({
            title: 'Êtes-vous sûr?',
            text: 'Vous ne pourrez pas revenir en arrière!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, supprimez-le!'
          });
  
          if (result.isConfirmed) {
            const response = await axios.delete(`gestioncandidat/destroy/${id}`);
            if (response.status === 200) {
              candidats.value.splice(index, 1);
            }
          }
        } catch (error) {
          console.error('Erreur lors de la suppression du candidat :', error);
        }
      };
  
      return {
        candidats,
        clickDelete,
        formatDate
      };
    }
  };
  </script>
  
  <style>
  .table th {
    background-color: #40E0D0;
    color: #FFFFFF;
  }
  </style>
  