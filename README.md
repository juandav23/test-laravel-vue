# Backend

### copy .env.example to .env

Create a database and change the information in the .env file

#### Run:

```sh
composer install
```

```sh
php artisan migrate
```

```sh
php artisan db:seed
```

```sh
php artisan optimize
```

```sh
php artisan serve
```

## FRONTEND

-   The frontend folder is in the root proyect and is called FRONT
-   If you want you can move it to another location
-   Go to file '''src/store/index.js''' and change the backend server url

#### Run:

```sh
npm install
```

```sh
npm run dev "for development or"
```

```sh
npm run build "to create the PDN package"
```

#### Done !!
