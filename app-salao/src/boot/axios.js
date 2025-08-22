import { boot } from 'quasar/wrappers';
import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8989/api', // URL da sua API Laravel
  headers: {
    'Content-Type': 'application/json',
  },
});

// Adiciona o token Bearer automaticamente se existir
api.interceptors.request.use(config => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default boot(({ app }) => {
  app.config.globalProperties.$api = api;
});

export { api };