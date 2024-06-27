# Hackathon Readme

## Lancement de projet

### Docker

Pour lancer le projet Symfony ;

```bash
docker-compose up -d --build
# pour le supprimer
docker-compose down -v
```

### Commande PHP/Symfony

Pour realiser les commandes Symfony, il faudra rentrer dans le container Symfony pour executer les commandes php/composer ;

```bash
docker-compose exec app bash
# pour sortir
exit
```