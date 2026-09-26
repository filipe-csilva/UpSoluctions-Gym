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
* Bootstrap / AdminLTE
* Tailwind CSS (dependência disponível)
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
* 🟩 Middleware de autorização
* ⬜ Policies
* 🟩 Gates
* ⬜ Permissões por módulo
* 🟩 Permissões por unidade
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
* 🟩 Controller
* 🟩 Requests
* 🟩 Rotas
* 🟩 CRUD
* 🟩 Ativação/Inativação
* 🟩 Interface

---

## Fase 04 — Alunos

* 🟩 Model
* 🟩 Migration
* 🟨 Relacionamentos
* 🟩 Controller
* 🟩 Requests/validações
* 🟩 Listagem paginada
* 🟩 Cadastro
* 🟩 Tela de detalhes
* 🟩 Edição do cadastro
* 🟩 Seeder com 10 alunos
* 🟩 Histórico

---

## Fase 05 — Professores

* 🟩 Model
* 🟩 Migration
* 🟨 Relacionamentos
* 🟩 Controller
* 🟩 Requests e validações
* 🟩 Listagem paginada com filtros
* 🟩 Cadastro
* 🟩 Tela de detalhes
* 🟩 Edição do cadastro
* 🟩 Exclusão lógica com registro no log
* ⬜ Alunos vinculados
* 🟩 Interface
* 🟩 Seeder com 5 instrutores distribuídos em 2 unidades

---

## Fase 06 — Planos

* 🟩 Model
* 🟩 Migration
* 🟨 Relacionamentos
* 🟩 Controller
* 🟩 Requests e validações
* 🟩 CRUD
* 🟩 Valores e periodicidade
* 🟩 Interface

---

## Fase 07 — Matrículas

* 🟩 Model
* 🟩 Migration
* 🟩 Relacionamentos
* 🟩 Controller
* 🟩 Requests
* 🟩 Criar matrícula
* 🟨 Renovar
* 🟩 Suspender
* 🟩 Cancelar
* 🟩 Alterar plano
* ⬜ Histórico

---

## Fase 08 — Financeiro

* 🟩 Model
* 🟩 Migration
* 🟩 Relacionamentos
* 🟩 Controller
* 🟩 Requests
* 🟩 Receitas
* 🟩 Despesas
* 🟩 Pagamentos
* 🟩 Inadimplência
* ⬜ Histórico
* 🟩 Dashboard financeiro

---

## Fase 09 — Presença

* 🟩 Model
* 🟩 Migration
* 🟨 Relacionamentos
* 🟩 Controller
* 🟩 Registro de entrada/saída
* 🟩 Histórico
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
* 🟩 Controller
* 🟩 Requests
* 🟩 CRUD
* 🟩 Grupo muscular
* 🟩 Equipamento
* 🟩 Instruções
* ⬜ Imagens/vídeos

---

## Fase 11 — Fichas de Treino

* 🟩 Models
* 🟩 Migrations
* 🟨 Relacionamentos
* 🟩 Controllers
* 🟩 Requests
* 🟩 Criar ficha
* 🟩 Adicionar exercícios
* 🟩 Séries
* 🟩 Repetições
* 🟩 Carga
* 🟩 Descanso
* ⬜ Histórico

---

## Fase 12 — Avaliação Física

* 🟩 Model
* 🟩 Migration
* 🟨 Relacionamentos
* 🟩 Controller
* 🟩 Requests
* 🟩 Cadastro
* 🟩 Histórico
* ⬜ Comparação
* ⬜ Evolução
* ⬜ Gráficos

---

## Fase 13 — Comunicados

* 🟩 Model
* 🟩 Migration
* 🟩 Relacionamentos
* 🟩 Controller
* 🟩 Requests
* 🟩 CRUD
* 🟩 Publicação por período e status
* 🟩 Destinatários por perfil e unidade
* 🟩 Interface

---

## Fase 14 — Dashboards

### Administrador

#### Tasks concluídas nesta atualização

* Dashboard baseado no layout adotado do AdminLTE.
* Evolução de alunos com gráfico em linha e filtros de período.
* Distribuição de alunos por unidade com gráfico pizza.
* Atividades recentes no dashboard.
* Perfil com avatar, iniciais e abas de senha e sessões.
* Registro de login e logout com informações de dispositivo, navegador e IP.

* 🟩 Dashboard inicial baseado no `demo/dashboard-v2` do AdminLTE
* 🟩 Total de alunos e instrutores
* 🟩 Lista dos 6 alunos mais recentes cadastrados no dia
* 🟩 Lista de até 6 instrutores presentes
* 🟩 Alunos ativos/inativos no dashboard
* 🟩 Matrículas ativas no dashboard
* 🟩 Receita mensal com pagamentos recebidos
* 🟩 Despesas
* 🟩 Saúde financeira com receita versus despesas
* 🟩 Ponto de equilíbrio financeiro
* ⬜ Presença
* 🟩 Indicadores por unidade
* 🟩 Gráficos
* 🟩 Cores dos cards nos modos claro e dark
* 🟩 População de teste com planos, matrículas e lançamentos financeiros
* 🟩 Próximos vencimentos financeiros
* 🟩 Próximos vencimentos de contas a pagar e contas a receber
* 🟩 Atalhos para o financeiro com filtros específicos
* 🟩 Filtros independentes de período para alunos e finanças

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

* 🟨 Alunos, matrículas e presença
* 🟨 Financeiro e inadimplência
* 🟨 Resumo por unidade
* 🟩 Exportação CSV financeira
* 🟨 Exportação PDF para visualização e impressão
* ⬜ Exportação Excel

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
| Unidades          | 🟩     |
| Alunos            | 🟩     |
| Professores       | 🟨     |
| Planos            | 🟨     |
| Matrículas        | 🟨     |
| Financeiro        | 🟨     |
| Presença          | 🟨     |
| Exercícios        | 🟨     |
| Treinos           | 🟨     |
| Avaliações        | 🟨     |
| Comunicados       | 🟩     |
| CRUDs de domínio  | 🟨      |
| Dashboards        | 🟩     |
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
1. Despesas e inadimplência no dashboard
        ↓
2. Relatórios operacionais
        ↓
3. Notificações internas e de vencimento
        ↓
4. Testes de domínio e autorização
        ↓
5. API REST
        ↓
6. Performance, CI/CD e produção
        ↓
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

## Implementação concluída - autorização, usuários e acompanhamento

Itens implementados nesta etapa:

* Policies formais para alunos, instrutores, matrículas, presenças e usuários.
* Gates granulares por módulo e configurações exclusivas do administrador.
* CRUD completo de usuários com perfil, unidade, status e senha.
* Histórico completo de matrículas por aluno.
* Listagem de alunos vinculados ao instrutor por fichas de treino.
* Histórico detalhado de frequência por aluno com filtros de período.
* Relatório de presença disponível na área de relatórios.
* Testes de autorização e gerenciamento administrativo.

---

# Tasks atualizadas

> Esta seção complementa o roadmap histórico acima e representa o estado atual do projeto.

## Concluído

- [x] Configurações gerais exclusivas do administrador persistidas no banco.
- [x] Cores, botões, ícones, logo, favicon, moeda, datas e fuso horário configuráveis.
- [x] Autorização por perfil e por unidade.
- [x] Cadastro público desabilitado; a rota /register não está disponível.
- [x] Inicialização automática do banco e das migrações.
- [x] Home integrada ao painel principal.
- [x] Financeiro com auditoria, receitas, despesas, contas futuras, recebimentos, estornos, fluxo de caixa e exportação.
- [x] Geração automática de contas a receber na matrícula.
- [x] Histórico de matrículas, treinos e avaliações físicas.
- [x] Ativação e desativação automática de alunos conforme matrícula e situação financeira.
- [x] Mensagens diretas, mensagens para unidade, recepção e respostas.
- [x] Responsabilidade automática da recepção para o primeiro admin, manager ou financeiro que abrir a mensagem.
- [x] Leitura individual de mensagens e comunicados.
- [x] Restrição de mensagens e comunicados conforme a data de cadastro do aluno.
- [x] Comunicado inicial padrão disponível para alunos novos.
- [x] Notificações operacionais e jobs.
- [x] Manifesto PWA e service worker.
- [x] CI com PHP 8.4, SQLite, testes e Pint.
- [x] 70 testes e 202 asserções aprovados localmente.

## Pendências atuais

- [ ] Implementar autenticação JWT na API.
- [ ] Completar os endpoints de todos os módulos.
- [ ] Disponibilizar login, usuários, mensagens, comunicados, financeiro, relatórios e configurações na API.
- [ ] Publicar documentação Swagger/OpenAPI completa.
- [ ] Definir se o frontend consumirá exclusivamente a API.
- [ ] Ativar integrações reais de e-mail, WhatsApp e push.
- [ ] Configurar Redis, cache, monitoramento, Docker, backup e deploy automatizado.
- [ ] Executar validação manual no navegador por perfil.
- [ ] Confirmar o workflow corrigido no GitHub Actions.

