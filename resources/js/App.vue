<template>
    <v-app>
      <v-app-bar app color="primary" dark>
        <v-toolbar-title>CRM System</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn text to="/clients">Клиенты</v-btn>
        <v-btn text to="/deals">Сделки</v-btn>
        <v-btn text to="/tasks">Задачи</v-btn>
        <v-btn text to="/login">Выйти</v-btn>
      </v-app-bar>

      <v-main>
        <router-view></router-view>
      </v-main>
    </v-app>
  </template>

  <script setup>
  </script>
