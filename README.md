<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About The Tasks List

This is a simple web page tasks list.
The user can create , edit task title ,mark completed and delete task.
There is also a search bar.

### Prerequisites

Make sure Docker is installed.

Otherwise install with composer , just make sure the ports are available
and that you have MySql and php installed locally
for further information visit Laravel.com .

If you have PHP and Composer installed **locally**, run:

```bash
composer install && ./vendor/bin/sail up -d
```

#### 💡 Important

If you **do not** have PHP or Composer installed locally on your machine, use this temporary Docker command to generate the vendor folder instead:

```bash
docker run --rm \
    -u "\((id -u):\)(id -g)" \
    -v "\$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

Once the Docker command finishes, boot up the environment using:

```bash
./vendor/bin/sail up -d
```

## Migration && Seed

In order of the app to work we need the db structre, seeding is optional

```bash
sail artisan migrate:fresh --seed
#Please be carefull not to use fresh in production it will reset your DB!
```
