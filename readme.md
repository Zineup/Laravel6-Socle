# SOCLE - Laravel 6   

### Demo Credentials

**User:** user@user.com  
**Password:** secret


### CRUD Basic

- Le projet est livré avec un CRUD Basic pour le model ``City``.

#### Contrôleur :

- Le contrôleur ``CityController`` se trouve sur l'emplacement ``app\Http\Controllers\Frontend\CRUD``, il contient les 7 méthodes du CRUD (index, create, store, show, edit, update et delete) qui utilisent la classe ``StoreCityRequest`` injectée pour valider et agir comme un deuxième contrôle de sécurité après le middleware.

- le contrôleur utilise la classe ``CityRepository`` injectée qui se trouve sur l'emplacement ``app\Repositories\Frontend\CRUD``

#### Vues :

- le CRUD est accompagné avec des vues qui facilitent la manipulation des données du model ``City``. ces vues se trouvent sur l'emplacement ``resources\views\frontend\crud``

#### Tests :
 
- Sur l'emplacement ``tests\Feature\Frontend\CRUD``, vous trouverer la classe ``CityTest`` pour tester les méthodes CRUD du contrôleur.

- vous pouvez lancer le test avec la commande :

```bash
vendor\bin\phpunit.bat tests\Feature\Frontend\CRUD\CityTest.php
```

#### Route :

- Après l'authentification, vous pouvez utiliser la route ``localhost:port/city`` pour accéder à la page index du CRUD City.
