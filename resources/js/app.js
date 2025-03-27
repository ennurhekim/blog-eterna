import { createApp } from 'vue';
import App from './App.vue';
import router from './router';

const app = createApp(App);
if (process.env.NODE_ENV === 'production') {
    app.config.globalProperties.$apiBaseURL = window.location.origin + "/api";  // Prod ortamında site URL'si
} else {
    app.config.globalProperties.$apiBaseURL = "http://localhost:8000/api";  // Geliştirme ortamında localhost
}
app.use(router);
app.mount('#app');
