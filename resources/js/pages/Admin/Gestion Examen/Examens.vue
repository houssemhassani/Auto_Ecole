<template>
    <div class="container mt-5">
      <div class="card border-primary">
        <div class="card-header bg-primary text-white">
          <h1 class="card-title mb-0">Questions Aléatoires</h1>
        </div>
        <div class="card-body">
          <div v-if="currentQuestion" class="animated fadeIn">
            <h3 class="mb-4">{{ currentQuestion.text }}</h3>
            <div class="progress mb-4">
              <div class="progress-bar" role="progressbar" :style="{ width: progressPercentage + '%' }" aria-valuenow="progressPercentage" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <p class="mb-3"><i class="far fa-clock"></i> Temps restant: {{ timeRemaining }} secondes</p>
            <ul class="list-group mb-4">
              <li v-for="(answer, answerIndex) in currentQuestion.answers" :key="answerIndex" class="list-group-item">
                <input type="checkbox" :id="'answer_' + answerIndex" :value="answer" v-model="selectedAnswers" class="form-check-input me-2">
                <label :for="'answer_' + answerIndex" class="form-check-label">{{ answer.text }}</label>
              </li>
            </ul>
            <button @click="displayNextQuestion" class="btn btn-primary">Suivant <i class="fas fa-arrow-right"></i></button>
          </div>
          <div v-else class="text-center animated fadeIn">
            <p class="lead">Fin des questions.</p>
          </div>
          <!-- Ajout de l'élément audio pour le son d'alerte -->
          <audio ref="timerAlert" src="../../../../../public/assets/timer-alert.mp3"></audio>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from '@/axios-config';
  
  export default {
    data() {
      return {
        questions: [],
        currentQuestionIndex: 0,
        currentQuestion: null,
        timeRemaining: 0,
        progressPercentage: 0,
        timerInterval: null,
        selectedAnswers: [],
      };
    },
    mounted() {
      this.fetchRandomQuestions();
    },
    methods: {
      fetchRandomQuestions() {
        axios.get('gestionQcm/questions/AllQuestion').then(response => {
          this.questions = response.data.questions;
          if (this.questions.length > 0) {
            this.currentQuestion = this.questions[this.currentQuestionIndex];
            this.startTimer();
          }
        }).catch(error => {
          console.error('Erreur lors de la récupération des questions aléatoires:', error);
        });
      },
      startTimer() {
        this.timeRemaining = this.currentQuestion.duration;
        this.timerInterval = setInterval(() => {
          if (this.timeRemaining > 0) {
            this.timeRemaining--;
            this.progressPercentage = ((this.currentQuestion.duration - this.timeRemaining) / this.currentQuestion.duration) * 100;
            // Jouer le son d'alerte si le temps restant est inférieur à 7 secondes
            if (this.timeRemaining < 7) {
              this.$refs.timerAlert.play();
            }
          } else {
            clearInterval(this.timerInterval);
            this.displayNextQuestion();
          }
        }, 1000);
      },
      displayNextQuestion() {
        this.currentQuestionIndex++;
        if (this.currentQuestionIndex < this.questions.length) {
          this.currentQuestion = this.questions[this.currentQuestionIndex];
          this.selectedAnswers = []; // Réinitialise les réponses sélectionnées
          this.progressPercentage = 0; // Réinitialise la barre de progression
          this.startTimer();
        } else {
          this.currentQuestion = null;
          clearInterval(this.timerInterval);
        }
      },
    },
    beforeDestroy() {
      clearInterval(this.timerInterval);
    },
  };
  </script>
  
  <style scoped>
  .animated {
    animation-duration: 1s;
  }
  
  .fadeIn {
    animation-name: fadeIn;
  }
  
  @keyframes fadeIn {
    from {
      opacity: 0;
    }
    to {
      opacity: 1;
    }
  }
  </style>
  