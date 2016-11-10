<?php
/*include("http://sestudiantiles.utpuebla.edu.mx/piv_2013/asistencias/funciones.php");
include("http://sestudiantiles.utpuebla.edu.mx/piv_2013/asistencias/clases.php");*/
require ("funciones_piva.php");
$periodo = new periodoGlobal(); // actual 20
$grupo_piva = new grupoPIVA("","","","",""); //<---Borrar
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
	<title>Programa Institucional de Valores</title>
	<!--link rel="shortcut icon" href="<?//= DIRECTORIO ?>/logos/piva_ico.ico">
	<link rel="stylesheet" type="text/css" href="<?//= DIRECTORIO ?>/css/estilo.css"/>
	<link rel="stylesheet" type="text/css" href="<?//= DIRECTORIO ?>/css/tutorias_2013.css"/>
	<link rel="stylesheet" type="text/css" href="<?//= DIRECTORIO ?>/css/fuentes.css"/>
	<script language="javascript" src="<?//= DIRECTORIO ?>/js/funciones.js"></script-->

    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="css/fancybox/jquery.fancybox.css" rel="stylesheet">
    <link href="skins/default.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="css/estilo.css">
    <script src="js/jquery.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
	<script src='https://www.google.com/recaptcha/api.js'></script>    
	<script>
	function validaCaptcha()	
	{
		var captcha = grecaptcha.getResponse();
		if(captcha==""){
            $("#message").attr("class","error");
            $("#message").html("<strong>Por favor indique que no es un robot</strong>");
			return true;
		}
		else
			return true;
	}
	</script>

</head>
<body>
<?php include("header.html");//include(DIRECTORIO."/encabezado_outlook_1.php"); ?>
<?php
if(isset($_POST["guardar"]))
{
	$matricula 	= $_GET["matricula"];
	$id_curso	= $_GET["clave"];

	$con_1 		= $_POST["con_1"];
	$con_2 		= $_POST["con_2"];
	$con_3 		= $_POST["con_3"];

	$ins_1 		= $_POST["ins_1"];
	$ins_2 		= $_POST["ins_2"];
	$ins_3 		= $_POST["ins_3"];
	$ins_4 		= $_POST["ins_4"];
	$ins_5 		= $_POST["ins_5"];

	$imp_1 		= $_POST["imp_1"];
	$imp_2 		= $_POST["imp_2"];
	$imp_3 		= $_POST["imp_3"];
	$imp_4 		= $_POST["imp_4"];
	$imp_5 		= $_POST["imp_5"];

	$fecha = date("d/m/Y H:i:s");

	$datab = mysql_connect('localhost', 'root', '');
	mysql_select_db('db_piva',$datab);
	$sql = "insert into `satisfaccion` values ('', $id_curso, '$matricula', $con_1, $con_2, $con_3, $ins_1, $ins_2, $ins_3, $ins_4, $ins_5, $imp_1, $imp_2, $imp_3, $imp_4, $imp_5, '$fecha')";
	//echo "$sql <br>";
	$insertar = mysql_query($sql,$datab);
	mysql_close($datab);
	?>
	<script>
		alert("Gracias por tu participación");
		//window.location = "encuesta_piva2016.php";
	</script>
	<?php
}
?>
<div id="contenido" class="container">
<?php
    if(isset($_POST["consultar"]))
	{ 
		$matricula = $_POST["matricula"];
		$alumno = new alumnoSAIIUT($matricula);
		$instructor = new InstructorPIVA($grupo_piva->DSInstructor, "", "");
?>
    <!--p class="derecha"><input type="button" name="" value="Imprimir" onclick="print();"></p!-->   
	<div>
        <h3><u>Información Alumno</u></h3>
        <p>Matrícula: <strong><?= $alumno->matricula ?></strong></p>
        <p>Nombre del  alumno: <strong> <?= utf8_encode($alumno->nombre." ".$alumno->app." ".$alumno->apm) ?> </strong></p>
        <p>División y  Carrera: <strong> <?= utf8_encode($alumno->carrera) ?> </strong></p>
        <p>Género: <strong> <?= $alumno->sexo ?></strong></p>
    </div>
    
    <!--table style="width:99%; margin: 10px 0;" class="tabla_azul_2"!-->
    <h3><u>Módulos cursados</u></h3>
    <div class="btn-group pull-right">
        <button class="btn btn-default" onclick="location.href='consulta_piva2016.php'">
            <span class="fa fa-home"></span>
            <span>Consultar otra matrícula</span>
        </button>
        <button class="btn btn-default" onclick="location.href='generarpdfqr.php?matricula=<?php echo $matricula; ?>'">
            <span class="fa fa-print"></span>
            <span>Imprimir</span>
        </button>
    </div>
    <div class="clearfix"></div>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>Curso</th>
                    <th>Módulo</th>
                    <th>Aprobado</th>
                    <th>Cuatrimestre</th>
                    <th>Carrera</th>
                    <th>Instructor</th>
                    <th>Fecha Captura</th>
                </tr>
            </thead>
            <tbody class="text-center">
            <?php
                $datab = mysql_connect("localhost", "root", "");
                mysql_select_db("db_piva",$datab);
                $sql = "SELECT * FROM `cursos` where DSMatricula='$matricula'";
                $resultado = mysql_query($sql ,$datab);
                while ($mirow = mysql_fetch_row($resultado)) 
                {
                    $grupo_piva = new grupoPIVA($mirow[8], "", "", "", "");
                    $evaluacion = new encuestaSatisfaccion($matricula, $grupo_piva->DSClave);
            ?>
                <!--tr class="centrado"-->
                <tr>
                    <td><?= $mirow[8] ?></td>
                    <td><?= $grupo_piva->nombre_curso ?></td>
                    <td>
                    <?php
                        if($grupo_piva->DSPeriodo>19)
                        {
                            if($mirow[4]=="si" && $evaluacion->folio>0)
                                echo "si";
                            else
                                echo "no";
                        }
                        else
                            echo $mirow[4];
                    ?>
                    </td>
                    <td><?= $mirow[2] ?></td>
                    <td><?= utf8_encode(dame_valor("global","carreras",$mirow[5],1)) ?></td>
                    <td><?= utf8_encode(dame_valor("piva","instructores",$mirow[7],1)) ?></td>
                    <td><?= $mirow[6] ?></td>
                </tr>
            <?php
                    unset($grupo_piva);
                    unset($evaluacion);
                }
            ?>
            </tbody>
        </table>
    </div>
    <!--div class="derecha" style="margin: 20px 0 10px 0;"!-->
    <!--a href="consulta_piva.php">Consultar otra matrícula</a!-->
    <div class="pull-right">
        <p class="small">
            <em>Fuente: Sistema PIVA, <?= $periodo->nombre ?></em>
            <em>Fecha: <?= date("d/m/Y H:i:s") ?></em>
        </p>
	</div>
<?php
	}
	else
    {
?>		
    <div id="login" class="row text-center">
        <div class="border col-xs-8 col-sm-6 col-md-4 col-xs-offset-2 col-sm-offset-3 col-md-offset-4">
            <h2>Acceso Alumnos</h2>
            <p class="lead">Por favor introduce tu <em>matrícula</em>:</p>
            <form method="post" onSubmit="return validaCaptcha();">
                <div class="input-group">
                    <p class="input-group-addon input-matricula"><span>Matrícula<img class="glyphicon" src="img/lock-icon.png" /></p>
                    <input type="text" name="matricula" id="matricula" class="form-control" placeholder="32........" required="required">
                </div>
                <div class="g-recaptcha" data-sitekey="6LcUxSkTAAAAAONr2s8oP-mM-AukEPqsmosZUIPn"></div>
                <input type="submit" name="consultar" id="consultar" value=" Consultar " class="btn btn-primary"/>
            </form>
            <p id="message"></p>
        </div>
    </div>
<?php
	} // fin if consultar
?>
</div> <!-- fin contenido-->
<?php include("footer.html"); ?>
</body>
</html>