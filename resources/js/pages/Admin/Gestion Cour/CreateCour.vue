<template>
  <div class="container">
    <div class="col-md-6 m-auto">
      <h1>Create Cour</h1>
      <div class="mt-3">
        <form @submit.prevent="submitCreate">
          <div class="mb-3">
            <label for="titre" class="form-label">Titre :</label>
            <input type="text" v-model="form.titre" id="titre" placeholder="Titre du cours" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="description" class="form-label">Description :</label>
            <textarea v-model="form.description" id="description" placeholder="Description du cours" class="form-control"></textarea>
          </div>
          <div class="mb-3">
            <label for="date_debut" class="form-label">Date de début :</label>
            <input type="date" v-model="form.date_debut" id="date_debut" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="date_fin" class="form-label">Date de fin :</label>
            <input type="date" v-model="form.date_fin" id="date_fin" class="form-control">
          </div>
          <div class="mb-3">
            <label for="nombre_heures" class="form-label">Nombre d'heures :</label>
            <input type="number" v-model="form.nombre_heures" id="nombre_heures" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-check-label">Type de cours :</label>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" v-model="form.pratique" id="pratique">
              <label class="form-check-label" for="pratique">Pratique</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" v-model="form.theorique" id="theorique">
              <label class="form-check-label" for="theorique">Théorique</label>
            </div>
          </div>
          <div class="mb-3">
            <label for="monitor_id" class="form-label">Moniteur :</label>
            <select v-model="form.monitor_id" id="monitor_id" class="form-control">
              <option value="" disabled selected>Choisissez un moniteur</option>
              <option v-for="monitor in monitors" :value="monitor.id">{{ monitor.name }}</option>
            </select>
          </div>
          <div class="mt-2">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from '@/axios-config';
import { useRouter } from 'vue-router';

const router = useRouter();
const form = ref({
    titre: '',
    description: '',
    date_debut: '',
    date_fin: '',
    nombre_heures: '',
    monitor_id: '',
    pratique: false, // Ajout des champs pratiques et théoriques
    theorique: false
});
const monitors = ref([]);

const submitCreate = async () => {
    try {
        const type = [];

        if (form.value.pratique) {
            type.push('pratique');
        }
        if (form.value.theorique) {
            type.push('theorique');
        }
        const typeString = type.join(', ');
        console.log(type[0]);
        const response = await axios.post('/gestioncour/addCour', {
            titre: form.value.titre,
            description: form.value.description,
            date_debut: form.value.date_debut,
            date_fin: form.value.date_fin,
            nombre_heures: form.value.nombre_heures,
            monitor_id: form.value.monitor_id,
            type: typeString // Envoyer les types sélectionnés
        });
        console.log(response.data);
        router.push('/home');
    } catch (error) {
        console.error('Erreur lors de la création du cours :', error);
    }
}

const fetchMonitors = async () => {
    try {
        const response = await axios.get('/gestioncour/create');
        monitors.value = response.data.monitors;
    } catch (error) {
        console.error('Erreur lors de la récupération des moniteurs :', error);
    }
}

fetchMonitors(); // Appel de la fonction pour récupérer les moniteurs au chargement du composant
</script>

<style scoped>
/* Ajoutez vos styles CSS ici */
</style>
