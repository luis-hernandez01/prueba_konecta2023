# prueba_konecta2023
 Prueba Konecta
 
 Se debera clonar el repositorio dentro de un servidor de pruebas o local y colocamdo la ruta del proyecto se verá el contenido del proyecto.
 
 El nombre de la Base de datos se debera de llamar: konecta
 
Realizar una consulta que permita conocer cuál es el producto que más stock tiene.
SELECT * FROM productos ORDER by stock DESC LIMIT 1


Realizar una consulta que permita conocer cuál es el producto más vendido.
SELECT p.nombre_producto, SUM(d.cantidad) AS mas_vendido, SUM(p.precio) AS valor_total 
FROM productos p, detalle d 
WHERE d.id_producto=p.id GROUP BY p.nombre_producto ORDER BY SUM(d.cantidad) desc LIMIT 1

Credenciales de ingreso al sistema
Usuario: konecta
Contraseña: 123456789
