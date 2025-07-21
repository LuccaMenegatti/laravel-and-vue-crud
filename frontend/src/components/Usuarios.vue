<template>
  <div class="container my-4">
    <h2 class="mb-4">Usuários</h2>

    <div class="card mb-4">
      <div class="card-body">
        <form @submit.prevent="salvarUsuario">
          <div class="row g-3">
            <div class="col-md-4">
              <input v-model="usuario.nome" type="text" class="form-control" placeholder="Nome completo" required />
            </div>
            <div class="col-md-4">
              <input v-model="usuario.data_nascimento" type="date" class="form-control" required />
            </div>
            <div class="col-md-4">
              <input v-model="usuario.cpf" type="text" class="form-control" placeholder="CPF" required />
            </div>
            <div class="col-md-12 d-flex justify-content-end">
              <button type="submit" class="btn btn-success">
                {{ usuario.id ? 'Atualizar' : 'Criar' }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <ul class="list-group">
      <li v-for="u in usuarios" :key="u.id" class="list-group-item d-flex justify-content-between align-items-center">
        <div>
          <strong>{{ u.nome }}</strong> ({{ u.cpf }}) - Nasc: {{ u.data_nascimento }}
        </div>
        <div>
          <button class="btn btn-sm btn-warning me-2" @click="editarUsuario(u)">Editar</button>
          <button class="btn btn-sm btn-danger" @click="deletarUsuario(u.id)">Excluir</button>
        </div>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const usuarios = ref([])
const usuario = ref({
  nome: '',
  data_nascimento: '',
  cpf: '',
})

const carregarUsuarios = async () => {
  const res = await axios.get('http://127.0.0.1:8000/api/usuarios')
  usuarios.value = res.data
}

const salvarUsuario = async () => {
  if (usuario.value.id) {
    await axios.put(`http://127.0.0.1:8000/api/usuarios/${usuario.value.id}`, usuario.value)
  } else {
    await axios.post('http://127.0.0.1:8000/api/usuarios', usuario.value)
  }
  usuario.value = { nome: '', data_nascimento: '', cpf: '' }
  await carregarUsuarios()
}

const editarUsuario = (u) => {
  usuario.value = { ...u }
}

const deletarUsuario = async (id) => {
  await axios.delete(`http://127.0.0.1:8000/api/usuarios/${id}`)
  await carregarUsuarios()
}

onMounted(() => carregarUsuarios())
</script>
