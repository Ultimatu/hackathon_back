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



