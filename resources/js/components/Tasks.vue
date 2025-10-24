<template>
    <v-container>
      <v-card class="pa-4">
        <v-card-title>
          Задачи
          <v-spacer></v-spacer>
          <v-select
            v-model="filterDeal"
            :items="deals"
            label="Фильтр по сделке"
            item-value="id"
            item-title="title"
            clearable
            hide-details
            dense
            class="mr-4"
            style="max-width: 250px"
          ></v-select>
          <v-select
            v-model="filterCompleted"
            :items="completedOptions"
            label="Статус"
            item-value="value"
            item-title="label"
            clearable
            hide-details
            dense
            class="mr-4"
            style="max-width: 200px"
          ></v-select>
          <v-btn color="primary" @click="dialog = true">Добавить задачу</v-btn>
        </v-card-title>

        <v-data-table
          :headers="headers"
          :items="tasks"
          :loading="loading"
          item-value="id"
          class="elevation-1 mt-4"
        >
          <template #item.completed="{ item }">
            <v-checkbox
              v-model="item.completed"
              @change="toggleCompleted(item)"
              hide-details
            ></v-checkbox>
          </template>

          <template #item.deal="{ item }">
            {{ item.deal?.title || '—' }}
          </template>

          <template #item.actions="{ item }">
            <v-btn icon="mdi-delete" color="error" @click="removeTask(item.id)"></v-btn>
          </template>
        </v-data-table>
      </v-card>

      <!-- Диалог добавления -->
      <v-dialog v-model="dialog" max-width="500px">
        <v-card class="pa-4">
          <v-card-title>Добавить задачу</v-card-title>
          <v-card-text>
            <v-text-field v-model="form.title" label="Название задачи" />
            <v-select
              v-model="form.deal_id"
              :items="deals"
              label="Сделка"
              item-value="id"
              item-title="title"
            />
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" @click="addTask">Сохранить</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-container>
  </template>

  <script setup>
  import { ref, onMounted, watch } from 'vue';
  import api from '../api';

  const tasks = ref([]);
  const deals = ref([]);
  const dialog = ref(false);
  const loading = ref(false);
  const filterDeal = ref(null);
  const filterCompleted = ref(null);

  const headers = [
    { title: 'ID', key: 'id' },
    { title: 'Название', key: 'title' },
    { title: 'Сделка', key: 'deal' },
    { title: 'Выполнено', key: 'completed' },
    { title: 'Действия', key: 'actions', sortable: false },
  ];

  const completedOptions = [
    { label: 'Все', value: null },
    { label: 'Выполнено', value: true },
    { label: 'Не выполнено', value: false },
  ];

  const form = ref({
    title: '',
    deal_id: null,
    completed: false,
  });

  const fetchTasks = async () => {
    loading.value = true;
    try {
      const params = {};
      if (filterDeal.value) params.deal_id = filterDeal.value;
      if (filterCompleted.value !== null) params.completed = filterCompleted.value;
      const res = await api.get('/tasks', { params });
      tasks.value = res.data;
    } catch (e) {
      console.error('Ошибка загрузки задач', e);
    } finally {
      loading.value = false;
    }
  };

  const fetchDeals = async () => {
    const res = await api.get('/deals');
    deals.value = res.data;
  };

  const addTask = async () => {
    try {
      await api.post('/tasks', form.value);
      dialog.value = false;
      await fetchTasks();
      form.value = { title: '', deal_id: null, completed: false };
    } catch (e) {
      alert('Ошибка добавления задачи');
    }
  };

  const toggleCompleted = async (task) => {
    try {
      await api.put(`/tasks/${task.id}`, { completed: task.completed });
    } catch (e) {
      alert('Ошибка обновления статуса');
    }
  };

  const removeTask = async (id) => {
    if (!confirm('Удалить задачу?')) return;
    try {
      await api.delete(`/tasks/${id}`);
      await fetchTasks();
    } catch (e) {
      alert('Ошибка удаления задачи');
    }
  };

  onMounted(async () => {
    await fetchDeals();
    await fetchTasks();
  });

  watch([filterDeal, filterCompleted], fetchTasks);
  </script>
