# sail
Jalankan berikut di directory source untuk install package composer (jika baru saja git clone, blm ada directory vendor)
```shell
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

```shell
sail artisan key:generate
sail artisan storage:link
```

## Tools
### Development only
- To be able to compile Javascript and CSS, first run:
```shell
npm install
```

### Recompile Javascript and CSS
- compile for debug:
```shell
npm run dev
```

- compile for production:
```shell
npm run build
```

- Run the Database\Seeders\UserSeeder class
```shell
php artisan db:seed
```

jalankan sail up utk create container
```shell
sail up -d
```
