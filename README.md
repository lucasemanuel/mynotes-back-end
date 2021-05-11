# MyNotes (Backend)
> Sistema para gerenciar anotações, backend em [Laravel](https://laravel.com/).

## Guia de Instalação <a name = "install"></a>

Instalar Dependências:
```
composer install
```

Fazer cópia do arquivo `.env.example` e nomear de `.env`:
```
cp .env.example .env
```

Gerar chave:
```
php artisan key:generate
```

Gerar hash do JWT:
```
php artisan jwt:secret
```

Configurar o banco de dados, crie previamente um banco para essa aplicação e preencha essas variáveis no arquivo `.env`
Recomendo usar [MySQL](https://www.mysql.com/) ou [PostgresSQL](https://www.postgresql.org/):
```
DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

Configure com a url da sua aplicação cliente, exemplo: `http://localhost:8080`:
```
APP_URL_FRONTEND=
```

Configurar as variáveis de email:
```
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=
MAIL_FROM_ADDRESS=
```

Configure drive da fila para `database`
```
QUEUE_CONNECTION=database
```

Executar as migrações:
```
php artisan migrate
```

Executar os testes (usando sqlite):
```
php artisan test
```

Iniciar o servidor:
```
php artisan serve
```

Em outra instancia do terminal, use o comando abaixo para startar a fila:
```
php artisan queue:work
```
