<template>
  <div class="container">
    <div class="card mt-5">
      <div class="card-header">
        <h1 class="card-title">Détails du Moniteur</h1>
      </div>
      <div class="card-body">
        <div v-if="monitor">
          <div class="mb-3">
            <strong>Nom:</strong> {{ monitor.name }}
          </div>
          <div class="mb-3">
            <strong>Email:</strong> {{ monitor.email }}
          </div>
          <div class="mb-3">
            <strong>Numéro professionnel:</strong> {{ monitor.num_professional }}
          </div>
          <div class="mb-3">
            <strong>Numéro CIN:</strong> {{ monitor.num_cin }}
          </div>
          <div class="mb-3">
            <strong>Auto-école:</strong> {{ monitor.auto_ecole_name }}
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
      monitor: {}
    };
  },
  mounted() {
    this.fetchMonitor();
  },
  methods: {
    fetchMonitor() {
      const monitorId = this.$route.params.id;
      axios.get(`/gestionmonitor/show/${monitorId}`)
        .then(response => {
          this.monitor = response.data;
        })
        .catch(error => {
          console.error('Erreur lors de la récupération du moniteur:', error);
        });
    }
  }
};
</script>

<style>
/* Ajoutez ici vos styles CSS personnalisés */
</style>
