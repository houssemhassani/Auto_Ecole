<template>
    <div class="create-vehicle">
      <h1>Ajouter un nouveau véhicule</h1>
      <form @submit.prevent="addVehicle">
        <div class="form-group">
          <label for="brand" :class="{ 'focused': isBrandFocused }">Marque</label>
          <input type="text" id="brand" v-model="brand" required @focus="isBrandFocused = true" @blur="isBrandFocused = false">
        </div>
        <div class="form-group">
          <label for="model" :class="{ 'focused': isModelFocused }">Modèle</label>
          <input type="text" id="model" v-model="model" required @focus="isModelFocused = true" @blur="isModelFocused = false">
        </div>
        <div class="form-group">
          <label for="year" :class="{ 'focused': isYearFocused }">Année</label>
          <input type="number" id="year" v-model="year" required @focus="isYearFocused = true" @blur="isYearFocused = false">
        </div>
        <div class="form-group">
          <label for="registration_number" :class="{ 'focused': isRegNumFocused }">Numéro d'immatriculation</label>
          <input type="text" id="registration_number" v-model="registration_number" required @focus="isRegNumFocused = true" @blur="isRegNumFocused = false">
        </div>
        <div class="form-group">
          <label for="monitor_id">Moniteur</label>
          <select id="monitor_id" v-model="monitor_id" required>
            <option value="" disabled>Sélectionnez un moniteur</option>
            <option v-for="monitor in monitors" :key="monitor.id" :value="monitor.id">{{ monitor.name }}</option>
          </select>
        </div>
        <div class="form-group">
          <label for="image">Image</label>
          <input type="file" id="image" @change="handleImageChange">
        </div>
        <button type="submit">Ajouter le véhicule</button>
        <transition name="fade">
          <div v-if="isSuccess" class="success-message">Véhicule ajouté avec succès! <button @click="redirectToVehiclesList">Voir la liste des véhicules</button></div>
        </transition>
      </form>
    </div>
  </template>
  

<script>
import axios from '@/axios-config';
import Swal from 'sweetalert2';

export default {
  data() {
    return {
      brand: '',
      model: '',
      year: '',
      registration_number: '',
      monitor_id: '',
      image: null,
      monitors: [],
      isSuccess: false,
      isBrandFocused: false,
      isModelFocused: false,
      isYearFocused: false,
      isRegNumFocused: false
    };
  },
  mounted() {
    this.fetchMonitors();
  },
  methods: {
    handleImageChange(event) {
      this.image = event.target.files[0];
    },
    fetchMonitors() {
      axios.get('/gestionvehicules/create')
        .then(response => {
          this.monitors = response.data.monitors;
        })
        .catch(error => {
          console.error('Erreur lors de la récupération des moniteurs :', error);
        });
    },
    addVehicle() {
      const formData = new FormData();
      formData.append('brand', this.brand);
      formData.append('model', this.model);
      formData.append('year', this.year);
      formData.append('registration_number', this.registration_number);
      formData.append('monitor_id', this.monitor_id);
      formData.append('image', this.image);

      axios.post('/gestionvehicules/addvehicules', formData)
        .then(response => {
          console.log(response.data.message);
          this.showSuccessAlert();
        })
        .catch(error => {
          console.error('Erreur lors de l\'ajout du véhicule :', error);
        });
    },
    redirectToVehiclesList() {
      this.$router.push('/admin/gestionVehicule/listvehicules');
    },
    showSuccessAlert() {
      Swal.fire({
        icon: 'success',
        title: 'Véhicule ajouté avec succès!',
        text: 'Cliquez sur le bouton pour voir la liste des véhicules.',
        confirmButtonColor: '#40E0D0',
        confirmButtonText: 'Voir la liste',
        allowOutsideClick: false
      }).then(() => {
        this.redirectToVehiclesList();
      });
    }
  }
};
</script>

  
  <style scoped>
  .create-vehicle {
    font-family: Arial, sans-serif;
    max-width: 400px;
    margin: 0 auto;
  }
  
  .form-group {
    margin-bottom: 20px;
  }
  
  label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    transition: color 0.3s ease;
  }
  
  input[type="text"],
  input[type="number"],
  input[type="file"],
  select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    transition: border-color 0.3s ease;
  }
  
  input[type="text"]:focus,
  input[type="number"]:focus,
  select:focus {
    outline: none;
    border-color: #007bff;
  }
  
  select {
    appearance: none;
    background-image: url('data:image/svg+xml;utf8,<svg fill="%23000000" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M7 10l5 5 5-5H7z"/><path d="M0 0h24v24H0z" fill="none"/></svg>');
    background-repeat: no-repeat;
    background-position: right 10px center;
    padding-right: 30px;
  }
  
  button {
    width: 100%;
    padding: 12px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }
  
  button:hover {
    background-color: #0056b3;
  }
  
  .focused {
    color: #007bff;
  }
  
  .success-message {
    opacity: 0;
    transition: opacity 0.5s;
  }
  
  .success-message.show {
    opacity: 1;
  }
  
  .success-message button {
    margin-top: 10px;
  }
  </style>
  