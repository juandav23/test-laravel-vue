# Backend

### copy .env.test to .env

You will need to free 8000 and 8081 ports and have installed docker and docker-compose

#### Run:

```sh
docker-compose up -d --build
```

```sh
docker-compose exec app php artisan optimize
```

```sh
docker-compose exec app php artisan migrate:fresh
```

```sh
docker-compose exec app php artisan db:seed
```

## FRONTEND

-   The FRONTEND folder is in the root proyect and is called FRONTEND
-   If you want you can move it to another location
-   Go to file '''src/store/index.js''' and change the backend server url

#### Run:

```sh
npm run prod
```

```sh
docker-compose up -d --build
```

Check URL http://localhost:8081/

#### Done !!
