<template>
    <div class="container">
        <div class="col-md-6 m-auto">
            <h1>Create Role</h1>
            <div class="mt-3">
                <form @submit.prevent="submitCreate">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" v-model="form.name" id="name" placeholder="Role Name" class="form-control">
                    </div>
                    <hr>
                    <div class="mb-2">
                        <label class="form-label">Pilih Permission</label><br>
                        <span v-for="(itemPerm, index) in permissions" :key="index">
                            <label for="" class="m-2">
                                <input type="checkbox" v-model="form.permission" :value="itemPerm.id" class="form-checkbox">
                                {{ itemPerm.name }}
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
const permissions = ref([]);
const form = ref({
    name: '',
    permission: []
});
const isProcess=ref(false);
onMounted(async () => {
    listPermission();
});
const listPermission = async () => {
    const response = await axios.get('/roles/create');
    permissions.value = response.data.permissions;
    console.log(response.status);
}
const submitCreate = async () => {
    isProcess.value = true;
    await axios.post('/roles', {
        name: form.value.name,
        permission: form.value.permission
    })
        .then(response => {
          
                isProcess.value = false;
                router.push('/admin/role');
            
            console.log(response.data.message);
        }).catch(error => {
            isProcess.value = false;
            //errmessage.value = error.response.data.message
        });
}


</script>