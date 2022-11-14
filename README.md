# Trabajo especial de Web 2 ApiRest

- url base http://localhost/proyectos/Tpe-parte2/api

## Juegos

### Listado de Juegos

GET /juegos

Mediante este recurso se genera una solicitud de listado de juegos.
Este recurso admite la posibilidad de agregar filtros por QueryParams:

- (opcional) order y sort son requeridos en conjunto.
- (opcional) limitPage: valor por defecto = 3. 
- (opcional) offset: valor por defecto = 0.
- (opcional) precio y operatorPrice son requeridos en conjunto.

Ejemplo: http://localhost/proyectos/Tpe-parte2/api/juegos?order=asc&sort=id_juego&offset=3&limitPage=3

#### Response code
- 200 Expected response [{"id_juego":4,"nombre":"Tom Raider","descripcion":"Relata los intensos y conflictivos or\u00edgenes de Lara Croft y su transformaci\u00f3n de joven asustadiza a endurecida superviviente.","precio":2000,"id_genero":2},{"id_juego":32,"nombre":"lol","descripcion":"dshjhakd","precio":1500,"id_genero":15},{"id_juego":33,"nombre":"wow","descripcion":"dnsajkdsakj","precio":150,"id_genero":15}]
- 404 Expected response "No se encontraron resultados"

### Informacion de un Juego

GET /juegos/{id_juego}

Mediante este recurso se genera una solicitud de informacion de un juego segun su id.

Ejemplo: http://localhost/proyectos/Tpe-parte2/api/juegos/{id_juego}

#### Response code
- 200 Expected response {"id_juego":4,"nombre":"Tom Raider","descripcion":"Relata los intensos y conflictivos or\u00edgenes de Lara Croft y su transformaci\u00f3n de joven asustadiza a endurecida superviviente.","precio":2000,"id_genero":2}
- 404 Expected response "No existe el juego con el id={id_juego}"

### Obtencion de Token

GET /auth/token

Mediante este recurso se genera un token de autenticacion.
El EndPoint requiere authorization, con username y password correspondientes a "email" y "password" de la tabla user respectivamente.

Ejemplo: http://localhost/proyectos/Tpe-parte2/api/auth/token

#### Response code
- 200 Expected response "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MSwibmFtZSI6InVzZXJAZ21haWwuY29tIiwiZXhwIjoxNjY4NDU2ODc0fQ.PTTi-LrsPcSFbTvex8sL9p4gE-F3PGgr-i4mp5O8JqE"
- 401 Expected response "No autorizado"

### Agregado de Juego

POST /juegos

Mediante este recurso se agrega una nueva entidad.
Para esta instancia se requiere un Token de autenticacion.

Ejemplo: http://localhost/proyectos/Tpe-parte2/api/juegos

--header 'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MSwibmFtZSI6InVzZXJAZ21haWwuY29tIiwiZXhwIjoxNjY4NDU2ODc0fQ.PTTi-LrsPcSFbTvex8sL9p4gE-F3PGgr-i4mp5O8JqE'

#### Response code
- 400 Expected response "Completa todos los campos"
- 401 Expected response "No estas logueado"
- 404 Expected response  "No existe el genero con el id={id_genero}"
- 201 Expected response {"id_juego": 41,"nombre": "God of War","descripcion": "este juego se inserto desde una api rest","precio": 7500, "id_genero": 4}

### Modificacion de Juego

PUT /juegos/{id_juego}

Mediante este recurso se modifica una entidad.
Para esta instancia se requiere un Token de autenticacion.

Ejemplo: http://localhost/proyectos/Tpe-parte2/api/juegos/{id_juego}

--header 'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MSwibmFtZSI6InVzZXJAZ21haWwuY29tIiwiZXhwIjoxNjY4NDU2ODc0fQ.PTTi-LrsPcSFbTvex8sL9p4gE-F3PGgr-i4mp5O8JqE'

#### Response code
- 400 Expected response "Completa todos los campos"
- 401 Expected response "No estas logueado"
- 404 Expected response "El juego no se ha encontrado"
- 201 Expected response "El juego ha sido actualizado"


### Eliminiacion de Juego

DELETE /juegos/{id_juego}

Mediante este recurso se elimina una entidad.

Ejemplo: http://localhost/proyectos/Tpe-parte2/api/juegos/{id_juego}

#### Response code
- 200 Expected response "Juego eliminado con exito"
- 404 Expected response "El juego con el id={id_juego} no existe"



