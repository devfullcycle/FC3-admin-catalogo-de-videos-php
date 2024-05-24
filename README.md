<center>
  <p align="center">
    <img src="https://user-images.githubusercontent.com/20674439/158480514-a529b310-bc19-46a5-ac95-fddcfa4776ee.png" width="150"/>&nbsp;
    <img src="https://icon-library.com/images/php-icon/php-icon-8.jpg"  width="150" />
  </p>  
  <h1 align="center">🚀 Microsserviço: Catálogo de Vídeos com PHP</h1>
  <p align="center">
    Microsserviço Catálogo de Vídeos com PHP/Laravel <br />
    Projeto com TDD, Clean Arch, DDD e etc;
  </p>
</center>



## Rodar o Projeto

Clone Repositório
```sh
git clone https://github.com/devfullcycle/FC3-admin-catalogo-de-videos-php.git microservice-catalog
```

```sh
cd microservice-catalog/
```

Remova o versionamento (opcional)
```sh
rm -rf .git/
```


Crie o Arquivo .env
```sh
cp .env.example .env
```


Atualize as variáveis de ambiente do arquivo .env
```dosini
APP_NAME="Full Cycle"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=code_micro_videos
DB_USERNAME=root
DB_PASSWORD=root

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```


Suba os containers do projeto
```sh
docker-compose up -d
```


Acesse o container app
```sh
docker-compose exec app bash
```


Instalar as dependências do projeto
```sh
composer install
```


Gerar a key do projeto Laravel
```sh
php artisan key:generate
```


Acesse o projeto
[http://localhost:8000](http://localhost:8000)
