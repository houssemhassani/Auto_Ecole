<template>
    <div class="container mt-5">
      <h1 class="mb-4" style="color: teal;">Mes Cours</h1>
      <div class="calendar-container">
        <FullCalendar :options="calendarOptions" class="custom-calendar">
          <template v-slot:eventContent="arg">
            <div class="event-title" @click="showAlert(arg.event.id, arg.event.title)">
              {{ arg.event.title }}
            </div>
          </template>
        </FullCalendar>
      </div>
    </div>
  </template>
  
  <script>
  import FullCalendar from '@fullcalendar/vue3';
  import dayGridPlugin from '@fullcalendar/daygrid';
  import interactionPlugin from '@fullcalendar/interaction';
  import axios from '@/axios-config';
  
  export default {
    components: {
      FullCalendar
    },
    data() {
      return {
        calendarOptions: {
          plugins: [dayGridPlugin, interactionPlugin],
          initialView: 'dayGridMonth',
          weekends: true,
          events: [],
          dateClick: this.handleDateClick,
          validRange: {
            start: new Date().toISOString().substring(0, 10),
          }
        }
      }
    },
    methods: {
      async fetchData() {
        try {
          // Remplacez '1' par l'ID du candidat actuellement connecté ou récupérez-le à partir de votre système d'authentification
          const response = await axios.get(`/gestioncandidat/10/courses`);
          const events = response.data.courses.map(course => ({
            title: course.titre,
            id: course.id,
            start: course.date_debut,
            end: course.date_fin,
          }));
          this.calendarOptions.events = events;
        } catch (error) {
          console.error('Erreur lors de la récupération des données:', error);
        }
      },
      
      showAlert(id, title) {
        const confirmMessage = `Titre du cours : ${title} \n `;
        if (confirm(confirmMessage)) {
          const confirmed = confirm(confirmMessage + "\n Que voulez-vous faire ? \n Cliquer sur OK si vous souhaitez voir plus de détails")
          if (confirmed) {
            this.$router.push({ name: 'ShowCour', params: { id } });
          } else {
            
          }
        }
      }
    },
    mounted() {
      this.fetchData();
    }
  }
  </script>
  
  <style scoped>
  .full-calendar {
    background-color: #F8F9FA;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
  }
  .calendar-container {
  background-color: #F8F9FA;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
}
  
  .event-title {
    cursor: pointer;
    color: blue;
    font-weight: bold;
  }
  
  .event-title:hover {
    text-decoration: underline;
  }
  </style>
  