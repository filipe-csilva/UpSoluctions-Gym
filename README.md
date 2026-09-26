# UpSoluctions Gym

Sistema web de gestão de academias e centros de treinamento, desenvolvido com Laravel.

O sistema contempla unidades, usuários, alunos, instrutores, planos, matrículas, financeiro, presença, exercícios, treinos, avaliações físicas, comunicados e mensagens.

## Stack

- PHP 8.3+
- Laravel 13
- Laravel Breeze
- Blade, Bootstrap e AdminLTE
- Vite
- MySQL em desenvolvimento
- SQLite no CI
- Pest, PHPUnit e Laravel Pint

## Status atual

### Concluído

- Configurações gerais exclusivas do administrador, persistidas no banco.
- Cores, botões, ícones, logo, favicon, moeda, datas e fuso horário configuráveis.
- Autorização por perfil e por unidade.
- Cadastro público desabilitado: /register não existe.
- Inicialização automática do banco e das migrações.
- Home integrada ao painel principal.
- Financeiro com auditoria, receitas, despesas, contas futuras, recebimentos, estornos, fluxo de caixa e exportação.
- Geração automática de contas a receber na matrícula.
- Histórico de matrículas, treinos e avaliações físicas.
- Ativação e desativação automática de alunos conforme matrícula e situação financeira.
- Mensagens diretas, mensagens para unidade, recepção e respostas.
- Responsabilidade automática da recepção para o primeiro admin, manager ou financeiro que abrir a mensagem.
- Leitura individual de mensagens e comunicados.
- Restrição de mensagens e comunicados conforme a data de cadastro do aluno.
- Comunicado inicial padrão disponível para alunos novos.
- Notificações operacionais e jobs.
- Manifesto PWA e service worker.
- CI com PHP 8.4, SQLite, testes e Pint.

## API e infraestrutura

Estas partes ainda estão pendentes:

- Autenticação JWT.
- Endpoints completos de todos os módulos.
- Login, usuários, mensagens, comunicados, financeiro, relatórios e configurações na API.
- Documentação Swagger/OpenAPI completa.
- Definição do frontend consumindo exclusivamente a API.
- Redis, cache, monitoramento, Docker, backup e deploy automatizado.
- Integrações reais de e-mail, WhatsApp e push.

## Instalação

    git clone https://github.com/filipe-csilva/UpSoluctions-Gym.git
    cd UpSoluctions-Gym
    composer install
    npm install

Configure o ambiente:

    cp .env.example .env
    php artisan key:generate
    php artisan app:initialize

No Windows:

    copy .env.example .env
    php artisan key:generate
    php artisan app:initialize

Configure o banco no .env e execute:

    php artisan migrate

Para iniciar:

    composer dev

## Testes e qualidade

    php artisan test --compact
    vendor/bin/pint --dirty --format agent
    php artisan route:list
    php artisan optimize:clear

Estado validado localmente: **70 testes e 202 asserções aprovados**.

## Documentação de tarefas

O detalhamento das tarefas implementadas e pendentes está em [TASKS.md](TASKS.md).

## Licença

Este projeto está licenciado sob a licença MIT.
