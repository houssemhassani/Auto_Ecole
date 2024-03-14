<template>
    <div class="vehicle-list">
      <div class="header">
        <h1 class="title">List of Vehicles</h1>
        <router-link to="/admin/gestionVehicule/createVehicule" class="add-button">Add Vehicle</router-link>
      </div>
      <div class="grid-container">
        <div v-for="vehicle in vehicles" :key="vehicle.id" class="card">
          <div class="card-image">
            <img :src="vehicle.imageUrl" alt="Vehicle Image" class="vehicle-image">
          </div>
          <div class="card-content">
            <h2 class="brand">{{ vehicle.brand }}</h2>
            <p class="model">{{ vehicle.model }}</p>
            <p class="year">Year: {{ vehicle.year }}</p>
            <p class="registration-number">Reg. Number: {{ vehicle.registration_number }}</p>
            <button @click="confirmDelete(vehicle.id)" class="delete-button">Delete</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from '@/axios-config';
  import Swal from 'sweetalert2';
  
  export default {
    data() {
      return {
        vehicles: [],
      };
    },
    mounted() {
      this.fetchVehicles();
    },
    methods: {
      fetchVehicles() {
        axios.get('/gestionvehicules/vehicules')
          .then(response => {
            this.vehicles = response.data.vehicles.map(vehicle => ({
              ...vehicle,
              imageUrl: `/images/${vehicle.image}` // Adjust the image URL if needed
            }));
          })
          .catch(error => {
            console.error('Error fetching vehicles:', error);
          });
      },
      confirmDelete(vehicleId) {
        Swal.fire({
          title: 'Are you sure?',
          text: 'You will not be able to recover this vehicle!',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            this.deleteVehicle(vehicleId);
          }
        });
      },
      deleteVehicle(vehicleId) {
        // Code pour supprimer le véhicule avec l'ID donné
        axios.delete(`/gestionvehicules/vehicules/${vehicleId}`)
          .then(() => {
            // Mettre à jour la liste des véhicules après la suppression
            this.vehicles = this.vehicles.filter(vehicle => vehicle.id !== vehicleId);
            Swal.fire('Deleted!', 'The vehicle has been deleted.', 'success');
          })
          .catch(error => {
            console.error('Error deleting vehicle:', error);
            Swal.fire('Error!', 'Failed to delete the vehicle.', 'error');
          });
      },
    },
  };
  </script>

<style scoped>
.vehicle-list {
    font-family: 'Roboto', sans-serif;
    padding: 20px;
}

.title {
    font-size: 32px;
    margin-bottom: 20px;
    color: #333;
}

.grid-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.card {
    background-color: #f9f9f9;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.card-image {
    overflow: hidden;
    height: 200px;
}

.vehicle-image {
    width: 50%;
    height: auto;
    object-fit: cover;
}

.card-content {
    padding: 20px;
}

.brand {
    font-size: 24px;
    margin-bottom: 5px;
    color: #333;
}

.model,
.year,
.registration-number {
    font-size: 16px;
    margin: 5px 0;
    color: #666;
}
</style>