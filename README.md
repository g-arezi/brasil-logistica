# BrasilLogistica (Fretebras Clone Skeleton)

Esqueleto completo em Laravel 11 com DDD Lite para marketplace de fretes, com foco em geolocalizacao, realtime e fluxos assincronos.

## Stack

- PHP 8.3+ (strict types)
- Laravel 11
- PostgreSQL + PostGIS
- Pest PHP
- Livewire + Tailwind (TALL)
- Laravel Reverb (WebSockets)
- Stancl Tenancy (multitenancy)

## Estrutura de dominios

- `app/Domains/Freight`
- `app/Domains/User`
- `app/Domains/Vehicle`

## Principais componentes

- `CreateFreightAction`: valida DTO, valida documento da empresa, calcula distancia e publica evento.
- `FreightFilterPipeline`: filtros por raio, tipo de caminhao e faixa de preco.
- `DistanceServiceInterface` + `MapboxDistanceService`.
- `FreightObserver`: dispara `SendFreightWebhookJob` para n8n (fila).
- API em `routes/api.php` com `GET /api/v1/freights` e `POST /api/v1/freights`.
- Livewire `FreightBoard` em tempo real com Reverb.
- Autenticacao com Laravel Breeze e redirecionamento por perfil (`driver`/`company`).

## Configuracao rapida

```bash
cp .env.example .env
php artisan key:generate
npm install
composer install
```

Ajuste as variaveis de banco no `.env` para PostgreSQL/PostGIS e rode:

```bash
php artisan migrate
php artisan queue:listen
php artisan reverb:start
php artisan serve
npm run dev
```

## Docker (PostgreSQL + PostGIS + Redis + Reverb)

Arquivos base prontos em `docker-compose.yml` e `docker/php/Dockerfile`.

```bash
cp .env.example .env
docker compose run --rm app composer install
docker compose run --rm app php artisan key:generate
docker compose up -d --build
docker compose exec app php artisan migrate
```

Detalhes em `docker/README.md`.

## Auth e perfis

- Registro captura `profile_type` (`driver`/`company`) e `document_number` (CPF/CNPJ).
- `GET /dashboard` redireciona automaticamente por perfil.
- Rotas protegidas por perfil:
  - `company.dashboard` em `/painel/empresa`
  - `driver.dashboard` em `/painel/motorista`

## CI no GitHub Actions

Pipeline em `.github/workflows/ci.yml` executa:

- `composer validate --strict`
- `./vendor/bin/pint --test`
- `php artisan test`
- `npm run build`

## Testes

```bash
php artisan test
```

## Versionamento no GitHub

```bash
git init
git add .
git commit -m "chore: bootstrap brasil logistica fretebras clone"
git branch -M main
git remote add origin https://github.com/SEU_USUARIO/brasil-logistica.git
git push -u origin main
```
