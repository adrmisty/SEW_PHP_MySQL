
<?php

session_start();

/*
    Representación de una base de datos con MySQL
    @author Adriana R.F. - UO282798
*/
class BaseDatos {

    // Constantes: cuenta de MySQL
    private $servername = "localhost";
    private $username = "DBUSER2022";
    private $password = "DBPSWD2022";

    // Queries de creación
    private $contarFilas = "SELECT * FROM pruebasusabilidad";
    private $verSiExiste = "SELECT * FROM pruebasusabilidad WHERE dni='";
    private $crearBase = "CREATE DATABASE IF NOT EXISTS usabilidad COLLATE utf8_spanish_ci";
    private $crearTabla = 
        "CREATE TABLE IF NOT EXISTS PruebasUsabilidad ( 
        dni VARCHAR(9) NOT NULL,
        nombre VARCHAR(255) NOT NULL, 
        apellidos VARCHAR(255) NOT NULL,  
        email VARCHAR(255) NOT NULL,
        telefono VARCHAR(9) NOT NULL,
        edad int NOT NULL,
        sexo VARCHAR(1) NOT NULL,
        nivel int NOT NULL,
        tiempo int NOT NULL,
        comentarios VARCHAR(255),
        propuestas VARCHAR(255),
        valoracion int,
        superado int,
        PRIMARY KEY (dni))";
    private $insertarPrueba = 
    "INSERT INTO pruebasusabilidad (dni, nombre, apellidos,email,telefono,
        edad,sexo,nivel,tiempo,comentarios,propuestas,valoracion,superado) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
    private $borrarTabla = "DROP TABLE PruebasUsabilidad IF EXISTS";
    private $buscarDatos = "SELECT * FROM pruebasusabilidad WHERE dni=?";
    private $borrarDatos = "DELETE FROM pruebasusabilidad WHERE dni=?";
    private $edadTotal = "SELECT sum(edad) from pruebasusabilidad";
    private $mujeresTotales = "SELECT count(*) from pruebasusabilidad where sexo='M'";
    private $hombresTotales = "SELECT count(*) from pruebasusabilidad where sexo='H'";
    private $nivelTotal = "SELECT sum(nivel) from pruebasusabilidad";
    private $valoracionTotal = "SELECT sum(valoracion) from pruebasusabilidad";
    private $tiempoTotal = "SELECT sum(tiempo) from pruebasusabilidad";
    private $superadoTotal = "SELECT sum(superado) from pruebasusabilidad";

    // Mensaje devuelto
    private $mensajeCreacionBD = "<p>No creada/conectada.</p>";
    private $mensajeCrearTabla = "<p>No creada.</p>";
    private $mensajeInsertarFila = "p>Todavía no se ha insertado nueva información.</p>";
    private $mensajeBuscarDatos = "<p>Todavía no ha realizado una búsqueda.</p>";
    private $mensajeModificarDatos = "<p>Todavía no ha realizado ninguna modificación.</p>";
    private $mensajeBorrarDatos = "<p>Todavía no ha realizado ningún borrado.</p>";
    private $mensajeInforme = "<p>Todavía no ha generado el informe.</p>";
    private $mensajeImportarCSV = "<p>Todavía no ha seleccionado un archivo para importar.</p>";
    private $mensajeExportarCSV = "<p>Todavía no se ha realizado la exportación de la BD.</p>";

    // Base de datos
    private $db;

    function __constructor(){}

    public function getMensajeBD(){
        echo $this->mensajeCreacionBD;
    }

    public function getMensajeCrearTabla(){
        echo $this->mensajeCrearTabla;
    }

    public function getMensajeInsertarFila(){
        echo $this->mensajeInsertarFila;
    }

    public function getMensajeBuscarDatos(){
        echo $this->mensajeBuscarDatos;
    }

    public function getMensajeModificarDatos(){
        echo $this->mensajeModificarDatos;
    }

    public function getMensajeBorrarDatos(){
        echo $this->mensajeBorrarDatos;
    }

    public function getMensajeInforme(){
        echo $this->mensajeInforme;
    }

    public function getMensajeImportarCSV(){
        echo $this->mensajeImportarCSV;
    }

    public function getMensajeExportarCSV(){
        echo $this->mensajeExportarCSV;
    }


    // Borra los datos en la tabla de usabilidad
    public function borrarTabla(){
        $this->db->query($this->borrarTabla);
    }

    // Reinicia los mensajes de la interfaz de usuario
    public function reiniciarMensajes(){
        $this->mensajeInsertarFila = "<p>Todavía no se ha insertado nueva información.</p>";
        $this->mensajeBorrarDatos = "<p>Todavía no ha realizado una búsqueda.</p>";            
        $this->mensajeBuscarDatos = "<p>Todavía no ha realizado ningún borrado.</p>";            
        $this->mensajeModificarDatos = "<p>Todavía no se ha realizado ninguna modificación.</p>";
        $this->mensajeInforme = "<p>Todavía no ha generado el informe.</p>";
        $this->mensajeImportarCSV = "<p>Todavía no ha seleccionado un archivo para importar.</p>";
        $this->mensajeExportarCSV = "<p>Todavía no se ha realizado la exportación de la BD.</p>";    
    }

    // Creación de una base de datos
    public function crearBD(){
        // Conexión al DBMS local
        $this->db = new mysqli($this->servername,$this->username,$this->password);
        $this->reiniciarMensajes();

        // Comprobar correcta conexión
        if ($this->db->connect_error){
            $this->mensajeCreacionBD="<p>ERROR de conexión: ". $this->db->connect_error . "</p>";
            exit();
        } else {
            $this->mensajeCreacionBD="<p>Conexión establecida con: ". $this->db->host_info . "</p>";
                    // En caso afirmativo, crear la BD
            if ($this->db->query($this->crearBase) === TRUE){
                $this->mensajeCreacionBD=$this->mensajeCreacionBD . "<p>Base de datos 'Usabilidad' creada con éxito</p>";
            } else {
                $this->mensajeCreacionBD= $this->mensajeCreacionBD . ". ERROR en la creación de la base de datos 'Usabilidad': " . $this->db->error;
                exit();
            } 
        }

    }

    // Creación de la tabla 'PruebasUsabilidad'
    public function crearTabla(){

        if ($this->mensajeCreacionBD === "<p>No creada.</p>"){
            $this->mensajeCrearTabla="<p>No se puede crear la tabla si no se ha conectado primero a la BD.</p>";
        } else {
            $this->reiniciarMensajes();
            $this->crearBD();
            $this->db->select_db("usabilidad");
            $this->borrarTabla();
            if ($this->db->query($this->crearTabla) === TRUE){
                $this->mensajeCrearTabla="<p>Tabla 'PruebasUsabilidad' creada con éxito</p>";
            } else {
                $this->mensajeCrearTabla="<p>ERROR en la creación de la tabla 'PruebasUsabilidad'. Error: " . $this->db->connect_error . "</p>";
            } 
            $this->db->close();           
        }
    }

    // Devuelve el número actual de filas en la tabla de pruebas
    public function getNumeroFilas(){
        $this->crearBD();
        $this->db->select_db("usabilidad");

        $count = ($this->db->query($this->contarFilas))->num_rows;
        $this->db->close();
        return $count;
    }

        // Devuelve el número actual de filas en la tabla de pruebas
    public function existeEnBD($dni){
        $this->crearBD();
        $this->db->select_db("usabilidad");

        $consulta = $this->verSiExiste . $dni . "'";
        $count = ($this->db->query($consulta))->num_rows;

        $this->db->close();
        return $count != 0;
    }


    // Insertar filas en la tabla
    public function insertarDatos(){
        $antes = $this->getNumeroFilas();
        $this->reiniciarMensajes();
        $this->crearBD();
        $this->db->select_db("usabilidad");
        $query = $this->db->prepare($this->insertarPrueba);

        // Para ver si la fila es insertada o ignorada

        // Añadir parámetros

        $query->bind_param('sssssisiissii', 
        $_POST["dni"],$_POST["nombre"], $_POST["apellidos"],
        $_POST["email"],$_POST["telefono"],$_POST["edad"],
        $_POST["sexo"],$_POST["nivel"],$_POST["tiempo"],
        $_POST["comentarios"],$_POST["propuestas"],$_POST["valoracion"],$_POST["superado"]);
        
        $query->execute();
        $despues = $this->getNumeroFilas();

        if ($antes<$despues)
            $this->mensajeInsertarFila = "<p>Fila insertada con éxito</p>";
        else
            $this->mensajeInsertarFila = "<p>La fila no ha podido insertarse por restricciones de integridad (el DNI ha de ser único)</p>";
        $query->close();
    }

    // Buscar datos a partir del DNI del participante
    public function buscarDatos(){

        // Preparar la consulta y ejecutarla
        $this->reiniciarMensajes();
        $this->crearBD();
        $this->db->select_db("usabilidad");
        $query = $this->db->prepare($this->buscarDatos);
        $dni = $_POST["buscarDNI"];
        $query->bind_param('s',$_POST["buscarDNI"]);
        $query->execute();

        // Coger el resultado
        $resultado = $query->get_result();
        // Mostrar los resultados
        if ($resultado->fetch_assoc()!=NULL){
            // Imprimir si existen
            $resultado->data_seek(0); // Solo tendrá un resultado
            $fila = $resultado->fetch_assoc();
            $val = $fila["valoracion"];
            if ($val){
                $superado = "SÍ";
            } else $superado = "NO";

            $this->mensajeBuscarDatos = "<p>Datos del participante con DNI '" . $dni . "': </p>";
            $this->mensajeBuscarDatos = $this->mensajeBuscarDatos . "<ul>" .
                "<li>Nombre: " . $fila["nombre"] . "</li>" .
                "<li>Apellidos: " . $fila["apellidos"] . "</li>" .
                "<li>E-mail: " . $fila["email"] . "</li>" .
                "<li>Teléfono: " . $fila["telefono"] . "</li>" .
                "<li>Edad: " . $fila["edad"] . "</li>" .
                "<li>Género: " . $fila["sexo"] . "</li>" .
                "<li>Nivel/Pericia tecnológica: " . $fila["nivel"] . "</li>" .
                "<li>Comentarios: " . $fila["comentarios"] . "</li>" .
                "<li>Propuestas: " . $fila["propuestas"] . "</li>" .
                "<li>Valoración de la app. (sobre 10): " . $fila["valoracion"] . "</li>" .
                "<li>Superado correctamente: " . $superado . "</li>" .
                "</ul>";
        } else {
            $this->mensajeBuscarDatos = "<p>La búsqueda del DNI '" . $dni . "' no ha arrojado resultados</p>";
        }
    }

    // Borrar un participante de la BD a partir del DNI
    public function borrarDatos(){

        // Preparar la consulta y ejecutarla
        $this->reiniciarMensajes();
        $this->crearBD();
        $this->db->select_db("usabilidad");
        $query = $this->db->prepare($this->borrarDatos);
        $dni = $_POST["borrarDNI"];
        if ($this->existeEnBD($dni)){
            $query->bind_param('s',$_POST["borrarDNI"]);
            $query->execute();
            $this->mensajeBorrarDatos = "<p>El participante con DNI '" . $dni . "' ha sido eliminado con éxito.<p>";
        } else {
            $this->mensajeBorrarDatos = "No se ha podido eliminar.</p>" . 
            "<p>El participante con DNI '" . $dni . "' no existe en la BD.</p>";
        }
    }


    // Modiicar datos en la tabla
    public function modificarDatos(){
        $this->reiniciarMensajes();
        $this->crearBD();
        $this->db->select_db("usabilidad");

        // --- Parámetros a modificar
        $dni = $_POST["modificarDNI"];
        $nombre = $_POST["modificarNombre"];
        $apellidos = $_POST["modificarApellidos"];
        $email = $_POST["modificarEmail"];
        $telefono = $_POST["modificarTelefono"];
        $edad = $_POST["modificarEdad"];
        $sexo = $_POST["modificarSexo"];
        $nivel = $_POST["modificarNivel"];
        $tiempo = $_POST["modificarTiempo"];
        $comentarios = $_POST["modificarComentarios"];
        $propuestas = $_POST["modificarPropuestas"];
        $valoracion = $_POST["modificarValoracion"];
        $superado = $_POST["modificarSuperado"];


        // Añadir parámetros
        // Modificar cada uno
        if ($this->existeEnBD($dni)){
            $modificacion = "";
            $this->crearBD();
            $this->db->select_db("usabilidad");
            if ($nombre != ""){
                $modificacion = "UPDATE PruebasUsabilidad SET nombre='" . $nombre ."' where dni='" . $dni . "'";
                $this->db->query($modificacion);
            }
    
            if ($apellidos != ""){
                $modificacion = "UPDATE PruebasUsabilidad SET apellidos='" . $apellidos ."' where dni='" . $dni . "'";
                $this->db->query($modificacion);
            }
    
            if ($email != ""){
                $modificacion = "UPDATE PruebasUsabilidad SET email='" . $email ."' where dni='" . $dni . "'";
                $this->db->query($modificacion);
            }
    
            if ($telefono != ""){
                $modificacion = "UPDATE PruebasUsabilidad SET telefono='" . $telefono ."' where dni='" . $dni . "'";
                $this->db->query($modificacion);
            }
    
            if ($edad != NULL){
                $modificacion = "UPDATE PruebasUsabilidad SET edad=" . $edad ." where dni='" . $dni . "'";
                $this->db->query($modificacion);
            }
    
            if ($sexo != ""){
                $modificacion = "UPDATE PruebasUsabilidad SET sexo='" . $sexo ."' where dni='" . $dni . "'";
                $this->db->query($modificacion);
            }
    
            if ($nivel != NULL){
                $modificacion = "UPDATE PruebasUsabilidad SET nivel=" . $nivel ." where dni='" . $dni . "'";
                $this->db->query($modificacion);
            }
    
            if ($tiempo != NULL){
                $modificacion = "UPDATE PruebasUsabilidad SET tiempo=" . $tiempo ." where dni='" . $dni . "'";
                $this->db->query($modificacion);
            }
    
            if ($comentarios != ""){
                $modificacion = "UPDATE PruebasUsabilidad SET comentarios='" . $comentarios ."' where dni='" . $dni . "'";
                $this->db->query($modificacion);
            }
    
            if ($propuestas != ""){
                $modificacion = "UPDATE PruebasUsabilidad SET propuestas='" . $propuestas ."' where dni='" . $dni . "'";
                $this->db->query($modificacion);
            }
    
            if ($valoracion != NULL){
                $modificacion = "UPDATE PruebasUsabilidad SET valoracion=" . $valoracion ." where dni='" . $dni . "'";
                $this->db->query($modificacion);
            }
    
            if ($superado != NULL){
                $modificacion = "UPDATE PruebasUsabilidad SET superado=" . $superado ." where dni='" . $dni . "'";
                $this->db->query($modificacion);
            }

            $this->mensajeModificarDatos = "Los cambios indicados han sido realizados con éxito.";    
        } else {
            echo "NO EXISTE";
            $this->mensajeModificarDatos = "No se ha podido modificar los datos. El DNI indicado no existe en la BD.";    
        }
    }

    // Devuelve la edad media de los participantes
    public function getEdadMedia($n){
        $this->crearBD();
        $this->db->select_db("usabilidad");

        $stmt = $this->db->prepare($this->edadTotal);
        $stmt->execute();
        $res = $stmt->get_result();
        $res->data_seek(0);
        $r = $res->fetch_assoc()["sum(edad)"];
        $this->db->close();
        return $r / $n;
    }

    // Devuelve la proporcion % de mujeres
    public function getProporcionMujeres($n){
        $this->crearBD();
        $this->db->select_db("usabilidad");

        $stmt = $this->db->prepare($this->mujeresTotales);
        $stmt->execute();
        $res = $stmt->get_result();
        $res->data_seek(0);
        $r = $res->fetch_assoc()["count(*)"];

        $this->db->close();
        return $r * 100 / $n;
    }

    // Devuelve la proporcion % de hombres
    public function getProporcionHombres($n){
        $this->crearBD();
        $this->db->select_db("usabilidad");

        $stmt = $this->db->prepare($this->hombresTotales);
        $stmt->execute();
        $res = $stmt->get_result();
        $res->data_seek(0);
        $r = $res->fetch_assoc()["count(*)"];

        $this->db->close();
        return $r * 100 / $n;
    }

    // Devuelve el tiempo medio de realizacion
    public function getTiempoMedio($n){
        $this->crearBD();
        $this->db->select_db("usabilidad");

        $stmt = $this->db->prepare($this->tiempoTotal);
        $stmt->execute();
        $res = $stmt->get_result();
        $res->data_seek(0);
        $r = $res->fetch_assoc()["sum(tiempo)"];

        $this->db->close();
        return $r / $n;
    }

    // Devuelve el tiempo medio de realizacion
    public function getNivelMedio($n){
        $this->crearBD();
        $this->db->select_db("usabilidad");

        $stmt = $this->db->prepare($this->nivelTotal);
        $stmt->execute();
        $res = $stmt->get_result();
        $res->data_seek(0);
        $r = $res->fetch_assoc()["sum(nivel)"];

        $this->db->close();
        return $r / $n;
    }

     // Devuelve el tiempo medio de realizacion
    public function getValoracionMedia($n){
        $this->crearBD();
        $this->db->select_db("usabilidad");

        $stmt = $this->db->prepare($this->valoracionTotal);
        $stmt->execute();
        $res = $stmt->get_result();
        $res->data_seek(0);
        $r = $res->fetch_assoc()["sum(valoracion)"];

        $this->db->close();
        return $r / $n;
    }


    // Devuelve el porcentaje de personas que han realizado la tarea bien
    public function getProporcionSuperado($n){
        $this->crearBD();
        $this->db->select_db("usabilidad");

        $stmt = $this->db->prepare($this->superadoTotal);
        $stmt->execute();
        $res = $stmt->get_result();
        $res->data_seek(0);
        $r = $res->fetch_assoc()["sum(superado)"];

        $this->db->close();
        return $r * 100 / $n;
    }




    // Genera un informe acerca de la base de datos
    public function generarInforme(){
        $this->reiniciarMensajes();
        $this->crearBD();
        $this->db->select_db("usabilidad");

        // Número de participantes
        $n = $this->getNumeroFilas();

        if ($n == 0){
            $this->mensajeInforme = "<p>No hay participantes en la BD para los que generar el informe.</p>";
        } else {
            $edad = $this->getEdadMedia($n);
            $mujeres = $this->getProporcionMujeres($n);
            $hombres = $this->getProporcionHombres($n);
            $nivel = $this->getNivelMedio($n);
            $tiempo = $this->getTiempoMedio($n);
            $valoracion = $this->getValoracionMedia($n);
            $superado = $this->getProporcionSuperado($n);

            $this->mensajeInforme = "<ul>" .
            "<li>Edad media: " . $edad . "</li>" .
            "<li>Proporción de mujeres: " . $mujeres . "% </li>" .
            "<li>Proporción de hombres: " . $hombres . "% </li>" .
            "<li>Nivel medio de pericia tecnológica: " . $nivel . "/10</li>" .
            "<li>Tiempo medio de realización: " . $tiempo . " segundos</li>" .
            "<li>Valoración media: " . $valoracion . "/10 </li>" .
            "<li>Superado correctamente: " . $superado . "% </li>" .
            "</ul>";
        }
    }

    private function insertarCSV($dni,$nombre,$apellidos,$email,
        $telefono,$edad, $sexo, $nivel, $tiempo, $comentarios, $propuestas, $valoracion, $superado){
            $this->crearBD();
            $this->db->select_db("usabilidad");
            $query = $this->db->prepare($this->insertarPrueba);
            // Añadir parámetros
            $query->bind_param('sssssisiissii', 
            $dni,$nombre,$apellidos,$email,
            $telefono,$edad,$sexo,$nivel, $tiempo, $comentarios, $propuestas, $valoracion, $superado);
        
            $query->execute();
            $despues = $this->getNumeroFilas();
            $query->close();
            return $despues;
        }

    // Exportar la base de datos a un archivo CSV
    public function exportarCSV(){
        $this->reiniciarMensajes();
        $this->crearBD();
        $this->db->select_db("usabilidad");

        // Archivo .csv
        $csv = $_POST['archivoCSV'] . ".csv"; // Nombre del archivo al que exportar
        $archivo = fopen($csv, "w");

        $query = $this->db->prepare($this->contarFilas);
        $query->execute();

        // Coger el resultado
        $result = $query->get_result();

        // Mostrar los resultados
        while ($fila = $result->fetch_array(MYSQLI_ASSOC)) {
                try {
                    $linea = implode(",",$fila) . "\n";
                    fwrite($archivo, $linea);                
                } catch (Exception $e) {
                    $this->mensajeExportarCSV = "<p>Ha ocurrido un error mientras se exportaban los datos.</p>";
                    return;
                }
            }
        $this->mensajeExportarCSV = "<p>La exportación se ha realizado con éxito.</p>";
        fclose($archivo);
    }

    // Importa un archivo en formato .csv y añade los contenidos a la BD
    public function importarCSV(){
        $this->reiniciarMensajes();
        $this->crearBD();
        $this->db->select_db("usabilidad");

        // Archivo .csv
        $archivo = $_FILES['seleccionarCSV']; // Array con toda la información del csv
        $csv = $_FILES['seleccionarCSV']['name']; // Array con toda la información del csv

        // Leer el archivo e insertar los datos
        if (($lector = fopen($csv, "r")) !== FALSE) {
            $i = 0;
            while (($datos = fgetcsv($lector, 1000, ",")) !== FALSE) { // Parsea el CSV
                // Array con los datos del csv
                
                MYSQLI_REPORT_OFF;
                error_reporting(E_ALL ^ (E_WARNING & E_NOTICE));
                error_reporting(E_ALL ^ (E_WARNING & E_NOTICE));

                try {
                    $dni = $datos[0];
                    $nombre = $datos[1];
                    $apellidos = $datos[2];
                    $email = $datos[3];
                    $telefono = $datos[4];
                    $edad = $datos[5];
                    $sexo = $datos[6];
                    $nivel = $datos[7];
                    $tiempo = $datos[8];
                    $comentarios = $datos[9];   
                    $propuestas = $datos[10];    
                    $valoracion = $datos[11];    
                    $superado = $datos[12];  
                    
                    // Ignorar posibles errores
                    if ($nombre==="" || $apellidos==="" || $email===""
                        || $telefono==="" || !is_numeric($telefono) || $sexo===""
                        || $tiempo<0 || $superado<0 || $superado>1 || $valoracion<0 || $valoracion>10){
                            continue;
                    } else {
                        $antes = $this->getNumeroFilas();
                        $this->reiniciarMensajes();
                        $despues = $this->insertarCSV($dni,$nombre,$apellidos,$email,
                        $telefono,$edad, $sexo, $nivel, $tiempo, $comentarios, $propuestas, $valoracion, $superado);

                        if ($antes==$despues){
                            $this->mensajeImportarCSV = "<p>No se ha podido insertar ninguna fila a partir del archivo .csv seleccionado.</p>";
                        } else {
                            $this->mensajeImportarCSV = "<p>Se han insertado las filas a partir del .csv importado con éxito.</p>";
                        }
                    }


                } catch (Exception $e){
                    $this->mensajeImportarCSV = "<p>Ha ocurrido un error con el procesamiento del archivo seleccionado.</p>";
                    break;
                }
                }
            fclose($lector);
        }
    }

}

// Definición de una nueva sesión
if (!isset($_SESSION['bd'])){
    $bd = new BaseDatos();
    $_SESSION['bd'] = $bd;        
}
// Interacción con todos los botones
if (count($_POST)>0)
{
    $bd = $_SESSION['bd'];

    if (isset($_POST['crearBD'])) $bd->crearBD();
    if (isset($_POST['crearTabla'])) $bd->crearTabla();
    if (isset($_POST['insertar'])) $bd->insertarDatos();
    if (isset($_POST['buscar'])) $bd->buscarDatos();
    if (isset($_POST['modificar'])) $bd->modificarDatos();
    if (isset($_POST['borrar'])) $bd->borrarDatos();
    if (isset($_POST['informe'])) $bd->generarInforme();
    if (isset($_POST['importarCSV'])) $bd->importarCSV();
    if (isset($_POST['exportarCSV'])) $bd->exportarCSV();

    $_SESSION['bd'] = $bd;
}

?>