<template>
    <div class="vehicle-list">
        <div class="header">
            <h1 class="title">Liste des Véhicules</h1>
            <router-link to="/admin/gestionVehicule/createVehicule" class="add-button">
                <i class="fas fa-plus-circle fa-lg"></i>
            </router-link>
        </div>
        <div class="grid-container">
            <div v-for="vehicle in vehicles" :key="vehicle.id" class="card">
                <div class="card-image">
                    <img :src="vehicle.imageUrl" alt="Vehicle Image" class="vehicle-image">
                </div>
                <div class="card-content">
                    <h2 class="brand">{{ vehicle.brand }}</h2>
                    <p class="model">{{ vehicle.model }}</p>
                    <p class="year">Année: {{ vehicle.year }}</p>
                    <p class="registration-number">Numéro d'immatriculation: {{ vehicle.registration_number }}</p>
                    <div class="button-group">
                        <button @click="showDetails(vehicle)" class="action-button show-details">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button @click="editVehicle(vehicle)" class="action-button edit-vehicle">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button @click="confirmDelete(vehicle.id)" class="action-button confirm-delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
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
                    this.vehicles = response.data.vehicules.map(vehicle => ({
                        ...vehicle,
                        imageUrl: `/images/${vehicle.image}`
                    }));
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des véhicules :', error);
                });
        },
        confirmDelete(vehicleId) {
            Swal.fire({
                title: 'Êtes-vous sûr?',
                text: 'Vous ne pourrez pas récupérer ce véhicule!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.deleteVehicle(vehicleId);
                }
            });
        },
        deleteVehicle(vehicleId) {
            axios.delete(`/gestionvehicules/vehicules/${vehicleId}`)
                .then(() => {
                    this.vehicles = this.vehicles.filter(vehicle => vehicle.id !== vehicleId);
                    Swal.fire('Supprimé!', 'Le véhicule a été supprimé.', 'success');
                })
                .catch(error => {
                    console.error('Erreur lors de la suppression du véhicule:', error);
                    Swal.fire('Erreur!', 'Impossible de supprimer le véhicule.', 'error');
                });
        },
        editVehicle(vehicle) {
            // Logique pour l'édition du véhicule
        },
        showDetails(vehicle) {
            axios.get(`/gestionvehicules/vehicules/${vehicle.id}`)
                .then(response => {
                    const vehicleDetails = response.data.details.vehicule;
                    const monitorDetails = response.data.details.monitor;

                    // Construire le contenu HTML de l'alerte Swal
                    const swalContent = `
            <div class="swal-details">
              <p><strong>Marque:</strong> ${vehicleDetails.brand}</p>
              <p><strong>Modèle:</strong> ${vehicleDetails.model}</p>
              <p><strong>Année:</strong> ${vehicleDetails.year}</p>
              <p><strong>Numéro d'immatriculation:</strong> ${vehicleDetails.registration_number}</p>
              <hr>
              <p><strong>Moniteur:</strong> ${monitorDetails ? monitorDetails.name : 'Non assigné'}</p>
              <p><strong>CIN du moniteur:</strong> ${monitorDetails ? monitorDetails.num_cin : 'Non assigné'}</p>
              <p><strong>Numéro professionnel du moniteur:</strong> ${monitorDetails ? monitorDetails.num_professional : 'Non assigné'}</p>
            </div>
          `;

                    // Afficher l'alerte Swal avec le contenu personnalisé
                    Swal.fire({
                        title: '<strong>Détails du véhicule</strong>',
                        html: swalContent,
                        icon: 'info',
                        showCloseButton: true,
                        showConfirmButton: false,
                        customClass: {
                            container: 'swal2-overflow-auto',
                            popup: 'swal2-popup-custom',
                            title: 'swal2-title-custom'
                        }
                    });
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des détails du véhicule:', error);
                    Swal.fire('Erreur!', 'Impossible de récupérer les détails du véhicule.', 'error');
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

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.title {
    font-size: 32px;
    margin-bottom: 20px;
    color: #333;
}

.swal-details {
    text-align: left;
    padding: 20px;
}

.swal2-popup-custom {
    width: 60%;
    /* Ajustez la largeur selon vos préférences */
}

.swal2-title-custom {
    font-size: 24px;
    color: #333;
    /* Couleur de votre choix */
}

.add-button {
    background-color: #007bff;
    color: #fff;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.add-button:hover {
    background-color: #0056b3;
}

.button-group {
    display: flex;
    gap: 10px;
}

.action-button {
    background-color: transparent;
    border: none;
    color: #333;
    cursor: pointer;
    transition: color 0.3s ease;
    font-size: 18px;
}

.action-button:hover {
    color: #007bff;
}

/* Couleurs des icônes spécifiques */
.action-button.show-details i {
    color: #28a745;
    /* Vert pour l'icône de détails */
}

.action-button.edit-vehicle i {
    color: #ffc107;
    /* Jaune pour l'icône d'édition */
}

.action-button.confirm-delete i {
    color: #dc3545;
    /* Rouge pour l'icône de suppression */
}

.grid-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.card {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.card-image {
    overflow: hidden;
    height: 200px;
}

.vehicle-image {
    width: 100%;
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
    font-size: 18px;
    margin: 5px 0;
    color: #666;
}

.delete-button {
    background-color: transparent;
    border: none;
    color: #dc3545;
    padding: 8px;
    cursor: pointer;
    transition: color 0.3s ease;
    font-size: 18px;
}

.delete-button:hover {
    color: #c82333;
    transform: scale(1.1);
}
</style>
