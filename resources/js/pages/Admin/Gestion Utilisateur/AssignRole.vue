<template>
    <div class="container">
        <div class="col-md-6 m-auto">
            <h1>Assign Roles to User</h1>
            <div class="mt-3">
                <form @submit.prevent="assignRoles">
                    <div class="mb-3">
                        <label for="userId" class="form-label">User ID</label>
                        <input type="text" :value="userId" id="userId" class="form-control" readonly>
                    </div>
                    <hr>
                    <div class="mb-2">
                        <label class="form-label">Select Roles</label><br>
                        <span v-for="(role, index) in roles" :key="index">
                            <label for="" class="m-2">
                                <input type="checkbox" v-model="selectedRoles" :value="role.name" class="form-checkbox">
                                {{ role.name }}
                            </label>
                            <br>
                        </span>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary">Assign Roles</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from '@/axios-config';
import { useRouter, useRoute } from 'vue-router';

const router = useRouter();
const route = useRoute();
const roles = ref([]);
const userId = ref(route.params.userId);
const selectedRoles = ref([]);
const isProcess = ref(false);

const assignRoles = async () => {
    isProcess.value = true;
    try {
        await axios.post(`/users/${userId.value}/assign-roles`, { roles: selectedRoles.value });
        isProcess.value = false;
        router.push('/');
    } catch (error) {
        isProcess.value = false;
        console.error('Failed to assign roles:', error);
    }
}

const fetchRoles = async () => {
    try {
        const response = await axios.get('/roles');
        roles.value = response.data.roles;
    } catch (error) {
        console.error('Failed to fetch roles:', error);
    }
}

fetchRoles();
</script>
