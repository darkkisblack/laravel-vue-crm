<template>
    <v-container>
      <v-card class="pa-6 mx-auto mt-12" max-width="400">
        <v-card-title class="text-h5">Вход в систему</v-card-title>
        <v-card-text>
          <v-text-field v-model="email" label="Email" />
          <v-text-field v-model="password" label="Пароль" type="password" />
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" block @click="login">Войти</v-btn>
        </v-card-actions>
      </v-card>
    </v-container>
  </template>

  <script setup>
  import axios from '../api';
  import { ref } from 'vue';
  import { useRouter } from 'vue-router';

  const email = ref('');
  const password = ref('');
  const router = useRouter();

  const login = async () => {
    try {
      const res = await axios.post('/login', {
        email: email.value,
        password: password.value,
      });

      localStorage.setItem('token', res.data.token);
      axios.defaults.headers.common['Authorization'] = `Bearer ${res.data.token}`;
      router.push('/clients');
    } catch (e) {
      alert('Ошибка авторизации. Проверьте логин и пароль.');
    }
  };
  </script>
