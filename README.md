# SnowTricks

Instructions pour installer le projet :

Récupérer le code.

Ajouter un serveur de base de données MySQL dans le fichier .env

Ajouter un serveur smtp dans le fichier .env

Entrer les commandes suivantes :

php bin/console doctrine:database:create

php bin/console doctrine:schema:update --force

php bin/console doctrine:fixture:load
