<template>
  <q-page class="q-pa-md">
    <q-toolbar class="bg-primary text-white rounded-borders q-mb-md">
      <q-toolbar-title>Dashboard</q-toolbar-title>
    </q-toolbar>

    <q-layout view="lHh Lpr lFf">
      <q-page-container>
        <div class="row q-col-gutter-md">
          <div class="col-12 col-md-4">
            <q-card>
              <q-card-section>
                <div class="text-h6">Agendamentos</div>
                <div class="text-subtitle2">Veja e gerencie os agendamentos do salão.</div>
              </q-card-section>
              <q-card-actions align="right">
                <q-btn label="Ver" color="primary" to="/admin/appointments" />
              </q-card-actions>
            </q-card>
          </div>
          <div class="col-12 col-md-4">
            <q-card>
              <q-card-section>
                <div class="text-h6">Serviços</div>
                <div class="text-subtitle2">Gerencie os serviços oferecidos.</div>
              </q-card-section>
              <q-card-actions align="right">
                <q-btn label="Ver" color="primary" to="/admin/services" />
              </q-card-actions>
            </q-card>
          </div>
          <div class="col-12 col-md-4">
            <q-card>
              <q-card-section>
                <div class="text-h6">Colaboradores</div>
                <div class="text-subtitle2">Gerencie a equipe do salão.</div>
              </q-card-section>
              <q-card-actions align="right">
                <q-btn label="Ver" color="primary" to="/admin/collaborators" />
              </q-card-actions>
            </q-card>
          </div>
        </div>
      </q-page-container>
    </q-layout>
  </q-page>
</template>

<script setup>
// Você pode adicionar lógica para buscar dados do backend aqui
</script>

<style scoped>
/* Ajustes para responsividade extra, se necessário */
</style>