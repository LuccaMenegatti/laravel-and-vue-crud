<template>
  <div class="container my-4">
    <h2 class="mb-4">Cursos</h2>

    <!-- Formulário de criação/edição -->
    <div class="card mb-4">
      <div class="card-body">
        <form @submit.prevent="salvarCurso">
          <div class="row g-3">
            <div class="col-md-6">
              <input
                v-model="curso.descricao"
                type="text"
                class="form-control"
                placeholder="Descrição"
                required
                minlength="3"
              />
              <span v-if="erros.descricao" class="text-danger small">{{ erros.descricao }}</span>
            </div>
            <div class="col-md-4">
              <select v-model="curso.tipo" class="form-select" required>
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

    <!-- Lista de cursos -->
    <div v-for="c in cursos" :key="c.id" class="card mb-3">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <div>
            <h5 class="mb-1">{{ c.descricao }}</h5>
            <small class="text-muted">{{ c.tipo }}</small>
          </div>
          <div>
            <button class="btn btn-sm btn-warning me-2" @click="editarCurso(c)">Editar</button>
            <button
              class="btn btn-sm btn-danger"
              :disabled="c.usuarios && c.usuarios.length > 0"
              @click="deletarCurso(c.id)"
              data-bs-toggle="tooltip"
              :title="c.usuarios && c.usuarios.length > 0
                ? 'Não é possível excluir um curso com usuários vinculados'
                : 'Excluir curso'"
            >
              Excluir
            </button>
          </div>
        </div>

        <div v-if="c.usuarios && c.usuarios.length > 0" class="mt-3">
          <h6 class="mb-2">Usuários vinculados:</h6>
          <ul class="list-group list-group-flush">
            <li class="list-group-item px-0 py-1" v-for="u in c.usuarios" :key="u.id">
              {{ u.nome_completo }} — {{ u.cpf }}
            </li>
          </ul>
        </div>
        <div v-else class="text-muted mt-2">Nenhum usuário vinculado.</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue'
import axios from 'axios'

const cursos = ref([])
const curso = ref({
  descricao: '',
  tipo: 'Graduação',
})

const erros = ref({})

const carregarCursos = async () => {
  const res = await axios.get('http://127.0.0.1:8000/api/cursos')
  cursos.value = res.data

  // Reativar tooltips do Bootstrap
  nextTick(() => {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el))
  })
}

const validarCurso = () => {
  erros.value = {}

  if (!curso.value.descricao || curso.value.descricao.trim().length < 3) {
    erros.value.descricao = 'A descrição deve ter pelo menos 3 caracteres.'
    return false
  }

  return true
}

const salvarCurso = async () => {
  if (!validarCurso()) return

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
