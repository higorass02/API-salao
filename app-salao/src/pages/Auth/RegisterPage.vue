<template>
  <q-page class="flex flex-center">
    <q-card class="q-pa-md" style="min-width: 300px;">
      <q-card-section>
        <div class="text-h6">Registrar</div>
      </q-card-section>

      <q-card-section>
        <q-input v-model="name" label="Nome" outlined dense />
        <q-input v-model="email" label="Email" type="email" outlined dense class="q-mt-sm" />
        <q-input v-model="password" label="Senha" type="password" outlined dense class="q-mt-sm" />
      </q-card-section>

      <q-card-actions align="right">
        <q-btn label="Registrar" color="primary" @click="register" />
      </q-card-actions>
    </q-card>
  </q-page>
</template>

<script setup>
import { ref } from 'vue';
import { api } from 'boot/axios'; // importamos a instância axios

const name = ref('');
const email = ref('');
const password = ref('');

async function register() {
  try {
    const response = await api.post('/register', {
      name: name.value,
      email: email.value,
      password: password.value,
    });

    console.log('Usuário criado com sucesso:', response.data);
    // redirecionar para login, exibir mensagem, etc
  } catch (error) {
    console.error('Erro ao registrar:', error.response?.data || error.message);
  }
}
</script>
