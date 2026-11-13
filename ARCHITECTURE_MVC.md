# Architecture MVC maison

## Organisation

- `index.php` est le point d'entrée de l'application.
- `app/bootstrap.php` démarre la session et distribue les pages vers les contrôleurs.
- `app/Controllers/` traite les demandes, vérifie les formulaires et choisit la vue à afficher.
- `app/Models/` contient les accès à la base de données et les requêtes préparées.
- `app/Views/` contient uniquement les pages HTML et leur affichage PHP.
- Les contrôleurs chargent leurs vues avec `require` après avoir préparé les variables nécessaires.
- `style.css` et `images/` restent à la racine pour conserver la configuration XAMPP actuelle.

## Parcours d'une requête

Le navigateur appelle une ancienne URL telle que `terrains.php`. Cette façade transmet la demande à `index.php`, qui appelle `app/bootstrap.php`. Le routeur sélectionne `TerrainController`, le contrôleur demande les données à `Terrain`, puis `View` affiche `app/Views/terrains/index.php`.

Les façades historiques sont conservées pour ne pas casser les liens déjà présents dans les pages.

## Contrôleurs et pages

- `HomeController` : page d'accueil.
- `TerrainController` : liste et détail des terrains.
- `ReservationController` : formulaire, création, annulation et historique des réservations.
- `AuthController` : connexion, inscription et déconnexion.
- `AdminController` : vérification du rôle admin et gestion des réservations.

## Base de données

Le schéma `sql.sql` définit désormais `client.role`, utilisé par l'administration. Pour une base déjà créée, vérifier d'abord la présence de la colonne :

```sql
SHOW COLUMNS FROM client LIKE 'role';
```

Si aucune ligne n'est retournée, exécuter une seule fois :

```sql
ALTER TABLE client ADD COLUMN role VARCHAR(20) NOT NULL DEFAULT 'client';
```

Pour donner les droits d'administration à un compte existant :

```sql
UPDATE client SET role = 'admin' WHERE login = 'ton_login';
```