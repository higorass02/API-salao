<template>
  <q-page class="flex flex-center">
    <q-card class="q-pa-md" style="min-width: 300px;">
      <q-card-section>
        <div class="text-h6">Login</div>
      </q-card-section>

      <q-card-section>
        <q-input v-model="email" label="Email" type="email" outlined dense />
        <q-input v-model="password" label="Senha" type="password" outlined dense class="q-mt-sm" />
      </q-card-section>

      <q-card-actions align="right">
        <q-btn label="Entrar" color="primary" @click="login" />
      </q-card-actions>
    </q-card>
  </q-page>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { api } from 'boot/axios'; // Adicione esta linha

const email = ref('');
const password = ref('');
const router = useRouter();

async function login() {
  try {
    const response = await api.post('/login', {
      email: email.value,
      password: password.value,
    });

    // Supondo que o token venha em response.data.token
    localStorage.setItem('token', response.data.token);

    // Redireciona para a dashboard
    router.push('/admin/dashboard');
  } catch (error) {
    console.error('Erro ao logar:', error.response?.data || error.message);
  }
}
</script>