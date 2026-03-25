# Brasil Logistica

Sistema moderno e escalavel focado em conectar Empresas, Transportadoras, Agenciadores e Motoristas de todo o Brasil para negociacao e gestao de fretes.

## 🚀 Principais Features e Funcionalidades

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
