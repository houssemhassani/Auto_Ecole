<template>
    <div class="container mt-5">
      <h1 class="mb-4">Cours non assignés à aucun candidat</h1>
      <div class="row">
        <div v-for="course in courses" :key="course.id" class="col-md-4 mb-4">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" :value="course.id" v-model="selectedCourses">
            <label class="form-check-label">
              <h5>{{ course.titre }}</h5>
              <p>{{ course.description }}</p>
              <p><strong>Date de début:</strong> {{ formatDate(course.date_debut) }}</p>
              <p><strong>Date de fin:</strong> {{ formatDate(course.date_fin) }}</p>
              <p><strong>Nombre d'heures:</strong> {{ course.nombre_heures }}</p>
            </label>
          </div>
        </div>
      </div>
      <div class="mt-4">
        <button @click="assignCourses" class="btn btn-primary">Assigner les cours sélectionnés</button>
      </div>
    </div>
  </template>
  
  <script>
import axios from '@/axios-config';
  
  export default {
    data() {
      return {
        courses: [],
        selectedCourses: [],
      };
    },
    mounted() {
      this.getCoursesWithoutCandidates();
    },
    methods: {
      async getCoursesWithoutCandidates() {
        try {
          const response = await axios.get('/gestioncandidat/courses/without-candidates');
          this.courses = response.data.courses;
        } catch (error) {
          console.error('Erreur lors de la récupération des cours non assignés :', error);
        }
      },
      async assignCourses() {
        // Implémentez la logique pour assigner les cours sélectionnés au candidat
        console.log('Cours sélectionnés:', this.selectedCourses);
      },
      formatDate(dateString) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        const date = new Date(dateString);
        return date.toLocaleString('fr-FR', options);
      },
    },
  };
  </script>
  
  <style scoped>
  /* Styles CSS spécifiques au composant */
  </style>
  