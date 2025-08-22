<template>
  <q-page class="q-pa-md">
    <div class="text-h6 q-mb-md">Agenda</div>
    <vue-cal
      :events="events"
      default-view="week"
      @event-click="onEventClick"
      @cell-click="onCellClick"
      time
      :disable-views="['years', 'year', 'month']"
      locale="pt-br"
    >
      <!-- Slot para customizar o visual dos eventos -->
      <template #event="props">
        <div
          class="custom-event"
          :title="props.event.title"
        >
          <div class="custom-event-title">{{ props.event.title }}</div>
          <div class="custom-event-content">{{ props.event.content }}</div>
        </div>
      </template>
    </vue-cal>
    <!-- Modal de novo agendamento -->
    <q-dialog v-model="showAdd">
      <q-card style="min-width:350px">
        <q-card-section>
          <div class="text-h6">Novo Agendamento</div>
          <q-input
            v-model="form.dt_appointment"
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
            v-model="form.staff_id"
            :options="collaborators.map(c => ({ label: c.user_name, value: c.id }))"
            label="Colaborador"
            outlined
            class="q-mb-sm"
            emit-value
            map-options
          />
          <q-select
            v-model="form.client_id"
            :options="clients.map(u => ({ label: u.name, value: u.id }))"
            label="Cliente"
            outlined
            class="q-mb-sm"
            emit-value
            map-options
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
  dt_appointment: '',
  service_id: '',
  staff_id: '',
  client_id: '',
  notes: ''
});

const services = ref([]);
const collaborators = ref([]);
const clients = ref([]);

async function fetchAppointments() {
  const response = await api.get('/appointments');
  const data = response.data.data || response.data;

  events.value = data.map(a => {
    // Garante formato ISO e converte para Date
    const dateStr = (a.dt_appointment || a.appointment_datetime || '').replace(' ', 'T');
    const start = dateStr ? new Date(dateStr) : null;
    // Adiciona 1 hora de duração
    const end = start ? new Date(start.getTime() + 60 * 60 * 1000) : null;
    return {
      id: a.id,
      start,
      end,
      title: a.service_name || a.title || 'Agendamento',
      content: a.notes || ''
    };
  }).filter(ev => ev.start instanceof Date && !isNaN(ev.start) && ev.end instanceof Date && !isNaN(ev.end));
}

async function fetchServices() {
  const response = await api.get('/services');
  services.value = response.data.data || response.data;
}

async function fetchCollaborators() {
  const response = await api.get('/collaborators');
  collaborators.value = response.data.data || response.data;
}

async function fetchClients() {
  const response = await api.get('/users');
  clients.value = response.data.data || response.data;
}

function onCellClick(cell) {
  let date = cell?.startDate || cell?.date;
  if (typeof date === 'string') {
    date = new Date(date);
  }
  if (date instanceof Date && !isNaN(date)) {
    const pad = n => n.toString().padStart(2, '0');
    const formatted =
      date.getFullYear() + '-' +
      pad(date.getMonth() + 1) + '-' +
      pad(date.getDate()) + 'T' +
      pad(date.getHours()) + ':' +
      pad(date.getMinutes());
    form.value.dt_appointment = formatted;
  } else {
    form.value.dt_appointment = '';
  }
  form.value.service_id = '';
  form.value.staff_id = '';
  form.value.client_id = '';
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
  fetchCollaborators();
  fetchClients();
  fetchServices();
  fetchAppointments();
});
</script>

<style scoped>
.custom-event {
  background: linear-gradient(90deg, #1976d2 60%, #42a5f5 100%);
  color: #fff;
  border-radius: 6px;
  border-left: 5px solid #1565c0;
  padding: 6px 10px;
  margin: 2px 0;
  box-shadow: 0 2px 6px rgba(25, 118, 210, 0.08);
  font-size: 15px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  min-height: 38px;
  cursor: pointer;
  transition: box-shadow 0.2s;
}
.custom-event:hover {
  box-shadow: 0 4px 12px rgba(25, 118, 210, 0.18);
  background: linear-gradient(90deg, #1565c0 60%, #1976d2 100%);
}
.custom-event-title {
  font-weight: bold;
  font-size: 15px;
  margin-bottom: 2px;
  text-shadow: 0 1px 2px rgba(0,0,0,0.08);
}
.custom-event-content {
  font-size: 13px;
  opacity: 0.85;
  white-space: pre-line;
}
</style>