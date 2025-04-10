# skeleton-symfony-vuejs

A web application skeleton using vuejs, vite and Symfony 6


## install

```
composer install
npm install
npm run build
php bin/console doctrine:database:create
php bin/console doctrine:migration:migrate
php bin/console lexik:jwt:generate-keypair
```

## configure

configure the `refresh_token_path` variable in the `assets/api/apiPrivate.js` file



## todo

  - if you delete a user you must also delete it in the `refresh_token` table