<?php

header('Content-Type: text/html; charset=UTF-8');

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Inicio de sesión</title>
    <meta http-equiv="Content-type" content="text/html; utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-select.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.custom.css">
    <script type="text/javascript" src="js/charts.loader.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap.custom.js"></script>
    <script src="js/bootstrap-select.js"></script>
</head>
<body>
    <div class="container">
        <form class="form-horizontal" role="form" action="login.php" method="post">
            <fieldset class="">
                <legend>Inicio de sesión</legend>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="id" name="id"></label>
                    <div class="col-md-4 input-group">
                        <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-user"></span></span>
                        <input id="id" name="id" type="text" placeholder="ID de campaña" class="form-control input-md" autocomplete="off" required autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label" for="password"></label>
                    <div class="col-md-4 input-group">
                        <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-lock"></span></span>
                        <input id="password" name="password" type="password" placeholder="Clave" class="form-control input-md" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8 input-group" align="right">
                        <fieldset>
                            <button id="ingresar" name="ingresar" class="btn btn-primary" style="padding:15px">Ingresar</button>
                        </fieldset>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</body>
</html>