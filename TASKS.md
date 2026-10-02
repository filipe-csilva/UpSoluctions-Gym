# Tarefas do projeto

## Implementado

### Configuração e acesso

- [x] Configurações gerais exclusivas do administrador persistidas no banco.
- [x] Cores, botões, ícones, logo, favicon, moeda, formato de data e fuso horário configuráveis.
- [x] Controle de permissões para alunos, instrutores, managers, financeiro e administrador.
- [x] Cadastro público desabilitado; a rota /register não está disponível.
- [x] Inicialização automática do banco e das migrações.
- [x] Home integrada ao painel principal.

### Financeiro

- [x] Auditoria financeira e classificação de despesas fixas e variáveis.
- [x] Separação entre receitas, despesas e contas futuras.
- [x] Recebimento com forma de pagamento e estorno pelo administrador.
- [x] Fluxo de caixa e relatório de parcelas vencidas.
- [x] Exportação financeira para Excel.
- [x] Geração automática de contas a receber na matrícula.

### Alunos, treinos e avaliações

- [x] Histórico de matrículas e fichas de treino.
- [x] Instrutor pode criar treino para seus alunos.
- [x] Histórico e comparação de avaliações físicas.
- [x] Evolução corporal e suporte a mídia nos exercícios.
- [x] Aluno pode registrar treino em qualquer unidade ativa.
- [x] Job de ativação e desativação automática conforme matrícula e situação financeira.

### Mensagens e comunicados

- [x] Mensagens diretas entre os perfis permitidos.
- [x] Mensagens para alunos da unidade enviadas por admin e manager.
- [x] Aluno pode enviar mensagem para a recepção da própria unidade.
- [x] Responsabilidade automática da recepção para o primeiro admin, manager ou financeiro que abrir a mensagem.
- [x] Respostas em conversas.
- [x] Leitura individual por usuário para mensagens e comunicados.
- [x] Aluno visualiza somente mensagens e comunicados posteriores ao próprio cadastro.
- [x] Comunicado inicial padrão permanece visível para alunos novos.
- [x] Alunos, instrutores, managers e financeiro disponíveis como destinatários conforme permissão.

### Notificações e infraestrutura

- [x] Notificações de mensalidade vencida, matrícula próxima do vencimento, nova ficha e nova avaliação.
- [x] Job operacional para processamento em segundo plano.
- [x] CI configurado com PHP 8.4, SQLite e execução automática dos testes.
- [x] Manifesto PWA e service worker disponíveis.
- [x] 70 testes e 202 asserções aprovados localmente.

## Atualizacao - 02/10/2026

- [x] Recibo financeiro em formatos A4 e bobina de 80 mm, com abertura em nova aba.
- [x] Botao de fechamento do recibo com retorno seguro para o financeiro.
- [x] Simulacao de 24 meses com receitas, despesas, mensagens e noticias.
- [x] Logo da academia, favicon e identidade visual persistidos nas configuracoes.
- [x] Restricao de cadastro de unidades exclusivamente ao administrador.
- [x] Cabecalhos HTTP de seguranca e cookies de sessao seguros em producao.
- [x] Limitacao de requisicoes na recuperacao de senha.
- [x] Auditoria de dependencias sem vulnerabilidades conhecidas.
- [x] GIF de demonstracao incluido em [`docs/gymcontrol-demo.gif`](docs/gymcontrol-demo.gif).
- [x] 75 testes e 224 assercoes aprovados localmente.

## Pendente

### API

- [ ] Implementar autenticação JWT para a API.
- [ ] Completar os endpoints dos módulos do sistema.
- [ ] Disponibilizar login, usuários, mensagens, comunicados, financeiro, relatórios e configurações na API.
- [ ] Publicar documentação Swagger/OpenAPI completa e atualizada.
- [ ] Definir se o acesso web continuará ativo ou será substituído pelo frontend via API.

### Integrações

- [ ] Ativar e validar envio real por e-mail, WhatsApp e push.
- [ ] Configurar Redis, filas, cache e monitoramento em ambiente de produção.
- [ ] Configurar Docker, backup automatizado e pipeline de deploy.

### Validação

- [ ] Executar testes manuais no navegador para cada perfil de acesso.
- [ ] Confirmar a execução bem-sucedida do workflow no GitHub Actions após a correção do SQLite.
- [ ] Avaliar a remoção futura dos campos legados read_at e read_by da tabela de mensagens.
