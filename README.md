# 🏋️ UpSoluctions Gym

Sistema web de gestão de academias e centros de treinamento, desenvolvido com Laravel. O objetivo é centralizar unidades, usuários, alunos, professores, planos, matrículas, financeiro, presença, treinos, avaliações físicas e comunicados.

> **Status:** fundação criada e domínio modelado, mas os módulos de negócio ainda não estão prontos para uso. Este README reflete o estado verificado no código em setembro de 2026.

## Stack

- PHP 8.5 / Laravel 13
- Laravel Breeze para autenticação
- Laravel Boost para suporte ao desenvolvimento assistido
- Blade, Tailwind CSS, Alpine.js, AdminLTE e Vite
- Pest, PHPUnit e Laravel Pint
- MySQL em desenvolvimento; SQLite em memória nos testes

## Situação verificada

| Área | Status | Evidência |
| --- | --- | --- |
| Fundação Laravel, Composer, NPM e Vite | ✅ | Aplicação inicial configurada |
| Login, logout e recuperação de senha | 🟡 | Fluxos existem, mas há falhas de rota nos testes |
| Verificação de e-mail e perfil | 🟡 | Controllers, rotas e testes existem; suíte não está verde |
| Roles | 🟡 | `UserRole`, migration e middleware existem |
| Autorização completa | ⬜ | Ainda não há Policies, Gates ou permissões por módulo/unidade |
| Modelagem do domínio | 🟡 | Migrations e classes de Model existem, em sua maioria sem relações/casts |
| Unidades | 🟡 | Migration, Model e rotas declaradas; controller/views inexistentes |
| Usuários administrativos | 🟡 | Rota declarada; controller inexistente |
| Alunos | 🟡 | Migration, Model e controller em rascunho; views e rotas funcionais ausentes |
| Professores | 🟡 | Migration e Model; CRUD inexistente |
| Planos | 🟡 | Migration, Model e rota declarada; controller inexistente |
| Matrículas | 🟡 | Migration e Model; CRUD e regras de negócio inexistentes |
| Financeiro | 🟡 | Migration e Model; operações e relatórios inexistentes |
| Presença | 🟡 | Migration e Model; registro e histórico inexistentes |
| Exercícios e treinos | 🟡 | Migrations e Models; CRUD e montagem de ficha inexistentes |
| Avaliações físicas | 🟡 | Migration e Model; cadastro e evolução inexistentes |
| Comunicados | 🟡 | Migration e Model; publicação e destinatários inexistentes |
| Dashboard | 🟡 | Layout `panel` existe; indicadores e dados reais inexistentes |
| Testes de domínio | ⬜ | Apenas cobertura inicial de autenticação e perfil |
| API, relatórios, notificações e produção | ⬜ | Ainda não iniciados |

### Legenda

- ✅ Concluído ou funcional
- 🟡 Iniciado / parcialmente implementado
- ⬜ Não iniciado

## Diagnóstico técnico atual

As verificações realizadas encontraram os seguintes bloqueios:

1. `php artisan route:list` falha porque `routes/admin.php` referencia `UnitController`, `UserController` e `PlanController`, mas esses controllers ainda não existem nem estão importados.
2. A aplicação e os testes usam a rota `dashboard`, mas o projeto não declara essa rota. A rota equivalente atual é `panel`, causando erros nos fluxos de autenticação, confirmação de senha e perfil.
3. `app/Http/Controllers/StudentController.php` contém cercas Markdown (```php e ```), portanto não está em formato PHP executável. Além disso, suas views `students.*` ainda não existem.
4. Os Models de domínio ainda são esqueletos: faltam `$fillable`/casts quando aplicável, relações Eloquent e regras de domínio.
5. `User` possui cast para `role`, mas sua lista de atributos preenchíveis não inclui `role` nem `unit_id`; o cadastro administrativo de usuários/alunos precisa tratar isso explicitamente.
6. A suíte atual executa 25 testes: 18 passam, 5 falham e 2 terminam com erro. O principal erro é `Route [dashboard] not defined`.

Esses itens são pré-requisitos para considerar a autenticação e os primeiros CRUDs concluídos.

## Roadmap atualizado

### Fase 1 — Estabilização da base

- 🟡 Corrigir a rota `dashboard`/`panel` e alinhar os redirects do Breeze
- 🟡 Corrigir as referências e imports de controllers administrativos
- 🟡 Remover o Markdown inválido do `StudentController`
- 🟡 Criar as views ou retirar temporariamente as rotas que ainda não têm tela
- 🟡 Fazer a suíte de autenticação e perfil voltar a ficar verde

### Fase 2 — Usuários e autorização

- ✅ Enum de roles criado
- ✅ Middleware `role` registrado
- 🟡 Corrigir persistência de `role` e `unit_id` no User
- ⬜ Criar Policies e Gates
- ⬜ Definir permissões por módulo e unidade
- ⬜ Implementar CRUD de usuários
- ⬜ Cobrir autorização com testes de acesso permitido e negado

### Fase 3 — Cadastros principais

Implementar, nesta ordem, para cada módulo:

1. Unidades
2. Alunos
3. Professores
4. Planos

Cada CRUD deve ter controller, Form Requests, rotas, views, relações, validações, autorização e testes de feature.

### Fase 4 — Operação da academia

- Matrículas: criar, renovar, suspender, cancelar e alterar plano
- Financeiro: receitas, despesas, pagamentos e inadimplência
- Presença: entrada, saída, histórico e frequência
- Exercícios e fichas de treino
- Avaliações físicas e evolução
- Comunicados por unidade e perfil

### Fase 5 — Visão do produto

- Dashboards por perfil: administrador, professor e aluno
- Área do aluno
- Relatórios e exportação CSV/PDF
- Notificações por e-mail e canais externos

### Fase 6 — Qualidade e produção

- Testes de todos os módulos e autorização
- API REST versionada e documentação OpenAPI
- Índices, eager loading, cache, filas e jobs
- Docker, CI/CD, backups, logs, monitoramento e deploy

## Estrutura relevante

```text
app/
├── Enums/
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
├── auth.php
└── admin.php

tests/
├── Feature/
└── Unit/
```

## Instalação

```bash
git clone https://github.com/filipe-csilva/UpSoluctions-Gym.git
cd UpSoluctions-Gym
composer install
npm install
```

Crie o ambiente e gere a chave:

```bash
# Windows
copy .env.example .env

# Linux/macOS
cp .env.example .env

php artisan key:generate
```

Configure o banco no `.env` e execute `php artisan migrate`.

Para executar:

```bash
composer dev
```

Ou em terminais separados:

```bash
php artisan serve
npm run dev
```

## Comandos úteis

```bash
# Testes
php artisan test

# Formatação
vendor/bin/pint

# Rotas
php artisan route:list

# Limpeza de cache
php artisan optimize:clear
```

> `php artisan migrate:fresh --seed` apaga as tabelas existentes. Use apenas em ambiente de desenvolvimento ou testes.

## Próxima prioridade

O próximo ciclo recomendado é: corrigir as rotas e os testes da base, fechar a autorização por role/unidade e então entregar o CRUD de Unidades. Só depois vale avançar para Alunos, Professores, Planos e Matrículas.

## Licença

Este projeto está licenciado sob a licença MIT. Consulte o arquivo `LICENSE` para mais informações.

---

**UpSoluctions Gym — Gestão inteligente para academias.**