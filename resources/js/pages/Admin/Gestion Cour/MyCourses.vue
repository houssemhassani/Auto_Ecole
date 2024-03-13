<template>
  <div class="container mt-5">
    <div class="weather-info">
      <div v-if="weatherData" class="weather-info-item">
        <div><strong>Ville:</strong> {{ weatherData.name }}</div>
        <div><strong>Météo:</strong> {{ weatherData.weather[0].description }}</div>
        <div><strong>Température:</strong> {{ weatherData.main.temp }}°C</div>
        <div><strong>Humidité:</strong> {{ weatherData.main.humidity }}%</div>
        <div><strong>Vitesse du vent:</strong> {{ weatherData.wind.speed }} m/s</div>
      </div>
      <div v-else class="weather-info-item">Chargement de la météo...</div>
    </div>
    <h1 class="mt-5 mb-4" style="color: teal;">Mes Cours</h1>
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
import axioss from 'axios';

export default {
  components: {
    FullCalendar
  },
  data() {
    return {
      weatherData: null,
      apiKey: '50cd964c75a1c6ecb67b776d7fcaa795',
      city: 'Monastir', 
      units: 'metric',
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
    async fetchWeatherData() {
      try {
        const response = await axioss.get(`https://api.openweathermap.org/data/2.5/weather?q=${this.city}&appid=${this.apiKey}&units=${this.units}`);
        this.weatherData = response.data;
      } catch (error) {
        console.error('Erreur lors de la récupération des données météorologiques:', error);
      }
    },
    async fetchData() {
      try {
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
          // Traitement si l'utilisateur annule
        }
      }
    }
  },
  mounted() {
    this.fetchWeatherData();
    this.fetchData();
  }
}
</script>

<style scoped>
.container {
  font-family: Arial, sans-serif;
}

.weather-info {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 20px;
}

.weather-info-item {
  background-color: #f0f0f0;
  padding: 10px;
  border-radius: 5px;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
  margin-bottom: 10px;
}

.event-title {
  cursor: pointer;
  color: blue;
  font-weight: bold;
}

.event-title:hover {
  text-decoration: underline;
}

.calendar-container {
  background-color: #f0f0f0;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}
</style>
