# <a name="logo" href="https://github.com/lucasemanuel/mynotes#-mynotes"><img src="https://raw.githubusercontent.com/lucasemanuel/mynotes-front-end/master/public/logo.svg" alt="logo mynotes" title="mynotes" height="60"/>myNotes</a> (Back-end)

Aplicativo simples de anotações desenvolvido com laravel (back-end).

## Install <a name = "install"></a>

Instalar Dependências
```
composer install
```

Fazer cópia do arquivo `.env.example` e nomear de `.env`
```
cp .env.example .env
```

Gerar chave
```
php artisan key:generate
```

Gerar secret do JWT
```
php artisan jwt:secret
```

Configurar o banco de dados, crie previamente o banco para essa aplicação e set essas variáveis no arquivo `.evn`

```
DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

Executar as migração

```
php artisan migrate
```

Iniciar o servidor

```
php artisan serve
```

Executar os testes

```
php artisan test
```

Aplicar Tradução
```
php artisan vendor:publish --tag=laravel-pt-br-localization
```
