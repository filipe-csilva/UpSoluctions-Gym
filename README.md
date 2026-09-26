# ðŸ‹ï¸ UpSoluctions Gym

Sistema web de **gestÃ£o de academias e centros de treinamento**, desenvolvido com Laravel.

O objetivo Ã© centralizar o gerenciamento de **unidades, usuÃ¡rios, alunos, professores, planos, matrÃ­culas, financeiro, presenÃ§a, exercÃ­cios, treinos, avaliaÃ§Ãµes fÃ­sicas e comunicados**.

> ðŸš§ **Status:** Em desenvolvimento ativo.

## ðŸ“Œ Legenda

| Status | Significado                   |
| ------ | ----------------------------- |
| ðŸŸ©     | ConcluÃ­do                     |
| ðŸŸ¨     | Iniciado / Em desenvolvimento |
| â¬œ      | NÃ£o iniciado                  |

> A existÃªncia de um Model ou Migration nÃ£o significa que o mÃ³dulo esteja concluÃ­do.

---

# ðŸ› ï¸ Stack

### Backend

* PHP 8.3+
* Laravel 13
* Laravel Breeze
* Laravel Tinker

### Frontend

* Blade
* Bootstrap / AdminLTE
* Tailwind CSS (dependÃªncia disponÃ­vel)
* Alpine.js
* Vite

### Qualidade

* Pest
* PHPUnit
* Laravel Pint

### Banco

* MySQL

---

# ðŸ“‚ Estrutura

```text
app/
â”œâ”€â”€ Http/
â”‚   â”œâ”€â”€ Controllers/
â”‚   â”œâ”€â”€ Middleware/
â”‚   â””â”€â”€ Requests/
â”œâ”€â”€ Models/
â””â”€â”€ View/

database/
â”œâ”€â”€ factories/
â”œâ”€â”€ migrations/
â””â”€â”€ seeders/

resources/
â”œâ”€â”€ css/
â”œâ”€â”€ js/
â””â”€â”€ views/

routes/
â”œâ”€â”€ web.php
â””â”€â”€ auth.php

tests/
â”œâ”€â”€ Feature/
â””â”€â”€ Unit/
```

---

# ðŸ—ƒï¸ DomÃ­nio

O projeto jÃ¡ possui Models e migrations para:

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

Os mÃ³dulos de domÃ­nio ainda precisam evoluir para CRUDs completos, regras de negÃ³cio, interfaces e testes.

---

# ðŸ” AutenticaÃ§Ã£o

A autenticaÃ§Ã£o inicial utiliza Laravel Breeze.

* ðŸŸ© Login
* ðŸŸ© Logout
* ðŸŸ© Registro
* ðŸŸ© RecuperaÃ§Ã£o de senha
* ðŸŸ© VerificaÃ§Ã£o de e-mail
* ðŸŸ© Perfil
* ðŸŸ© ProteÃ§Ã£o de rotas
* ðŸŸ¨ Roles
* â¬œ PermissÃµes granulares
* â¬œ Policies
* â¬œ Gates

A rota `/` direciona para o login e `/dashboard` Ã© protegida por autenticaÃ§Ã£o.

---

# ðŸš€ InstalaÃ§Ã£o

## 1. Clonar

```bash
git clone https://github.com/filipe-csilva/UpSoluctions-Gym.git
cd UpSoluctions-Gym
```

## 2. DependÃªncias

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

AplicaÃ§Ã£o:

```text
http://127.0.0.1:8000
```

---

# ðŸ§ª Comandos

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

> âš ï¸ `migrate:fresh` apaga as tabelas existentes.

---

# ðŸ—ºï¸ Roadmap

## Fase 01 â€” FundaÃ§Ã£o

* ðŸŸ© Projeto Laravel
* ðŸŸ© Composer / NPM
* ðŸŸ© Vite
* ðŸŸ© Tailwind CSS
* ðŸŸ© Alpine.js
* ðŸŸ© Laravel Breeze
* ðŸŸ© AutenticaÃ§Ã£o
* ðŸŸ© Perfil
* ðŸŸ© Estrutura inicial do banco

---

## Fase 02 â€” UsuÃ¡rios e Acesso

* ðŸŸ¨ Roles
* ðŸŸ© Middleware de autorizaÃ§Ã£o
* â¬œ Policies
* ðŸŸ© Gates
* â¬œ PermissÃµes por mÃ³dulo
* ðŸŸ© PermissÃµes por unidade
* â¬œ CRUD de usuÃ¡rios

**Perfis:**

```text
Administrador
Gestor
Professor
Aluno
```

---

## Fase 03 â€” Unidades

* ðŸŸ© Model
* ðŸŸ© Migration
* ðŸŸ¨ Relacionamentos
* ðŸŸ© Controller
* ðŸŸ© Requests
* ðŸŸ© Rotas
* ðŸŸ© CRUD
* ðŸŸ© AtivaÃ§Ã£o/InativaÃ§Ã£o
* ðŸŸ© Interface

---

## Fase 04 â€” Alunos

* ðŸŸ© Model
* ðŸŸ© Migration
* ðŸŸ¨ Relacionamentos
* ðŸŸ© Controller
* ðŸŸ© Requests/validaÃ§Ãµes
* ðŸŸ© Listagem paginada
* ðŸŸ© Cadastro
* ðŸŸ© Tela de detalhes
* ðŸŸ© EdiÃ§Ã£o do cadastro
* ðŸŸ© Seeder com 10 alunos
* ðŸŸ© HistÃ³rico

---

## Fase 05 â€” Professores

* ðŸŸ© Model
* ðŸŸ© Migration
* ðŸŸ¨ Relacionamentos
* ðŸŸ© Controller
* ðŸŸ© Requests e validaÃ§Ãµes
* ðŸŸ© Listagem paginada com filtros
* ðŸŸ© Cadastro
* ðŸŸ© Tela de detalhes
* ðŸŸ© EdiÃ§Ã£o do cadastro
* ðŸŸ© ExclusÃ£o lÃ³gica com registro no log
* â¬œ Alunos vinculados
* ðŸŸ© Interface
* ðŸŸ© Seeder com 5 instrutores distribuÃ­dos em 2 unidades

---

## Fase 06 â€” Planos

* ðŸŸ© Model
* ðŸŸ© Migration
* ðŸŸ¨ Relacionamentos
* ðŸŸ© Controller
* ðŸŸ© Requests e validaÃ§Ãµes
* ðŸŸ© CRUD
* ðŸŸ© Valores e periodicidade
* ðŸŸ© Interface

---

## Fase 07 â€” MatrÃ­culas

* ðŸŸ© Model
* ðŸŸ© Migration
* ðŸŸ© Relacionamentos
* ðŸŸ© Controller
* ðŸŸ© Requests
* ðŸŸ© Criar matrÃ­cula
* ðŸŸ¨ Renovar
* ðŸŸ© Suspender
* ðŸŸ© Cancelar
* ðŸŸ© Alterar plano
* â¬œ HistÃ³rico

---

## Fase 08 â€” Financeiro

* ðŸŸ© Model
* ðŸŸ© Migration
* ðŸŸ© Relacionamentos
* ðŸŸ© Controller
* ðŸŸ© Requests
* ðŸŸ© Receitas
* ðŸŸ© Despesas
* ðŸŸ© Pagamentos
* ðŸŸ© InadimplÃªncia
* â¬œ HistÃ³rico
* ðŸŸ© Dashboard financeiro

---

## Fase 09 â€” PresenÃ§a

* ðŸŸ© Model
* ðŸŸ© Migration
* ðŸŸ¨ Relacionamentos
* ðŸŸ© Controller
* ðŸŸ© Registro de entrada/saÃ­da
* ðŸŸ© HistÃ³rico
* â¬œ FrequÃªncia
* â¬œ RelatÃ³rios

**Futuro:**

* â¬œ QR Code
* â¬œ Biometria
* â¬œ IntegraÃ§Ã£o com catracas

---

## Fase 10 â€” ExercÃ­cios

* ðŸŸ© Model
* ðŸŸ© Migration
* ðŸŸ© Controller
* ðŸŸ© Requests
* ðŸŸ© CRUD
* ðŸŸ© Grupo muscular
* ðŸŸ© Equipamento
* ðŸŸ© InstruÃ§Ãµes
* â¬œ Imagens/vÃ­deos

---

## Fase 11 â€” Fichas de Treino

* ðŸŸ© Models
* ðŸŸ© Migrations
* ðŸŸ¨ Relacionamentos
* ðŸŸ© Controllers
* ðŸŸ© Requests
* ðŸŸ© Criar ficha
* ðŸŸ© Adicionar exercÃ­cios
* ðŸŸ© SÃ©ries
* ðŸŸ© RepetiÃ§Ãµes
* ðŸŸ© Carga
* ðŸŸ© Descanso
* â¬œ HistÃ³rico

---

## Fase 12 â€” AvaliaÃ§Ã£o FÃ­sica

* ðŸŸ© Model
* ðŸŸ© Migration
* ðŸŸ¨ Relacionamentos
* ðŸŸ© Controller
* ðŸŸ© Requests
* ðŸŸ© Cadastro
* ðŸŸ© HistÃ³rico
* â¬œ ComparaÃ§Ã£o
* â¬œ EvoluÃ§Ã£o
* â¬œ GrÃ¡ficos

---

## Fase 13 â€” Comunicados

* ðŸŸ© Model
* ðŸŸ© Migration
* ðŸŸ© Relacionamentos
* ðŸŸ© Controller
* ðŸŸ© Requests
* ðŸŸ© CRUD
* ðŸŸ© PublicaÃ§Ã£o por perÃ­odo e status
* ðŸŸ© DestinatÃ¡rios por perfil e unidade
* ðŸŸ© Interface

---

## Fase 14 â€” Dashboards

### Administrador

#### Tasks concluÃ­das nesta atualizaÃ§Ã£o

* Dashboard baseado no layout adotado do AdminLTE.
* EvoluÃ§Ã£o de alunos com grÃ¡fico em linha e filtros de perÃ­odo.
* DistribuiÃ§Ã£o de alunos por unidade com grÃ¡fico pizza.
* Atividades recentes no dashboard.
* Perfil com avatar, iniciais e abas de senha e sessÃµes.
* Registro de login e logout com informaÃ§Ãµes de dispositivo, navegador e IP.

* ðŸŸ© Dashboard inicial baseado no `demo/dashboard-v2` do AdminLTE
* ðŸŸ© Total de alunos e instrutores
* ðŸŸ© Lista dos 6 alunos mais recentes cadastrados no dia
* ðŸŸ© Lista de atÃ© 6 instrutores presentes
* ðŸŸ© Alunos ativos/inativos no dashboard
* ðŸŸ© MatrÃ­culas ativas no dashboard
* ðŸŸ© Receita mensal com pagamentos recebidos
* ðŸŸ© Despesas
* ðŸŸ© SaÃºde financeira com receita versus despesas
* ðŸŸ© Ponto de equilÃ­brio financeiro
* â¬œ PresenÃ§a
* ðŸŸ© Indicadores por unidade
* ðŸŸ© GrÃ¡ficos
* ðŸŸ© Cores dos cards nos modos claro e dark
* ðŸŸ© PopulaÃ§Ã£o de teste com planos, matrÃ­culas e lanÃ§amentos financeiros
* ðŸŸ© PrÃ³ximos vencimentos financeiros
* ðŸŸ© PrÃ³ximos vencimentos de contas a pagar e contas a receber
* ðŸŸ© Atalhos para o financeiro com filtros especÃ­ficos
* ðŸŸ© Filtros independentes de perÃ­odo para alunos e finanÃ§as

### Professor

* â¬œ Meus alunos
* â¬œ Treinos
* â¬œ AvaliaÃ§Ãµes
* â¬œ FrequÃªncia

### Aluno

* â¬œ Perfil
* â¬œ Plano
* â¬œ MatrÃ­cula
* â¬œ Financeiro
* â¬œ Treino
* â¬œ AvaliaÃ§Ãµes
* â¬œ FrequÃªncia

---

## Fase 15 â€” RelatÃ³rios

* ðŸŸ¨ Alunos, matrÃ­culas e presenÃ§a
* ðŸŸ¨ Financeiro e inadimplÃªncia
* ðŸŸ¨ Resumo por unidade
* ðŸŸ© ExportaÃ§Ã£o CSV financeira
* ðŸŸ¨ ExportaÃ§Ã£o PDF para visualizaÃ§Ã£o e impressÃ£o
* â¬œ ExportaÃ§Ã£o Excel

---

## Fase 16 â€” NotificaÃ§Ãµes

* â¬œ NotificaÃ§Ãµes internas
* â¬œ Mensalidade prÃ³xima do vencimento
* â¬œ Mensalidade vencida
* â¬œ MatrÃ­cula vencendo
* â¬œ Nova ficha de treino
* â¬œ Nova avaliaÃ§Ã£o
* â¬œ Comunicados
* â¬œ E-mail
* â¬œ WhatsApp
* â¬œ Push

---

## Fase 17 â€” Testes

* ðŸŸ¨ Estrutura inicial
* â¬œ Testes de usuÃ¡rios
* â¬œ Testes de unidades
* â¬œ Testes de alunos
* â¬œ Testes de professores
* â¬œ Testes de planos
* â¬œ Testes de matrÃ­culas
* â¬œ Testes financeiros
* â¬œ Testes de presenÃ§a
* â¬œ Testes de treinos
* â¬œ Testes de avaliaÃ§Ãµes
* â¬œ Testes de autorizaÃ§Ã£o

---

## Fase 18 â€” API

* â¬œ API REST
* â¬œ Versionamento
* â¬œ AutenticaÃ§Ã£o
* â¬œ API Resources
* â¬œ Endpoints dos mÃ³dulos
* â¬œ Swagger/OpenAPI

---

## Fase 19 â€” Performance e Infraestrutura

* â¬œ OtimizaÃ§Ã£o de queries
* â¬œ Ãndices
* â¬œ Cache
* â¬œ Redis
* â¬œ Jobs
* â¬œ Filas
* â¬œ Docker
* â¬œ CI/CD

---

## Fase 20 â€” ProduÃ§Ã£o

* â¬œ Servidor
* â¬œ Nginx
* â¬œ PHP-FPM
* â¬œ HTTPS
* â¬œ Backup
* â¬œ Logs
* â¬œ Monitoramento
* â¬œ Deploy automatizado

---

# ðŸ“Š Estado Atual

| Ãrea              | Status |
| ----------------- | ------ |
| FundaÃ§Ã£o          | ðŸŸ©     |
| AutenticaÃ§Ã£o      | ðŸŸ©     |
| Modelagem         | ðŸŸ©     |
| Migrations        | ðŸŸ©     |
| UsuÃ¡rios / Roles  | ðŸŸ¨     |
| Unidades          | ðŸŸ©     |
| Alunos            | ðŸŸ©     |
| Professores       | ðŸŸ¨     |
| Planos            | ðŸŸ¨     |
| MatrÃ­culas        | ðŸŸ¨     |
| Financeiro        | ðŸŸ¨     |
| PresenÃ§a          | ðŸŸ¨     |
| ExercÃ­cios        | ðŸŸ¨     |
| Treinos           | ðŸŸ¨     |
| AvaliaÃ§Ãµes        | ðŸŸ¨     |
| Comunicados       | ðŸŸ©     |
| CRUDs de domÃ­nio  | ðŸŸ¨      |
| Dashboards        | ðŸŸ©     |
| Ãrea do aluno     | â¬œ      |
| RelatÃ³rios        | â¬œ      |
| NotificaÃ§Ãµes      | â¬œ      |
| Testes de domÃ­nio | â¬œ      |
| API               | â¬œ      |
| Docker / CI/CD    | â¬œ      |
| ProduÃ§Ã£o          | â¬œ      |

---

# ðŸŽ¯ PrÃ³xima Prioridade

A prÃ³xima sequÃªncia recomendada Ã©:

```text
1. Despesas e inadimplÃªncia no dashboard
        â†“
2. RelatÃ³rios operacionais
        â†“
3. NotificaÃ§Ãµes internas e de vencimento
        â†“
4. Testes de domÃ­nio e autorizaÃ§Ã£o
        â†“
5. API REST
        â†“
6. Performance, CI/CD e produÃ§Ã£o
        â†“
```

---

# ðŸ‘¨â€ðŸ’» Desenvolvedor

**Filipe Silva**

Projeto desenvolvido para aplicaÃ§Ã£o prÃ¡tica de:

* PHP
* Laravel
* Backend
* Banco de Dados
* Arquitetura
* AutenticaÃ§Ã£o
* AutorizaÃ§Ã£o
* Testes
* DevOps

---

# ðŸ“„ LicenÃ§a

Este projeto estÃ¡ licenciado sob a licenÃ§a **MIT**.

Consulte o arquivo `LICENSE` para mais informaÃ§Ãµes.

---

**UpSoluctions Gym â€” GestÃ£o inteligente para academias. ðŸ‹ï¸**

## ImplementaÃ§Ã£o concluÃ­da - autorizaÃ§Ã£o, usuÃ¡rios e acompanhamento

Itens implementados nesta etapa:

* Policies formais para alunos, instrutores, matrÃ­culas, presenÃ§as e usuÃ¡rios.
* Gates granulares por mÃ³dulo e configuraÃ§Ãµes exclusivas do administrador.
* CRUD completo de usuÃ¡rios com perfil, unidade, status e senha.
* HistÃ³rico completo de matrÃ­culas por aluno.
* Listagem de alunos vinculados ao instrutor por fichas de treino.
* HistÃ³rico detalhado de frequÃªncia por aluno com filtros de perÃ­odo.
* RelatÃ³rio de presenÃ§a disponÃ­vel na Ã¡rea de relatÃ³rios.
* Testes de autorizaÃ§Ã£o e gerenciamento administrativo.


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

