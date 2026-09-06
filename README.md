# 🏋️ UpSoluctions Gym

Sistema completo de **gerenciamento de academias**, desenvolvido para centralizar a administração de alunos, unidades, matrículas, planos, treinos, pagamentos e demais processos relacionados à gestão de uma academia.

O **UpSoluctions Gym** está sendo desenvolvido com uma arquitetura modular, permitindo sua evolução gradual desde um sistema administrativo básico até uma plataforma completa para gerenciamento de múltiplas unidades.

> 🚧 **Status:** Em desenvolvimento

---

## 📋 Sobre o Projeto

O **UpSoluctions Gym** tem como objetivo fornecer uma solução para academias que necessitam controlar suas operações administrativas e oferecer aos alunos acesso às suas próprias informações.

A plataforma será estruturada principalmente em dois grandes perfis:

* 👨‍💼 **Administrador**
* 🏋️ **Aluno**

O sistema também foi planejado para suportar a expansão para outros perfis, como:

* 👨‍🏫 Professor / Personal Trainer
* 💰 Financeiro
* 👨‍💻 Administrador do sistema
* 🏢 Gerente de unidade

---

# 🎯 Objetivos

O projeto tem como principais objetivos:

* Centralizar a administração da academia.
* Controlar alunos e matrículas.
* Gerenciar planos e mensalidades.
* Controlar pagamentos e inadimplência.
* Gerenciar múltiplas unidades.
* Criar e administrar fichas de treino.
* Permitir que alunos acompanhem seus treinos.
* Registrar evolução dos alunos.
* Disponibilizar dashboards administrativos.
* Gerar relatórios gerenciais.
* Criar uma estrutura preparada para crescimento.

---

# 👥 Perfis do Sistema

## 👨‍💼 Administrador

O administrador terá acesso aos principais recursos administrativos da plataforma.

### Dashboard

* Quantidade de alunos ativos.
* Quantidade de alunos inativos.
* Novas matrículas.
* Matrículas próximas do vencimento.
* Mensalidades pendentes.
* Receita mensal.
* Inadimplência.
* Indicadores por unidade.
* Indicadores de crescimento.

### Gestão de Alunos

* Cadastro de alunos.
* Edição de dados pessoais.
* Consulta de alunos.
* Ativação e inativação.
* Histórico do aluno.
* Documentos.
* Dados de contato.
* Informações físicas.
* Histórico de matrículas.

### Matrículas

* Criar matrícula.
* Renovar matrícula.
* Cancelar matrícula.
* Suspender matrícula.
* Transferir matrícula entre unidades.
* Histórico de matrículas.
* Controle de vencimento.
* Status da matrícula.

### Planos

* Cadastro de planos.
* Nome do plano.
* Descrição.
* Valor.
* Duração.
* Periodicidade.
* Benefícios.
* Limitações.
* Status.

Exemplos:

* Plano Mensal
* Plano Trimestral
* Plano Semestral
* Plano Anual

### Financeiro

* Mensalidades.
* Pagamentos.
* Pendências.
* Inadimplência.
* Vencimentos.
* Histórico financeiro.
* Formas de pagamento.
* Controle de receitas.
* Relatórios financeiros.

### Relatórios

* Alunos ativos.
* Alunos inativos.
* Matrículas.
* Cancelamentos.
* Receita.
* Inadimplência.
* Pagamentos.
* Crescimento de alunos.
* Desempenho das unidades.

### Gestão de Unidades

O sistema será preparado para trabalhar com múltiplas unidades.

Exemplo:

```text
Academia
├── Unidade 01
└── Unidade 02
```

Cada unidade poderá possuir:

* Alunos.
* Funcionários.
* Professores.
* Matrículas.
* Planos.
* Financeiro.
* Treinos.
* Indicadores próprios.

---

# 🏋️ Área do Aluno

Cada aluno terá acesso individual ao sistema.

### Login

* Login.
* Logout.
* Recuperação de senha.
* Verificação de e-mail.
* Controle de sessão.

### Perfil

O aluno poderá visualizar e atualizar:

* Nome.
* E-mail.
* Telefone.
* Endereço.
* Data de nascimento.
* Dados pessoais.

### Plano

O aluno poderá consultar:

* Plano atual.
* Data de início.
* Data de vencimento.
* Status.
* Valor.
* Unidade vinculada.

### Ficha de Treino

O aluno poderá visualizar sua ficha de treino.

Exemplo:

```text
Treino A
├── Supino reto
│   ├── 4 séries
│   ├── 10 repetições
│   └── 30kg
│
├── Crucifixo
│   ├── 3 séries
│   ├── 12 repetições
│   └── 15kg
│
└── Tríceps pulley
    ├── 3 séries
    ├── 12 repetições
    └── 20kg
```

### Evolução

Possíveis recursos:

* Peso.
* Altura.
* IMC.
* Medidas corporais.
* Avaliações físicas.
* Histórico de evolução.
* Fotos de evolução.
* Gráficos.

---

# 👨‍🏫 Professor / Personal Trainer

Uma futura versão poderá disponibilizar um perfil específico para professores.

### Recursos planejados

* Visualizar alunos.
* Criar fichas.
* Editar treinos.
* Prescrever exercícios.
* Definir séries.
* Definir repetições.
* Definir carga.
* Definir descanso.
* Registrar observações.
* Acompanhar evolução.

---

# 🏢 Multiunidades

O sistema foi planejado considerando academias que possuem mais de uma unidade.

### Estrutura planejada

```text
Empresa
│
├── Unidade 01
│   ├── Alunos
│   ├── Professores
│   ├── Matrículas
│   └── Financeiro
│
└── Unidade 02
    ├── Alunos
    ├── Professores
    ├── Matrículas
    └── Financeiro
```

O administrador poderá visualizar os dados de todas as unidades ou filtrar uma unidade específica.

---

# 🗺️ Roadmap

## 🟢 Fase 01 — Fundação do Projeto

* ✅ Criar projeto Laravel
* ✅ Configurar Git
* ✅ Criar repositório GitHub
* ✅ Configurar Laravel Breeze
* ✅ Configurar autenticação
* ✅ Configurar Vite
* ✅ Configurar Tailwind CSS
* ✅ Criar estrutura inicial do dashboard
* 🚧 Configurar identidade visual
* [ ] Configurar layout principal
* [ ] Criar menu lateral
* [ ] Criar navegação responsiva

---

## 🟡 Fase 02 — Usuários e Autenticação

* ✅ Login
* ✅ Logout
* ✅ Registro
* ✅ Recuperação de senha
* ✅ Verificação de e-mail
* ✅ Perfil do usuário
* [ ] Sistema de permissões
* [ ] Roles
* [ ] Policies
* [ ] Controle de acesso por perfil

### Perfis

* [ ] Administrador
* [ ] Gerente
* [ ] Professor
* [ ] Aluno
* [ ] Funcionário

---

## 🟡 Fase 03 — Gestão de Unidades

* [ ] Cadastro de unidades
* [ ] Edição de unidades
* [ ] Inativação de unidades
* [ ] Endereço
* [ ] Telefone
* [ ] Horários de funcionamento
* [ ] Responsável
* [ ] Controle de usuários por unidade

---

## 🟡 Fase 04 — Gestão de Alunos

* [ ] CRUD de alunos
* [ ] Dados pessoais
* [ ] CPF
* [ ] Data de nascimento
* [ ] Telefone
* [ ] E-mail
* [ ] Endereço
* [ ] Foto
* [ ] Status
* [ ] Histórico
* [ ] Busca avançada
* [ ] Filtros
* [ ] Paginação

---

## 🟡 Fase 05 — Planos

* [ ] CRUD de planos
* [ ] Valor
* [ ] Duração
* [ ] Periodicidade
* [ ] Benefícios
* [ ] Status
* [ ] Associação com unidades
* [ ] Histórico de alterações

---

## 🟠 Fase 06 — Matrículas

* [ ] Nova matrícula
* [ ] Renovação
* [ ] Cancelamento
* [ ] Suspensão
* [ ] Transferência
* [ ] Histórico
* [ ] Controle de vencimento
* [ ] Status automático
* [ ] Alertas de vencimento

---

## 🟠 Fase 07 — Financeiro

* [ ] Mensalidades
* [ ] Pagamentos
* [ ] Pendências
* [ ] Inadimplência
* [ ] Formas de pagamento
* [ ] Controle de vencimentos
* [ ] Histórico financeiro
* [ ] Receitas
* [ ] Despesas
* [ ] Fluxo financeiro

### Futuramente

* [ ] PIX
* [ ] Cartão
* [ ] Integração com gateway de pagamento
* [ ] Emissão de comprovantes
* [ ] Notificações automáticas

---

## 🟠 Fase 08 — Fichas de Treino

* [ ] Cadastro de exercícios
* [ ] Categorias
* [ ] Grupos musculares
* [ ] Equipamentos
* [ ] Exercícios
* [ ] Fichas
* [ ] Treinos A/B/C/D
* [ ] Séries
* [ ] Repetições
* [ ] Carga
* [ ] Descanso
* [ ] Observações

---

## 🔵 Fase 09 — Área do Aluno

* [ ] Dashboard do aluno
* [ ] Perfil
* [ ] Plano atual
* [ ] Vencimento
* [ ] Situação financeira
* [ ] Ficha de treino
* [ ] Histórico de treinos
* [ ] Evolução
* [ ] Avaliação física

---

## 🔵 Fase 10 — Avaliação Física

* [ ] Cadastro de avaliação
* [ ] Peso
* [ ] Altura
* [ ] IMC
* [ ] Circunferências
* [ ] Percentual de gordura
* [ ] Massa muscular
* [ ] Histórico
* [ ] Gráficos de evolução

---

## 🔵 Fase 11 — Dashboard Administrativo

### Indicadores

* [ ] Total de alunos
* [ ] Alunos ativos
* [ ] Alunos inativos
* [ ] Novas matrículas
* [ ] Cancelamentos
* [ ] Matrículas vencendo
* [ ] Receita mensal
* [ ] Inadimplência
* [ ] Comparativo entre unidades

---

## 🟣 Fase 12 — Relatórios

* [ ] Relatório de alunos
* [ ] Relatório de matrículas
* [ ] Relatório financeiro
* [ ] Relatório de inadimplência
* [ ] Relatório de pagamentos
* [ ] Relatório de cancelamentos
* [ ] Relatório de evolução
* [ ] Relatório por unidade
* [ ] Exportação PDF
* [ ] Exportação Excel

---

## 🟣 Fase 13 — Notificações

* [ ] E-mail de matrícula
* [ ] Aviso de vencimento
* [ ] Aviso de pagamento
* [ ] Aviso de inadimplência
* [ ] Recuperação de senha
* [ ] Notificações internas

### Futuramente

* [ ] WhatsApp
* [ ] Push Notifications

---

## 🔴 Fase 14 — Segurança

* [ ] Policies
* [ ] Gates
* [ ] Controle de permissões
* [ ] Proteção CSRF
* [ ] Validação de dados
* [ ] Rate limiting
* [ ] Logs
* [ ] Auditoria
* [ ] Controle de sessões
* [ ] Backup do banco

---

## 🔴 Fase 15 — Testes

### Testes Unitários

* [ ] Models
* [ ] Services
* [ ] Regras de negócio

### Testes de Feature

* [ ] Autenticação
* [ ] Alunos
* [ ] Matrículas
* [ ] Planos
* [ ] Financeiro
* [ ] Treinos

### Testes de Interface

* [ ] Login
* [ ] Dashboard
* [ ] Cadastro
* [ ] Área do aluno

---

# 🧱 Arquitetura

O projeto utiliza o padrão arquitetural fornecido pelo Laravel, com separação entre:

```text
app/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
│
├── Models/
│
├── Services/
│
└── Policies/

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

A estrutura poderá ser expandida conforme os módulos do sistema forem implementados.

---

# 🛠️ Tecnologias

## Backend

* PHP 8.3+
* Laravel 13
* Laravel Breeze
* Laravel Tinker

O projeto atualmente declara PHP `^8.3` e Laravel `^13.17` no `composer.json`.

## Frontend

* Blade
* Tailwind CSS
* Vite
* Alpine.js

O `package.json` atual utiliza Vite e possui dependências relacionadas ao Tailwind CSS, Alpine.js e Laravel Vite Plugin.

## Banco de Dados

Configurado inicialmente para:

* MySQL

A configuração de exemplo do projeto utiliza MySQL na porta `3306`, com banco `upsoluctions_gym`.

### Futuramente

* PostgreSQL

---

# 💻 Requisitos

Antes de iniciar o projeto, certifique-se de possuir:

* PHP 8.3+
* Composer
* Node.js
* NPM
* MySQL
* Git

---

# 🚀 Instalação

Clone o projeto:

```bash
git clone https://github.com/filipe-csilva/UpSoluctions-Gym.git
```

Entre na pasta:

```bash
cd UpSoluctions-Gym
```

Instale as dependências PHP:

```bash
composer install
```

Copie o arquivo de ambiente:

```bash
cp .env.example .env
```

No Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Gere a chave da aplicação:

```bash
php artisan key:generate
```

---

# 🗄️ Configuração do Banco

Configure o `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=upsoluctions_gym
DB_USERNAME=root
DB_PASSWORD=
```

Crie o banco:

```sql
CREATE DATABASE upsoluctions_gym;
```

Execute as migrations:

```bash
php artisan migrate
```

Se necessário, execute os seeders:

```bash
php artisan db:seed
```

---

# 📦 Instalação do Frontend

Instale as dependências:

```bash
npm install
```

Execute o ambiente de desenvolvimento:

```bash
npm run dev
```

Em outro terminal:

```bash
php artisan serve
```

O projeto poderá ser acessado em:

```text
http://localhost:8000
```

---

# ⚡ Executando o Projeto

Para desenvolvimento, execute:

```bash
php artisan serve
```

e:

```bash
npm run dev
```

O projeto também possui scripts Composer preparados para executar simultaneamente o servidor Laravel, fila e Vite através do comando:

```bash
composer run dev
```

Essa configuração já está presente no `composer.json`.

---

# 🧪 Testes

Execute os testes com:

```bash
php artisan test
```

Ou:

```bash
composer run test
```

O projeto utiliza **Pest** e o plugin Laravel para testes.

---

# 📊 Status do Desenvolvimento

| Módulo            | Status                |
| ----------------- | --------------------- |
| Estrutura Laravel | 🟢 Concluído          |
| Autenticação      | 🟢 Inicial            |
| Perfil            | 🟢 Inicial            |
| Dashboard         | 🟡 Inicial            |
| Usuários          | 🟡 Em desenvolvimento |
| Unidades          | ⚪ Planejado           |
| Alunos            | ⚪ Planejado           |
| Planos            | ⚪ Planejado           |
| Matrículas        | ⚪ Planejado           |
| Financeiro        | ⚪ Planejado           |
| Treinos           | ⚪ Planejado           |
| Área do aluno     | ⚪ Planejado           |
| Avaliação física  | ⚪ Planejado           |
| Relatórios        | ⚪ Planejado           |
| Notificações      | ⚪ Planejado           |
| Multiunidades     | ⚪ Planejado           |
| Testes            | 🟡 Inicial            |
| Produção          | ⚪ Futuro              |

### Legenda

* 🟢 Concluído
* 🟡 Em desenvolvimento
* ⚪ Planejado
* 🔴 Bloqueado

---

# 📁 Estrutura Atual

A estrutura atual do repositório já possui os principais diretórios do Laravel:

```text
UpSoluctions-Gym/
│
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
│
├── .env.example
├── artisan
├── composer.json
├── composer.lock
├── package.json
├── package-lock.json
├── phpunit.xml
├── tailwind.config.js
├── vite.config.js
└── README.md
```

Essa estrutura corresponde ao estado atual do repositório no GitHub.

---

# 🔐 Autenticação

O projeto utiliza **Laravel Breeze** para fornecer a estrutura inicial de autenticação.

Atualmente estão disponíveis as rotas relacionadas a:

* Login
* Registro
* Logout
* Recuperação de senha
* Verificação de e-mail
* Perfil

A rota `/` atualmente direciona para a tela de login e o `/dashboard` está protegido pelos middlewares `auth` e `verified`.

---

# 🧩 Funcionalidades Futuras

A longo prazo, o UpSoluctions Gym poderá evoluir para uma plataforma completa contendo:

```text
                    ┌──────────────────────┐
                    │   UpSoluctions Gym   │
                    └──────────┬───────────┘
                               │
          ┌────────────────────┼────────────────────┐
          │                    │                    │
          ▼                    ▼                    ▼
     Administrador           Aluno              Professor
          │                    │                    │
          ▼                    ▼                    ▼
      Dashboard             Treinos             Alunos
      Unidades              Plano               Fichas
      Alunos                Financeiro          Evolução
      Matrículas            Evolução
      Financeiro
      Relatórios
```

---

# 📈 Evolução do Projeto

O desenvolvimento será realizado de maneira incremental.

### MVP

Primeiro objetivo:

```text
Autenticação
      ↓
Usuários
      ↓
Alunos
      ↓
Planos
      ↓
Matrículas
      ↓
Financeiro
```

### Versão 2

Posteriormente:

```text
Treinos
      ↓
Professor
      ↓
Área do Aluno
      ↓
Avaliação Física
      ↓
Evolução
```

### Versão 3

Evolução para:

```text
Multiunidades
      ↓
Relatórios
      ↓
Notificações
      ↓
Integrações
      ↓
Automação
```

---

# 🤝 Contribuição

Contribuições são bem-vindas.

Para contribuir:

```bash
git clone https://github.com/filipe-csilva/UpSoluctions-Gym.git
```

Crie uma branch:

```bash
git checkout -b feature/nova-funcionalidade
```

Faça suas alterações:

```bash
git add .
git commit -m "feat: adiciona nova funcionalidade"
```

Envie para o GitHub:

```bash
git push origin feature/nova-funcionalidade
```

Depois, abra um Pull Request.

---

# 📝 Convenção de Commits

O projeto recomenda o uso de **Conventional Commits**.

Exemplos:

```text
feat: adiciona cadastro de alunos
fix: corrige validação da matrícula
refactor: reorganiza serviço financeiro
docs: atualiza README
test: adiciona testes para alunos
chore: atualiza dependências
```

---

# 📄 Licença

Este projeto está licenciado sob a licença **MIT**.

Consulte o arquivo `LICENSE` para mais informações.

---

# 👨‍💻 Desenvolvedor

**Filipe Silva**

Projeto desenvolvido como parte da evolução profissional e prática em desenvolvimento **Backend**, **PHP**, **Laravel**, bancos de dados e arquitetura de aplicações web.

---

# ⭐ UpSoluctions Gym

Sistema de gerenciamento de academia desenvolvido para transformar processos administrativos em uma plataforma integrada, organizada e escalável.

> **Do cadastro do aluno ao controle financeiro, do treino à evolução.**

🚀 **UpSoluctions Gym — Gestão inteligente para academias.**
