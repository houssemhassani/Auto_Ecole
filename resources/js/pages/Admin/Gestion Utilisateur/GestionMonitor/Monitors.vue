<template>
  <div class="container mt-5">
    <div class="row mb-3">
      <div class="col">
        <h1 class="text-primary">Liste des Moniteurs</h1>
      </div>
      <div class="col text-end">
        <router-link to="/admin/gestionmonitor/create">
          <button class="btn btn-primary">Ajouter Moniteur</button>
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
                  <div class="d-flex">
                    <router-link :to="{ name: 'ShowMonitor', params: { id: monitor.id } }">
                      <button class="btn btn-info btn-sm me-2">Voir</button>
                    </router-link>
                    <router-link :to="`/admin/edit-monitor/${monitor.id}`">
                      <button class="btn btn-warning btn-sm me-2">Modifier</button>
                    </router-link>
                    <button class="btn btn-danger btn-sm" @click="clickDelete(monitor.id, index)">Supprimer</button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
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

<style scoped>
.btn-primary {
  background-color: #007bff;
  border-color: #007bff;
}

.btn-primary:hover {
  background-color: #0056b3;
  border-color: #0056b3;
}

.btn-info,
.btn-warning,
.btn-danger {
  color: #fff;
}

.btn-info {
  background-color: #17a2b8;
  border-color: #17a2b8;
}

.btn-info:hover {
  background-color: #138496;
  border-color: #138496;
}

.btn-warning {
  background-color: #ffc107;
  border-color: #ffc107;
}

.btn-warning:hover {
  background-color: #e0a800;
  border-color: #d39e00;
}

.btn-danger {
  background-color: #dc3545;
  border-color: #dc3545;
}

.btn-danger:hover {
  background-color: #bd2130;
  border-color: #8b353e;
}
</style>
