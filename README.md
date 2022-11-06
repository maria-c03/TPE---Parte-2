EndPoint GET Juegos
curl --location --request GET 'http://localhost/proyectos/Tpe-parte2/api/juegos?order=asc&sort=id_juego&offset=3&limitPage=3' \
--header 'Content-Type: application/json'

expected response [{"id_juego":4,"nombre":"Tom Raider","descripcion":"Relata los intensos y conflictivos or\u00edgenes de Lara Croft y su transformaci\u00f3n de joven asustadiza a endurecida superviviente.","precio":2000,"id_genero":2},{"id_juego":32,"nombre":"lol","descripcion":"dshjhakd","precio":1500,"id_genero":15},{"id_juego":33,"nombre":"wow","descripcion":"dnsajkdsakj","precio":150,"id_genero":15}]

PARAMETROS:
order y sort son requeridos en conjunto.
limitPage y offset no son requeridos, limitPage tiene valor por defecto igual a 3 y offseet posee un valor por defecto igual a 0.