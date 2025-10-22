import './bootstrap';
import { createApp } from 'vue';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import 'vuetify/styles';
import '@mdi/font/css/materialdesignicons.css';

const app = createApp({});

const vuetify = createVuetify({
    components,
    directives,
    theme: {
        defaultTheme: 'light',
    },
});

app.use(vuetify);
app.mount('#app');
