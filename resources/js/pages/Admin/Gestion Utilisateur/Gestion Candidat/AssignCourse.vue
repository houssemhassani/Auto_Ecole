<template>
  <div>
    <div class="parallax-container">
      <img src="../../../../images/coursdisponible.png" alt="Image" class="parallax">
    </div>

    <div class="row">
      <div class="col-md-4" v-for="course in courses" :key="course.id">
        <div class="card mb-3" style="background-color: #F8F9FA;">
          <div class="card-body">
            <h5 class="card-title" style="color: #000;">{{ course.titre }}</h5>
            <p class="card-text" style="color: #000;">Date de début : {{ course.date_debut }}</p>
            <button class="btn btn-primary" @click="assignCourse(course.id)">Attribuer</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from '@/axios-config'; // Importez simplement axios

export default {
  data() {
    return {
      courses: [],
      candidatId: null
    };
  },
  mounted() {
    this.getCoursesWithoutCandidates();
  },
  methods: {
    getCoursesWithoutCandidates() {
      axios.get('/gestioncandidat/courses/without-candidates')
        .then(response => {
          this.courses = response.data.courses;
        })
        .catch(error => {
          console.error(error);
        });
    },
    assignCourse(courseId) {
      const candidatId = this.$route.params.candidatId;
      const formData = {
        candidat_id: candidatId,
        type: 'theorique' // Vous devez spécifier le type de cours ici, ou le récupérer dynamiquement depuis votre interface
      };

      axios.post(`/gestioncour/assign-course/${courseId}`, formData)
        .then(response => {
          console.log(response.data.message);
          this.getCoursesWithoutCandidates();
        })
        .catch(error => {
          console.error(error);
        });
    }
  }
};
</script>

<style scoped>
.parallax-container {
  position: relative;
  overflow: inherit;
  width: 70%;
  height: 250px;
}

.parallax {
  display: block;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: auto;
  animation: parallaxTranslate 5s infinite alternate;
}

@keyframes parallaxTranslate {
  0% {
    transform: translateX(0);
  }

  100% {
    transform: translateX(50%);
  }
}

.card {
  transition: box-shadow 0.3s ease;
}

.card:hover {
  box-shadow: 0 0 11px rgba(33, 33, 33, .2);
}
</style>
