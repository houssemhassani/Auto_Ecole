<template>
    <div id="app">
        <div class="container">
            <div class="d-flex justify-content-between">
                <h1>List Role</h1>
                 <router-link to="role/create">
                    <button class="btn btn-primary" style="background-color: #40E0D0; border-color: #40E0D0;color: grey;">Add</button>
                </router-link>  
            </div>
            <div class="col-md-6">
                <table class="table table-info" style="background-color: teal;">
                    <thead>
                        <tr >
                            <th scope="col" style=" color: coral;">No.</th>
                            <th scope="col" style=" color: coral;">Name Role</th>
                            <th scope="col" style=" coral; color: coral;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in roles" :key="index">
                            <th style=" color: coral;" scope="row">{{ index + 1 }}</th>
                            <td style="color: grey;">{{ item.name }}</td>
                            <td class="d-flex justify-content-end">
                                <button class="btn btn-success" style="background-color: gray; border-color: gray; color: #40E0D0;">view</button>
                                <router-link :to="`/admin/edit-role/${item.id}`">
                                    <button class="btn btn-info" style="background-color: #40E0D0; border-color: #40E0D0;color: grey;">edit</button>          
                                </router-link>
                                <button style="background-color: #FFA500; border-color: #FFA500; color: #8B0000;" class="btn btn-danger" @click="clickDel(item.id, index)">
                                    delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import axios from '@/axios-config';
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2';
const  router=useRouter();
export default {
    setup() {
        const roles = ref({});
        onMounted(async () => {
            const response = await axios.get('/roles');
            roles.value = response.data.roles;
            console.log(roles)
        });

        const clickDel = (id, index) => {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    await axios.delete('/roles/' + id)
                        .then(response => {
                            if (response.status === 200) {
                                roles.value.splice(index, 1);
                            }
                        })
                }
            })
        }

        return {
            roles,
            clickDel
        };
    }
};
</script>
