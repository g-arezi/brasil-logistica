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

## Configuracao rapida

```bash
cp .env.example .env
php artisan key:generate
npm install
```

Ajuste as variaveis de banco no `.env` para PostgreSQL/PostGIS e rode:

```bash
php artisan migrate
php artisan queue:listen
php artisan reverb:start
php artisan serve
npm run dev
```

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
