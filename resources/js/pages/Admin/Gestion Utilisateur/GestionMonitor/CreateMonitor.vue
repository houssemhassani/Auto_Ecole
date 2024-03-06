<template>
  <div class="container">
    <div class="col-md-6 m-auto">
      <h1>Create Monitor</h1>
      <div class="mt-3">
        <form @submit.prevent="submitCreate">
          <div class="mb-3">
            <label for="name" class="form-label">Name :</label>
            <input type="text" v-model="form.name" id="name" placeholder="Monitor Name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email :</label>
            <input type="email" v-model="form.email" id="email" placeholder="Monitor Email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password :</label>
            <input type="password" v-model="form.password" id="password" placeholder="Monitor Password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="num_professional" class="form-label">Numéro professionnel :</label>
            <input type="text" v-model="form.numProfessional" id="num_professional" placeholder="Professional Number" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="num_cin" class="form-label">Numéro CIN :</label>
            <input type="text" v-model="form.numCin" id="num_cin" placeholder="CIN Number" class="form-control" required>
          </div>
          <hr>
          <div class="mb-2">
            <label class="form-label">Choisir l'Auto Ecole :</label><br>
            <span v-for="(itemEcole, index) in autoEcoles" :key="index">
              <label for="" class="m-2">
                <input type="checkbox" v-model="form.autoEcoleId" :value="itemEcole.id" class="form-checkbox">
                {{ itemEcole.name }}
              </label>
              <br>
            </span>
          </div>
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
const autoEcoles = ref([]);
const form = ref({
    name: '',
    email: '',
    password: '',
    numProfessional: '',
    numCin: '',
    autoEcoleId: []
});
const isProcess = ref(false);

onMounted(async () => {
    await fetchAutoEcoles();
});

const fetchAutoEcoles = async () => {
    try {
        const response = await axios.get('/gestionmonitor/autoecoles');
        autoEcoles.value = response.data.autoecoles;
    } catch (error) {
        console.error('Error fetching auto-ecoles:', error);
    }
}

const submitCreate = async () => {
    isProcess.value = true;
    try {
      console.log(form.value.autoEcoleId[0])
        const response = await axios.post('/gestionmonitor/store', {
            name: form.value.name,
            email: form.value.email,
            password: form.value.password,
            num_professional: form.value.numProfessional,
            num_cin: form.value.numCin,
            auto_ecole_id: form.value.autoEcoleId[0]
        });
        console.log(response.data);
        isProcess.value = false;
        router.push('/admin/monitor');
    } catch (error) {
        console.error('Error creating monitor:', error.value);
        isProcess.value = false;
    }
}
</script>
