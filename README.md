# 🏋️ UpSoluctions Gym

Sistema web de **gestão de academias e centros de treinamento**, desenvolvido com Laravel.

O objetivo é centralizar o gerenciamento de **unidades, usuários, alunos, professores, planos, matrículas, financeiro, presença, exercícios, treinos, avaliações físicas e comunicados**.

> 🚧 **Status:** Em desenvolvimento ativo.

## 📌 Legenda

| Status | Significado                   |
| ------ | ----------------------------- |
| 🟩     | Concluído                     |
| 🟨     | Iniciado / Em desenvolvimento |
| ⬜      | Não iniciado                  |

> A existência de um Model ou Migration não significa que o módulo esteja concluído.

---

# 🛠️ Stack

### Backend

* PHP 8.3+
* Laravel 13
* Laravel Breeze
* Laravel Tinker

### Frontend

* Blade
* Tailwind CSS
* Alpine.js
* Vite

### Qualidade

* Pest
* PHPUnit
* Laravel Pint

### Banco

* MySQL

---

# 📂 Estrutura

```text
app/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
├── Models/
└── View/

database/
├── factories/
├── migrations/
└── seeders/

resources/
├── css/
├── js/
└── views/

routes/
├── web.php
└── auth.php

tests/
├── Feature/
└── Unit/
```

---

# 🗃️ Domínio

O projeto já possui Models e migrations para:

```text
User
Unit
StudentProfile
TeacherProfile
Plan
Enrollment
FinancialTransaction
Attendance
Exercise
WorkoutPlan
WorkoutExercise
PhysicalAssessment
Announcement
```

Os módulos de domínio ainda precisam evoluir para CRUDs completos, regras de negócio, interfaces e testes.

---

# 🔐 Autenticação

A autenticação inicial utiliza Laravel Breeze.

* 🟩 Login
* 🟩 Logout
* 🟩 Registro
* 🟩 Recuperação de senha
* 🟩 Verificação de e-mail
* 🟩 Perfil
* 🟩 Proteção de rotas
* 🟨 Roles
* ⬜ Permissões granulares
* ⬜ Policies
* ⬜ Gates

A rota `/` direciona para o login e `/dashboard` é protegida por autenticação.

---

# 🚀 Instalação

## 1. Clonar

```bash
git clone https://github.com/filipe-csilva/UpSoluctions-Gym.git
cd UpSoluctions-Gym
```

## 2. Dependências

```bash
composer install
npm install
```

## 3. Ambiente

### Windows

```powershell
copy .env.example .env
```

### Linux/macOS

```bash
cp .env.example .env
```

Gerar chave:

```bash
php artisan key:generate
```

## 4. Banco

Configure o `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=upsoluctions_gym
DB_USERNAME=root
DB_PASSWORD=
```

Execute:

```bash
php artisan migrate
```

## 5. Executar

```bash
composer dev
```

Ou separadamente:

```bash
php artisan serve
npm run dev
```

Aplicação:

```text
http://127.0.0.1:8000
```

---

# 🧪 Comandos

### Testes

```bash
php artisan test
```

ou:

```bash
./vendor/bin/pest
```

### Code Style

```bash
./vendor/bin/pint
```

### Limpar cache

```bash
php artisan optimize:clear
```

### Rotas

```bash
php artisan route:list
```

### Migrations

```bash
php artisan migrate
```

```bash
php artisan migrate:fresh --seed
```

> ⚠️ `migrate:fresh` apaga as tabelas existentes.

---

# 🗺️ Roadmap

## Fase 01 — Fundação

* 🟩 Projeto Laravel
* 🟩 Composer / NPM
* 🟩 Vite
* 🟩 Tailwind CSS
* 🟩 Alpine.js
* 🟩 Laravel Breeze
* 🟩 Autenticação
* 🟩 Perfil
* 🟩 Estrutura inicial do banco

---

## Fase 02 — Usuários e Acesso

* 🟨 Roles
* ⬜ Middleware de autorização
* ⬜ Policies
* ⬜ Gates
* ⬜ Permissões por módulo
* ⬜ Permissões por unidade
* ⬜ CRUD de usuários

**Perfis:**

```text
Administrador
Gestor
Professor
Aluno
```

---

## Fase 03 — Unidades

* 🟩 Model
* 🟩 Migration
* 🟨 Relacionamentos
* ⬜ Controller
* ⬜ Requests
* ⬜ Rotas
* ⬜ CRUD
* ⬜ Ativação/Inativação
* ⬜ Interface

---

## Fase 04 — Alunos

* 🟩 Model
* 🟩 Migration
* 🟨 Relacionamentos
* ⬜ Controller
* ⬜ Requests
* ⬜ CRUD
* ⬜ Validações
* ⬜ Histórico
* ⬜ Interface

---

## Fase 05 — Professores

* 🟩 Model
* 🟩 Migration
* 🟨 Relacionamentos
* ⬜ Controller
* ⬜ Requests
* ⬜ CRUD
* ⬜ Alunos vinculados
* ⬜ Interface

---

## Fase 06 — Planos

* 🟩 Model
* 🟩 Migration
* 🟨 Relacionamentos
* ⬜ Controller
* ⬜ Requests
* ⬜ CRUD
* ⬜ Valores e periodicidade
* ⬜ Interface

---

## Fase 07 — Matrículas

* 🟩 Model
* 🟩 Migration
* 🟨 Relacionamentos
* ⬜ Controller
* ⬜ Requests
* ⬜ Criar matrícula
* ⬜ Renovar
* ⬜ Suspender
* ⬜ Cancelar
* ⬜ Alterar plano
* ⬜ Histórico

---

## Fase 08 — Financeiro

* 🟩 Model
* 🟩 Migration
* 🟨 Relacionamentos
* ⬜ Controller
* ⬜ Requests
* ⬜ Receitas
* ⬜ Despesas
* ⬜ Pagamentos
* ⬜ Inadimplência
* ⬜ Histórico
* ⬜ Dashboard financeiro

---

## Fase 09 — Presença

* 🟩 Model
* 🟩 Migration
* 🟨 Relacionamentos
* ⬜ Controller
* ⬜ Registro de entrada/saída
* ⬜ Histórico
* ⬜ Frequência
* ⬜ Relatórios

**Futuro:**

* ⬜ QR Code
* ⬜ Biometria
* ⬜ Integração com catracas

---

## Fase 10 — Exercícios

* 🟩 Model
* 🟩 Migration
* ⬜ Controller
* ⬜ Requests
* ⬜ CRUD
* ⬜ Grupo muscular
* ⬜ Equipamento
* ⬜ Instruções
* ⬜ Imagens/vídeos

---

## Fase 11 — Fichas de Treino

* 🟩 Models
* 🟩 Migrations
* 🟨 Relacionamentos
* ⬜ Controllers
* ⬜ Requests
* ⬜ Criar ficha
* ⬜ Adicionar exercícios
* ⬜ Séries
* ⬜ Repetições
* ⬜ Carga
* ⬜ Descanso
* ⬜ Histórico

---

## Fase 12 — Avaliação Física

* 🟩 Model
* 🟩 Migration
* 🟨 Relacionamentos
* ⬜ Controller
* ⬜ Requests
* ⬜ Cadastro
* ⬜ Histórico
* ⬜ Comparação
* ⬜ Evolução
* ⬜ Gráficos

---

## Fase 13 — Comunicados

* 🟩 Model
* 🟩 Migration
* 🟨 Relacionamentos
* ⬜ Controller
* ⬜ Requests
* ⬜ CRUD
* ⬜ Publicação
* ⬜ Destinatários
* ⬜ Interface

---

## Fase 14 — Dashboards

### Administrador

* 🟨 Dashboard inicial
* ⬜ Alunos ativos/inativos
* ⬜ Matrículas
* ⬜ Receita
* ⬜ Despesas
* ⬜ Inadimplência
* ⬜ Presença
* ⬜ Indicadores por unidade
* ⬜ Gráficos

### Professor

* ⬜ Meus alunos
* ⬜ Treinos
* ⬜ Avaliações
* ⬜ Frequência

### Aluno

* ⬜ Perfil
* ⬜ Plano
* ⬜ Matrícula
* ⬜ Financeiro
* ⬜ Treino
* ⬜ Avaliações
* ⬜ Frequência

---

## Fase 15 — Relatórios

* ⬜ Alunos
* ⬜ Matrículas
* ⬜ Financeiro
* ⬜ Inadimplência
* ⬜ Presença
* ⬜ Treinos
* ⬜ Avaliações
* ⬜ Por unidade
* ⬜ Exportação PDF
* ⬜ Exportação Excel/CSV

---

## Fase 16 — Notificações

* ⬜ Notificações internas
* ⬜ Mensalidade próxima do vencimento
* ⬜ Mensalidade vencida
* ⬜ Matrícula vencendo
* ⬜ Nova ficha de treino
* ⬜ Nova avaliação
* ⬜ Comunicados
* ⬜ E-mail
* ⬜ WhatsApp
* ⬜ Push

---

## Fase 17 — Testes

* 🟨 Estrutura inicial
* ⬜ Testes de usuários
* ⬜ Testes de unidades
* ⬜ Testes de alunos
* ⬜ Testes de professores
* ⬜ Testes de planos
* ⬜ Testes de matrículas
* ⬜ Testes financeiros
* ⬜ Testes de presença
* ⬜ Testes de treinos
* ⬜ Testes de avaliações
* ⬜ Testes de autorização

---

## Fase 18 — API

* ⬜ API REST
* ⬜ Versionamento
* ⬜ Autenticação
* ⬜ API Resources
* ⬜ Endpoints dos módulos
* ⬜ Swagger/OpenAPI

---

## Fase 19 — Performance e Infraestrutura

* ⬜ Otimização de queries
* ⬜ Índices
* ⬜ Cache
* ⬜ Redis
* ⬜ Jobs
* ⬜ Filas
* ⬜ Docker
* ⬜ CI/CD

---

## Fase 20 — Produção

* ⬜ Servidor
* ⬜ Nginx
* ⬜ PHP-FPM
* ⬜ HTTPS
* ⬜ Backup
* ⬜ Logs
* ⬜ Monitoramento
* ⬜ Deploy automatizado

---

# 📊 Estado Atual

| Área              | Status |
| ----------------- | ------ |
| Fundação          | 🟩     |
| Autenticação      | 🟩     |
| Modelagem         | 🟩     |
| Migrations        | 🟩     |
| Usuários / Roles  | 🟨     |
| Unidades          | 🟨     |
| Alunos            | 🟨     |
| Professores       | 🟨     |
| Planos            | 🟨     |
| Matrículas        | 🟨     |
| Financeiro        | 🟨     |
| Presença          | 🟨     |
| Exercícios        | 🟨     |
| Treinos           | 🟨     |
| Avaliações        | 🟨     |
| Comunicados       | 🟨     |
| CRUDs de domínio  | ⬜      |
| Dashboards        | 🟨     |
| Área do aluno     | ⬜      |
| Relatórios        | ⬜      |
| Notificações      | ⬜      |
| Testes de domínio | ⬜      |
| API               | ⬜      |
| Docker / CI/CD    | ⬜      |
| Produção          | ⬜      |

---

# 🎯 Próxima Prioridade

A próxima sequência recomendada é:

```text
1. Roles e autorização
        ↓
2. CRUD de Unidades
        ↓
3. CRUD de Alunos
        ↓
4. CRUD de Professores
        ↓
5. CRUD de Planos
        ↓
6. Matrículas
        ↓
7. Financeiro
        ↓
8. Presença
        ↓
9. Exercícios e Treinos
        ↓
10. Avaliações
        ↓
11. Dashboards
        ↓
12. Área do Aluno
        ↓
13. Relatórios
        ↓
14. Testes
        ↓
15. API / Produção
```

---

# 👨‍💻 Desenvolvedor

**Filipe Silva**

Projeto desenvolvido para aplicação prática de:

* PHP
* Laravel
* Backend
* Banco de Dados
* Arquitetura
* Autenticação
* Autorização
* Testes
* DevOps

---

# 📄 Licença

Este projeto está licenciado sob a licença **MIT**.

Consulte o arquivo `LICENSE` para mais informações.

---

**UpSoluctions Gym — Gestão inteligente para academias. 🏋️**
