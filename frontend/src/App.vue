<template>
  <div id="app">
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg bg-white shadow-sm border-bottom">
      <div class="container">
        <a class="navbar-brand fw-semibold text-primary fs-4" href="#">📘 CRUD</a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
          <ul class="navbar-nav gap-2">
            <li class="nav-item">
              <router-link to="/" class="nav-link" exact-active-class="active">Home</router-link>
            </li>
            <li class="nav-item">
              <router-link to="/usuarios" class="nav-link" exact-active-class="active">Usuários</router-link>
            </li>
            <li class="nav-item">
              <router-link to="/cursos" class="nav-link" exact-active-class="active">Cursos</router-link>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <router-view />
  </div>
</template>

<script setup>
</script>

<style>

html,
body,
#app {
  height: 100%;
  margin: 0;
  font-family: "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
  background: linear-gradient(to right, #e3f2fd, #fce4ec);
  color: #333;
}

.navbar .nav-link {
  color: #555;
  font-weight: 500;
  transition: color 0.2s ease;
}

.navbar .nav-link:hover,
.navbar .nav-link.active {
  color: #0d6efd;
  text-decoration: underline;
}

.card {
  background-color: #fff;
  border: none;
  border-radius: 0.5rem;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.container {
  max-width: 960px;
}
</style>
