<template>
    <div id="app">
        <div class="container">
            <h1>List des utilisateurs</h1>
            <table class="table table-info">

                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Date Création</th>
                        <th scope="colgroup">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(user, index) in users" :key="index">
                        <th scope="row">{{ index + 1 }}</th>
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>{{ formatDate(user.created_at) }}</td>
                        <td>
                            <button style="background-color: #FFA500; border-color: #FFA500; color: #8B0000;width:50%;"
                                @click="deleteUser(user.id)" class="btn btn-danger">Delete</button>
                            <button style="background-color: coral; border-color: coral;color: teal; width: 50%;"
                                @click="assignRole(user.id)" class="btn btn-danger">Assign Role</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import axios from '@/axios-config';

export default {
    data() {
        return {
            users: []
        };
    },
    mounted() {
        // Make an HTTP request to fetch users data
        axios.get('/gestionUser/AllUsers') // Assuming this is the endpoint returning users data
            .then(response => {
                console.log(response.data.data)
                this.users = response.data.data;
            })
            .catch(error => {
                console.error('Error fetching users:', error);
            });
    },
    methods: {
        formatDate(date) {
            // Format the date using JavaScript Date object
            return new Date(date).toLocaleDateString();
        },
        deleteUser(userId) { // Méthode pour supprimer un utilisateur
            axios.delete(`/gestionUser/destroy/${userId}`)
                .then(response => {
                    console.log('User deleted successfully');
                    // Mettre à jour la liste des utilisateurs après suppression
                    this.users = this.users.filter(user => user.id !== userId);
                })
                .catch(error => {
                    console.error('Error deleting user:', error);
                });
        },
        assignRole(userId) {

            // Rediriger vers la page d'assignation de rôle avec l'ID de l'utilisateur en tant que paramètre
            this.$router.push({ name: 'assignRole', params: { userId: userId } });

        }
    }
};
</script>