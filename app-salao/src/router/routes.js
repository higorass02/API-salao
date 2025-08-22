// import { RouteRecordRaw } from 'vue-router';
import MainLayout from 'layouts/MainLayout.vue';
import AuthLayout from 'layouts/AuthLayout.vue';
import LoginPage from 'pages/Auth/LoginPage.vue';
import RegisterPage from 'pages/Auth/RegisterPage.vue';
import ServicesPage from 'pages/Admin/ServicesPage.vue';
import DashboardPage from 'pages/Admin/DashboardPage.vue';

const routes = [
  {
    path: '/',
    component: AuthLayout,
    children: [
      { path: 'login', component: LoginPage },
      { path: 'register', component: RegisterPage }
    ]
  },
  // Admin
  {
    path: '/admin',
    component: MainLayout,
    children: [
      { path: 'dashboard', component: DashboardPage },
      { path: 'services', component: ServicesPage },
    ]
  },
  // Layout principal
  // {
  //   path: '/',
  //   component: () => import('layouts/MainLayout.vue'),
  //   children: [
  //     { path: '', redirect: '/admin/dashboard' },
  //     { path: 'admin/dashboard', component: () => import('pages/Admin/DashboardPage.vue') },
  //     { path: 'admin/services', component: () => import('pages/Admin/ServicesPage.vue') },
  //     { path: 'admin/collaborators', component: () => import('pages/Admin/CollaboratorsPage.vue') },
  //     { path: 'admin/appointments', component: () => import('pages/Admin/AppointmentsPage.vue') },
  //   ]
  // },
  // 404
  { path: '/:catchAll(.*)*', component: () => import('pages/ErrorNotFound.vue') }
];
export default routes;
