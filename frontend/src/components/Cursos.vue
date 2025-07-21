<template>
  <div class="container my-4">
    <h2 class="mb-4">Cursos</h2>

    <div class="card mb-4">
      <div class="card-body">
        <form @submit.prevent="salvarCurso">
          <div class="row g-3">
            <div class="col-md-6">
              <input v-model="curso.descricao" type="text" class="form-control" placeholder="Descrição" required />
            </div>
            <div class="col-md-4">
              <select v-model="curso.tipo" class="form-select">
                <option value="Graduação">Graduação</option>
                <option value="Pós-Graduação">Pós-Graduação</option>
              </select>
            </div>
            <div class="col-md-2 d-flex justify-content-end">
              <button type="submit" class="btn btn-success w-100">
                {{ curso.id ? 'Atualizar' : 'Criar' }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <ul class="list-group">
      <li v-for="c in cursos" :key="c.id" class="list-group-item d-flex justify-content-between align-items-center">
        <div>
          <strong>{{ c.descricao }}</strong> - {{ c.tipo }}
        </div>
        <div>
          <button class="btn btn-sm btn-warning me-2" @click="editarCurso(c)">Editar</button>
          <button class="btn btn-sm btn-danger" @click="deletarCurso(c.id)">Excluir</button>
        </div>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const cursos = ref([])
const curso = ref({
  descricao: '',
  tipo: 'Graduação',
})

const carregarCursos = async () => {
  const res = await axios.get('http://127.0.0.1:8000/api/cursos')
  cursos.value = res.data
}

const salvarCurso = async () => {
  if (curso.value.id) {
    await axios.put(`http://127.0.0.1:8000/api/cursos/${curso.value.id}`, curso.value)
  } else {
    await axios.post('http://127.0.0.1:8000/api/cursos', curso.value)
  }
  curso.value = { descricao: '', tipo: 'Graduação' }
  await carregarCursos()
}

const editarCurso = (c) => {
  curso.value = { ...c }
}

const deletarCurso = async (id) => {
  await axios.delete(`http://127.0.0.1:8000/api/cursos/${id}`)
  await carregarCursos()
}

onMounted(() => carregarCursos())
</script>
