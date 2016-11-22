<?php
    include 'php/php_func.php';
    header('Content-Type: text/html; charset=UTF-8');

    $usuario = $_POST['id'];
    $password = $_POST['password'];

    $db_srv = '172.27.48.125';
    $db_usr = 'merhengo';
    $db_psw = '10ceroun0';
    $db_name = 'abps_control';

    $connection_options = array('Database'=>''.$db_name.'', 'UID'=>''.$db_usr.'', 'PWD'=>''.$db_psw.'');
    $conexion = sqlsrv_connect($db_srv, $connection_options);
    if(!is_resource($conexion)){
        session_start();
        $_SESSION['ns'] = 3;
        header('Location: index.php'); var_dump(sqlsrv_errors(SQLSRV_ERR_ALL)); exit(0);
    }
    
    $comilladoble = '"';
    $prohibidos = array("'", $comilladoble, ";", "=");

    $usuario = str_replace($prohibidos, "-", $usuario);
    $password = str_replace($prohibidos, "-", $password);
    
    session_start();

    $tabla = '';
    $columna='';
    $passw = '';

    if($_SESSION['rol'] == '0')
    {
        $tabla    = 'dbo.admins';
        $columna  = 'id_admin';
        $passw    = 'pass_admin';
        $apellido = 'apellidos_admin';
        $fecha    = 'ultimoacceso_admin';
    }
    else if($_SESSION['rol'] == '1')
    {
        $tabla = 'dbo.coordinadores';
        $columna='id_coordinador';
        $passw = 'pass_coordinador';
        $apellido = 'apellidos_coordinador';
        $fecha = 'ultimoacceso_coordinador';
    }
    $sql = "select * from ".$tabla." where ".$columna." = '".$usuario."' and ".$passw." COLLATE Modern_Spanish_CS_AS = '".$password."' order by ".$apellido." COLLATE Modern_Spanish_CS_AS_KS";
    //$sql = "select * from ".$tabla." where ".$columna." COLLATE Modern_Spanish_CS_AS = '".$usuario."' and ".$passw." COLLATE Modern_Spanish_CS_AS = '".$password."' COLLATE latin2_czech_cs";
    $qry = sqlsrv_query($conexion, $sql, array(), array( "Scrollable" => 'static' ));
    $resultado = sqlsrv_fetch_array($qry);

    if($resultado[0] == $usuario)//--------------------------------acceso autorizado
    {
        $sql = "update ".$tabla." set ".$fecha." = GETDATE() where ".$columna." = '".$usuario."'";
        $qry = sqlsrv_query($conexion, $sql, array(), array( "Scrollable" => 'static' ));
        if($_SESSION['rol'] == '1')
        {
            $_SESSION['nombres']  = $resultado['nombres_coordinador'];
            $_SESSION['apellidos']= $resultado['apellidos_coordinador'];
            $_SESSION['id']= $usuario;
            $_SESSION['horaAcceso'] = time();
             header('Location: index2.php?i=0');
        }
        else if ($_SESSION['rol'] == '0')
        {
            $_SESSION['nombres']  = $resultado['nombres_admin'];
            $_SESSION['apellidos']= $resultado['apellidos_admin'];
            $_SESSION['horaAcceso'] = time();
             header('Location: index2.php?i=0');
        }
        $_SESSION['ns'] = 0;
    }
    else
    {
        $_SESSION['ns'] = 1;//-------------------------------------acceso restringido
        if($_SESSION['rol'] == '0')
        {
            header('Location: index2.php?i=1');
        }
        else if($_SESSION['rol'] == '1')
        {
            header('Location: index2.php?i=1');
        }
    }
    sqlsrv_close($conn);
?>