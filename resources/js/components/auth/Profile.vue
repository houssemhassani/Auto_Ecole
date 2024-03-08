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
                                                <input class="form-control" v-model="formData.cin" placeholder="CIN"
                                                    disabled>
                                            </div>
                                            <div class="mb-3">
                                                <strong class="form-label">Address</strong>
                                                <input class="form-control" v-model="formData.address"
                                                    placeholder="Address">
                                            </div>
                                            <div class="mb-3">
                                                <strong class="form-label">Date of Birth</strong>
                                                <input class="form-control" v-model="formData.date_of_birth"
                                                    type="date">
                                            </div>
                                            <div class="mb-3">
                                                <strong class="form-label">Phone Number</strong>
                                                <input class="form-control" v-model="formData.num_tel"
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
                date_of_birth: '',
                num_tel: '',
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
            if (!dateString) return ''; 
            const date = new Date(dateString);
            const options = { year: 'numeric', month: 'long', day: 'numeric' }; 
            return date.toLocaleDateString('fr-FR', options); 
        },
        loadAdditionalData(email) {
            axios.get(`/gestionUser/${email}`)
                .then(response => {

                    this.additionalData = response.data.autre;
                    // console.log(this.additionalData)
                    this.fillFormData(response.data.user);
                })
                .catch(error => {
                    console.error(error);
                });
        },
        toggleEditMode() {
            
            this.editMode = !this.editMode;
        },
        fillFormData(user) {
            this.formData.name = user.name;
            this.formData.email = user.email;
            this.formData.cin = this.additionalData.candidat ? this.additionalData.candidat.cin : '';
            this.formData.address = this.additionalData.candidat ? this.additionalData.candidat.adresse : '';
            this.formData.date_of_birth = this.additionalData.candidat ? this.additionalData.candidat.date_of_birth : '';
            this.formData.num_tel = this.additionalData.candidat ? this.additionalData.candidat.num_tel : '';
            this.formData.professionalNumber = this.additionalData.monitor ? this.additionalData.monitor.num_professional : '';
        },

        updateProfile() {
           
            if (this.additionalData.candidat) {
                
                const updatedData = {
                    name: this.formData.name,
                    email: this.formData.email,
                    cin: this.formData.cin,
                    address: this.formData.address,
                    date_of_birth: this.formatDateForBackend(this.formData.date_of_birth),
                    num_tel: this.formData.num_tel
                };

               
                axios.post('/gestioncandidat/updateProfile', updatedData)
                    .then(response => {
                        console.log("Profile updated successfully", response.data);
                        localStorage.setItem('currentUser', JSON.stringify(response.data.user));
                        this.editMode = false; 
                    })
                    .catch(error => {
                        console.error("Error updating profile", error);
                    });
            } else {
                if (this.additionalData.monitor) {
                    
                    const updatedData = {
                        name: this.formData.name,
                        email: this.formData.email,
                        professionalNumber: this.formData.professionalNumber
                    };

                    
                    axios.post('/gestioncandidat/updateProfile', updatedData)
                        .then(response => {
                            console.log("Profile updated successfully", response.data);
                            localStorage.setItem('currentUser', JSON.stringify(response.data.user));
                            this.editMode = false;
                        })
                        .catch(error => {
                            console.error("Error updating profile", error);
                        });
                } else {
                    console.log("admin");
                    this.editMode = false;
                }
            }
        },
        formatDateForBackend(dateString) {
            
            const date = new Date(dateString);
            const year = date.getFullYear();
            const month = ('0' + (date.getMonth() + 1)).slice(-2);
            const day = ('0' + date.getDate()).slice(-2);
            return `${year}-${month}-${day}`;
        }

    }
}
</script>

<style scoped>
.page-holder {
    min-height: 100vh;
}

.contentDiv {
    min-height: calc(100vh - 160px);
}
</style>