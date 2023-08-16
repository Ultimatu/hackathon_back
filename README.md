## Configuration  Base de données


- Type de base de données : MySQL
- Nom de la base de données : `mavoix_database
- Nom d'utilisateur : `root`
- Mot passe : ''


#code sql : `CREATE DATABASE mavoix_database;`


## Migration de la base de données
une foix la base de données crée, allumer le serveur et faites la migration de la base de données avec la commande suivante : `php artisan migrate` ou  importez le fichier `mavoix_database.sql` qui se trouve dans le dossier `db_file` dans la base de données que vous avez crée Mais surtout pas les deux en même temps.

## Lancez le serveur
Pour lancer le serveur, utilisez la commande suivante : `php artisan serve`


## Version : laravel:10 

