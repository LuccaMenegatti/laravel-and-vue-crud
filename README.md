# 🎓 Laravel + Vue 3 + PostgreSQL - CRUD de Usuários e Cursos

Este é um projeto full stack que implementa um sistema de gerenciamento de **Usuários** e **Cursos** com uma interface moderna utilizando **Bootstrap**, backend em **Laravel**, frontend em **Vue.js 3**, e banco de dados **PostgreSQL**. Cada usuário pode estar vinculado a vários cursos e vice-versa (relação N:N).

## 🚀 Tecnologias Utilizadas

### Backend (API)
- [Laravel 10](https://laravel.com/)
- [PHP 8.x](https://www.php.net/)
- [PostgreSQL](https://www.postgresql.org/)
- [Composer](https://getcomposer.org/)
- Migrations, Seeders, Eloquent ORM
- Relacionamentos Many-to-Many

### Frontend
- [Vue.js 3](https://vuejs.org/)
- [Vite](https://vitejs.dev/)
- [Axios](https://axios-http.com/)
- [Bootstrap 5](https://getbootstrap.com/)

### Outros
- [Node.js 18+](https://nodejs.org/)
- [NPM](https://www.npmjs.com/)
- [Git](https://git-scm.com/)

## 🧰 Pré-requisitos

Antes de rodar o projeto, certifique-se de ter instalado:

- PHP 8.x
- Composer
- Node.js (versão 18 ou superior)
- NPM
- PostgreSQL

## ⚙️ Instalação

### 1. Clonar o repositório
```bash
git clone https://github.com/seu-usuario/seu-repositorio.git
cd seu-repositorio
```

### 2. Importar o Banco de Dados
Você pode importar o arquivo SQL fornecido para criar as tabelas e inserir dados iniciais.
```bash
psql -U seu_usuario -d nome_do_banco < caminho/do/arquivo.sql
```


### 3. Backend (Laravel)
```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configure o arquivo .env com os dados do seu banco PostgreSQL:
```ini
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=nome_do_banco
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

Inicie o servidor Laravel:
```bash
php artisan serve
```

### 4. Frontend (Vue.js)
```bash
cd frontend
npm install
npm run dev
```

## 📄 Funcionalidades

- ✅ Cadastro, edição e listagem de usuários

- ✅ Cadastro, edição e listagem de cursos

- ✅ Relacionamento N:N entre usuários e cursos

- ✅ Interface amigável com Bootstrap

- ✅ Paginação na listagem

- ✅ Separação total entre frontend e backend

## 📦 Estrutura de Pastas
```bash
laravel-and-vue-crud/
├── backend/                    # Backend Laravel
├── frontend/                   # Frontend Vue.js
├── alunos_cursos_db_backup     # Arquivo .sql ou .backup do PostgreSQL
└── README.md
```

## 👨‍💻 Desenvolvedor 
### Lucca Menegatti