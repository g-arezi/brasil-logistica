# Docker quickstart

1. Copy env:

```bash
cp .env.example .env
```

2. Install dependencies in the app container:

```bash
docker compose run --rm app composer install
docker compose run --rm app php artisan key:generate
```

3. Start stack:

```bash
docker compose up -d --build
```

4. Run migrations:

```bash
docker compose exec app php artisan migrate
```

5. Install front dependencies and build assets locally or in another Node container.

