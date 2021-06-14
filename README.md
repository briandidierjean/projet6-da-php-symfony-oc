# SnowTricks

Instructions pour installer le projet :

Récupérer le code.

Copier le contenu du fichier .env dans un nouveau fichier nommé .env.local

Mettre la valeur APP_ENV=prod

Ajouter un serveur de base de données MySQL dans le fichier .env.local

Ajouter un serveur smtp dans le fichier .env.local

Entrer les commandes suivantes :

php bin/console doctrine:database:create

php bin/console doctrine:schema:update --force

php bin/console doctrine:fixture:load
