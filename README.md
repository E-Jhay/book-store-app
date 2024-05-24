## About Book Store

Book Store is a simple crud application that manages books. It is made with VILT stack.

## Installation

- clone the app.
- Go to the folder application using cd command on your cmd or terminal.
- Run composer install on your cmd or terminal.
- Copy .env.example file to .env on the root folder. You can type copy .env.example .env if using command prompt Windows or cp .env.example .env if using terminal.
- Open your .env file and change the database name (DB_DATABASE) to whatever you have, username (DB_USERNAME) and password (DB_PASSWORD) field correspond to your configuration. the default of this is sqlite
- Run php artisan key:generate.
- Run php artisan migrate.
- Run php artisan db:seed.
- Run php artisan serve.
- Go to http://localhost:8000/.
