# Symfony Docker (PHP8 / Caddy / Postgresql)

A [Docker](https://www.docker.com/)-based installer and runtime for the [Symfony](https://symfony.com) web framework, with full [HTTP/2](https://symfony.com/doc/current/weblink.html), HTTP/3 and HTTPS support.

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/)
2. Run `docker compose build --pull --no-cache` to build fresh images
3. Run `docker compose up` (the logs will be displayed in the current shell) or Run `docker compose up -d` to run in background 
4. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
5. Run `docker compose down --remove-orphans` to stop the Docker containers.
6. Run `docker compose logs -f` to display current logs, `docker compose logs -f [CONTAINER_NAME]` to display specific container's current logs 

## Migrations and Fixtures

The entities represent our tables in the database. While the database is dockerized and running, it is effectively empty. Run the following 2 commands to generate migrations and run them to create the tables.

```bash
# inside the php container IF THERE ARE NO MIGRATION FILES
docker compose exec -it php bin/console make:migration
# then run migrations
# doctrine:migration:migrate
docker compose exec -it php bin/console d:m:m
```

Finally, run fixtures to add faker data into our database, so that it isn't empty.

```bash
# doctrine:fixtures:load
docker compose exec -it php bin/console d:f:l
```

## Ollama 

To ensure Mistral.AI is installed inside our project, we will have to manually install it with Ollama with the following command ;

```bash
# get inside ollama container
docker compose exec ollama
# Preferably run ollama pull mistral inside docker ollama's container exec function.
# perform the following command to download and use mistral.ai
ollama pull mistral
# to exit ;
exit
```