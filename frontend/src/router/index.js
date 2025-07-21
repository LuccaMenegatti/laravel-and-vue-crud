import { createRouter, createWebHistory } from 'vue-router'
import Home from '../components/Home.vue'
import Usuarios from '../components/Usuarios.vue'
import Cursos from '../components/Cursos.vue'

const routes = [
  { path: '/', component: Home },
  { path: '/usuarios', component: Usuarios },
  { path: '/cursos', component: Cursos },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
