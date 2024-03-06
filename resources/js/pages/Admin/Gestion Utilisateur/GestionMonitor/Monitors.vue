<template>
  <div id="app">
    <div class="container mt-5">
      <div class="row">
        <div class="col">
          <h1 class="mb-4" style="color: #007bff;">Liste des Moniteurs</h1>
        </div>
        <div class="col text-end">
          <router-link to="/admin/gestionmonitor/create">
            <button class="btn btn-primary" style="background-color: #007bff; border-color: #007bff;">Ajouter</button>
          </router-link>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead class="table-dark">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Nom</th>
                  <th scope="col">Email</th>
                  <th scope="col">Numéro Professionnel</th>
                  <th scope="col">École</th>
                  <th scope="col">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(monitor, index) in monitors" :key="monitor.id">
                  <td>{{ index + 1 }}</td>
                  <td>{{ monitor.user_name }}</td>
                  <td>{{ monitor.user_email }}</td>
                  <td>{{ monitor.num_professional }}</td>
                  <td>{{ monitor.auto_ecole_name }}</td>
                  <td>
                    <router-link :to="{ name: 'ShowMonitor', params: { id: monitor.id } }">
                      <button class="btn btn-info btn-sm">Voir</button>
                    </router-link>
                    <router-link :to="`/admin/edit-monitor/${monitor.id}`">
                      <button class="btn btn-warning btn-sm">Modifier</button>
                    </router-link>
                    <button class="btn btn-danger btn-sm" @click="clickDelete(monitor.id, index)">Supprimer</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
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
    const monitors = ref([]);

    onMounted(async () => {
      try {
        const response = await axios.get('/gestionmonitor/allmonitors');
        monitors.value = response.data.monitors;
      } catch (error) {
        console.error('Erreur lors de la récupération des moniteurs :', error);
      }
    });

    const clickDelete = async (id, index) => {
      try {
        const result = await Swal.fire({
          title: 'Êtes-vous sûr?',
          text: 'Vous ne pourrez pas revenir en arrière!',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#007bff',
          confirmButtonText: 'Oui, supprimez-le!'
        });

        if (result.isConfirmed) {
          const response = await axios.delete(`gestionmonitor/destroy/${id}`);
          if (response.status === 200) {
            monitors.value.splice(index, 1);
          }
        }
      } catch (error) {
        console.error('Erreur lors de la suppression du moniteur :', error);
      }
    };

    return {
      monitors,
      clickDelete
    };
  }
};
</script>

