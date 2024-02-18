#Feed back Backend

### Clone the project bu using following command

```sh
git clone -b feedback-backend https://github.com/raheelsafder/feed-back-backend.git
```

Setup your db creds in config/constants/dev_constants.php file

### Convert .env.example file to .env by using follwing command

```sh
cp .env.example .env
```

### Run the following command to install all the dependencies

```sh
composer install
```

### Run the following command to insall passport

```sh
php artisan passport:install
```

### Run the following command to migrate the database

```sh
php artisan migrate
```

### Run the following command to serve backend

```sh
php artisan serve
```
