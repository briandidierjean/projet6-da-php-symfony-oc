# SnowTricks

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/1ae088e4e1f341aa9ea3b95bcde53e8f)](https://app.codacy.com/gh/briandidierjean/projet6-da-php-symfony-oc?utm_source=github.com&utm_medium=referral&utm_content=briandidierjean/projet6-da-php-symfony-oc&utm_campaign=Badge_Grade_Settings)

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
