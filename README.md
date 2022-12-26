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

Monte os contêineres do Docker:

```bash
$ docker run --rm --interactive --tty -v $(pwd):/app composer install
```

Copie e configure as variavels de ambiente:

```bash
$ cp .env.example .env
$ cp .env.example .env.testing
```

Inicie os contêineres:

```bash
$ ./vendor/bin/sail up -d
```

Gere uma nova chave para o projeto:

```bash
$ ./vendor/bin/sail artisan key:generate
```

Execute as migrations do banco de dados:

```bash
$ ./vendor/bin/sail artisan migrate
$ ./vendor/bin/sail artisan --env=testing migrate
```

Acesse o projeto no seu navegador:

```bash
http://localhost:80
```
