<template>
  <div class="container">
    <div class="card mt-5">
      <div class="card-header">
        <h1 class="card-title">Détails du Cours</h1>
      </div>
      <div class="card-body">
        <div v-if="cours">
          <div class="mb-3">
            <strong>Titre:</strong> {{ cours.titre }}
          </div>
          <div class="mb-3">
            <strong>Description:</strong> {{ cours.description }}
          </div>
          <div class="mb-3">
            <strong>Date de début:</strong> {{ formatDate(cours.date_debut) }}
          </div>
          <div class="mb-3">
            <strong>Date de fin:</strong> {{ formatDate(cours.date_fin) }}
          </div>
          <div class="mb-3">
            <strong>Nombre d'heures:</strong> {{ cours.nombre_heures }}
          </div>
          <div class="mb-3">
            <strong>Moniteur:</strong> {{ cours.name }}
          </div>
          <div class="mb-3">
            <strong>Numéro professionnel du moniteur:</strong> {{ cours.num_professional }}
          </div>
          <div class="d-flex justify-content-end">
            <button class="btn btn-danger mr-2" @click="deleteCours">Supprimer</button>
            <router-link class="btn btn-primary" :to="{ name: 'EditCour', params: { id: cours.id } }">Modifier</router-link>
          </div>
        </div>
        <div v-else>
          <p>Chargement en cours...</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from '@/axios-config';

export default {
  data() {
    return {
      cours: null
    };
  },
  mounted() {
    this.fetchCours();
  },
  methods: {
    fetchCours() {
      const id = this.$route.params.id;
      axios.get(`/gestioncour/showCour/${id}`)
        .then(response => {
          this.cours = response.data.cours[0];
        })
        .catch(error => {
          console.error('Erreur lors de la récupération des détails du cours :', error);
        });
    },
    deleteCours() {
      const id = this.$route.params.id;
      axios.delete(`/gestioncour/destroy/${id}`)
        .then(() => {
          console.log('Cours supprimé avec succès');
          // Rediriger l'utilisateur vers une autre page, par exemple la liste des cours
          this.$router.push('/admin/gestioncour/allCours');
        })
        .catch(error => {
          console.error('Erreur lors de la suppression du cours :', error);
        });
    },
    formatDate(dateString) {
      const options = { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric' };
      const date = new Date(dateString);
      return date.toLocaleString('fr-FR', options);
    }
  }
};
</script>

<style>
/* Ajoutez ici vos styles CSS personnalisés */
</style>
