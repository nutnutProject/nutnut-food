#NutNut food

## Pour travailler sur le projet

```
#Clonage du projet

cd c:\xampp\htdocs
git clone projet
cd projet
```

On installe les dépendances

```
composer install
```

On configure la base de données dans `.env.local`.

On crée la base de données

```
php bin\console doctrine:database:create
```

On importe le shéma de la BDD :

```
php bin\console doctrine:migrations:migrate
