<template>
    <v-container>
      <v-card class="pa-4">
        <v-card-title>
          Клиенты
          <v-spacer></v-spacer>
          <v-btn color="primary" @click="dialog = true">Добавить клиента</v-btn>
        </v-card-title>

        <v-data-table
          :headers="headers"
          :items="clients"
          :loading="loading"
          item-value="id"
          class="elevation-1 mt-4"
        >
          <template #item.actions="{ item }">
            <v-btn icon="mdi-delete" color="error" @click="removeClient(item.id)"></v-btn>
          </template>
        </v-data-table>
      </v-card>

      <!-- Диалог добавления клиента -->
      <v-dialog v-model="dialog" max-width="500px">
        <v-card class="pa-4">
          <v-card-title>Добавить клиента</v-card-title>
          <v-card-text>
            <v-text-field label="Имя" v-model="form.name" />
            <v-text-field label="Email" v-model="form.email" />
            <v-text-field label="Телефон" v-model="form.phone" />
            <v-textarea label="Заметки" v-model="form.notes" />
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" @click="addClient">Сохранить</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-container>
  </template>

  <script setup>
  import { ref, onMounted } from 'vue';
  import api from '../api';

  const clients = ref([]);
  const loading = ref(false);
  const dialog = ref(false);

  const form = ref({
    name: '',
    email: '',
    phone: '',
    notes: '',
  });

  const headers = [
    { title: 'ID', key: 'id' },
    { title: 'Имя', key: 'name' },
    { title: 'Email', key: 'email' },
    { title: 'Телефон', key: 'phone' },
    { title: 'Заметки', key: 'notes' },
    { title: 'Действия', key: 'actions', sortable: false },
  ];

  const fetchClients = async () => {
    loading.value = true;
    try {
      const res = await api.get('/clients');
      clients.value = res.data;
    } catch (e) {
      console.error('Ошибка загрузки клиентов', e);
    } finally {
      loading.value = false;
    }
  };

  const addClient = async () => {
    try {
      await api.post('/clients', form.value);
      dialog.value = false;
      await fetchClients();
      form.value = { name: '', email: '', phone: '', notes: '' };
    } catch (e) {
      alert('Ошибка добавления клиента');
    }
  };

  const removeClient = async (id) => {
    if (!confirm('Удалить клиента?')) return;
    try {
      await api.delete(`/clients/${id}`);
      await fetchClients();
    } catch (e) {
      alert('Ошибка удаления клиента');
    }
  };

  onMounted(fetchClients);
  </script>
