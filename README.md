# Challenge
Este é um projeto criado com o Laravel Sail.

## Instalação
Para instalar e iniciar o projeto, siga os seguintes passos:

Clone este repositório para sua máquina local:

```bash
$ git clone https://github.com/Lucas-Samuel/challenge.git
```

Acesse o diretório do projeto e execute o comando para construir as imagens do Docker:

```bash
$ cd challenge
```

Inicie os contêineres do Docker:

```bash
$ ./vendor/bin/sail up -d
```

Acesse o contêiner do PHP:

```bash
$ ./vendor/bin/sail exec app bash
```

execute os comandos para instalar as dependências do Laravel e gere os arquivos de variáveis de ambiente:

```bash
$ composer install
$ cp .env.example .env
$ cp .env.example .env.testing
```

Gere uma nova chave para o projeto:

```bash
$ php artisan key:generate
```

Acesse o projeto no seu navegador:

```bash
http://localhost:8000
```
