import './bootstrap';
import { createApp } from 'vue';
import { createVuetify } from 'vuetify';
import router from './router/index';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import 'vuetify/styles';
import '@mdi/font/css/materialdesignicons.css';
import App from './App.vue';

const app = createApp(App);

const vuetify = createVuetify({
    components,
    directives,
    theme: {
        defaultTheme: 'light',
    },
});

app.use(vuetify);
app.use(router);
app.mount('#app');
