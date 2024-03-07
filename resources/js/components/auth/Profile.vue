<template>
    <div id="app">
        <div class="page-holder bg-light">
            <header class="header bg-info shadow align-self-end py-3 px-xl-5 w-100">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start fw-bold">
                            <p class="mb-2 mb-md-0 fw-bold">Aut-Ecole</p>
                        </div>
                        <div class="col-md-6 text-center text-md-end text-gray-400">
                            <p class="mb-0" v-if="userData">{{ userData.name }}</p>
                        </div>
                    </div>
                </div>
            </header>
            <div class="container-fluid px-lg-4 px-xl-5 contentDiv">
                <section>
                    <div class="row">
                        <div class="col-lg-18" v-if="!editMode">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4 class="card-heading">My Profile</h4>
                                </div>
                                <div class="card-body">
                                    <div v-if="userData">
                                        <div class="mb-3">
                                            <strong class="form-label">Name</strong>
                                            <p>{{ userData.name }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <strong class="form-label">Email</strong>
                                            <p>{{ userData.email }}</p>
                                        </div>
                                    </div>
                                    <div v-if="additionalData.candidat">
                                        <div class="mb-3">
                                            <strong class="form-label">CIN</strong>
                                            <p>{{ additionalData.candidat.cin }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <strong class="form-label">Address</strong>
                                            <p>{{ additionalData.candidat.adresse }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <strong class="form-label">Date of Birth</strong>
                                            <p>{{ formatDate(additionalData.candidat.date_of_birth) }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <strong class="form-label">Phone Number</strong>
                                            <p>{{ additionalData.candidat.num_tel }}</p>
                                        </div>
                                    </div>
                                    <div v-if="additionalData.monitor">
                                        <div class="mb-3">
                                            <strong class="form-label">Professional Number</strong>
                                            <p>{{ additionalData.monitor.num_professional }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <strong class="form-label">CIN</strong>
                                            <p>{{ additionalData.monitor.num_cin }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-18">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4 class="card-heading">Edit Profile</h4>
                                </div>
                                <div class="card-footer" v-if="!editMode">
                                    <button @click="toggleEditMode" class="btn btn-primary">Modifier mes
                                        données</button>
                                </div>
                                <div class="card-body" v-else>
                                    <form @submit.prevent="updateProfile">
                                        <div class="mb-3">
                                            <strong class="form-label">Name</strong>
                                            <input class="form-control" v-model="formData.name" placeholder="Your name">
                                        </div>
                                        <div class="mb-3">
                                            <strong class="form-label">Email</strong>
                                            <input class="form-control" v-model="formData.email"
                                                placeholder="you@domain.com" disabled>
                                        </div>
                                        <div v-if="additionalData.candidat">
                                            <div class="mb-3">
                                                <strong class="form-label">CIN</strong>
                                                <input class="form-control" v-model="formData.cin" placeholder="CIN">
                                            </div>
                                            <div class="mb-3">
                                                <strong class="form-label">Address</strong>
                                                <input class="form-control" v-model="formData.address"
                                                    placeholder="Address">
                                            </div>
                                            <div class="mb-3">
                                                <strong class="form-label">Date of Birth</strong>
                                                <input class="form-control" v-model="formData.dateOfBirth" type="date">
                                            </div>
                                            <div class="mb-3">
                                                <strong class="form-label">Phone Number</strong>
                                                <input class="form-control" v-model="formData.phoneNumber"
                                                    placeholder="Phone Number">
                                            </div>
                                        </div>
                                        <div v-if="additionalData.monitor">
                                            <div class="mb-3">
                                                <strong class="form-label">Professional Number</strong>
                                                <input class="form-control" v-model="formData.professionalNumber"
                                                    placeholder="Professional Number">
                                            </div>
                                            <div class="mb-3">
                                                <strong class="form-label">CIN</strong>
                                                <input class="form-control" v-model="formData.cin" placeholder="CIN">
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">Update Profile</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <footer class="footer bg-white shadow align-self-end py-3 px-xl-5 w-100">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start fw-bold">
                            <p class="mb-2 mb-md-0 fw-bold">Auto-Ecole © 2024</p>
                        </div>
                        <div class="col-md-6 text-center text-md-end text-gray-400">
                            <p class="mb-0" v-if="userData">{{ userData.name }}</p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</template>

<script>
import "bootstrap/dist/css/bootstrap.min.css";
import axios from '@/axios-config';

export default {
    data() {
        return {
            userData: {},
            additionalData: {},
            formData: {
                name: '',
                email: '',
                cin: '',
                address: '',
                dateOfBirth: '',
                phoneNumber: '',
                professionalNumber: ''
            },
            editMode: false
        };
    },
    mounted() {
        this.getCurrentUser();
    },
    methods: {
        getCurrentUser() {
            const currentUser = JSON.parse(localStorage.getItem('currentUser'));
            if (currentUser) {
                this.userData = currentUser;
                this.loadAdditionalData(currentUser.email);
            }
        },
        formatDate(dateString) {
            if (!dateString) return ''; // Vérifie si la date est définie

            const date = new Date(dateString); // Convertit la chaîne de date en objet Date
            const options = { year: 'numeric', month: 'long', day: 'numeric' }; // Options de formatage de la date
            return date.toLocaleDateString('fr-FR', options); // Formate la date selon les options spécifiées
        },
        loadAdditionalData(email) {
            axios.get(`/gestionUser/${email}`)
                .then(response => {
                    this.additionalData = response.data.autre;
                    this.fillFormData(response.data.user);
                })
                .catch(error => {
                    console.error(error);
                });
        },
        toggleEditMode() {
            // Toggle edit mode
            this.editMode = !this.editMode;
        },
        fillFormData(user) {
            this.formData.name = user.name;
            this.formData.email = user.email;
            this.formData.cin = this.additionalData.candidat ? this.additionalData.candidat.cin : '';
            this.formData.address = this.additionalData.candidat ? this.additionalData.candidat.adresse : '';
            this.formData.dateOfBirth = this.additionalData.candidat ? this.additionalData.candidat.date_of_birth : '';
            this.formData.phoneNumber = this.additionalData.candidat ? this.additionalData.candidat.num_tel : '';
            this.formData.professionalNumber = this.additionalData.monitor ? this.additionalData.monitor.num_professional : '';
        },
        updateProfile() {
            // Envoyer les données mises à jour au backend
            this.editMode = false;
            console.log("Updating profile...", this.formData);
        }
    }
};
</script>

<style scoped>
.page-holder {
    min-height: 100vh;
}

.contentDiv {
    min-height: calc(100vh - 160px);
}
</style>