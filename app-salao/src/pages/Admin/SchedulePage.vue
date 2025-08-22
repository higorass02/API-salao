<template>
  <q-page class="q-pa-md">
    <div class="text-h6 q-mb-md">Agenda</div>
    <vue-cal
      style="height: 600px"
      :events="events"
      default-view="week"
      @event-click="onEventClick"
      @cell-click="onCellClick"
      time="24"
      :disable-views="['years', 'year', 'month']"
      locale="pt-br"
    />
  </q-page>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import VueCal from 'vue-cal';
import 'vue-cal/dist/vuecal.css';
import { api } from 'boot/axios';

const events = ref([]);

async function fetchAppointments() {
  const response = await api.get('/appointments');
  events.value = (response.data.data || response.data).map(a => ({
    start: a.appointment_datetime,
    end: a.appointment_datetime,
    title: a.service_name || 'Agendamento',
    content: a.notes || '',
    ...a
  }));
}

function onEventClick({ event }) {
  alert(`Agendamento: ${event.title}`);
}

function onCellClick(/* { date } */) {
  // Futuramente use o parâmetro
}

onMounted(fetchAppointments);
</script>