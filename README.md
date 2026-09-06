# 🏋️ UpSoluctions Gym

Sistema web para **gerenciamento completo de academias e centros de treinamento**, desenvolvido com Laravel.

O projeto tem como objetivo centralizar a administração de academias, permitindo o gerenciamento de **unidades, usuários, alunos, professores, planos, matrículas, financeiro, presença, exercícios, fichas de treino, avaliações físicas, comunicados, dashboards e relatórios**.

> 🚧 **Status:** Em desenvolvimento ativo.

---

# 📌 Status do Projeto

| Status | Significado                   |
| ------ | ----------------------------- |
| 🟩     | Concluído                     |
| 🟨     | Iniciado / Em desenvolvimento |
| ⬜      | Não iniciado                  |

> **Importante:** a existência de um Model ou Migration não significa que o módulo esteja funcional. Um módulo somente será considerado concluído quando suas regras de negócio, Controllers, Requests, rotas, interface e testes necessários estiverem implementados.

---

# 🎯 Objetivo

O UpSoluctions Gym foi projetado para atender academias que precisam controlar suas operações administrativas e esportivas em uma única plataforma.

O sistema deverá permitir:

* Gerenciamento de múltiplas unidades;
* Controle de usuários;
* Controle de permissões;
* Cadastro de alunos;
* Cadastro de professores;
* Cadastro de planos;
* Controle de matrículas;
* Controle financeiro;
* Controle de presença;
* Cadastro de exercícios;
* Criação de fichas de treino;
* Avaliações físicas;
* Comunicados;
* Dashboards;
* Relatórios;
* Notificações;
* Área exclusiva do aluno.

---

# 🧩 Perfis do Sistema

O sistema será estruturado para trabalhar inicialmente com quatro perfis principais:

```text
Administrador
     │
     ├── Gestão completa do sistema
     │
Gestor
     │
     ├── Gestão da unidade
     │
Professor
     │
     ├── Alunos
     ├── Treinos
     └── Avaliações
     
Aluno
     │
     ├── Perfil
     ├── Plano
     ├── Matrícula
     ├── Treino
     ├── Financeiro
     └── Avaliação
```

---

# 🚀 Tecnologias

## Backend

* PHP 8.3+
* Laravel 13
* Laravel Breeze
* Laravel Tinker

O projeto atualmente utiliza PHP `^8.3` e Laravel `^13.17`.

## Frontend

* Blade
* Tailwind CSS
* Alpine.js
* Vite

O projeto utiliza Vite 8, Alpine.js, Tailwind CSS e Laravel Vite Plugin.

## Testes

* Pest
* Pest Laravel
* PHPUnit
* Mockery
* Laravel Pint

## Banco de dados

Banco inicialmente configurado para:

* MySQL

Com possibilidade futura de suporte a:

* PostgreSQL

---

# 🏗️ Arquitetura

Estrutura principal:

```text
UpSoluctions-Gym/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   ├── Controller.php
│   │   │   └── ProfileController.php
│   │   │
│   │   ├── Middleware/
│   │   └── Requests/
│   │
│   ├── Models/
│   │
│   └── View/
│
├── bootstrap/
│
├── config/
│
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
│
├── public/
│
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│
├── routes/
│   ├── web.php
│   └── auth.php
│
├── storage/
│
├── tests/
│   ├── Feature/
│   └── Unit/
│
├── artisan
├── composer.json
├── package.json
├── vite.config.js
├── tailwind.config.js
└── README.md
```

---

# 🗃️ Domínio Atual

O projeto já possui Models para os principais conceitos do domínio:

```text
Announcement
Attendance
Enrollment
Exercise
FinancialTransaction
PhysicalAssessment
Plan
StudentProfile
TeacherProfile
Unit
User
WorkoutExercise
WorkoutPlan
```

As migrations correspondentes também já foram criadas.

---

# 🗄️ Banco de Dados

## Tabelas principais

```text
users
units
student_profiles
teacher_profiles
plans
enrollments
financial_transactions
attendances
exercises
workout_plans
workout_exercises
physical_assessments
announcements
```

Além das tabelas padrão do Laravel:

```text
cache
jobs
```

---

# 🔗 Modelo Conceitual

```text
                         ┌─────────────┐
                         │    Unit     │
                         └──────┬──────┘
                                │
                ┌───────────────┼───────────────┐
                │               │               │
                ▼               ▼               ▼
             Users           Students        Teachers
                                │
                ┌───────────────┼───────────────┐
                │               │               │
                ▼               ▼               ▼
             Enrollment     Attendance       Assessment
                │
                ▼
               Plan
                │
                ▼
            Financial
                │
                ▼
          Workout Plan
                │
                ▼
       Workout Exercises
                │
                ▼
             Exercise
```

---

# 🔐 Autenticação

A autenticação inicial foi implementada utilizando Laravel Breeze.

## Funcionalidades atuais

* 🟩 Login
* 🟩 Logout
* 🟩 Registro
* 🟩 Recuperação de senha
* 🟩 Verificação de e-mail
* 🟩 Confirmação de senha
* 🟩 Perfil
* 🟩 Proteção de rotas
* 🟨 Controle de roles
* ⬜ Controle granular de permissões

---

# 🛣️ Rotas Atuais

A rota `/` atualmente apresenta a tela de login.

```text
GET /
```

O dashboard está protegido por autenticação:

```text
GET /dashboard
```

O perfil possui:

```text
GET    /profile
PATCH  /profile
DELETE /profile
```

Essas rotas estão atualmente definidas em `routes/web.php`.

---

# ⚙️ Configuração

O `.env.example` atualmente utiliza MySQL como configuração padrão:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=upsoluctions_gym
DB_USERNAME=root
DB_PASSWORD=
```

A aplicação também utiliza:

```env
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```

---

# 📦 Requisitos

Antes de executar o projeto, instale:

* PHP >= 8.3
* Composer
* Node.js
* NPM
* MySQL
* Git

Recomendado:

```text
PHP 8.3+
Composer 2+
Node.js LTS
NPM
MySQL 8+
Git
```

---

# 🚀 Instalação

## 1. Clonar o projeto

```bash
git clone https://github.com/filipe-csilva/UpSoluctions-Gym.git
```

Entrar no projeto:

```bash
cd UpSoluctions-Gym
```

---

## 2. Instalar dependências PHP

```bash
composer install
```

---

## 3. Configurar `.env`

### Windows

```powershell
copy .env.example .env
```

### Linux/macOS

```bash
cp .env.example .env
```

---

## 4. Gerar chave

```bash
php artisan key:generate
```

---

## 5. Configurar banco

Crie o banco:

```text
upsoluctions_gym
```

Depois configure:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=upsoluctions_gym
DB_USERNAME=root
DB_PASSWORD=
```

---

## 6. Executar migrations

```bash
php artisan migrate
```

Para recriar o banco:

```bash
php artisan migrate:fresh
```

Para recriar e executar seeders:

```bash
php artisan migrate:fresh --seed
```

---

# 🎨 Frontend

Instalar dependências:

```bash
npm install
```

Executar desenvolvimento:

```bash
npm run dev
```

Gerar build:

```bash
npm run build
```

Os scripts atualmente disponíveis são `dev` e `build`.

---

# 💻 Executar Aplicação

Servidor Laravel:

```bash
php artisan serve
```

Aplicação:

```text
http://127.0.0.1:8000
```

Frontend:

```bash
npm run dev
```

---

# ⚡ Ambiente Completo

O projeto possui um script `composer dev` que executa:

```text
Laravel Server
Queue Worker
Vite
```

Pode ser iniciado com:

```bash
composer dev
```

Esse comportamento está definido no `composer.json`.

---

# 🧹 Limpar Cache

```bash
php artisan optimize:clear
```

Ou:

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

---

# 🧪 Testes

Executar testes:

```bash
php artisan test
```

Ou:

```bash
./vendor/bin/pest
```

No Windows:

```powershell
vendor\bin\pest
```

---

# 🧹 Laravel Pint

Executar:

```bash
./vendor/bin/pint
```

Windows:

```powershell
vendor\bin\pint
```

---

# 🗺️ ROADMAP

# FASE 01 — Fundação

## Projeto

* 🟩 Criar projeto Laravel
* 🟩 Configurar Composer
* 🟩 Configurar NPM
* 🟩 Configurar Vite
* 🟩 Configurar Tailwind
* 🟩 Configurar Alpine.js
* 🟩 Configurar `.env`
* 🟩 Configurar `.env.example`
* 🟩 Configurar Git
* 🟩 Configurar `.gitignore`

## Autenticação

* 🟩 Instalar Laravel Breeze
* 🟩 Login
* 🟩 Logout
* 🟩 Registro
* 🟩 Recuperação de senha
* 🟩 Verificação de e-mail
* 🟩 Perfil
* 🟩 Dashboard inicial
* 🟩 Proteção de rotas

## Banco

* 🟩 Migration `users`
* 🟩 Migration `cache`
* 🟩 Migration `jobs`
* 🟩 Migration de role
* 🟩 Configuração de sessão
* 🟩 Configuração de cache
* 🟩 Configuração de filas

---

# FASE 02 — Usuários

## Model

* 🟩 Model `User`
* 🟩 Campo `role`
* 🟨 Relacionamento com `Unit`
* 🟨 Relacionamento com perfil de aluno
* 🟨 Relacionamento com perfil de professor

## Roles

* 🟨 Definir Administrador
* 🟨 Definir Gestor
* 🟨 Definir Professor
* 🟨 Definir Aluno

## Autorização

* ⬜ Middleware de roles
* ⬜ Policies
* ⬜ Gates
* ⬜ Permission Service
* ⬜ Controle de acesso por unidade
* ⬜ Tela de gerenciamento de usuários

## Usuários

* ⬜ Listar usuários
* ⬜ Criar usuário
* ⬜ Editar usuário
* ⬜ Inativar usuário
* ⬜ Excluir usuário
* ⬜ Alterar role
* ⬜ Vincular unidade

---

# FASE 03 — Unidades

## Banco

* 🟩 Model `Unit`
* 🟩 Migration `units`
* 🟨 Relacionamentos
* 🟨 `unit_id` em usuários

## Backend

* ⬜ `UnitController`
* ⬜ `UnitRequest`
* ⬜ Validação
* ⬜ Policies
* ⬜ Rotas

## CRUD

* ⬜ Listar unidades
* ⬜ Criar unidade
* ⬜ Visualizar unidade
* ⬜ Editar unidade
* ⬜ Excluir unidade
* ⬜ Ativar unidade
* ⬜ Inativar unidade

## Dados

* ⬜ Nome
* ⬜ CNPJ
* ⬜ Telefone
* ⬜ E-mail
* ⬜ Endereço
* ⬜ Número
* ⬜ Bairro
* ⬜ Cidade
* ⬜ Estado
* ⬜ CEP
* ⬜ Responsável
* ⬜ Horário de funcionamento

## Interface

* ⬜ Listagem
* ⬜ Formulário
* ⬜ Detalhes
* ⬜ Dashboard da unidade

---

# FASE 04 — Alunos

## Banco

* 🟩 Model `StudentProfile`
* 🟩 Migration `student_profiles`
* 🟨 Relacionamento com User
* 🟨 Relacionamento com Unit
* 🟨 Relacionamento com Enrollment
* 🟨 Relacionamento com Attendance
* 🟨 Relacionamento com WorkoutPlan
* 🟨 Relacionamento com PhysicalAssessment

## Backend

* ⬜ `StudentController`
* ⬜ `StudentRequest`
* ⬜ Validação
* ⬜ Policies
* ⬜ Rotas

## Cadastro

* ⬜ Nome
* ⬜ CPF
* ⬜ RG
* ⬜ Data de nascimento
* ⬜ Sexo
* ⬜ Telefone
* ⬜ E-mail
* ⬜ Endereço
* ⬜ CEP
* ⬜ Cidade
* ⬜ Estado
* ⬜ Unidade
* ⬜ Status

## CRUD

* ⬜ Listagem
* ⬜ Cadastro
* ⬜ Visualização
* ⬜ Edição
* ⬜ Inativação
* ⬜ Exclusão

## Histórico

* ⬜ Matrículas
* ⬜ Financeiro
* ⬜ Presença
* ⬜ Treinos
* ⬜ Avaliações

---

# FASE 05 — Professores

## Banco

* 🟩 Model `TeacherProfile`
* 🟩 Migration `teacher_profiles`
* 🟨 Relacionamento com User
* 🟨 Relacionamento com Unit
* 🟨 Relacionamento com WorkoutPlan
* 🟨 Relacionamento com PhysicalAssessment

## Backend

* ⬜ `TeacherController`
* ⬜ `TeacherRequest`
* ⬜ Policies
* ⬜ Rotas

## CRUD

* ⬜ Listar professores
* ⬜ Criar professor
* ⬜ Visualizar professor
* ⬜ Editar professor
* ⬜ Inativar professor
* ⬜ Excluir professor

## Informações

* ⬜ Dados pessoais
* ⬜ Contato
* ⬜ Unidade
* ⬜ Especialidade
* ⬜ Status
* ⬜ CREF

## Relacionamentos

* ⬜ Alunos
* ⬜ Treinos
* ⬜ Avaliações

---

# FASE 06 — Planos

## Banco

* 🟩 Model `Plan`
* 🟩 Migration `plans`
* 🟨 Relacionamento com Unit
* 🟨 Relacionamento com Enrollment

## Backend

* ⬜ `PlanController`
* ⬜ `PlanRequest`
* ⬜ Policies
* ⬜ Rotas

## CRUD

* ⬜ Listar planos
* ⬜ Criar plano
* ⬜ Visualizar plano
* ⬜ Editar plano
* ⬜ Ativar plano
* ⬜ Inativar plano
* ⬜ Excluir plano

## Informações

* ⬜ Nome
* ⬜ Descrição
* ⬜ Valor
* ⬜ Periodicidade
* ⬜ Duração
* ⬜ Unidade
* ⬜ Benefícios
* ⬜ Status

---

# FASE 07 — Matrículas

## Banco

* 🟩 Model `Enrollment`
* 🟩 Migration `enrollments`
* 🟨 Relacionamento com Student
* 🟨 Relacionamento com Plan
* 🟨 Relacionamento com Unit

## Backend

* ⬜ `EnrollmentController`
* ⬜ `EnrollmentRequest`
* ⬜ Policies
* ⬜ Rotas

## Operações

* ⬜ Criar matrícula
* ⬜ Renovar matrícula
* ⬜ Alterar plano
* ⬜ Suspender matrícula
* ⬜ Reativar matrícula
* ⬜ Cancelar matrícula
* ⬜ Transferir unidade

## Controle

* ⬜ Data de início
* ⬜ Data de término
* ⬜ Vencimento
* ⬜ Status
* ⬜ Histórico

## Status

```text
ATIVA
PENDENTE
SUSPENSA
CANCELADA
VENCIDA
```

---

# FASE 08 — Financeiro

## Banco

* 🟩 Model `FinancialTransaction`
* 🟩 Migration `financial_transactions`
* 🟨 Relacionamento com Student
* 🟨 Relacionamento com Enrollment
* 🟨 Relacionamento com Unit

## Backend

* ⬜ `FinancialTransactionController`
* ⬜ `FinancialTransactionRequest`
* ⬜ Policies
* ⬜ Rotas

## Receitas

* ⬜ Mensalidades
* ⬜ Matrículas
* ⬜ Renovações
* ⬜ Outros recebimentos

## Despesas

* ⬜ Cadastro
* ⬜ Categoria
* ⬜ Valor
* ⬜ Vencimento
* ⬜ Pagamento

## Pagamentos

* ⬜ Registrar pagamento
* ⬜ Editar pagamento
* ⬜ Cancelar pagamento
* ⬜ Histórico

## Indicadores

* ⬜ Receita diária
* ⬜ Receita mensal
* ⬜ Receita anual
* ⬜ Despesas
* ⬜ Saldo
* ⬜ Inadimplência
* ⬜ Contas vencidas
* ⬜ Contas pendentes

---

# FASE 09 — Presença

## Banco

* 🟩 Model `Attendance`
* 🟩 Migration `attendances`
* 🟨 Relacionamento com Student
* 🟨 Relacionamento com Unit

## Backend

* ⬜ `AttendanceController`
* ⬜ `AttendanceRequest`
* ⬜ Policies
* ⬜ Rotas

## Funcionalidades

* ⬜ Registrar entrada
* ⬜ Registrar saída
* ⬜ Consultar presença
* ⬜ Histórico
* ⬜ Frequência semanal
* ⬜ Frequência mensal
* ⬜ Frequência anual

## Futuro

* ⬜ QR Code
* ⬜ Cartão
* ⬜ Biometria
* ⬜ Integração com dispositivos

---

# FASE 10 — Exercícios

## Banco

* 🟩 Model `Exercise`
* 🟩 Migration `exercises`

## Backend

* ⬜ `ExerciseController`
* ⬜ `ExerciseRequest`
* ⬜ Policies
* ⬜ Rotas

## CRUD

* ⬜ Listar exercícios
* ⬜ Criar exercício
* ⬜ Visualizar exercício
* ⬜ Editar exercício
* ⬜ Excluir exercício

## Informações

* ⬜ Nome
* ⬜ Descrição
* ⬜ Grupo muscular
* ⬜ Equipamento
* ⬜ Nível
* ⬜ Instruções
* ⬜ Observações
* ⬜ Status

## Conteúdo

* ⬜ Imagem
* ⬜ Vídeo
* ⬜ Instruções detalhadas

---

# FASE 11 — Fichas de Treino

## Banco

* 🟩 Model `WorkoutPlan`
* 🟩 Model `WorkoutExercise`
* 🟩 Migration `workout_plans`
* 🟩 Migration `workout_exercises`
* 🟨 Relacionamentos

## Backend

* ⬜ `WorkoutPlanController`
* ⬜ `WorkoutExerciseController`
* ⬜ Requests
* ⬜ Policies
* ⬜ Rotas

## Ficha

* ⬜ Criar ficha
* ⬜ Editar ficha
* ⬜ Excluir ficha
* ⬜ Ativar ficha
* ⬜ Finalizar ficha
* ⬜ Duplicar ficha
* ⬜ Histórico

## Exercícios

* ⬜ Adicionar exercício
* ⬜ Remover exercício
* ⬜ Reordenar exercício
* ⬜ Séries
* ⬜ Repetições
* ⬜ Carga
* ⬜ Descanso
* ⬜ Observações

## Relacionamentos

* ⬜ Aluno
* ⬜ Professor
* ⬜ Exercícios
* ⬜ Unidade

---

# FASE 12 — Avaliação Física

## Banco

* 🟩 Model `PhysicalAssessment`
* 🟩 Migration `physical_assessments`
* 🟨 Relacionamento com Student
* 🟨 Relacionamento com Teacher

## Backend

* ⬜ `PhysicalAssessmentController`
* ⬜ `PhysicalAssessmentRequest`
* ⬜ Policies
* ⬜ Rotas

## Medidas

* ⬜ Peso
* ⬜ Altura
* ⬜ IMC
* ⬜ Percentual de gordura
* ⬜ Massa muscular
* ⬜ Braço
* ⬜ Peito
* ⬜ Cintura
* ⬜ Abdômen
* ⬜ Quadril
* ⬜ Coxa
* ⬜ Panturrilha

## Histórico

* ⬜ Criar avaliação
* ⬜ Editar avaliação
* ⬜ Visualizar avaliação
* ⬜ Histórico
* ⬜ Comparação
* ⬜ Evolução

## Gráficos

* ⬜ Peso
* ⬜ Gordura
* ⬜ Massa muscular
* ⬜ Circunferências

---

# FASE 13 — Comunicados

## Banco

* 🟩 Model `Announcement`
* 🟩 Migration `announcements`
* 🟨 Relacionamento com Unit
* 🟨 Relacionamento com User

## Backend

* ⬜ `AnnouncementController`
* ⬜ `AnnouncementRequest`
* ⬜ Policies
* ⬜ Rotas

## CRUD

* ⬜ Criar comunicado
* ⬜ Editar comunicado
* ⬜ Excluir comunicado
* ⬜ Publicar comunicado
* ⬜ Arquivar comunicado

## Destinatários

* ⬜ Todos
* ⬜ Unidade
* ⬜ Professores
* ⬜ Alunos
* ⬜ Usuário específico

---

# FASE 14 — Dashboard Administrativo

## Estrutura

* 🟨 Dashboard inicial
* ⬜ Dashboard administrativo completo
* ⬜ Dashboard por unidade

## Indicadores

* ⬜ Total de alunos
* ⬜ Alunos ativos
* ⬜ Alunos inativos
* ⬜ Novos alunos
* ⬜ Matrículas ativas
* ⬜ Matrículas vencidas
* ⬜ Matrículas canceladas
* ⬜ Professores
* ⬜ Receita
* ⬜ Despesas
* ⬜ Inadimplência
* ⬜ Presença

## Gráficos

* ⬜ Crescimento de alunos
* ⬜ Receita mensal
* ⬜ Inadimplência
* ⬜ Presença
* ⬜ Matrículas
* ⬜ Cancelamentos
* ⬜ Receita por unidade

---

# FASE 15 — Dashboard do Professor

* ⬜ Dashboard
* ⬜ Meus alunos
* ⬜ Alunos ativos
* ⬜ Alunos inativos
* ⬜ Treinos
* ⬜ Avaliações
* ⬜ Presença
* ⬜ Alunos sem frequência
* ⬜ Fichas recentes
* ⬜ Comunicados

---

# FASE 16 — Área do Aluno

## Dashboard

* ⬜ Dashboard
* ⬜ Resumo da matrícula
* ⬜ Status do plano
* ⬜ Próximo vencimento

## Perfil

* ⬜ Dados pessoais
* ⬜ Contato
* ⬜ Endereço
* ⬜ Foto

## Plano

* ⬜ Plano atual
* ⬜ Valor
* ⬜ Vencimento
* ⬜ Status

## Financeiro

* ⬜ Histórico
* ⬜ Pendências
* ⬜ Pagamentos
* ⬜ Recibos

## Treino

* ⬜ Ficha atual
* ⬜ Exercícios
* ⬜ Séries
* ⬜ Repetições
* ⬜ Cargas
* ⬜ Descanso

## Avaliação

* ⬜ Avaliação atual
* ⬜ Histórico
* ⬜ Evolução

## Presença

* ⬜ Histórico
* ⬜ Frequência

---

# FASE 17 — Relatórios

## Alunos

* ⬜ Lista de alunos
* ⬜ Ativos
* ⬜ Inativos
* ⬜ Novos alunos
* ⬜ Cancelamentos

## Matrículas

* ⬜ Matrículas ativas
* ⬜ Matrículas vencidas
* ⬜ Renovações
* ⬜ Cancelamentos

## Financeiro

* ⬜ Receitas
* ⬜ Despesas
* ⬜ Inadimplência
* ⬜ Pagamentos
* ⬜ Fluxo financeiro

## Presença

* ⬜ Frequência
* ⬜ Alunos mais frequentes
* ⬜ Alunos com baixa frequência

## Treinos

* ⬜ Fichas
* ⬜ Exercícios
* ⬜ Professores

## Avaliações

* ⬜ Evolução física
* ⬜ Peso
* ⬜ Gordura
* ⬜ Massa muscular

## Exportação

* ⬜ PDF
* ⬜ Excel
* ⬜ CSV

---

# FASE 18 — Notificações

## Sistema

* ⬜ Notificações internas
* ⬜ Central de notificações
* ⬜ Marcar como lida
* ⬜ Histórico

## Financeiro

* ⬜ Mensalidade próxima
* ⬜ Mensalidade vencida
* ⬜ Pagamento confirmado

## Matrícula

* ⬜ Matrícula próxima do vencimento
* ⬜ Matrícula vencida
* ⬜ Renovação

## Treino

* ⬜ Nova ficha
* ⬜ Alteração de ficha

## Avaliação

* ⬜ Nova avaliação

## Comunicação

* ⬜ Novo comunicado

## Futuro

* ⬜ E-mail
* ⬜ WhatsApp
* ⬜ Push Notification

---

# FASE 19 — Segurança

## Autenticação

* 🟩 Login
* 🟩 Logout
* 🟩 Registro
* 🟩 Recuperação de senha
* 🟩 Verificação de e-mail
* 🟩 Middleware `auth`

## Autorização

* 🟨 Role
* ⬜ Policies
* ⬜ Gates
* ⬜ Middleware de autorização
* ⬜ Permissões por módulo
* ⬜ Permissões por unidade

## Segurança

* ⬜ Rate limiting
* ⬜ Auditoria
* ⬜ Logs de ações
* ⬜ Proteção contra acesso indevido
* ⬜ Validação de entrada
* ⬜ Proteção de dados sensíveis

---

# FASE 20 — Testes

## Estrutura

* 🟨 Estrutura inicial
* ⬜ Cobertura completa

## Autenticação

* ⬜ Login
* ⬜ Logout
* ⬜ Registro
* ⬜ Recuperação de senha
* ⬜ Verificação

## Usuários

* ⬜ Roles
* ⬜ Permissões

## Unidades

* ⬜ CRUD
* ⬜ Autorização

## Alunos

* ⬜ CRUD
* ⬜ Validações
* ⬜ Relacionamentos

## Professores

* ⬜ CRUD
* ⬜ Relacionamentos

## Planos

* ⬜ CRUD
* ⬜ Validações

## Matrículas

* ⬜ CRUD
* ⬜ Renovação
* ⬜ Cancelamento
* ⬜ Suspensão

## Financeiro

* ⬜ Receitas
* ⬜ Despesas
* ⬜ Pagamentos

## Presença

* ⬜ Registro
* ⬜ Histórico

## Treinos

* ⬜ Fichas
* ⬜ Exercícios

## Avaliações

* ⬜ Cadastro
* ⬜ Histórico

---

# FASE 21 — Performance

* ⬜ Eager Loading
* ⬜ Paginação
* ⬜ Índices de banco
* ⬜ Cache
* ⬜ Cache de consultas
* ⬜ Redis
* ⬜ Filas
* ⬜ Jobs
* ⬜ Otimização de queries
* ⬜ Otimização de assets
* ⬜ Monitoramento

---

# FASE 22 — API REST

## Estrutura

* ⬜ API
* ⬜ Versionamento
* ⬜ API Resources
* ⬜ API Requests
* ⬜ Autenticação API

## Endpoints planejados

```text
/api/v1/auth
/api/v1/users
/api/v1/units
/api/v1/students
/api/v1/teachers
/api/v1/plans
/api/v1/enrollments
/api/v1/financial
/api/v1/attendances
/api/v1/exercises
/api/v1/workouts
/api/v1/assessments
/api/v1/announcements
```

## Documentação

* ⬜ Swagger/OpenAPI
* ⬜ Documentação de endpoints
* ⬜ Exemplos de requests
* ⬜ Exemplos de responses

---

# FASE 23 — Docker

## Desenvolvimento

* ⬜ Dockerfile
* ⬜ Docker Compose
* ⬜ PHP
* ⬜ Nginx
* ⬜ MySQL
* ⬜ Redis
* ⬜ Queue Worker

## Produção

* ⬜ Dockerfile de produção
* ⬜ Nginx
* ⬜ PHP-FPM
* ⬜ Banco
* ⬜ Redis
* ⬜ Worker
* ⬜ Scheduler

---

# FASE 24 — Produção

## Servidor

* ⬜ Configurar servidor
* ⬜ PHP-FPM
* ⬜ Nginx
* ⬜ Banco
* ⬜ Redis

## Segurança

* ⬜ HTTPS
* ⬜ SSL
* ⬜ Firewall
* ⬜ Usuário de deploy
* ⬜ Variáveis de ambiente

## Backup

* ⬜ Backup do banco
* ⬜ Backup dos arquivos
* ⬜ Backup automático
* ⬜ Política de retenção
* ⬜ Teste de restauração

## Monitoramento

* ⬜ Logs
* ⬜ Erros
* ⬜ Performance
* ⬜ Disponibilidade
* ⬜ Alertas

---

# FASE 25 — CI/CD

* ⬜ GitHub Actions
* ⬜ Instalação de dependências
* ⬜ Testes automatizados
* ⬜ Pint
* ⬜ Build frontend
* ⬜ Deploy automático
* ⬜ Deploy staging
* ⬜ Deploy production

---

# 📱 Aplicativo Mobile

Etapa futura.

## Funcionalidades

* ⬜ Login
* ⬜ Perfil
* ⬜ Plano
* ⬜ Matrícula
* ⬜ Financeiro
* ⬜ Treino
* ⬜ Exercícios
* ⬜ Presença
* ⬜ Avaliação
* ⬜ Comunicados
* ⬜ Push Notifications

---

# 🔌 Integrações Futuras

## Pagamentos

* ⬜ Gateway de pagamento
* ⬜ PIX
* ⬜ Cartão
* ⬜ Boleto
* ⬜ Webhooks

## Comunicação

* ⬜ E-mail
* ⬜ WhatsApp
* ⬜ SMS
* ⬜ Push Notification

## Academia

* ⬜ QR Code
* ⬜ Biometria
* ⬜ Catraça
* ⬜ Leitor de cartão

---

# 📊 Dashboard Final

O objetivo final do dashboard administrativo é apresentar:

```text
┌──────────────────────────────────────────┐
│              DASHBOARD                   │
├──────────────┬──────────────┬────────────┤
│ Alunos       │ Matrículas   │ Professores│
│     350      │     312      │     12     │
├──────────────┼──────────────┼────────────┤
│ Receita      │ Despesas     │ Inadimpl.  │
│ R$ 35.500    │ R$ 12.000    │     27     │
└──────────────┴──────────────┴────────────┘
```

---

# 🔄 Fluxo Principal

```text
                 ADMINISTRADOR
                       │
                       ▼
                    UNIDADE
                       │
          ┌────────────┴────────────┐
          │                         │
          ▼                         ▼
       ALUNOS                   PROFESSORES
          │                         │
          ▼                         │
        PLANO                      │
          │                         │
          ▼                         │
      MATRÍCULA                     │
          │                         │
     ┌────┴────┐                    │
     │         │                    │
     ▼         ▼                    ▼
 FINANCEIRO PRESENÇA             TREINOS
                                   │
                                   ▼
                              AVALIAÇÃO
```

---

# 🏋️ Fluxo do Aluno

```text
Cadastro
   ↓
Unidade
   ↓
Plano
   ↓
Matrícula
   ↓
Pagamento
   ↓
Acesso à academia
   ↓
Presença
   ↓
Avaliação física
   ↓
Ficha de treino
   ↓
Acompanhamento
   ↓
Renovação
```

---

# 💰 Fluxo Financeiro

```text
Aluno
  ↓
Plano
  ↓
Matrícula
  ↓
Mensalidade
  ↓
Vencimento
  ↓
┌───────────────┐
│               │
▼               ▼
Pago          Vencido
│               │
▼               ▼
Receita      Inadimplência
```

---

# 🏋️ Fluxo de Treino

```text
Professor
    ↓
Aluno
    ↓
Ficha
    ↓
Exercícios
    ↓
Séries
    ↓
Repetições
    ↓
Carga
    ↓
Descanso
    ↓
Acompanhamento
```

---

# 📏 Fluxo de Avaliação

```text
Aluno
  ↓
Avaliação Inicial
  ↓
Peso
Altura
IMC
Gordura
Massa muscular
Circunferências
  ↓
Treinamento
  ↓
Nova avaliação
  ↓
Comparação
  ↓
Evolução
```

---

# 🧑‍💻 Convenção de Desenvolvimento

## Controllers

Utilizar Controllers específicos por domínio:

```text
UnitController
StudentController
TeacherController
PlanController
EnrollmentController
FinancialTransactionController
AttendanceController
ExerciseController
WorkoutPlanController
PhysicalAssessmentController
AnnouncementController
```

---

# 📝 Form Requests

Validações deverão ser separadas em Form Requests.

Exemplo:

```text
StoreUnitRequest
UpdateUnitRequest

StoreStudentRequest
UpdateStudentRequest

StoreTeacherRequest
UpdateTeacherRequest

StorePlanRequest
UpdatePlanRequest
```

---

# 🛡️ Policies

Cada domínio deverá possuir autorização específica.

Exemplo:

```text
UnitPolicy
StudentPolicy
TeacherPolicy
PlanPolicy
EnrollmentPolicy
FinancialTransactionPolicy
AttendancePolicy
ExercisePolicy
WorkoutPlanPolicy
PhysicalAssessmentPolicy
AnnouncementPolicy
```

---

# 🧱 Services

Services deverão ser utilizados quando houver regras de negócio complexas.

Exemplo:

```text
EnrollmentService
FinancialService
AttendanceService
WorkoutService
PhysicalAssessmentService
NotificationService
ReportService
```

---

# 🗂️ Organização do Código

A arquitetura deverá evoluir para:

```text
app/
│
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
│
├── Models/
│
├── Policies/
│
├── Services/
│
├── Actions/
│
└── View/
```

---

# 🌱 Git Flow

Branches principais:

```text
main
develop
```

Branches de desenvolvimento:

```text
feature/
fix/
refactor/
test/
docs/
chore/
```

Exemplos:

```bash
git checkout -b feature/unit-crud
```

```bash
git checkout -b feature/student-crud
```

```bash
git checkout -b feature/enrollment
```

---

# 📝 Conventional Commits

Exemplos:

```bash
feat: adiciona cadastro de alunos
```

```bash
feat: adiciona crud de unidades
```

```bash
feat: adiciona controle de matrículas
```

```bash
fix: corrige validação de matrícula
```

```bash
refactor: reorganiza serviço financeiro
```

```bash
test: adiciona testes para alunos
```

```bash
docs: atualiza roadmap
```

---

# 📋 Próxima Sequência de Desenvolvimento

A sequência recomendada para implementação é:

```text
1. Controle de Roles
        ↓
2. Policies e autorização
        ↓
3. CRUD de Unidades
        ↓
4. CRUD de Alunos
        ↓
5. CRUD de Professores
        ↓
6. CRUD de Planos
        ↓
7. CRUD de Matrículas
        ↓
8. Financeiro
        ↓
9. Presença
        ↓
10. Exercícios
        ↓
11. Fichas de Treino
        ↓
12. Avaliações
        ↓
13. Comunicados
        ↓
14. Dashboard Administrativo
        ↓
15. Dashboard Professor
        ↓
16. Área do Aluno
        ↓
17. Relatórios
        ↓
18. Notificações
        ↓
19. Testes
        ↓
20. API
        ↓
21. Docker
        ↓
22. CI/CD
        ↓
23. Produção
```

---

# 📊 Status Atual do Projeto

## Fundação

```text
🟩 Laravel
🟩 PHP 8.3+
🟩 Composer
🟩 NPM
🟩 Vite
🟩 Tailwind
🟩 Alpine.js
🟩 Laravel Breeze
🟩 Autenticação
🟩 Perfil
🟩 Dashboard inicial
```

## Banco

```text
🟩 Users
🟩 Roles — estrutura inicial
🟩 Units
🟩 Student Profiles
🟩 Teacher Profiles
🟩 Plans
🟩 Enrollments
🟩 Financial Transactions
🟩 Attendances
🟩 Exercises
🟩 Workout Plans
🟩 Workout Exercises
🟩 Physical Assessments
🟩 Announcements
```

## Backend de domínio

```text
⬜ UnitController
⬜ StudentController
⬜ TeacherController
⬜ PlanController
⬜ EnrollmentController
⬜ FinancialTransactionController
⬜ AttendanceController
⬜ ExerciseController
⬜ WorkoutPlanController
⬜ PhysicalAssessmentController
⬜ AnnouncementController
```

## Requests

```text
⬜ Unit Requests
⬜ Student Requests
⬜ Teacher Requests
⬜ Plan Requests
⬜ Enrollment Requests
⬜ Financial Requests
⬜ Attendance Requests
⬜ Exercise Requests
⬜ Workout Requests
⬜ Assessment Requests
⬜ Announcement Requests
```

## Interface

```text
🟩 Login
🟩 Registro
🟩 Perfil
🟨 Dashboard inicial

⬜ Dashboard Administrativo
⬜ Gestão de Unidades
⬜ Gestão de Alunos
⬜ Gestão de Professores
⬜ Gestão de Planos
⬜ Gestão de Matrículas
⬜ Gestão Financeira
⬜ Gestão de Presença
⬜ Gestão de Exercícios
⬜ Gestão de Treinos
⬜ Avaliações
⬜ Comunicados
⬜ Dashboard Professor
⬜ Área do Aluno
⬜ Relatórios
```

## Segurança

```text
🟩 Autenticação
🟩 Middleware Auth
🟨 Role
⬜ Policies
⬜ Gates
⬜ Permissões
⬜ Auditoria
```

## Testes

```text
🟨 Estrutura inicial
⬜ Testes de unidades
⬜ Testes de alunos
⬜ Testes de professores
⬜ Testes de planos
⬜ Testes de matrículas
⬜ Testes financeiros
⬜ Testes de presença
⬜ Testes de exercícios
⬜ Testes de treinos
⬜ Testes de avaliações
⬜ Testes de autorização
```

## Infraestrutura

```text
⬜ Docker
⬜ Redis
⬜ Queue Worker em produção
⬜ CI/CD
⬜ Deploy
⬜ HTTPS
⬜ Backup
⬜ Monitoramento
```

---

# 📈 Progresso Conceitual

```text
Fundação             🟩
Autenticação         🟩
Modelagem            🟩
Migrations           🟩
Roles                🟨
Unidades             🟨
Alunos               🟨
Professores          🟨
Planos               🟨
Matrículas           🟨
Financeiro           🟨
Presença             🟨
Exercícios           🟨
Treinos              🟨
Avaliações           🟨
Comunicados          🟨
Controllers          ⬜
Requests             ⬜
CRUDs                ⬜
Interfaces           ⬜
Dashboards           🟨
Relatórios           ⬜
Notificações         ⬜
Testes               🟨
API                  ⬜
Docker               ⬜
CI/CD                ⬜
Produção             ⬜
```

---

# 📚 Documentação Futura

* ⬜ Documentação da arquitetura
* ⬜ Documentação do banco
* ⬜ Diagrama ER
* ⬜ Diagrama de arquitetura
* ⬜ Documentação da API
* ⬜ Swagger/OpenAPI
* ⬜ Manual administrativo
* ⬜ Manual do professor
* ⬜ Manual do aluno
* ⬜ Manual de instalação
* ⬜ Manual de deploy

---

# 🐳 Docker — Planejamento

Arquitetura futura:

```text
                    Docker Compose
                          │
        ┌─────────────────┼─────────────────┐
        │                 │                 │
        ▼                 ▼                 ▼
      Nginx              PHP             MySQL
        │                 │
        │                 ├── Laravel
        │                 └── Queue
        │
        └───────────────────────────────────
                          │
                        Redis
```

---

# ☁️ Produção — Planejamento

```text
Internet
    │
    ▼
 HTTPS
    │
    ▼
 Nginx
    │
    ▼
 Laravel
    │
 ┌──┼───────────┐
 │  │           │
 ▼  ▼           ▼
DB Redis      Queue
```

---

# 🔒 Boas Práticas

O desenvolvimento seguirá os seguintes princípios:

* Clean Code
* SOLID
* DRY
* KISS
* Separation of Concerns
* Single Responsibility
* Form Requests
* Policies
* Services
* Eloquent Relationships
* Validação
* Testes automatizados
* Controle de acesso
* Logs
* Tratamento de exceções

---

# 📦 Comandos Úteis

## Laravel

```bash
php artisan serve
```

```bash
php artisan migrate
```

```bash
php artisan migrate:fresh
```

```bash
php artisan migrate:fresh --seed
```

```bash
php artisan route:list
```

```bash
php artisan optimize:clear
```

```bash
php artisan make:model ModelName -m
```

```bash
php artisan make:controller ControllerName
```

```bash
php artisan make:request RequestName
```

```bash
php artisan make:policy PolicyName
```

---

# 🎨 Frontend

```bash
npm install
```

```bash
npm run dev
```

```bash
npm run build
```

---

# 🧪 Qualidade

```bash
php artisan test
```

```bash
./vendor/bin/pest
```

```bash
./vendor/bin/pint
```

---

# 🏆 Objetivo Final

O objetivo do UpSoluctions Gym é chegar a uma plataforma capaz de controlar integralmente uma academia:

```text
                    UPSOLUCTIONS GYM
                           │
        ┌──────────────────┼──────────────────┐
        │                  │                  │
        ▼                  ▼                  ▼
   ADMINISTRAÇÃO       PROFESSORES          ALUNOS
        │                  │                  │
        ▼                  ▼                  ▼
     UNIDADES            TREINOS             PERFIL
     USUÁRIOS            ALUNOS              PLANO
     PLANOS              AVALIAÇÕES          MATRÍCULA
     MATRÍCULAS          PRESENÇA             TREINO
     FINANCEIRO                               FINANCEIRO
     RELATÓRIOS
        │
        └──────────────────┬─────────────────┘
                           ▼
                       DATABASE
```

---

# 🎯 Resultado Esperado

Ao final do projeto, o administrador deverá conseguir:

```text
Cadastrar unidade
       ↓
Cadastrar usuário
       ↓
Cadastrar aluno
       ↓
Cadastrar professor
       ↓
Cadastrar plano
       ↓
Criar matrícula
       ↓
Controlar mensalidade
       ↓
Registrar presença
       ↓
Criar treino
       ↓
Realizar avaliação
       ↓
Acompanhar evolução
       ↓
Gerar relatórios
```

O professor deverá conseguir:

```text
Visualizar alunos
       ↓
Criar fichas
       ↓
Adicionar exercícios
       ↓
Definir séries/repetições/carga
       ↓
Realizar avaliações
       ↓
Acompanhar evolução
```

E o aluno deverá conseguir:

```text
Acessar sistema
       ↓
Visualizar perfil
       ↓
Visualizar plano
       ↓
Consultar matrícula
       ↓
Consultar pagamentos
       ↓
Visualizar treino
       ↓
Visualizar exercícios
       ↓
Consultar avaliações
       ↓
Consultar frequência
```

---

# 📄 Licença

Este projeto está licenciado sob a licença MIT.

Consulte:

```text
LICENSE
```

para mais informações.

---

# 👨‍💻 Desenvolvedor

**Filipe Silva**

Projeto desenvolvido para estudo, evolução profissional e aplicação prática de conceitos de:

* PHP
* Laravel
* Backend
* Banco de Dados
* Arquitetura
* Autenticação
* Autorização
* APIs
* Testes
* DevOps

---

# ⭐ UpSoluctions Gym

> **Sistema completo de gerenciamento para academias.**

```text
Laravel
+
PHP
+
MySQL
+
Blade
+
Tailwind
+
Alpine.js
=
UpSoluctions Gym
```

---

## 🚧 Projeto em Desenvolvimento

O projeto está sendo desenvolvido de forma incremental.

As marcações do roadmap representam o estado atual de cada tarefa e deverão ser atualizadas conforme novas funcionalidades forem implementadas.

### Legenda final

```text
🟩 Concluído
🟨 Iniciado / Em desenvolvimento
⬜ Não iniciado
```

---

**UpSoluctions Gym — Gestão inteligente para academias. 🏋️**
