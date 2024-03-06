<template>
    <div id="app">

    </div>
    <div class="page-holder bg-gray-100">
        <header class="header bg-white shadow align-self-end py-3 px-xl-5 w-100">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start fw-bold">
                        <p class="mb-2 mb-md-0 fw-bold">Aut-Ecole</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end text-gray-400">
                        <p class="mb-0" v-if="currentUser">{{ currentUser?.name }}</p>
                    </div>
                </div>
            </div>
        </header>
        <div class="container-fluid px-lg-4 px-xl-5 contentDiv">
            <!-- Page Header-->

            <section>
                <div class="row">
                    <div class="col-lg-4">


                        <form class="card mb-4">
                            <div class="card-header">
                                <h4 class="card-heading">My Profile</h4>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-auto d-flex align-items-center"><img class="avatar avatar-lg p-1"
                                            src="https://therichpost.com/wp-content/uploads/2021/03/avatar2.png"
                                            alt="Avatar"></div>
                                    <div class="col">
                                        <label class="form-label">Name</label>
                                        <input class="form-control" placeholder="Your name">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Bio</label>
                                    <textarea class="form-control"
                                        rows="8">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input class="form-control" placeholder="you@domain.com">
                                </div>
                                <label class="form-label">Password</label>
                                <input class="form-control" type="password" value="password">
                            </div>
                            <div class="card-footer text-end">
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>

                    <form class="card mb-4">
                        <div class="card-header">
                            <h4 class="card-heading">Edit Profile</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="mb-4">
                                        <label class="form-label">Company</label>
                                        <input class="form-control" type="text" placeholder="Company" value="Jassa Rich">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="mb-4">
                                        <label class="form-label">Username</label>
                                        <input class="form-control" type="text" placeholder="Username" value="Jassa">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Email address</label>
                                        <input class="form-control" type="email" placeholder="Email">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label">First Name</label>
                                        <input class="form-control" type="text" placeholder="First name">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label">Last Name</label>
                                        <input class="form-control" type="text" placeholder="Last Name">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <label class="form-label">Address</label>
                                        <input class="form-control" type="text" placeholder="Home Address">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">City</label>
                                        <input class="form-control" type="text" placeholder="City">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="mb-4">
                                        <label class="form-label">ZIP</label>
                                        <input class="form-control" type="number" placeholder="ZIP">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="mb-4">
                                        <label class="form-label">Country</label>
                                        <select class="form-control custom-select">
                                            <option value="">UK</option>
                                            <option value="">US</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-0">
                                        <label class="form-label">About Me</label>
                                        <textarea class="form-control" rows="5"
                                            placeholder="Here can be your description">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button class="btn btn-primary" type="submit">Update Profile</button>
                        </div>
                    </form>
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
                        <p class="mb-0" v-if="currentUser">{{ currentUser.name }}</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
  
<script >
import "bootstrap/dist/css/bootstrap.min.css";
export default {
    data() {
        return {
            currentUser: null
        };
    },
    mounted() {
        this.getCurrentUser();
    },
    methods: {
        getCurrentUser() {
            const currentUser = localStorage.getItem('currentUser');
            if (currentUser) {
                this.currentUser = JSON.parse(currentUser);
                console.log(this.currentUser)
            }
        }
    }
};
</script>
