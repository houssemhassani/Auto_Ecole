import { createRouter, createWebHistory } from 'vue-router';
import Login from '../components/auth/Login.vue';
import Home from '../pages/Home.vue';
import Register from '../components/auth/Register.vue'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home,
    meta: { requiresAuth: true } // Indique que la page d'accueil nécessite une authentification
  },
  {
    path: '/register',
    name: 'Register',
    component: Register, // Indique que la page de connexion est accessible uniquement si l'utilisateur n'est pas connecté
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
    // Indique que la page de connexion est accessible uniquement si l'utilisateur n'est pas connecté
  },
  {
    path: '/user/profile',
    name: 'Profile',
    component: () => import("../components/auth/Profile.vue"),
    meta: { requiresAuth: true } // Indique que la page de profil nécessite une authentification
  },
  {
    path: '/forgot',
    name: 'Forgot',
    meta: { requiresGuest: true },
    component: () => import("../components/auth/Forgot.vue")
  },
  {
    path: '/checkCode',
    name: 'CheckCode',
    meta: { requiresGuest: true },
    component: () => import("../components/auth/CodeReset.vue")

  },
  {
    path: '/resetPassword',
    name: 'ResetPassword',
    meta: { requiresGuest: true },
    component: () => import("../components/auth/Reset.vue")
  },
  {
    path: '/admin/gestioncandidat/create',
    name: 'createCandidat',
    meta: { requiresAuth: true },
    component: () => import("../pages/Admin/Gestion Utilisateur/Gestion Candidat/CreateCandidat.vue")

  },
  {
    path: '/admin/gestionmonitor/create',
    name: 'createMonitor',
    meta: { requiresAuth: true },
    component: () => import("../pages/Admin/Gestion Utilisateur/GestionMonitor/CreateMonitor.vue")

  },
  {
    path: '/admin/gestionmonitor/:id',
    name: 'ShowMonitor',
    meta: { requiresAuth: true },
    component: () => import("../pages/Admin/Gestion Utilisateur/GestionMonitor/ShowMonitor.vue")
  },
  {
    path: '/admin/gestioncour/allCours',
    name: 'AllCours',
    meta: { requiresAuth: true },
    component: () => import("../pages/Admin/Gestion Cour/Cours.vue")
  },
  {
    path: '/admin/gestioncour/show/:id',
    name: 'ShowCour',
    component: () => import("../pages/Admin/Gestion Cour/Show.vue"),
    meta: { requiresAuth: true },
    props: true
  },
  {
    path: '/admin/gestioncour/edit/:id',
    name: 'EditCour',
    meta: { requiresAuth: true },
    component: () => import("../pages/Admin/Gestion Cour/Edit.vue"),
    props: true
  },
  {
    path: '/admin/gestioncour/create',
    name: 'CreateCour',
    meta: { requiresAuth: true },
    component: () => import("../pages/Admin/Gestion Cour/CreateCour.vue")
  },
  {
    path: '/admin/gestionmonitor/',
    name: 'allMonitors',
    meta: { requiresAuth: true },
    component: () => import("../pages/Admin/Gestion Utilisateur/GestionMonitor/Monitors.vue")

  },
  {
    path: '/admin/gestioncandidat/',
    name: 'allCandidats',
    meta: { requiresAuth: true },
    component: () => import("../pages/Admin/Gestion Utilisateur/Gestion Candidat/Candidats.vue")

  },
  {
    path: '/admin/gestioncandidat/assignCourse',
    name: 'assignCourse',
    meta: { requiresAuth: true },
    component: () => import("../pages/Admin/Gestion Utilisateur/Gestion Candidat/AssignCourse.vue")

  },
  {
    path: '/admin/role',
    name: 'Role',
    meta: { requiresAuth: true },
    component: () => import("../pages/Admin/roles/Role.vue")

  },
  {
    path: '/admin/assignRole/:userId',
    name: 'assignRole',
    meta: { requiresAuth: true },
    component: () => import("../pages/Admin/Gestion Utilisateur/AssignRole.vue")

  },
  {
    path: '/admin/role/create/',
    name: 'CreateRole',
    meta: { requiresAuth: true },
    component: () => import("../pages/Admin/roles/FormRole.vue")

  },
  {
    path: '/admin/edit-role/:id',
    name: 'EditRole',
    meta: { requiresAuth: true },
    component: () => import("../pages/Admin/roles/EditRole.vue")

  },
  {
    path: '/admin/gestionUser/listUsers',
    name: 'ListUsers',
    meta: { requiresAuth: true },
    component: () => import("../pages/Admin/Gestion Utilisateur/ListOfUser.vue")
  }
  // Ajoutez d'autres "routes" si nécessaire
];

const router = createRouter({
  history: createWebHistory(),
  routes
});
router.beforeEach((to, from, next) => {
  const isLoggedIn = localStorage.getItem('token');

  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!isLoggedIn) {
      next('/login'); // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    } else {
      next();
    }
  } else if (to.matched.some(record => record.meta.requiresGuest)) {
    if (isLoggedIn) {
      next('/'); // Redirige vers la page d'accueil si l'utilisateur est déjà connecté
    } else {
      next();
    }
  } else {
    next();
  }
});


export default router;
