<template>
  <div class="container my-5 py-4 rounded-3 bg-white shadow-sm" style="max-width: 900px;">
    <h2 class="mb-4 text-primary fw-bold text-center">Usuários</h2>

    <div class="card mb-5">
      <div class="card-body">
        <form @submit.prevent="salvarUsuario">
          <div class="row g-3">
            <div class="col-md-4">
              <input
                v-model="usuario.nome_completo"
                type="text"
                class="form-control"
                placeholder="Nome completo"
                required
              />
              <span v-if="erros.nome_completo" class="text-danger small">{{ erros.nome_completo }}</span>
            </div>
            <div class="col-md-4">
              <input
                v-model="usuario.data_nascimento"
                type="date"
                class="form-control"
                required
              />
              <span v-if="erros.data_nascimento" class="text-danger small">{{ erros.data_nascimento }}</span>
            </div>
            <div class="col-md-4">
              <input
                v-model="usuario.cpf"
                type="text"
                class="form-control"
                placeholder="CPF"
                required
                maxlength="14"
                @input="formatarCPF"
              />
              <span v-if="erros.cpf" class="text-danger small">{{ erros.cpf }}</span>
            </div>
          </div>

          <div class="mt-3">
            <label class="form-label fw-semibold">Cursos</label>
            <div class="d-flex flex-wrap gap-3">
              <div v-for="curso in cursos" :key="curso.id" class="form-check">
                <input
                  class="form-check-input"
                  type="checkbox"
                  :id="'curso_' + curso.id"
                  :value="curso.id"
                  v-model="usuario.curso_ids"
                />
                <label class="form-check-label" :for="'curso_' + curso.id">
                  {{ curso.descricao }}
                </label>
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-end mt-3">
            <button type="submit" class="btn btn-success px-4">
              {{ usuario.id ? 'Atualizar' : 'Criar' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <div class="table-responsive shadow-sm rounded">
      <table class="table table-striped table-hover align-middle mb-0">
        <thead class="table-primary">
          <tr>
            <th>Nome</th>
            <th>Data de Nascimento</th>
            <th>CPF</th>
            <th>Cursos</th>
            <th class="text-center" style="width: 150px;">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="u in usuariosPaginaAtual" :key="u.id">
            <td>{{ u.nome_completo }}</td>
            <td>{{ formatarData(u.data_nascimento) }}</td>
            <td>{{ formatarCPFExibicao(u.cpf) }}</td>
            <td>
              <div style="max-width: 250px;">
                <template v-if="u.cursos && u.cursos.length > 0">
                  <span
                    v-for="curso in u.cursos"
                    :key="curso.id"
                    class="badge bg-primary me-1 mb-1"
                    style="font-size: 0.8rem;"
                    >{{ curso.descricao }}</span
                  >
                </template>
                <span v-else>-</span>
              </div>
            </td>
            <td class="text-center">
              <button class="btn btn-sm btn-warning me-2" @click="editarUsuario(u)">
                Editar
              </button>
              <button
                class="btn btn-sm btn-danger"
                @click="deletarUsuario(u.id)"
                data-bs-toggle="tooltip"
                title="Excluir usuário"
              >
                Excluir
              </button>
            </td>
          </tr>
          <tr v-if="usuarios.length === 0">
            <td colspan="5" class="text-center text-muted fst-italic">Nenhum usuário cadastrado</td>
          </tr>
        </tbody>

      </table>
    </div>

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
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const usuarios = ref([])
const usuario = ref({
  nome_completo: '',
  data_nascimento: '',
  cpf: '',
  curso_ids: [],
})

const cursos = ref([])

const paginaAtual = ref(1)
const itensPorPagina = 10

const totalPaginas = computed(() => {
  return Math.ceil(usuarios.value.length / itensPorPagina)
})

const usuariosPaginaAtual = computed(() => {
  const start = (paginaAtual.value - 1) * itensPorPagina
  const end = start + itensPorPagina
  return usuarios.value.slice(start, end)
})

const carregarUsuarios = async () => {
  const res = await axios.get('http://127.0.0.1:8000/api/usuarios')
  usuarios.value = res.data
  paginaAtual.value = 1
  inicializarTooltips()
}

const carregarCursos = async () => {
  const res = await axios.get('http://127.0.0.1:8000/api/cursos')
  cursos.value = res.data
}

const erros = ref({
  nome_completo: '',
  data_nascimento: '',
  cpf: '',
})

const validarFormulario = () => {
  let valido = true
  erros.value = { nome_completo: '', data_nascimento: '', cpf: '' }

  if (!usuario.value.nome_completo || usuario.value.nome_completo.trim().length < 3) {
    erros.value.nome_completo = 'Nome deve ter ao menos 3 caracteres.'
    valido = false
  }

  const data = new Date(usuario.value.data_nascimento)
  if (!usuario.value.data_nascimento || isNaN(data.getTime()) || data > new Date()) {
    erros.value.data_nascimento = 'Data de nascimento inválida.'
    valido = false
  }

  const cpf = usuario.value.cpf.replace(/\D/g, '')
  if (!validarCPF(cpf)) {
    erros.value.cpf = 'CPF inválido.'
    valido = false
  }

  return valido
}

const validarCPF = (cpf) => {
  if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false

  const calcularDigito = (base, pesoInicial) => {
    const soma = base.split('').reduce((acc, num, i) => acc + parseInt(num) * (pesoInicial - i), 0)
    const resto = (soma * 10) % 11
    return resto === 10 ? 0 : resto
  }

  const base = cpf.slice(0, 9)
  const digito1 = calcularDigito(base, 10)
  const digito2 = calcularDigito(base + digito1, 11)
  return cpf.endsWith(`${digito1}${digito2}`)
}

const salvarUsuario = async () => {
  if (!validarFormulario()) return

  const payload = {
    ...usuario.value,
    curso_ids: usuario.value.curso_ids || [],
  }

  if (usuario.value.id) {
    await axios.put(`http://127.0.0.1:8000/api/usuarios/${usuario.value.id}`, payload)
  } else {
    await axios.post('http://127.0.0.1:8000/api/usuarios', payload)
  }

  usuario.value = { nome_completo: '', data_nascimento: '', cpf: '', curso_ids: [] }
  await carregarUsuarios()
}

const editarUsuario = (u) => {
  usuario.value = {
    ...u,
    curso_ids: u.cursos ? u.cursos.map(c => c.id) : [],
  }
}

const deletarUsuario = async (id) => {
  if (confirm('Deseja realmente excluir este usuário?')) {
    await axios.delete(`http://127.0.0.1:8000/api/usuarios/${id}`)
    await carregarUsuarios()
  }
}

const formatarData = (dataISO) => {
  if (!dataISO) return ''
  const dt = new Date(dataISO)
  return dt.toLocaleDateString('pt-BR')
}

const formatarCPFExibicao = (cpf) => {
  if (!cpf) return ''
  const cleaned = cpf.replace(/\D/g, '')
  return cleaned.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4')
}

const formatarCPF = (e) => {
  let val = e.target.value.replace(/\D/g, '')
  if (val.length > 11) val = val.slice(0, 11)
  val = val.replace(/(\d{3})(\d)/, '$1.$2')
  val = val.replace(/(\d{3})(\d)/, '$1.$2')
  val = val.replace(/(\d{3})(\d{1,2})$/, '$1-$2')
  usuario.value.cpf = val
}

const mudarPagina = (n) => {
  if (n < 1 || n > totalPaginas.value) return
  paginaAtual.value = n
}

const listarCursosUsuario = (u) => {
  if (u.cursos && u.cursos.length > 0) {
    return u.cursos.map(c => c.descricao).join(', ')
  }
  return '-'
}

const inicializarTooltips = () => {
  const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
  tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el))
}

onMounted(() => {
  carregarUsuarios()
  carregarCursos()
  inicializarTooltips()
})
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
