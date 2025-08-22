<template>
  <q-page class="q-pa-md">
    <div class="row items-center q-mb-md">
      <div class="col">
        <q-input
          filled
          dense
          debounce="300"
          v-model="search"
          placeholder="Buscar colaborador..."
          prepend-inner-icon="search"
        />
      </div>
      <div class="col-auto">
        <q-btn color="primary" label="Novo Colaborador" @click="showAdd = true" />
      </div>
    </div>

    <div style="position: relative;">
      <q-table
        :rows="collaborators"
        :columns="columns"
        row-key="id"
        flat
        bordered
        :loading="loading"
        :filter="debouncedSearch"
        no-data-label="Nenhum colaborador cadastrado"
      >
        <template v-slot:body-cell-actions="props">
          <q-td align="center">
            <q-btn size="sm" color="primary" icon="edit" @click="openEdit(props.row)" flat round />
            <q-btn
              size="sm"
              color="negative"
              icon="delete"
              class="q-ml-sm"
              @click="confirmDelete(props.row.id)"
              flat
              round
            />
          </q-td>
        </template>
      </q-table>
      <q-inner-loading :showing="deleting">
        <q-spinner color="negative" size="50px" />
        <div class="text-negative q-mt-md">Excluindo colaborador...</div>
      </q-inner-loading>
    </div>

    <!-- Modal de adicionar colaborador -->
    <q-dialog v-model="showAdd">
      <q-card>
        <q-card-section>
          <div class="text-h6">Adicionar Colaborador</div>
          <q-input v-model="form.bio" label="Bio" outlined class="q-mb-sm" />
          <q-input v-model="form.specialities" label="Especialidades" outlined class="q-mb-sm" />
          <q-select
            v-model="form.user_id"
            :options="filteredUsers"
            label="Usuário"
            outlined
            class="q-mb-sm"
            emit-value
            map-options
            option-label="label"
            use-input
            fill-input
            input-debounce="0"
            @filter="onUserFilter"
          />
        </q-card-section>
        <q-card-actions align="right">
          <q-btn flat label="Cancelar" v-close-popup />
          <q-btn color="primary" label="Salvar" @click="addCollaborator" :loading="saving" />
        </q-card-actions>
      </q-card>
    </q-dialog>

    <!-- Modal de editar colaborador -->
    <q-dialog v-model="showEdit">
      <q-card>
        <q-card-section>
          <div class="text-h6">Editar Colaborador</div>
          <q-input v-model="editForm.bio" label="Bio" outlined class="q-mb-sm" />
          <q-input v-model="editForm.specialities" label="Especialidades" outlined class="q-mb-sm" />
          <q-select
            v-model="editForm.user_id"
            :options="users.map(u => ({ label: `${u.name} (id ${u.id})`, value: u.id }))"
            label="Usuário"
            outlined
            class="q-mb-sm"
            emit-value
            map-options
            option-label="label"
          />
        </q-card-section>
        <q-card-actions align="right">
          <q-btn flat label="Cancelar" v-close-popup />
          <q-btn color="primary" label="Salvar" @click="updateCollaborator" :loading="saving" />
        </q-card-actions>
      </q-card>
    </q-dialog>

    <!-- Modal de confirmação de exclusão -->
    <q-dialog v-model="showDeleteDialog">
      <q-card>
        <q-card-section>
          <div class="text-h6">Confirmar exclusão</div>
          <div>Tem certeza que deseja excluir este colaborador?</div>
        </q-card-section>
        <q-card-actions align="right">
          <q-btn flat label="Cancelar" color="primary" v-close-popup />
          <q-btn flat label="Excluir" color="negative" @click="deleteCollaborator" :loading="deleting" />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { api } from 'boot/axios';

const collaborators = ref([]);
const users = ref([]);
const filteredUsers = ref([]);
const loading = ref(false);
const showAdd = ref(false);
const showEdit = ref(false);
const saving = ref(false);
const deleting = ref(false);
const showDeleteDialog = ref(false);
const collaboratorToDelete = ref(null);

const search = ref('');
const debouncedSearch = ref('');
let searchTimeout = null;
let filterTimeout = null;

watch(search, (val) => {
  if (searchTimeout) clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    debouncedSearch.value = val;
  }, 500);
});

const form = ref({
  bio: '',
  specialities: '',
  user_id: '' // sempre o id numérico
});

const editForm = ref({
  id: null,
  bio: '',
  specialities: '',
  user_id: ''
});

const columns = [
  { name: 'bio', label: 'Bio', field: 'bio', align: 'left', sortable: true },
  { name: 'specialities', label: 'Especialidades', field: 'specialities', align: 'left', sortable: true },
  { name: 'user_name', label: 'Usuário', field: 'user_name', align: 'left', sortable: true },
  { name: 'actions', label: 'Ações', field: 'actions', align: 'center' }
];

async function fetchCollaborators() {
  loading.value = true;
  try {
    const response = await api.get('/collaborators');
    collaborators.value = response.data.data || response.data;
  } catch {
    collaborators.value = [];
  }
  loading.value = false;
}

async function fetchUsers() {
  try {
    const response = await api.get('/users');
    users.value = response.data.data || response.data;
    filteredUsers.value = users.value.map(u => ({
      label: `${u.name} (id ${u.id})`,
      value: u.id
    }));
  } catch {
    users.value = [];
  }
}

async function addCollaborator() {
  saving.value = true;
  try {
    await api.post('/collaborators', form.value);
    showAdd.value = false;
    form.value = { bio: '', specialities: '', user_id: '' };
    fetchCollaborators();
  } catch {
    // Trate o erro conforme necessário
  }
  saving.value = false;
}

async function openEdit(collaborator) {
  if (users.value.length === 0) {
    await fetchUsers();
  }
  // Garante que user_id é um número e existe nas opções
  const userId = Number(collaborator.user_id);
  const userExists = users.value.some(u => u.id === userId);

  editForm.value = {
    id: collaborator.id,
    bio: collaborator.bio,
    specialities: collaborator.specialities,
    user_id: userExists ? userId : null // só seta se existir nas opções
  };
  showEdit.value = true;
}

async function updateCollaborator() {
  saving.value = true;
  try {
    await api.put(`/collaborators/${editForm.value.id}`, editForm.value);
    showEdit.value = false;
    fetchCollaborators();
  } catch {
    // Trate o erro conforme necessário
  }
  saving.value = false;
}

function confirmDelete(id) {
  collaboratorToDelete.value = id;
  showDeleteDialog.value = true;
}

async function deleteCollaborator() {
  if (!collaboratorToDelete.value) return;
  deleting.value = true;
  try {
    await api.delete(`/collaborators/${collaboratorToDelete.value}`);
    fetchCollaborators();
  } catch {
    // Trate o erro conforme necessário
  }
  deleting.value = false;
  showDeleteDialog.value = false;
  collaboratorToDelete.value = null;
}

function onUserFilter(val, update) {
  if (filterTimeout) clearTimeout(filterTimeout);
  filterTimeout = setTimeout(() => {
    const needle = val ? val.toLowerCase() : '';
    filteredUsers.value = users.value
      .filter(u => `${u.name} (id ${u.id})`.toLowerCase().includes(needle))
      .map(u => ({
        label: `${u.name} (id ${u.id})`,
        value: u.id
      }));
    update();
  }, 3000); // 3 segundos
}

onMounted(() => {
  fetchCollaborators();
  fetchUsers();
});
</script>

<style scoped>
.q-field__native input.q-field__input {
  color: transparent !important;
  caret-color: #000; /* mantém o cursor visível */
}

.q-field__native span.ellipsis {
  display: none !important;
}
</style>