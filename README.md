# laravel-auth
projetos de autenticação e autorização usando o Laravel

### Projeto básico de autenticação e autorização por sessão: basic-auth-site (Starter kit Breeze)

docs: 

https://laravel.com/docs/10.x/authentication#protecting-routes
https://laravel.com/docs/10.x/installation
https://laravel.com/docs/10.x/starter-kits#laravel-breeze

#### = Configuração inicial para todos os projetos: =

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
* controle de quantiadades de tentativas de login durante um intervalo de tempo;
* Recuperação de senha com e-mail. 

#### = Recuperação de senha com e-mail: =

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

São outros mecanismos de autenticação por sessão do Larvel por meio de starter kits:
Laravel Jetstream e Laravel Fortify.

#### = Confirmação de senha ao criar usuário: =

Para configurar o e-mail de registro de confirmação com o Laravel Breeze é necessário
configurar um trait manualmente ao User conforme as orientações abaixo:
https://laracasts.com/discuss/channels/laravel/laravel-breeze-sending-registration-emails-email-customizations

Com a simples implementação abaixo em user é possível confirmar a conta somente após confirmação via e-mail:

```php
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
```

#### = Editando e traduzindo as mensagens da aplicação: =

Usar o seguinte módulo do Laravel: https://github.com/lucascudo/laravel-pt-BR-localization

Passo a passo:

```shell
php artisan lang:publish
composer require lucascudo/laravel-pt-br-localization --dev
php artisan vendor:publish --tag=laravel-pt-br-localization
```
Altere Linha 86 do arquivo config/app.php para:
'locale' => 'pt_BR'

* Não funcionou de forma adequada. 

#### = Implementando RBAC (role based access control): =
https://laravel.com/docs/10.x/eloquent-relationships#retrieving-intermediate-table-columns
https://laravel.com/docs/10.x/migrations

A estratégia é simples. Criar uma tabela com papéis que quando relacionados ao User em um relacionamento N:N permita gerir melhor as ações dentro do sistema. 
O objetivo é criar diversos papéis e esses serem associados a um ou mais usuários do sistema. 

1. Criando a tabela de Roles: Tabela se cria com plural.
```shell
php artisan make:migration create_roles_table
```
2. Configurar a tabela de roles
```php
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('authority');
        });
    }
```
3. Rodar a migration e popular o banco.
```shel
php artisan migrate
```
4.Configurar a tabela de roles / User - relacionamento a nível de tabela
```shell
php artisan make:migration create_roles_users_table
```
5. Definir o relacionamento a nível de tabela na migration: 
```php
    public function up(): void
    {
        Schema::create('roles_users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            // relacionamento da entidade user
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            // relacionamento da entidade role
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles');
        });
    }
```
6. Criar Model de role: 

````shell
php artisan make:model Role
````

7. Criar o relacionamento de Role em User e vice versa: 

```php
// Método inserido em User: 

    /**
     * Relacionamento de model entre User e Role com tabela intermediária.
     */
     use Illuminate\Database\Eloquent\Relations\BelongsToMany;
     
    public function roles(): BelongsToMany
    {
        // Relacionar com a tabela criada anteriormente - roles_users
        return $this->belongsToMany(Role::class, 'roles_users', 'user_id', 'role_id');
    }

// Relacionamento inverso definido em Role:
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;

    protected $fillable = [
        'authority',
    ];

    /**
     * Relacionamento inverso Role x User.
     */
    public function users(): BelongsToMany
    {
        // Relacionar com a tabela criada anteriormente - roles_users
        return $this->belongsToMany(User::class, 'roles_users', 'user_id', 'role_id');
    }
```
8. Testando o relacionamento:

* Cria dados fakes direto no banco. Na aplicação, criar um model para fazer essa interação. 
* Cria rota para retornar dados por meio de um usuário.
````php
use App\Models\User;
use App\Models\Role;

// Rota relacionamento N:N User x Role
Route::get('/user-role', function () {

    $user = User::find(1); // Seleciona o primeiro usuário da tabela
    return $user->roles()->first(); // Seleciona e retorna o primeiro registro de papel
    return response()->json($user->roles); // retorna todos os roles associados a um usuário

    $role = Role::find(1); // Pega o primeiro papel
    return response()->json($role->users); // retorna todos os roles associados a um usuário
});
````
Os papéis na aplicação poderão servir para alguma autorização. Envolvendo Gates e Policies.
Para relacionar pode fazer: 

````php
    #Relaciona dados
    // resgata um user
    $user = User::with('roles')->find(2);
    // cria um role
    $newRole = Role::create(['authority'=>'writer']);
    // relaciona
    $user->roles()->save($newRole);
    return response()->json($user->roles);
````
mas é melhor usar um model
