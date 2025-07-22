<template>
  <div class="container my-5 py-4 rounded-3 bg-white shadow-sm" style="max-width: 900px;">
    <h2 class="mb-4 text-primary fw-bold text-center">Cursos</h2>

    <!-- Formulário -->
    <div class="card mb-5">
      <div class="card-body">
        <form @submit.prevent="salvarCurso">
          <div class="row g-3">
            <div class="col-md-6">
              <input
                v-model="curso.descricao"
                type="text"
                class="form-control"
                placeholder="Descrição do curso"
                required
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

    <!-- Tabela de cursos -->
    <div class="table-responsive shadow-sm rounded">
      <table class="table table-striped table-hover align-middle mb-0">
        <thead class="table-primary">
          <tr>
            <th>Descrição</th>
            <th>Tipo</th>
            <th>Usuários Vinculados</th>
            <th class="text-center" style="width: 150px;">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="c in cursosPaginaAtual" :key="c.id">
            <td>{{ c.descricao }}</td>
            <td>{{ c.tipo }}</td>
            <td>
              <div style="max-width: 250px;">
                <template v-if="c.usuarios && c.usuarios.length > 0">
                  <ul class="mb-0 ps-3">
                    <li v-for="u in c.usuarios" :key="u.id" style="font-size: 0.9rem;">
                      {{ u.nome_completo }} — {{ formatarCPF(u.cpf) }}
                    </li>
                  </ul>
                </template>
                <span v-else>-</span>
              </div>
            </td>
            <td class="text-center">
              <button class="btn btn-sm btn-warning me-2" @click="editarCurso(c)">
                Editar
              </button>
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
            </td>
          </tr>
          <tr v-if="cursos.length === 0">
            <td colspan="4" class="text-center text-muted fst-italic">Nenhum curso cadastrado</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Paginação -->
    <nav v-if="totalPaginas > 1" aria-label="Navegação de página" class="mt-3 d-flex justify-content-center">
      <ul class="pagination mb-0">
        <li :class="['page-item', { disabled: paginaAtual === 1 }]">
          <button class="page-link" @click="mudarPagina(paginaAtual - 1)" :disabled="paginaAtual === 1">
            Anterior
          </button>
        </li>

        <li
          v-for="page in totalPaginas"
          :key="page"
          :class="['page-item', { active: paginaAtual === page }]"
        >
          <button class="page-link" @click="mudarPagina(page)">{{ page }}</button>
        </li>

        <li :class="['page-item', { disabled: paginaAtual === totalPaginas }]">
          <button class="page-link" @click="mudarPagina(paginaAtual + 1)" :disabled="paginaAtual === totalPaginas">
            Próximo
          </button>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick, computed } from 'vue'
import axios from 'axios'

const cursos = ref([])
const curso = ref({
  descricao: '',
  tipo: 'Graduação',
})
const erros = ref({})

const paginaAtual = ref(1)
const itensPorPagina = 5

const totalPaginas = computed(() => {
  return Math.ceil(cursos.value.length / itensPorPagina)
})

const cursosPaginaAtual = computed(() => {
  const start = (paginaAtual.value - 1) * itensPorPagina
  const end = start + itensPorPagina
  return cursos.value.slice(start, end)
})

const mudarPagina = (n) => {
  if (n < 1 || n > totalPaginas.value) return
  paginaAtual.value = n
}

const carregarCursos = async () => {
  const res = await axios.get('http://127.0.0.1:8000/api/cursos')
  cursos.value = res.data
  paginaAtual.value = 1

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

const formatarCPF = (cpf) => {
  if (!cpf) return ''
  const cleaned = cpf.replace(/\D/g, '')
  return cleaned.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4')
}

onMounted(() => carregarCursos())
</script>

<style scoped>
body,
html {
  height: 100%;
  margin: 0;
  background: linear-gradient(135deg, #74ebd5 0%, #9face6 100%);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.container {
  min-height: 90vh;
}

.btn-warning:hover {
  background-color: #e6b600;
  border-color: #e6b600;
  color: #fff;
}

.btn-danger:hover {
  background-color: #d42f2f;
  border-color: #d42f2f;
  color: #fff;
}

.page-item.disabled .page-link {
  cursor: not-allowed;
  pointer-events: none;
  opacity: 0.6;
}
</style>
