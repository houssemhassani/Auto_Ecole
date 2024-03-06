<template>
    <div class="container mt-5">
      <h1 class="mb-4" style="color: teal;">Liste des Cours</h1>
      <router-link :to="{ path: '/admin/gestioncour/create' }">
        <button class="btn btn-primary" style="background-color: coral; border-color: coral;">Ajouter un cours</button>
      </router-link>
      <div class="mt-4">
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
  import FullCalendar from '@fullcalendar/vue3'
  import dayGridPlugin from '@fullcalendar/daygrid'
  import interactionPlugin from '@fullcalendar/interaction'
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
          events: []
        }
      }
    },
    methods: {
      showAlert(id, title) {
        const confirmMessage = `Titre du cours : ${title} \n `;
        if (confirm(confirmMessage)) {
          const confirmed = confirm(confirmMessage + "\n Que voulez-vous faire ? \n 1/Cliquer sur Ok si vous souhaitez modifier" +
            "\n 2/Cliquer sur Annuler si vous souhaitez voir plus de détails")
          if (confirmed) {
            this.$router.push({ name: 'EditCour', params: { id } });
          } else {
            this.$router.push({ name: 'ShowCour', params: { id } });
          }
        }
      },
      fetchData() {
        axios.get('/gestioncour/allCours')
          .then(response => {
            this.calendarOptions.events = response.data.cours.map(event => ({
              title: event.titre,
              id: event.id,
              start: event.date_debut,
              end: event.date_fin,
            }))
          })
          .catch(error => {
            console.error('Erreur lors de la récupération des données:', error)
          })
      }
    },
    mounted() {
      this.fetchData()
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
  
  .event-title {
    cursor: pointer;
    color: blue;
    font-weight: bold;
  }
  
  .event-title:hover {
    text-decoration: underline;
  }
  </style>
  