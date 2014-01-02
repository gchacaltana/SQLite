<?php
/**
* Operaciones SQLite con PHP
* @author: Gonzalo Chacaltana Buleje <gchacaltanab@outlook.com>
*/
//Obteniendo la version de SQLite y PHP que estemos utilizando.
//**************************************************
header('Content-Type: text/html; charset=utf-8');

echo "Versión de SQLite : ". sqlite_libversion()."<br/>";
echo "Versión de PHP : ". phpversion();

//Insertando registros
//**************************************************
//A diferencia de una base de datos convencional, en SQLite, creamos
//archvios .db que serian nuetras tablas, por lo que lo recomendable
//es que los almacenemos en un directorio
$connect = sqlite_open('base_datos_sqlite/tabla_persona.db', 0666, $error);
if (!$connect)
    die($error);

//SQL que queremos ejecutar.
$sql = "CREATE TABLE personas(id integer PRIMARY KEY," .
 "documento_identidad text UNIQUE NOT NULL,".
 "nombre_completo text NOT NULL,".
 "sexo text CHECK(sexo IN ('M', 'F')))";

//Ejecutamos SQL
$exec = sqlite_exec($connect, $sql, $error);

//verificamos resultado.
if (!$exec)
    die("No se pudo ejecutar el query. $error");

echo "Base de datos creado exitosamente";

//cerramos la conexion.
sqlite_close($connect);


//Insertando registros II
//**************************************************
//abrimos la conexion a nuestra tabla persona.db
$connect = sqlite_open('base_datos_sqlite/persona.db', 0666, $error);

if (!$connect)
    die($error);

//Armamos insert SQL
$sql = "INSERT INTO personas VALUES(1,'42548565','Carlos Prada', 'M');".
"INSERT INTO personas VALUES(2,'49542522','Jimena Casas', 'F');".
"INSERT INTO personas VALUES(3,'47542543','Gonzalo Chacaltana', 'M');";

$exec = sqlite_exec($connect, $sql);
if (!$exec)
    die("No se pudo ejecutar la sentencia SQL.");

echo "Registros ingresados exitosamente.";

//cerramos conexion SQLite
sqlite_close($connect);


//Si tuvieras que ingresar un nombre como por ejemplo "O'Neil" (con comilla simple), 
//deberías poder primero aplicar un scape string, por ejemplo:
//*****************************************************************

$connect = sqlite_open('base_datos_sqlite/persona.db', 0666, $error);

if (!$connect)
    die($error);

$name = "Hugo O'Neill";
$name_es = sqlite_escape_string($name);

$sql = "INSERT INTO personas VALUES(4,'43587854', '$name_es', 'M');";

$exec = sqlite_exec($connect, $sql);
if (!$exec)
    die("No se pudo ejecutar la sentencia SQL.");

echo "Registro ingresado exitosamente.";

//cerramos la conexion SQLite
sqlite_close($connect);


//Recuperando registro de SQLite
//**************************************************
//Abriendo conexion a nuestra tabla persona.db
$connect = sqlite_open('base_datos_sqlite/persona.db', 0666, $error);

if (!$connect)
    die($error);

$query = "SELECT id,documento_identidad,nombre_completo,sexo FROM personas";
$result = sqlite_query($connect, $query);
if (!$result)
    die("No se puede ejecutar la consulta.");

//Obtenemos la informacion en forma de array asociativo.
$row = sqlite_fetch_array($result, SQLITE_ASSOC);
print_r($row);
echo "<br>";

//La funcion sqlite_rewind, hace que el puntero regrese al inicio del conjunto
//de resultados.
sqlite_rewind($result);
$row = sqlite_fetch_array($result, SQLITE_NUM);
print_r($row);
echo "<br>";

sqlite_rewind($result);
$row = sqlite_fetch_array($result, SQLITE_BOTH);
print_r($row);
echo "<br>";

//cerramos conexion
sqlite_close($connect);


//Recorriendo los datos con indices asociativos
//**************************************************

//abrimos conexion a la tabla persona.db
$connect = sqlite_open('base_datos_sqlite/persona.db', 0666, $error);

if (!$connect)
    die($error);

$query = "SELECT id,documento_identidad,nombre_completo,sexo FROM personas";
$result = sqlite_query($connect, $query);
if (!$result)
    die("No se puede ejecutar la consulta.");

while ($row = sqlite_fetch_array($result, SQLITE_ASSOC)) {
    echo $row['documento_identidad'] . " : " . $row['nombre_completo'];
    echo "<br>";
}

//cerramos la conexion.
sqlite_close($connect);
?>
