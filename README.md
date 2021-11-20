# Cache Decorator

...

## Docker

local
```shell
docker build -t kuaukutsu/cache:php .
docker run --init -it --rm -v "$(pwd):/app" -w /app kuaukutsu/cache:php sh
```

## Testing

### Unit testing

The package is tested with [PHPUnit](https://phpunit.de/). To run tests:

```shell
./vendor/bin/phpunit
```

local
```shell
docker run --init -it --rm -v "$(pwd):/app" -w /app kuaukutsu/cache:php ./vendor/bin/phpunit 
```

### Code Sniffer

local

```shell
docker run --init -it --rm -v "$(pwd):/app" -w /app kuaukutsu/cache:php ./vendor/bin/phpcs 
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
docker run --init -it --rm -v "$(pwd):/app" -w /app kuaukutsu/cache:php ./vendor/bin/psalm 
```

phpqa
```shell
docker run --init -it --rm -v "$(pwd):/app" -v "$(pwd)/phpqa/tmp:/tmp" -w /app jakzal/phpqa psalm
```
