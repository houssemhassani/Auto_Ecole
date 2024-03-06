<template>
    <div class="container">
        <div class="col-md-6 m-auto">
            <h1>Edit Role</h1>
            <div class="mt-3">
                <form @submit.prevent="submitUpdate">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" v-model="form.role" id="name" placeholder="Role Name" class="form-control">
                    </div>
                    <hr>
                    <div class="mb-2">
                        <label class="form-label">Pilih Permission</label><br>
                        <span v-for="(itemPerm, index) in permissions" :key="index">
                            <label for="" class="m-2">
                                <input type="checkbox" v-model="form.permission" 
                                :value="itemPerm.id" class="form-checkbox"
                                :checked="isItemChecked(itemPerm.id,index)"
                                @change="toggleCheckbox(index)">
                                {{ itemPerm.name }}
                            </label>
                            <br>
                        </span>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-info">Update</button>
                        <button class="btn btn-primary" type="button"
                         v-if="isProcess" disabled>Loading</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted } from 'vue';
import axios from '@/axios-config';
import { useRouter, useRoute } from 'vue-router'
const router = useRouter();
const route=useRoute();
const permissions = ref([]);
const rolePermission=ref([]);
const form = ref({
    role: '',
    permission: []
});
const isProcess=ref(false);
onMounted(async () => {
    getEdit();
});
const getEdit = async () => {
    axios.get('/roles/'+route.params.id+'/edit')
    .then(response =>{
        console.log(response.data);
        form.value.role=response.data.role.name;
        permissions.value=response.data.permission;
        rolePermission.value=response.data.rolePermission;
    }).catch(error =>{
        isProcess.value=false;

    });
};
const toggleCheckbox= (index)=>{
    if(form.value.permission[index]!= null){
        rolePermission.value[form.value.permission[index]]=null;
        form.value.permission[index]=false;
    }
    else{
        rolePermission.value[form.value.permission[index]]=
        form.value.permission[index];
        form.value.permission[index]=form.value.permission[index];
    }
};
const isItemChecked =(value,index)=>{
    if(value == rolePermission.value[value]){
        form.value.permission[index]=value;
        return true;
    }
    else{
        return false;
    }
}
const submitUpdate = async () => {
    isProcess.value = true;
    await axios.post(`/roles/${route.params.id}`, {
        name: form.value.role,
        permission: form.value.permission,
        _method:'put'
        
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