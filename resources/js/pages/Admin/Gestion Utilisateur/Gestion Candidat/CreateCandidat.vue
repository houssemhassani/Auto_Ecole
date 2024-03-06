<template>
    <div class="container">
      <div class="col-md-6 m-auto">
        <h1>Create Candidat</h1>
        <div class="mt-3">
          <form @submit.prevent="submitCreate">
            <div class="mb-3">
              <label for="name" class="form-label">Name :</label>
              <input type="text" v-model="form.name" id="name" placeholder="Candidat Name" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="first_name" class="form-label">First Name :</label>
              <input type="text" v-model="form.first_name" id="first_name" placeholder="Candidat First Name" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="last_name" class="form-label">Last Name :</label>
              <input type="text" v-model="form.last_name" id="last_name" placeholder="Candidat Last Name" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email :</label>
              <input type="email" v-model="form.email" id="email" placeholder="Candidat Email" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password :</label>
              <input type="password" v-model="form.password" id="password" placeholder="Candidat Password" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="cin" class="form-label">CIN :</label>
              <input type="text" v-model="form.cin" id="cin" placeholder="CIN Number" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="adresse" class="form-label">Adresse :</label>
              <input type="text" v-model="form.adresse" id="adresse" placeholder="Candidat Address" class="form-control">
            </div>
            <div class="mb-3">
              <label for="date_of_birth" class="form-label">Date of Birth :</label>
              <input type="date" v-model="form.date_of_birth" id="date_of_birth" class="form-control">
            </div>
            <div class="mb-3">
              <label for="num_tel" class="form-label">Phone Number :</label>
              <input type="text" v-model="form.num_tel" id="num_tel" placeholder="Candidat Phone Number" class="form-control">
            </div>
            <!-- <div class="mb-3">
              <label class="form-label">Select Course :</label><br>
              <span v-for="(itemCourse, index) in courses" :key="index">
                <label for="" class="m-2">
                  <input type="checkbox" v-model="form.cour_id" :value="itemCourse.id" class="form-checkbox">
                  {{ itemCourse.title }}
                </label>
                <br>
              </span>
            </div> -->
            <div class="mt-2">
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from '@/axios-config';
  import { useRouter } from 'vue-router'
  const router = useRouter();
  const courses = ref([]);
  const form = ref({
      name: '',
      first_name: '',
      last_name: '',
      email: '',
      password: '',
      cin: '',
      adresse: '',
      date_of_birth: '',
      num_tel: '',
      cour_id: []
  });
  const isProcess = ref(false);
  
  onMounted(async () => {
      await fetchCourses();
  });
  
  const fetchCourses = async () => {
      try {
          const response = await axios.get('/gestioncour/allCours');
          courses.value = response.data.cours;
      } catch (error) {
          console.error('Error fetching courses:', error);
      }
  }
  
  const submitCreate = async () => {
      isProcess.value = true;
      try {
          const response = await axios.post('/gestioncandidat/store', {
              name: form.value.name,
              first_name: form.value.first_name,
              last_name: form.value.last_name,
              email: form.value.email,
              password: form.value.password,
              cin: form.value.cin,
              adresse: form.value.adresse,
              date_of_birth: form.value.date_of_birth,
              num_tel: form.value.num_tel,
              cour_id: null
          });
          console.log(response.data);
          isProcess.value = false;
          router.push('/admin/monitor');
      } catch (error) {
          console.error('Error creating candidat:', error.value);
          isProcess.value = false;
      }
  }
  </script>
  