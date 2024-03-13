import { createApp } from 'vue'; // Modification ici pour importer `createApp` correctement
import App from './App.vue';
import router from './router';

const app = createApp(App); // Créez l'application Vue en utilisant createApp

app.use(router); 

// Vous pouvez également inclure NowUiKit si nécessaire
// import NowUiKit from './plugins/now-ui-kit';
// app.use(NowUiKit);

app.mount('#app'); // Montez l'application dans l'élément avec l'ID 'app'
