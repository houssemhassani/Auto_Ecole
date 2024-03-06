<template>
    <div>
      <h1>Edit Cour</h1>
      <form @submit.prevent="submitEdit">
        <div class="mb-3">
          <label for="titre" class="form-label">Titre</label>
          <input type="text" v-model="form.titre" id="titre" class="form-control">
        </div>
        <div class="mb-3">
          <label for="description" class="form-label">Description</label>
          <textarea v-model="form.description" id="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
          <label for="date_debut" class="form-label">Date de début</label>
          <input type="date" v-model="form.date_debut" id="date_debut" class="form-control">
        </div>
        <div class="mb-3">
          <label for="date_fin" class="form-label">Date de fin</label>
          <input type="date" v-model="form.date_fin" id="date_fin" class="form-control">
        </div>
        <div class="mb-3">
          <label for="nombre_heures" class="form-label">Nombre d'heures</label>
          <input type="number" v-model="form.nombre_heures" id="nombre_heures" class="form-control">
        </div>
        <div class="mb-3">
          <label for="monitor" class="form-label">Moniteur</label>
          <select v-model="form.monitor_id" id="monitor" class="form-select">
            <option>Select one</option>
            <option v-for="monitor in monitors" :key="monitor.id" :value="monitor.id">{{ monitor.name }}</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
      </form>
    </div>
  </template>
  
  <script>
  import axios from '@/axios-config';
  import { ref, onMounted } from 'vue';
  import { useRouter } from 'vue-router';
  
  export default {
    setup() {
      const router = useRouter();
      const courseId = ref(null);
      const form = ref({
        titre: '',
        description: '',
        date_debut: '',
        date_fin: '',
        nombre_heures: '',
        monitor_id: null, // Ajout de la propriété monitor_id pour stocker l'ID du moniteur sélectionné
      });
      const monitors = ref([]); // Tableau pour stocker la liste des moniteurs
  
      onMounted(() => {
        courseId.value = router.currentRoute.value.params.id;
        getCourseDetails();
      });
  
      const getCourseDetails = async () => {
        try {
          const response = await axios.get(`/gestioncour/${courseId.value}/edit`);
          const { cour, monitors: fetchedMonitors } = response.data;
          cour.date_debut = new Date(cour.date_debut).toISOString().split('T')[0];
          cour.date_fin = new Date(cour.date_fin).toISOString().split('T')[0];
          form.value = { ...cour };
          monitors.value = fetchedMonitors; // Mettre à jour la liste des moniteurs
        } catch (error) {
          console.error('Erreur lors de la récupération des détails du cours :', error);
        }
      };
  
      const submitEdit = async () => {
        try {
          const response = await axios.put(`/gestioncour/update/${courseId.value}`, form.value);
          console.log('Cours mis à jour avec succès :', response.data.cour);
          router.push('/admin/gestioncour');
        } catch (error) {
          console.error('Erreur lors de la mise à jour du cours :', error);
        }
      };
  
      return {
        form,
        monitors,
        submitEdit
      };
    }
  };
  </script>
  
  <style>
  /* Vos styles CSS personnalisés */
  </style>
  