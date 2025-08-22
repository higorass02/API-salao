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
    <!-- Modal de novo agendamento -->
    <q-dialog v-model="showAdd">
      <q-card style="min-width:350px">
        <q-card-section>
          <div class="text-h6">Novo Agendamento</div>
          <q-input
            v-model="form.appointment_datetime"
            label="Data e Hora"
            type="datetime-local"
            outlined
            class="q-mb-sm"
          />
          <q-select
            v-model="form.service_id"
            :options="services.map(s => ({ label: s.name, value: s.id }))"
            label="Serviço"
            outlined
            class="q-mb-sm"
            emit-value
            map-options
          />
          <q-select
            v-model="form.collaborator_id"
            :options="collaborators.map(c => ({ label: c.user_name, value: c.id }))"
            label="Colaborador"
            outlined
            class="q-mb-sm"
            emit-value
            map-options
          />
          <q-input
            v-model="form.client_name"
            label="Cliente"
            outlined
            class="q-mb-sm"
          />
          <q-input
            v-model="form.notes"
            label="Observações"
            type="textarea"
            outlined
            class="q-mb-sm"
          />
        </q-card-section>
        <q-card-actions align="right">
          <q-btn flat label="Cancelar" v-close-popup />
          <q-btn color="primary" label="Salvar" @click="addAppointment" :loading="saving" />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import VueCal from 'vue-cal';
import 'vue-cal/dist/vuecal.css';
import { api } from 'boot/axios';

const events = ref([]);
const showAdd = ref(false);
const saving = ref(false);
const form = ref({
  appointment_datetime: '',
  service_id: '',
  collaborator_id: '',
  client_name: '',
  notes: ''
});

const services = ref([]);
const collaborators = ref([]);

async function fetchAppointments() {
  const response = await api.get('/appointments');
  events.value = (response.data.data || response.data).map(a => ({
    start: new Date(a.appointment_datetime),
    end: new Date(a.appointment_datetime), // ajuste se tiver duração
    title: a.service_name || 'Agendamento',
    content: a.notes || '',
    ...a
  }));
}

async function fetchServices() {
  const response = await api.get('/services');
  services.value = response.data.data || response.data;
}

async function fetchCollaborators() {
  const response = await api.get('/collaborators');
  collaborators.value = response.data.data || response.data;
}

function onCellClick(cell) {
  const date = cell?.startDate || cell?.date;
  if (date instanceof Date) {
    form.value.appointment_datetime = date.toISOString().slice(0, 16);
  } else {
    form.value.appointment_datetime = '';
  }
  form.value.service_id = '';
  form.value.collaborator_id = '';
  form.value.client_name = '';
  form.value.notes = '';
  showAdd.value = true;
}

async function addAppointment() {
  saving.value = true;
  try {
    await api.post('/appointments', form.value);
    showAdd.value = false;
    fetchAppointments();
  } catch (e) {
    console.error(e);
  }
  saving.value = false;
}

function onEventClick({ event }) {
  alert(`Agendamento: ${event.title}`);
}

onMounted(() => {
  fetchAppointments();
  fetchServices();
  fetchCollaborators();
});
</script>