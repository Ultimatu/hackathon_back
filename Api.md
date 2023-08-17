## Description des API


### Authentification
- [POST] /api/auth/login
  requête:
  ```json
  {
    "email": "string",
    "password": "string"
  }
  ```

  reponse:  En cas de succès
  ```json
  { 
    
    "Authorization:" {
        "token": "string",
        "type": "string"
        
    },
    "user": {
        "id": "integer",
        "nom": "string",
        "prenom": "string",
        "email": "string",
        "email_verified_at": "string",
        "commune": "string",
        "adresse": "string",
        "phone": "string",
        "numero_cni": "string",
        "elector_card": "string",
        "role_id": "integer",
        "created_at": "string",
        "updated_at": "string"


    }


  }
  ```
    reponse:  En cas d'erreur
    ```json
    {
        "error": "string",
    }
    ```
- [POST] /api/auth/register

 requête: 
 ```json
    {
        "nom": "string",
        "prenom": "string",
        "email": "string",
        "password": "string",
        "commune": "string",
        "adresse": "string",
        "phone": "string",
        "numero_cni": "string",
        "elector_card": "string",
        "role_id": "integer" // 1 pour les administrateurs et 2 pour les candidats et 3 pour les électeurs ou utilisateurs lambda
    }
 ```
 réponse:  En cas de succès
 ```json
  { 
    
    "Authorization:" {
        "token": "string",
        "type": "string"
        
    },
    "user": {
        "id": "integer",
        "nom": "string",
        "prenom": "string",
        "email": "string",
        "email_verified_at": "string",
        "commune": "string",
        "adresse": "string",
        "phone": "string",
        "numero_cni": "string",
        "elector_card": "string",
        "role_id": "integer",
        "created_at": "string",
        "updated_at": "string"


    }, 
    "status": 201,
    "message": "string",
}

    ```
    réponse:  En cas d'erreur
    ```json
        {
            "message": "string",
            "code": "400||422"
            "si code = 422": {
                "errors": {
                    //specifique à chaque champ non valide
                }
            }
        }
```
 

- [POST] api/auth/logout

 requete: 
 ```json
    {
        "Authorization en header": {
            "token": "string",
            "type": "Bearer"
        }
    }
 ```


### Administration des Utilisateurs


- pour faire une requête pour admin il faut ajouter le header suivant:
```json
    {
        "Authorization": {
            "token": "string", // le token de l'administrateur renvoyé lors de la connexion
            "type": "Bearer"
        }
    }
```
 # requete pour les administrateurs
-  [GET] /api/admin/users

  requête: 
  ```json
    {
        "Authorization en header": {
            "token": "string",
            "type": "Bearer"
        }
    }
  ```
  ````json{
  "success": true,
  "message": "list de tout les utilisateurs paginé par 10",
  "data": [] 
  // les données de la pagination que renvoie laravel sont : 
  "current_page": // le numéro de la page actuelle,
    "data": [] // les données de la page actuelle,
    "first_page_url": // l'url de la première page,
    "from": // le numéro du premier élément de la page actuelle,
    "last_page": // le numéro de la dernière page,
    "last_page_url": // l'url de la dernière page,
    "next_page_url": // l'url de la page suivante,
    "path": // l'url de la page actuelle,
    "per_page": // le nombre d'éléments par page,
    "prev_page_url": // l'url de la page précédente,
    "to": // le numéro du dernier élément de la page actuelle,
    "total": // le nombre total d'éléments
  
} ````




- [GET] api/admin/users/{id}

  requête Entête: 
  ```json
    {
        "Authorization en header": {
            "token": "string",
            "type": "Bearer"
        }
    }
  ```
  reponse:  En cas de succès
  ```json
  { 
    "success": true,
    "message": "utilisateur trouvé",
    "data": {
        "id": "integer",
        "nom": "string",
        "prenom": "string",
        "email": "string",
        "email_verified_at": "string",
        "commune": "string",
        "adresse": "string",
        "phone": "string",
        "numero_cni": "string",
        "elector_card": "string",
        "role_id": "integer",
        "created_at": "string",
        "updated_at": "string"
    }
  }
  ```
    reponse:  En cas d'erreur
    ```json
    {
        "success": false,
        "message": "utilisateur non trouvé",
        
    }
    ```


- [DELETE] api/admin/users/{id}

  requête Entête: 
  ```json
    {
        "Authorization en header": {
            "token": "string",
            "type": "Bearer"
        }
    }
  ```
  reponse:  En cas de succès
  ```json
  { 
    "success": true,
    "message": "utilisateur supprimé",
  }
  ```
    reponse:  En cas d'erreur
    ```json
    {
        "success": false,
        "message": "utilisateur non trouvé",
        
  }
```




