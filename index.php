<?php

$db_srv = '172.27.48.125';
$db_usr = 'merhengo';
$db_psw = '10ceroun0';
$db_name = 'abps_control';
header('Content-Type: text/html; charset=UTF-8');

$connection_options = array('Database'=>''.$db_name.'', 'UID'=>''.$db_usr.'', 'PWD'=>''.$db_psw.'');
$conn = sqlsrv_connect($db_srv, $connection_options);

$sql1 = "select id_coordinador, nombres_coordinador, apellidos_coordinador, campaign_coordinador from coordinadores order by nombres_coordinador";
$sql2 = "select nombre_campaign, id_campaign from campaigns order by nombre_campaign asc";
$stmt2 = sqlsrv_query($conn, $sql2);

$campaigns = array();
$coordinadores = array();

while($camp = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC))
{
    $stmt1 = sqlsrv_query($conn, $sql1);
    while ($coord = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC))
    {
        if($coord["campaign_coordinador"] == $camp["id_campaign"])
        {
            $nombre = $coord['nombres_coordinador']." ".$coord['apellidos_coordinador'];
            $pushable = ("$('<option></option>').");
            $pushable = $pushable.'attr("value", "'.$coord['id_coordinador'].'").text("'.$nombre.'")';
            array_push($coordinadores, $pushable);
        }
    }
    if(!$coordinadores == array()){
        array_push($campaigns, array($camp['id_campaign'], $coordinadores));
        $coordinadores = array();
    }
}

for($i = 0; $i < count($campaigns); $i++)
{
    $camp[$campaigns[$i][0]] = $campaigns[$i][1];
}


function llamarCoord(){
    global $camp;
    $indices = array_keys($camp);
    for($i = 0; $i < count($indices); $i++){
        $idcamp = $indices[$i];
        echo "idcamps.push(".$idcamp.");";
        
        $pushable = ("$('<option></option>').");
        $pushable = $pushable.'attr("value", "'.$idcamp.'").text("'.$idcamp.'")';
        
        echo "idcamps.push(".$pushable."); ";
                
        for($j = 0; $j < count($camp[$idcamp]); $j++){
            echo "    arreglo.push(".$camp[$idcamp][$j].");";
        }
        echo "    gral[".$idcamp."] = arreglo;";
        echo "    arreglo = [];";
    }
    
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Información de diadema</title>

    <meta http-equiv="Content-type" content="text/html; utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-select.css" rel="stylesheet">
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/bootstrap.custom.css" rel="stylesheet">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-select.js"></script>
</head>
<body>
    <div class="container">
        <form class="form-horizontal" role="form" action="agregar.php" method="post" id="formulario" name="formulario">
            <fieldset class="">
                <legend align="center">Ingreso de información</legend>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <?php
                        
                        if(isset($_GET['do']))
                        {
                            if($_GET['do'] == 1)
                            {
                                //echo '<h3>Registro agregado correctamente</h3>';
                                echo '<div class="alert alert-success" role="alert">';
                                echo '  <strong>Dispositivo agregado correctamente.</strong>';
                                echo '</div>';
                            }
                            else if($_GET['do'] == 0)
                            {
                                //echo '<h3>Error al realizar la operación</h3>';
                                echo '<div class="alert alert-danger" role="alert">';
                                echo '    <strong>No se agregó el dispositivo.</strong>';
                                echo '</div>';
                            }
                            else
                            {
                                echo '<h3>&nbsp;</h3>';
                                echo '<br>';
                            }
                        }
                        else
                        {
                            echo '<h3>&nbsp;</h3>';
                            echo '<br>';
                        }
                        
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="campaign"></label>
                    <div class="col-md-4 input-group">
                        <span class="input-group-addon"><strong>&nbsp;&nbsp;&nbsp;Campaña&nbsp;&nbsp;&nbsp;</strong></span>
                        <select id="campaign" name="campaign" class="form-control" required>
                            <option value="" selected disabled>Seleccione su campaña</option>
                        <?php
                            $stmt2 = sqlsrv_query($conn, $sql2);
                            while($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC))
                            {
                                $nombre = $row['nombres_coordinador']." ".$row['apellidos_coordinador'];
                                $campana= $row['id_campaign'];
                                
                                echo '                    <option value="'.$row['id_campaign'].'">'.$row['nombre_campaign'].'</option>';
                                echo "\xA";
                            }
                            sqlsrv_free_stmt($stmt2);
                        ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="coordinador"></label>
                    <div class="col-md-4 input-group">
                        <span class="input-group-addon"><strong> Coordinador</strong></span>
                        <select id="coordinador" name="coordinador" class="form-control" required>
                            <option value="" selected disabled>Seleccione su coordinador</option>
                            <?php
                                while($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC))
                                {
                                    echo '                    <option value="'.$row['id_campaign'].'">'.$row['nombre_campaign'].'</option>';
                                }
                                sqlsrv_free_stmt($stmt1);
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-md-4 control-label" for="marc"></label>
                    <div class="col-md-4 input-group">
                        <span class="input-group-addon">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        <select id="marca" name="marca" class="selectpicker show-tick form-control" data-live-search="true" title="Seleccione una marca" required>
                            <option value="china">Contact Center Américas</option>
                            <option value="china">Avaya</option>
                            <option value="jabra">Jabra</option>
                            <option value="plantronics">Plantronics</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="sn"></label>
                    <div class="col-md-4 input-group">
                        <span class="input-group-addon">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="fa fa-slack fa-fw"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        <input id="sn" name="sn" type="text"  data-toggle="tooltip" data-placement="bottom" title="<br><img src='img/sn.jpg' width='80%' height='80%'><br><br>Verifique el serial que indica la imagen, ubicado al respaldo del control de sonido.<br><br>" placeholder="Serial de fábrica" class="form-control input-md" placeholder="Serial Jabra" class="form-control input-md" required autocomplete="off" disabled>
                    </div>
                </div>
                <div class="form-group" style="position:relative">
                    <label class="col-md-4 control-label" for="id" name="id"></label>
                    <div class="col-md-4 input-group">
                        <span class="input-group-addon" id="basic-addon1"><strong> Consecutivo</strong></span>
                        <input id="id" name="id" type="text" data-toggle="tooltip" data-placement="bottom" title="<br><img src='img/consecutivo.jpg' width='95%' height='95%'><br><br>Verifique el consecutivo grabado en la bocina de la diadema. Si encuentra más de uno, ingrese el que inicia en ABPS. <br><br>Si no encuentra ninguno, escriba <strong>''no tiene''<strong><br><br>" placeholder="Consecutivo grabado en la bocina" class="form-control input-md" autocomplete="off" required>
                    </div>
                </div>
                <div class="form-group" style="position:relative; top: -20px; max-height:0px" align="right">
                    <label class="col-md-4 control-label" for="tnt" name="tnt"></label>
                    <div class="col-md-4 input-group">
                        <label class="checkbox-inline"><input id="tnt" name="tnt" type="checkbox"> No tiene</label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="nombre"></label>
                    <div class="col-md-4 input-group">
                        <span class="input-group-addon">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        <input id="nombre" name="nombre" type="text" data-toggle="tooltip" data-placement="top" title="<br><img src='img/ejip.jpg' width='80%' height='80%'><br><br>Ingrese su nombre completo. Si la diadema está fija al equipo, ingrese la dirección IP del computador.<br><br>" placeholder="Nombre del agente o IP del equipo" class="form-control input-md" required autocomplete="off">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8 input-group" align="right">
                        <fieldset>
                            <button id="ingresar" name="ingresar" class="btn btn-success">Agregar información</button>
                        </fieldset>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
    <script>
        
        var gral = [];
        var arreglo = [];
        var idcamps = [];
        
        $('#tnt').change(function() {
            if (this.checked) {
                $("#id").prop("value", "No tiene");
                $("#id").prop("disabled", true);
            } else {
                $("#id").prop("value", "");
                $("#id").prop("disabled", false);
            }
        });
        
        $("#marca").on("changed.bs.select", function (e) {
            var val = $("#marca").val();
            if(val == "jabra")
            {
                $("#sn").prop("disabled", false);
            }
            else
            {
                $("#sn").prop("disabled", true);
            }
        });
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip({
                animation: true,
                animated: 'fade',
                html: true,
                delay: {show: 300, hide: 100}
            });
            $('[data-toggle="tooltip"]').tooltip().off("focusin focusout");
        });
        
        <?php
            llamarCoord();
        ?>

        $('#campaign').change(function () {
            $lacamp = $( "#campaign option:selected" ).val();
            $("#coordinador").empty();
            $("#coordinador").append(gral[$lacamp]);
        });
        
        </script>
</body>
</html>