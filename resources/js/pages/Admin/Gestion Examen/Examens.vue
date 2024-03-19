<template>
  <div class="container mt-5">
    <div v-if="showQuestions">
      <div v-for="(question, index) in questions" :key="index"
        class="card border-primary text-center align-items-center mb-5">
        <div class="card-header bg-primary text-white">
          <h2 class="card-title mb-0">Question {{ index + 1 }}</h2>
        </div>
        <div class="card-body">
          <h4 class="mb-4">{{ question.text }}</h4>
          <div v-if="question.image">
            <img :src="question.image" class="img-fluid mb-4" alt="Question Image"
              style="max-width: 100%; max-height: 300px;">
          </div>
          <ul class="list-group mb-4">
            <li v-for="(answer, answerIndex) in question.answers" :key="answerIndex" class="list-group-item">
              <div class="form-check">
                <input type="checkbox" :id="'answer_' + answerIndex + '_question_' + index" :value="answer"
                  v-model="selectedAnswers[index]" class="form-check-input me-2">
                <label :for="'answer_' + answerIndex + '_question_' + index" class="form-check-label">{{ answer
                  }}</label>
              </div>
            </li>
          </ul>
        </div>
      </div>
      <div class="text-center">
        <button @click="submitAnswers" class="btn btn-primary">Soumettre les réponses</button>
      </div>
    </div>
    <div v-else>
      <h2 class="mb-3">Résultat :</h2>
      <p>Nombre de réponses correctes : {{ totalCorrect }} sur {{ questions.length }}</p>
      <div class="user-responses">
        <div v-for="(answers, index) in userResponses" :key="index" class="user-response mb-4">
          <h3 class="mb-2">Question {{ index + 1 }}</h3>
          <ul class="answers-list">
            <li v-for="(answer, answerIndex) in answers" :key="answerIndex" class="answer">
              Question ID : {{ answer.question_id }}
              <br>
              Réponse : {{ answer.answer }}
              <br>
              <span v-if="answer.userCorrect" class="text-success">Correct</span>
              <span v-else class="text-danger">Incorrect</span>
              <br>
              Réponses correctes :
              <span v-for="(correctAnswer, correctIndex) in correctAnswers[index]" :key="correctIndex">
                {{ correctAnswer.text }}
                <span v-if="correctIndex !== correctAnswers[index].length - 1">, </span>
              </span>
            </li>
          </ul>
        </div>
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
      selectedAnswers: [],
      userResponses: [],
      showQuestions: true,
      totalCorrect: 0,
      candidatId: null, // ID du candidat connecté
      correctAnswers: [], // Réponses correctes récupérées depuis le backend
    };
  },
  mounted() {
    this.fetchRandomQuestions();
    // Récupérer l'ID du candidat connecté lors du montage du composant
    const candidat = JSON.parse(localStorage.getItem('currentUser'))
    this.candidatId = candidat.id;
    console.log(this.candidatId)
  },
  methods: {
    fetchRandomQuestions() {
      axios.get('gestionQcm/questions/AllQuestion')
        .then(response => {
          this.questions = response.data.questions.map(question => {
            return {
              ...question,
              answers: question.answers.map(answer => answer.text)
            };
          });
          this.selectedAnswers = new Array(this.questions.length).fill([]);
        })
        .catch(error => {
          console.error('Erreur lors de la récupération des questions aléatoires:', error);
        });
    },
    submitAnswers() {
      this.showQuestions = false;

      // Convertir les données des réponses sélectionnées dans le format attendu par le serveur
      const candidat = JSON.parse(localStorage.getItem('candidat'));

      // Récupérer l'ID du candidat
      const candidatIdd = candidat.id;

      // Convertir les données des réponses sélectionnées dans le format attendu par le serveur
      const userAnswersData = this.selectedAnswers.map((answers, index) => {
        return answers.map((answer) => {
          return {
            candidat_id: candidatIdd,
            question_id: this.questions[index].id,
            answer_id: answer.id,
          };
        });
      });

      // Envoyer la requête POST pour ajouter les réponses utilisateur
      axios.post('/addUserAnswers', { user_answers: userAnswersData })
        .then(response => {
          // Gérer la réponse si nécessaire
          console.log(response.data);
        })
        .catch(error => {
          console.error('Erreur lors de l\'enregistrement des réponses utilisateur:', error);
        });
    },
    getCorrectAnswers() {
      axios.get('/gestionQcm/questions/getCorrectlyAnswers')
        .then(response => {
          this.correctAnswers = response.data;
          // Calculer le nombre total de réponses correctes
          this.calculateTotalCorrect();
        })
        .catch(error => {
          console.error('Erreur lors de la récupération des réponses correctes:', error);
        });
    },
    calculateTotalCorrect() {
      // Initialiser le compteur de réponses correctes
      let correctCount = 0;

      // Parcourir les réponses de l'utilisateur et vérifier si elles sont correctes
      this.userResponses.forEach(answers => {
        answers.forEach(answer => {
          const questionId = answer.question_id;
          const userAnswer = answer.answer;

          // Vérifier si la réponse de l'utilisateur est correcte
          const correctAnswer = this.correctAnswers.find(a => a.question_id === questionId);
          if (correctAnswer && correctAnswer.text === userAnswer) {
            correctCount++;
          }
        });
      });

      // Mettre à jour le nombre total de réponses correctes
      this.totalCorrect = correctCount;
    }
  }
};
</script>

<style scoped>
.card {
  transition: transform 0.3s ease-in-out;
}

.card:hover {
  transform: scale(1.05);
}

.btn {
  transition: background-color 0.3s ease-in-out;
}

.btn:hover {
  background-color: #3b8bff;
}

.user-responses {
  max-width: 800px;
  margin: 0 auto;
}

.user-response {
  background-color: #f8f9fa;
  border: 1px solid #dee2e6;
  border-radius: 5px;
  padding: 15px;
  margin-bottom: 20px;
}

.user-response h3 {
  margin-bottom: 10px;
}

.answers-list {
  list-style-type: none;
  padding: 0;
}

.answer {
  padding: 5px 0;
  border-bottom: 1px solid #ced4da;
}

.answer:last-child {
  border-bottom: none;
}
</style>
