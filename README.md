# Cache Decorator

## Docker

local
```shell
docker run --init -it --rm -v "$(pwd):/app" -w /app ghcr.io/hrmessenger/php:fpm sh
```

first run: 
```shell
/app# apk add composer
/app# composer update
```

## Testing

### Unit testing

The package is tested with [PHPUnit](https://phpunit.de/). To run tests:

```shell
./vendor/bin/phpunit
```

local
```shell
docker run --init -it --rm -v "$(pwd):/app" -w /app ghcr.io/hrmessenger/php:fpm ./vendor/bin/phpunit 
```

### Code Sniffer

local

```shell
docker run --init -it --rm -v "$(pwd):/app" -w /app ghcr.io/hrmessenger/php:fpm ./vendor/bin/phpcs 
```

phpqa

```shell
docker run --init -it --rm -v "$(pwd):/app" -v "$(pwd)/phpqa/tmp:/tmp" -w /app jakzal/phpqa phpcs
```

### Static analysis

The code is statically analyzed with [Psalm](https://psalm.dev/). To run static analysis:

```shell
./vendor/bin/psalm
```

local
```shell
docker run --init -it --rm -v "$(pwd):/app" -w /app ghcr.io/hrmessenger/php:fpm ./vendor/bin/psalm 
```

phpqa
```shell
docker run --init -it --rm -v "$(pwd):/app" -v "$(pwd)/phpqa/tmp:/tmp" -w /app jakzal/phpqa psalm
```
