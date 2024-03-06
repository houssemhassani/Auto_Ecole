<template>
    <div class="container">
        <div class="col-md-4 m-auto">
            <h1>Forgot Password</h1>
            <div class="mt-3" style="color: green;">
                {{ message }}
            
            </div>
            <div class="mt-3">
                <form @submit.prevent="submitForgot">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" placeholder="name@example.com" class="form-control" v-model="form.email">
                    </div>
                    <div>
                        <button class="btn btn-primary">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref } from 'vue';
import axios from '@/axios-config';
import { useRouter } from 'vue-router';
//import { authStore } from '../stores/authStore';
const router =useRouter();
const form =ref({
    email : '',
});
const message = ref('');
const submitForgot =async () =>{
    //localStorage.getItem('token');
    await axios.post('/user/forgot-password',{
        email : form.value.email,
    }) 
    .then(response => {
        message.value =response.data.message;
        localStorage.setItem('email',form.value.email);
        router.push( '/checkCode');

    })
    .catch(error => {
        console.log(error.response);
    });
}
</script>