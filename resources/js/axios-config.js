import axios from 'axios';

const instance = axios.create({
    baseURL: 'http://localhost:8000/api/',
    timeout: 10000,
    headers: {
        'Content-Type': 'application/json',
    }
});

// Ajouter un intercepteur pour inclure le jeton d'authentification dans chaque requête
instance.interceptors.request.use(config => {
    const token = localStorage.getItem('token'); // Récupérer le jeton depuis le stockage local par exemple
    if (token) {
        config.headers['Authorization'] = `Bearer ${token}`;
    }
    return config;
}, error => {
    return Promise.reject(error);
});

export default instance;
