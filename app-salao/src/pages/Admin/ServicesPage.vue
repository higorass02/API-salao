<template>
  <q-page class="q-pa-md">
    <div class="row items-center q-mb-md">
      <div class="col">
        <q-input
          filled
          dense
          v-model="search"
          placeholder="Buscar serviço..."
          prepend-inner-icon="search"
        />
      </div>
      <div class="col-auto">
        <q-btn color="primary" label="Novo Serviço" @click="showAdd = true" />
      </div>
    </div>
    <div style="position: relative;">
      <q-table
        :rows="services"
        :columns="columns"
        row-key="id"
        flat
        bordered
        :loading="loading"
        :filter="debouncedSearch"
        no-data-label="Nenhum serviço cadastrado"
      >
    <template v-slot:body-cell-actions="props">
        <q-td align="center">
        <q-btn size="sm" color="primary" icon="edit" @click="openEdit(props.row)" flat round />
        </q-td>
    </template>
    </q-table>
      <q-inner-loading :showing="searching">
        <q-spinner size="50px" color="primary" />
        <div class="text-primary q-mt-md">Buscando...</div>
      </q-inner-loading>
    </div>

    <!-- Diálogo de adicionar serviço -->
    <q-dialog v-model="showAdd">
    <q-card>
        <q-card-section>
        <div class="text-h6">Adicionar Serviço</div>
        <q-input v-model="form.name" label="Nome" outlined class="q-mb-sm" />
        <q-input v-model="form.description" label="Descrição" outlined class="q-mb-sm" />
        <q-input v-model="form.duration" label="Duração (min)" type="number" outlined class="q-mb-sm" />
        <q-input v-model="form.price" label="Preço" type="number" outlined class="q-mb-sm" />
        <q-input v-model="form.category" label="Categoria" outlined class="q-mb-sm" />
        </q-card-section>
        <q-card-actions align="right">
        <q-btn flat label="Cancelar" v-close-popup />
        <q-btn color="primary" label="Salvar" @click="addService" :loading="saving" />
        </q-card-actions>
    </q-card>
    </q-dialog>

    <!-- Diálogo de editar serviço -->
    <q-dialog v-model="showEdit">
      <q-card>
        <q-card-section>
          <div class="text-h6">Editar Serviço</div>
          <q-input v-model="editForm.name" label="Nome" outlined class="q-mb-sm" />
          <q-input v-model="editForm.description" label="Descrição" outlined class="q-mb-sm" />
          <q-input v-model="editForm.duration" label="Duração (min)" type="number" outlined class="q-mb-sm" />
          <q-input v-model="editForm.price" label="Preço" type="number" outlined class="q-mb-sm" />
          <q-input v-model="editForm.category" label="Categoria" outlined class="q-mb-sm" />
        </q-card-section>
        <q-card-actions align="right">
          <q-btn flat label="Cancelar" v-close-popup />
          <q-btn color="primary" label="Salvar" @click="updateService" :loading="saving" />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { api } from 'boot/axios';

const services = ref([]);
const loading = ref(false);
const showAdd = ref(false);
const showEdit = ref(false);
const saving = ref(false);

const search = ref('');
const debouncedSearch = ref('');
let searchTimeout = null;
const searching = ref(false);

watch(search, (val) => {
  if (searchTimeout) clearTimeout(searchTimeout);
  searching.value = true;
  searchTimeout = setTimeout(() => {
    debouncedSearch.value = val;
    searching.value = false;
  }, 5000);
});

const form = ref({
  name: '',
  description: '',
  duration: '',
  price: '',
  category: ''
});

const editForm = ref({
  id: null,
  name: '',
  description: '',
  duration: '',
  price: '',
  category: ''
});

const columns = [
  { name: 'name', label: 'Nome', field: 'name', align: 'left', sortable: true },
  { name: 'description', label: 'Descrição', field: 'description', align: 'left', sortable: true },
  { name: 'duration', label: 'Duração', field: 'duration', align: 'left', sortable: true },
  { name: 'price', label: 'Preço', field: 'price', align: 'left', sortable: true },
  { name: 'category', label: 'Categoria', field: 'category', align: 'left', sortable: true },
  { name: 'actions', label: 'Ações', field: 'actions', align: 'center' }
];

async function fetchServices() {
  loading.value = true;
  try {
    const response = await api.get('/services');
    services.value = response.data.data || response.data;
  } catch {
    services.value = [];
  }
  loading.value = false;
}

async function addService() {
  saving.value = true;
  try {
    await api.post('/services', form.value);
    showAdd.value = false;
    form.value = { name: '', description: '', duration: '', price: '', category: '' };
    fetchServices();
  } catch {
    // Trate o erro conforme necessário
  }
  saving.value = false;
}

function openEdit(service) {
  editForm.value = { ...service };
  showEdit.value = true;
}

async function updateService() {
  saving.value = true;
  try {
    await api.put(`/services/${editForm.value.id}`, editForm.value);
    showEdit.value = false;
    fetchServices();
  } catch {
    // Trate o erro conforme necessário
  }
  saving.value = false;
}

onMounted(fetchServices);
</script>