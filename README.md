# laravel-auth
projetos de autenticação e autorização usando o Laravel

### Projeto básico de autenticação e autorização por sessão: basic-auth-site (Starter kit Breeze)

docs: 

https://laravel.com/docs/10.x/authentication#protecting-routes
https://laravel.com/docs/10.x/installation
https://laravel.com/docs/10.x/starter-kits#laravel-breeze

#### Configuração inicial para todos os projetos: 

1. Mysql: Criar base de dados (schema) chamada basic-auth
2.  Editar arquivo .env para conectar ao banco de dados

- DB_CONNECTION=sqlite 
- DB_CONNECTION=mysql
- DB_HOST=127.0.0.1
- DB_PORT=3306
- DB_DATABASE=laravel
- DB_USERNAME=root
- DB_PASSWORD= senha

3. Rodar as migrations para testar a conexão:

```shel
php artisan migrate
```

4. Inicializar o Laravel: 
```shel
php artisan serve
```
Demais configurações: https://laravel.com/docs/10.x/configuration
#### Laravel Breeze:
O Laravel Breeze é uma implementação mínima e simples de todos os recursos de autenticação do Laravel , incluindo login, registro, redefinição de senha, verificação de e-mail e confirmação de senha. Além disso, o Breeze inclui uma página simples de "perfil" onde o usuário pode atualizar seu nome, endereço de e-mail e senha.

1. Instalação:
```shel
composer require laravel/breeze --dev
```
Uma vez que o Breeze esteja instalado, você pode desenvolver seu aplicativo usando uma das "pilhas" do Breeze discutidas na documentação: Blade, Vue.js, Next.js ou React.js.

2. Usando o blade templates com breeze:

Comandos em sequência:
```shel
php artisan breeze:install
```
Aqui já cria as novas views e edita o welcome.blade.php com os links de fluxo de usuário, além disso cria rotas protegidas e toda estrutura básica de login por sessão.

```shel
php artisan migrate
npm install
npm run dev
```
Após rodar esses comandos uma estrutura completa de autenticação é implementada com o seguinte: 

* tela e implementação de login/logout;
* tela e implementação de registro;
* tela e implamentação de crud perfil de usuário;
* Recuperação de senha com e-mail. 

Configuração para recuperar senha:
https://laravel.com/docs/10.x/mail 

Configuração do mailpit:    
https://github.com/axllent/mailpit
```shel
brew tap axllent/apps
brew install mailpit
```
rode para iniciar. 
```shel
 mailpit
```

Configuração usando gmail:
https://www.iankumu.com/blog/laravel-send-emails/ 

Depois de configurado os campos .env com os dados abaixo, seja de qual provedor de e-mail desejar, será possível testar o recovery: dados do gmail.
* MAIL_MAILER=smtp
* MAIL_HOST=smtp.gmail.com
* MAIL_PORT=465
* MAIL_USERNAME=meu-email@gmail.com
* MAIL_PASSWORD=minha-senha
* MAIL_ENCRYPTION=ssl
* MAIL_FROM_ADDRESS=meu-email@gmail.com
* MAIL_FROM_NAME="${APP_NAME}"

