## Pré-requisitos

1. **Docker**  
   Certifique-se de que o Docker está instalado e funcionando na sua máquina.  
   [Instalar Docker](https://docs.docker.com/get-docker/)

2. **Git**  
   Verifique se o Git está instalado.  
   [Instalar Git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git)

3. **Composer** 
   [Instalar Composer](https://getcomposer.org/download/)

---

## Passos para Configuração

### 1. Clonar o Repositório

Clone o repositório do GitHub e navegue até o diretório do projeto:

```bash
git clone <https://github.com/BrunoSantosCosta/encurtado>
cd <encurtado>
```

### 2. Instalar as Dependências com Composer

Para instalar as dependências, execute:

```bash
composer install
```

### 3. Copiar o Arquivo .env

Na raiz do projeto crie o arquivo .env e copie o .env.exemple:

```bash
cp .env.example .env
```

### 4. Configurar o Laravel Sail

Certifique-se de que o Laravel Sail está listado como dependência no arquivo composer.json. Se o Laravel Sail não estiver instalado, você pode adicioná-lo manualmente:

```bash
composer require laravel/sail --dev
```

### 5. Subir os Contêineres

Suba os contêineres Docker com o seguinte comando:

```bash
./vendor/bin/sail up -d
```

### 6. Migrar o Banco de Dados

Execute as migrações para preparar o banco de dados:

```bash
./vendor/bin/sail artisan migrate
```

## Acessando o Projeto
Após subir os contêineres, o projeto estará acessível no navegador em:


http://localhost

Nota: Verifique se a porta especificada no arquivo .env (normalmente 80) não está em uso por outro serviço.
