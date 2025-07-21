<template>
  <div class="home-container d-flex align-items-center justify-content-center text-center">
    <div class="p-5 rounded shadow-lg bg-white bg-opacity-75">
      <h1 class="mb-4">Bem-vindo ao Sistema</h1>
      <p class="lead mb-5">Gerencie seus usuários e cursos com facilidade</p>
      <div class="d-flex justify-content-center gap-3">
        <router-link to="/usuarios" class="btn btn-primary btn-lg px-4">Usuários</router-link>
        <router-link to="/cursos" class="btn btn-outline-primary btn-lg px-4">Cursos</router-link>
      </div>
    </div>
  </div>
</template>

<style scoped>
.home-container {
  height: 100vh;
  background: linear-gradient(to right, #74ebd5, #9face6);
  background-size: cover;
  background-position: center;
  padding: 2rem;
}

h1 {
  font-weight: bold;
}

p.lead {
  font-size: 1.25rem;
  color: #333;
}
</style>
