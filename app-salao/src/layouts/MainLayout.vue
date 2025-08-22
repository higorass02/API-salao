<template>
  <q-layout view="hHh lpR fFf">
    <q-header elevated>
      <q-toolbar>
        <q-toolbar-title>Admin Dashboard</q-toolbar-title>
      </q-toolbar>
    </q-header>

    <q-drawer show-if-above v-model="drawer" side="left">
      <q-list>
        <q-item to="/admin/dashboard" clickable>Dashboard</q-item>
        <q-item to="/admin/services" clickable>Serviços</q-item>
        <q-item to="/admin/collaborators" clickable>Colaboradores</q-item>
        <q-item to="/admin/appointments" clickable>Agendamentos</q-item>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { ref } from 'vue';
const drawer = ref(true);
</script>
