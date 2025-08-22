// import { RouteRecordRaw } from 'vue-router';
import MainLayout from 'layouts/MainLayout.vue';
import AuthLayout from 'layouts/AuthLayout.vue';
import LoginPage from 'pages/Auth/LoginPage.vue';
import RegisterPage from 'pages/Auth/RegisterPage.vue';
import ServicesPage from 'pages/Admin/ServicesPage.vue';
import DashboardPage from 'pages/Admin/DashboardPage.vue';
import SchedulePage from 'src/pages/Admin/SchedulePage.vue';
import CollaboratorsPage from 'src/pages/Admin/CollaboratorsPage.vue';

const routes = [
  {
    path: '/',
    component: AuthLayout,
    children: [
      { path: 'login', component: LoginPage },
      { path: 'register', component: RegisterPage },
    ]
  },
  // Admin
  {
    path: '/admin',
    component: MainLayout,
    children: [
      { path: '', component: SchedulePage },
      { path: 'dashboard', component: DashboardPage },
      { path: 'services', component: ServicesPage },
      { path: 'collaborators', component: CollaboratorsPage },
    ]
  },
  // 404
  { path: '/:catchAll(.*)*', component: () => import('pages/ErrorNotFound.vue') }
];
export default routes;
