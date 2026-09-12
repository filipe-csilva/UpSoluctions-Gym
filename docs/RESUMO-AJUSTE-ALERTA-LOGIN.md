# Resumo do procedimento — Alerta de login inválido

## Objetivo

Exibir uma mensagem visual quando o usuário informar e-mail ou senha inválidos no login da aplicação.

## Comportamento implementado

O sistema agora apresenta um alerta com:

- Mensagem: **Usuário ou senha inválidos.**
- Ícone de exclamação (`!`)
- Texto vermelho
- Fundo cinza
- Posição fixa no topo direito da tela
- Desaparecimento automático após 5 segundos
- Transição suave antes da remoção do alerta

## Procedimento realizado

1. O fluxo de autenticação foi analisado em `LoginRequest`.
2. A mensagem genérica de falha foi substituída por uma mensagem em português, sem revelar se o erro ocorreu no e-mail ou na senha.
3. A view de login foi atualizada para renderizar o alerta quando existir erro de autenticação no campo de e-mail.
4. O alerta recebeu o identificador `login-error-alert` para ser localizado pelo JavaScript.
5. Foi adicionado um temporizador de 5.000 milissegundos.
6. Após os 5 segundos, o alerta reduz a opacidade e sobe levemente; depois é removido do DOM.
7. O alerta genérico duplicado foi removido para evitar duas mensagens sobre o mesmo erro.
8. O teste de autenticação com senha inválida foi atualizado para validar a mensagem retornada na sessão.
9. Também foi corrigido o cast do enum `UserRole` no model `User`, que impedia a criação de usuários durante o teste.

## Arquivos envolvidos

- `app/Http/Requests/Auth/LoginRequest.php`
  - Define a mensagem segura para credenciais inválidas.
- `resources/views/auth/login.blade.php`
  - Renderiza e remove automaticamente o alerta.
- `tests/Feature/Auth/AuthenticationTest.php`
  - Confirma que a tentativa com senha inválida mantém o usuário desautenticado e retorna o erro esperado.
- `app/Models/User.php`
  - Usa `UserRole::class` no cast do atributo `role`.

## Validação

Foram executadas as seguintes verificações:

```bash
php -l app/Http/Requests/Auth/LoginRequest.php
php artisan test tests/Feature/Auth/AuthenticationTest.php --filter="invalid password" --compact
php artisan view:cache
```

Resultado:

- Sintaxe PHP: aprovada
- Teste de credenciais inválidas: aprovado
- Compilação das views Blade: aprovada

## Observação

O alerta é exibido apenas quando há erro associado ao campo de e-mail retornado pelo fluxo de login. A mensagem é intencionalmente genérica para não informar se o e-mail existe ou se apenas a senha está incorreta.