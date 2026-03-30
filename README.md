<p align="center">
  <img src="public/images/Logo.png" alt="Brasil Logistica Logo" width="250">
</p>

# Brasil Logistica

Sistema moderno e escalavel focado em conectar Empresas, Transportadoras, Agenciadores e Motoristas de todo o Brasil para negociacao e gestao de fretes.

##  Principais Features e Funcionalidades

### 1. Sistema de Perfis e Acesso (Multi-Tenancy / Roles)
- O sistema possui controle rigido de acesso atravessando **Niveis de Acesso**:
  - `Admin`: Gestao irrestrita.
  - `Empresa/Agenciador/Transportadora`: Perfis geradores de cargas. Podem visualizar motoristas, usar o chat e focar na insercao de mercadorias no sistema.
  - `Motorista`: Visualiza as cargas, mas nao possui acesso a interfaces de publicacao.
- Login com verificacao e ambiente de middleware assegurando direcionamentos dinamicos `/dashboard` conformes com a responsabilidade do acesso.

### 2. Painel de Fretes de Ultima Geracao (Freight Board)
- **Filtros Avancados Dinamicos:** Interacao realtime (via Livewire) para filtrar fretes instantaneamente por *Estado, Cidade, e Tipo de Veiculo* (selecao modernizada via tags clicaveis).
- **Lista de Fretes Otimizada:** Apresenta claramente o frete disponivel, preco, veiculo obrigatorio, trajeto base e botoes de acao (Chat, Detalhes).
- **Updates em Tempo Real:** Escuta de WebSockets (`Reverb/Echo`) implementada para recarregar o quadro assim que um novo frete eh lancado (`FreightPublished`).

### 3. Gerenciamento e Publicacao de Cargas
- **Postagem de Frete (`PostFreight`):** Tela exclusiva e padronizada em Dark Mode para preenchimento de Origem, Destino, Veiculo requerido, Preco financeiro e _Detalhes/Observacoes extras_.
- **Sistematica Geografica Preparada:** Sistema arquitetado com suporte hibrido para conexoes PostgreSQL (com processamentos geometricos `ST_MakePoint` utilizando `PostGIS`) em prol da visualizacao geografica via raio (`ST_DWithin`).
- **Exclusao Segura:** Autonomia do publicador. Quem publica o frete, recebe a autorizacao logica na modelagem para pode-lo excluir `auth()->id() === company_id`.

### 4. Interface Grafica Padronizada (UI/UX)
- Totalmente envelopada em componentes Blade rodando o core do **TailwindCSS**.
- Focado e unificado no design **Dark Mode**, gerando economia visual e profissionalidade em monitores de logistica.
- **Painel de Detalhes em Modais:** "Ver Mais" e modal responsivo que mostra de forma interativa tempo, distancia, preco pre-formatado, e as informacoes adicionais postadas pelo dono do frete.

### 5. Chat & Suporte (Comunicacao)
- Insercoes nativas engatilhadas nos botoes *"Falar no Chat"* dos fretes permitindo a ponte relacional entre o Motorista disponivel a rodar com aquela placa e a Empresa/Agenciador.
- Rotas protegidas `/chat` e `/suporte`.

## 🛠 Instalacao e Setup 

**Requisitos:** 
- PHP 8.2+
- Composer
- Node.js & NPM
- Servidor de Banco de Dados de sua preferencia (PostgreSQL recomendado pelas features geoespaciais, mas compativel com SQLite/MySQL nativo em outras frentes).

1. Clone o repositorio:
```bash
git clone https://github.com/SEU_USUARIO/BrasilLogistica.git
cd BrasilLogistica
```

2. Instale as dependencias do PHP e Node:
```bash
composer install
npm install && npm run build
```

3. Configure o `.env`:
```bash
cp .env.example .env
php artisan key:generate
```

4. Execute as migracoes (que criarao o banco) e carregue os dados de testes (Seeders contem os usuarios base da demonstracao: `admin@demo.com`, `empresa@demo.com`, `transportadora@demo.com`, `agenciador@demo.com`, `motorista@demo.com` todos utilizando senha `password`):
```bash
php artisan migrate:fresh --seed
```

5. Rode a aplicacao e o websocket:
```bash
php artisan serve
php artisan reverb:start
```

## 🐳 Executando com Docker

Se preferir rodar o projeto isolado utilizando as configuracoes prontas do Docker e Docker Compose, siga os passos abaixo:

1. Clone o repositorio e copie as variaveis de ambiente:
```bash
git clone https://github.com/SEU_USUARIO/BrasilLogistica.git
cd BrasilLogistica
cp .env.example .env
```

2. Edite o seu arquivo `.env` para conectar aos containers criados pelo Docker (PostgreSQL e Redis):
```env
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=brasil_logistica
DB_USERNAME=postgres
DB_PASSWORD=postgres

CACHE_STORE=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
REDIS_HOST=redis
```

3. Suba as maquinas com Docker Compose:
```bash
docker-compose up -d
```

4. Acesse o container principal para gerar as chaves e popular o banco de dados:
```bash
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate:fresh --seed
```

5. Instale as dependencias do front-end e compile os assets do Vite:
```bash
npm install && npm run build
```

Nesse momento o app estara exposto na sua porta local mapeada **https://localhost** com o websocket funcionando internamente na 8080.

> **Aviso de "Conexão Insegura" no Localhost:** O uso de `https` no ambiente de desenvolvimento com o Docker aciona o servidor web Caddy que, por não conseguir emitir certificados oficiais para `localhost`, assina o seu próprio certificado. Isso gerará um aviso em seu navegador (ex: "A conexão não é segura"). Basta avançar/ignorar o aviso para abrir a aplicação normalmente.
>
> ### 🌍 Publicando em uma VPS (Produção com HTTPS Oficial)
> 
> Na hora de hospedar seu projeto e vinculá-lo a um **domínio real** (ex: `seu-site.com.br`), o próprio Caddy emitirá o certificado SSL automaticamente e gratuitamente através da Let's Encrypt, e **o aviso de segurança sumirá (Cadeado Verde)**.
> 
> Para isso, apenas 2 passos são necessários antes de rodar o repositório na VPS:
> 
> 1. No arquivo `docker-compose.yml`, altere o comando do servio `web`:
> ```yaml
> command: caddy reverse-proxy --from https://seu-dominio.com.br --to http://app:8000
> ```
> 2. No arquivo `.env`, altere o `APP_URL`:
> ```env
> APP_URL=https://seu-dominio.com.br
> ```
> Garanta que os registros de DNS do seu domnio esto apontando para o IP pblico da sua VPS e rode `docker-compose up -d`. O servidor web far o resto!

## 🚀 Posso rodar esse projeto no Vercel utilizando Docker?
Sim e não. O Vercel é focado nativamente em Serverless Functions (para Next.js, Nuxt, etc.) e **não suporta Docker diretamente** de forma tradicional (com banco de dados, Redis e filas em background) na plataforma principal.
No entanto, caso você queira fazer o deploy da aplicação Laravel apenas como API ou rodar PHP no Vercel, isso é possível utilizando bibliotecas como o `vercel-php`, mas perderá suporte a features de background workers, websockets e containers de banco de dados.
A **recomendação ideal** para projetos complexos com banco, filas e Redis é utilizar uma VPS comum (DigitalOcean, AWS EC2, Hetzner, etc.) e rodar via Docker como configurado acima.

## 🔒 Testes Automaticos
A plataforma conta com a suite de testes Pest:
```bash
php artisan test
```
**Nota:** O arquivo `phpunit.xml` foi devidamente modificado para isolar o disparo do ambiente de testes como banco de dados em driver array p/ sessions, blindando conflitos de cookies/CSRF durante avaliacoes do frontend.

## CI no GitHub Actions

Pipeline em `.github/workflows/ci.yml` executa:

- `composer validate --strict`
- `./vendor/bin/pint --test`
- `php artisan test`
- `npm run build`


## Dados mockados para desenvolvimento

O seeder cria usuarios e fretes de demonstracao:

- Empresa: `empresa@demo.com`
- Transportadora: `transportadora@demo.com`
- Agenciador: `agenciador@demo.com`
- Admin: `admin@demo.com`
- Motorista: `motorista@demo.com`
- Senha padrao dos usuarios de factory/breeze: `password`

```bash
php artisan migrate:fresh --seed
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
