<template>
  <div>
    <!-- Barre de navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
      <div class="container">
        <a class="navbar-brand" href="#">Météo</a>
        <a class="navbar-brand" href="#" @click="fetchTrafficData">Trafic</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
          <div class="weather-info navbar-text">
            <div v-if="weatherData" class="weather-info-content">
              <span class="weather-info-item" style="color: black;">Ville: {{ weatherData.name }}</span>
              <span class="weather-info-item" style="color: black;">Météo: {{ weatherData.weather[0].description
                }}</span>
              <span class="weather-info-item" style="color: black;">Température: {{ weatherData.main.temp }}°C</span>
              <span class="weather-info-item" style="color: black;">Humidité: {{ weatherData.main.humidity }}%</span>
              <span class="weather-info-item" style="color: black;">Vitesse du vent: {{ weatherData.wind.speed }}
                m/s</span>
              <img :src="weatherIconUrl" alt="Weather Icon" class="weather-icon">
            </div>
            <div v-else>Chargement de la météo...</div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Contenu principal -->
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
      city: '', // La ville sera remplacée par les coordonnées géographiques obtenues
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
  computed: {
    weatherIconUrl() {
      if (this.weatherData) {
        return `http://openweathermap.org/img/w/${this.weatherData.weather[0].icon}.png`;
      }
      return '';
    }
  },
  methods: {
    async fetchTrafficData() {
  try {
    // Récupérer la position géographique actuelle de l'utilisateur
    const position = await this.getCurrentPosition();

    // Utiliser les coordonnées géographiques pour récupérer les données de circulation
    const response = await axioss.get(`https://api.tomtom.com/traffic/services/4/flowSegmentData/absolute/10/json?key=R04Ot04d0NIVuxWfKGAYgwyO4mL7M6G6&point=${position.latitude},${position.longitude}`);
    
    console.log(response.data); // Affichez les données de circulation dans la console ou traitez-les selon vos besoins
  } catch (error) {
    if (error.response && error.response.data && error.response.data.detailedError) {
      console.error('Erreur lors de la récupération des données de circulation:', error.response.data.detailedError.message);
    } else {
      console.error('Erreur lors de la récupération des données de circulation:', error);
    }
  }
},
getCurrentPosition() {
  return new Promise((resolve, reject) => {
    // Demander l'autorisation à l'utilisateur pour accéder à sa position géographique
    navigator.geolocation.getCurrentPosition(
      position => {
        resolve({
          latitude: position.coords.latitude,
          longitude: position.coords.longitude
        });
      },
      error => {
        reject(error);
      }
    );
  });
},
    async fetchWeatherData() {
      try {
        // Demander l'autorisation à l'utilisateur pour accéder à sa position géographique
        navigator.geolocation.getCurrentPosition(async position => {
          const latitude = position.coords.latitude;
          const longitude = position.coords.longitude;
          // Utiliser les coordonnées géographiques pour récupérer les données météorologiques
          const response = await axioss.get(`https://api.openweathermap.org/data/2.5/weather?lat=${latitude}&lon=${longitude}&appid=${this.apiKey}&units=${this.units}`);
          this.weatherData = response.data;
        }, error => {
          console.error('Erreur lors de la récupération de la position géographique:', error);
        });
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
    this.fetchTrafficData();
  }
}
</script>

<style scoped>
.calendar-container {
  background-color: #fefefe;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}

.event-title {
  cursor: pointer;
  color: blue;
  font-weight: bold;
}

.event-title:hover {
  text-decoration: underline;
}

.weather-info {
  display: flex;
  align-items: center;
  overflow: hidden;
}

.weather-info-content {
  animation: slide-in 18s linear infinite;
}

@keyframes slide-in {
  0% {
    transform: translateX(100%);
  }

  100% {
    transform: translateX(-100%);
  }
}

.weather-info span {
  margin-right: 50px;
}

.weather-info-item {
  color: #050505;
}

.weather-icon {
  width: 50px;
  height: 50px;
}

@media (max-width: 768px) {
  .weather-info {
    font-size: 1.8rem;
  }
}
</style>
