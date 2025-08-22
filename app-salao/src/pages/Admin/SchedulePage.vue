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
          tabindex="0"
          role="button"
          aria-label="Agendamento: {{ props.event.title }}. {{ props.event.content }}"
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

    <!-- Modal de detalhes do agendamento -->
    <q-dialog v-model="showDetails">
      <q-card style="min-width:350px;max-width:400px">
        <q-card-section>
          <div class="text-h6 q-mb-sm">Detalhes do Agendamento</div>
          <div class="q-mb-xs"><b>Serviço:</b> {{ details.service }}</div>
          <div class="q-mb-xs"><b>Colaborador:</b> {{ details.collaborator }}</div>
          <div class="q-mb-xs"><b>Cliente:</b> {{ details.client }}</div>
          <div class="q-mb-xs"><b>Data e Hora:</b> {{ details.datetime }}</div>
          <div class="q-mb-xs"><b>Observações:</b> <br> <span style="white-space: pre-line">{{ details.notes }}</span></div>
        </q-card-section>
        <q-card-actions align="right">
          <q-btn flat label="Fechar" v-close-popup />
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
const showDetails = ref(false);
const saving = ref(false);
const details = ref({
  service: '',
  collaborator: '',
  client: '',
  datetime: '',
  notes: ''
});
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

// Carrega todos os dados auxiliares antes de montar os eventos
async function loadAllAndAppointments() {
  await fetchServices();
  await fetchCollaborators();
  await fetchClients();
  await fetchAppointments();
}

async function fetchAppointments() {
  const response = await api.get('/appointments');
  const data = response.data.data || response.data;

  events.value = data.map(a => {
    const dateStr = (a.dt_appointment || a.appointment_datetime || '').replace(' ', 'T');
    const start = dateStr ? new Date(dateStr) : null;
    const end = start ? new Date(start.getTime() + 60 * 60 * 1000) : null;
    // Busca nomes pelo id
    const service = services.value.find(s => s.id === a.service_id);
    const collaborator = collaborators.value.find(c => c.id === a.staff_id);
    const client = clients.value.find(u => u.id === a.client_id);
    return {
      id: a.id,
      start,
      end,
      title: `${service ? service.name : 'Agendamento'} - ${client ? client.name : ''}`,
      content: a.notes || '',
      raw: a,
      service: service ? service.name : '',
      collaborator: collaborator ? collaborator.user_name : '',
      client: client ? client.name : '',
      datetime: start ? start.toLocaleString('pt-BR') : '',
      notes: a.notes || ''
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
    await fetchAppointments();
  } catch (e) {
    console.error(e);
  }
  saving.value = false;
}

function onEventClick(payload) {
  let event = payload?.event || payload;
  if (event) {
    details.value = {
      service: event.service,
      collaborator: event.collaborator,
      client: event.client,
      datetime: event.datetime,
      notes: event.notes
    };
    showDetails.value = true;
  }
}

onMounted(() => {
  loadAllAndAppointments();
});
</script>

<style scoped>
.custom-event {
  background: #1565c0;
  color: #fff;
  border-radius: 8px;
  border-left: 6px solid #0d47a1;
  padding: 10px 12px;
  margin: 4px 0;
  box-shadow: 0 2px 8px rgba(21, 101, 192, 0.10);
  font-size: 16px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  min-height: 44px;
  cursor: pointer;
  outline: none;
  transition: box-shadow 0.2s, background 0.2s;
}
.custom-event:focus,
.custom-event:hover {
  box-shadow: 0 4px 16px rgba(21, 101, 192, 0.22);
  background: #0d47a1;
}
.custom-event-title {
  font-weight: 700;
  font-size: 16px;
  margin-bottom: 2px;
  text-shadow: 0 1px 2px rgba(0,0,0,0.10);
  letter-spacing: 0.5px;
}
.custom-event-content {
  font-size: 14px;
  opacity: 0.95;
  white-space: pre-line;
  margin-top: 2px;
}
</style>