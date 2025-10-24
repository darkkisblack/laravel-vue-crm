<template>
    <v-container>
      <v-card class="pa-4">
        <v-card-title>
          Сделки
          <v-spacer></v-spacer>
          <v-select
            v-model="filterStatus"
            :items="statuses"
            item-title="label"
            item-value="value"
            label="Фильтр по статусу"
            dense
            hide-details
            class="mr-4"
            style="max-width: 200px"
          ></v-select>
          <v-btn color="primary" @click="dialog = true">Добавить сделку</v-btn>
        </v-card-title>

        <v-data-table
          :headers="headers"
          :items="deals"
          :loading="loading"
          item-value="id"
          class="elevation-1 mt-4"
        >
          <template #item.status="{ item }">
            <v-chip :color="statusColor(item.status)" dark>{{ statusText(item.status) }}</v-chip>
          </template>

          <template #item.client="{ item }">
            {{ item.client?.name || '—' }}
          </template>

          <template #item.actions="{ item }">
            <v-btn icon="mdi-delete" color="error" @click="removeDeal(item.id)"></v-btn>
          </template>
        </v-data-table>
      </v-card>

      <!-- Диалог добавления -->
      <v-dialog v-model="dialog" max-width="600px">
        <v-card class="pa-4">
          <v-card-title>Добавить сделку</v-card-title>
          <v-card-text>
            <v-text-field v-model="form.title" label="Название" />
            <v-text-field v-model="form.amount" label="Сумма" type="number" />
            <v-select
              v-model="form.status"
              :items="statuses"
              label="Статус"
              item-value="value"
              item-title="label"
            />
            <v-select
              v-model="form.client_id"
              :items="clients"
              label="Клиент"
              item-value="id"
              item-title="name"
            />
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" @click="addDeal">Сохранить</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-container>
  </template>

  <script setup>
  import { ref, onMounted, watch } from 'vue';
  import api from '../api';

  const deals = ref([]);
  const clients = ref([]);
  const dialog = ref(false);
  const loading = ref(false);
  const filterStatus = ref(null);

  const statuses = [
    { label: 'Все', value: null },
    { label: 'Новая', value: 'new' },
    { label: 'В работе', value: 'in_progress' },
    { label: 'Выиграна', value: 'won' },
    { label: 'Проиграна', value: 'lost' },
  ];

  const headers = [
    { title: 'ID', key: 'id' },
    { title: 'Название', key: 'title' },
    { title: 'Клиент', key: 'client' },
    { title: 'Сумма', key: 'amount' },
    { title: 'Статус', key: 'status' },
    { title: 'Действия', key: 'actions', sortable: false },
  ];

  const form = ref({
    title: '',
    amount: '',
    status: 'new',
    client_id: null,
  });

  const fetchDeals = async () => {
    loading.value = true;
    try {
      const params = {};
      if (filterStatus.value) params.status = filterStatus.value;
      const res = await api.get('/deals', { params });
      deals.value = res.data;
    } catch (e) {
      console.error('Ошибка загрузки сделок', e);
    } finally {
      loading.value = false;
    }
  };

  const fetchClients = async () => {
    const res = await api.get('/clients');
    clients.value = res.data;
  };

  const addDeal = async () => {
    try {
      await api.post('/deals', form.value);
      dialog.value = false;
      await fetchDeals();
      form.value = { title: '', amount: '', status: 'new', client_id: null };
    } catch (e) {
      alert('Ошибка при добавлении сделки');
    }
  };

  const removeDeal = async (id) => {
    if (!confirm('Удалить сделку?')) return;
    try {
      await api.delete(`/deals/${id}`);
      await fetchDeals();
    } catch (e) {
      alert('Ошибка удаления сделки');
    }
  };

  const statusText = (status) => {
    const map = {
      new: 'Новая',
      in_progress: 'В работе',
      won: 'Выиграна',
      lost: 'Проиграна',
    };
    return map[status] || status;
  };

  const statusColor = (status) => {
    const map = {
      new: 'grey',
      in_progress: 'blue',
      won: 'green',
      lost: 'red',
    };
    return map[status] || 'grey';
  };

  onMounted(async () => {
    await fetchClients();
    await fetchDeals();
  });

  watch(filterStatus, fetchDeals);
  </script>
