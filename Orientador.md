Role: Atue como Staff Software Engineer e Arquiteto Laravel Senior.
Contexto: Gerar o esqueleto completo de um clone do Fretebras (Plataforma de Logística).
Stack: PHP 8.3 (Strict Types), Laravel 11, PostgreSQL + PostGIS, Pest PHP, TailwindCSS + Livewire (TALL Stack).

Diretrizes de Arquitetura (Obrigatório):

Domain-Driven Design (Lite): Organize em app/Domains/{Freight, User, Vehicle}.

Strict Typing: Todo método deve ter type-hinting de retorno e de argumentos.

Imutabilidade: Use readonly properties e Constructor Property Promotion.

Patterns: Use Pipeline para filtros, Actions para lógica de negócio e DTOs (Spatie Laravel Data) para entrada de dados.

Componentes a serem gerados:

1. Database & Models:

Migrations com tipos geography(Point, 4326) para Origem/Destino e índices GIST.

Model Freight com UUID, Casts para Money (inteiros) e Scope de distância via PostGIS.

Model Vehicle com tipos (Truck, Bitrem, Sider, etc) via Enums PHP 8.

2. Core Logic (Actions & Services):

CreateFreightAction: Valida DTO, salva no DB e dispara FreightPublished event.

FreightFilterPipeline: Implemente o motor de busca usando o Pipeline Pattern (filtros por raio KM, tipo de caminhão e faixa de preço).

DistanceService: Interface e implementação para calcular KM e tempo estimado (via Google Maps ou Mapbox).

3. API & Web:

FreightController (API): Endpoints index e store usando Laravel Resources.

FreightBoard (Livewire Component): Uma listagem em tempo real com filtros reativos e paginação.

4. Integração & Eventos:

FreightObserver: Ao criar um frete, disparar um Webhook assíncrono (via Queue) para um endpoint do n8n (Evolution API).

5. Qualidade:

Um teste de feature em Pest PHP cobrindo o fluxo: "Motorista busca fretes num raio de 50km e encontra resultados compatíveis".

Instrução de Saída: Forneça a estrutura de pastas e o código dos arquivos principais. Priorize a legibilidade para o PhpStorm (use PHPDoc @property onde o Eloquent for dinâmico).

Aqui está a estrutura inicial para o seu Web App de logística inspirado no Fretebras, utilizando PHP 8.3, Laravel 11 e PostgreSQL.
### Migrations

```php
// database/migrations/2024_06_01_000000_create_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;  
class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('profile_type', ['driver', 'company']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
```

```php
// database/migrations/2024_06_01_000001_create_freights_table.php
use Illuminate\Database\Migrations\Migration;       
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateFreightsTable extends Migration
{
    public function up()
    {
        Schema::create('freights', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('origin_city');
            $table->string('origin_state');
            $table->decimal('origin_lat', 10, 7);
            $table->decimal('origin_long', 10, 7);
            $table->string('destination_city');
            $table->string('destination_state');
            $table->decimal('destination_lat', 10, 7);
            $table->decimal('destination_long', 10, 7); 
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('users')->onDelete('cascade');
        });
    }   
```
Multitenancy: planejo que as transportadoras gerenciem suas próprias frotas lá dentro, considere usar o pacote archtechx/tenancy.

Websockets: Use o Laravel Reverb (nativo do Laravel 11) para atualizar a lista de fretes na tela do motorista sem que ele precise dar F5.

Segurança: foque na validação rigorosa de documentos (CNPJ/CPF) via APIs de terceiros para evitar golpes de "frete fantasma".