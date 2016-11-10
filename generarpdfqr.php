<?php
//agregar libreria tcpdf
require_once 'tcpdf/tcpdf.php';
include('phpqrcode/qrlib.php');

 
 $matricula = $_GET['matricula'];

	$h = "localhost";
	$u = "root";
	$p = "";
	$server= mysqli_connect($h,$u,$p,'db_piva')
		or die ("No se pudo conectar".mysqli_error($server));		
	$sql = "select * from cursos where DSMatricula='".$matricula."';";
	$sql2=mysqli_query($server, $sql);
	
	$filas = mysqli_fetch_array($sql2);

	$mat = $filas['DSMatricula'];
	$modulo = $filas['DSModulo'];
	$periodo = $filas['DSPeriodo'];
	$grupo = $filas['DSGrupo'];
	$apr =$filas['DSAprobado'];
	$car= $filas['DSCarrera'];
	$fech = $filas['DSFecha_cap'];
	$ins = $filas['DSInstructor'];
	$curso = $filas['DSCurso'];
	
	//ubicacion de las imagenes QR que se generen
	$ruta = "codigos/";
	$datos = "Alumno: desconocido, Carrera: ".$car.", matricula: ".$mat.", modulos: ".$modulo.", periodo: ".$periodo.", grupo: ".$grupo.",
	 aprobado: ".$apr.", fecha: ".$fech.", instructor: ".$ins.", curso: ".$curso;
	$filename = $ruta."QR".md5($datos).".png";
	QRcode::png($datos, $filename, 'M', 2, 2);
	
//clase para crear header y footer personalizado
class mipdf extends TCPDF{  
  //Header personalizado
  public function Header() {
    //imagen en header
    $logo = 'img/utp.png';
    $this->Image($logo, 25, 10, 25, '', 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
        
    $this->SetFont('helvetica', 'B', 20);
    $this->Cell(0, 0, 'Talleres PIVA', 0, false, 'C', 0, '', 0, false, 'T', 'M');
		
	$logop = 'img/piva_ico.png';
    $this->Image($logop, 165, 10, 25, '', 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
  }
  
  //footer personalizado
  public function Footer() {
    // posicion
    $this->SetY(-15);
    // fuente
    $this->SetFont('helvetica', 'I', 8);
    // numero de pagina
    $this->Cell(0, 10, '© Derechos Reservados Universidad Tecnológica de Puebla Departamento de Servicios Estudiantiles Políticas de Uso Rev. SYS: Octubre 2016', 0, false, 'C', 0, '', 0, false, 'T', 'M');
	
  }
}
 
//iniciando un nuevo pdf
$pdf = new mipdf(PDF_PAGE_ORIENTATION, 'mm', 'Letter', true, 'UTF-8', false);
 
//establecer margenes
$pdf->SetMargins(10, 35, 10);
$pdf->SetHeaderMargin(20);
 
//informacion del pdf
$pdf->SetCreator('desconocido');
$pdf->SetAuthor('desconocido');
$pdf->SetTitle('Ejemplo de pdf con tcpdf');
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
 
//tipo de fuente y tamanio
$pdf->SetFont('helvetica', '', 12);
 
//agregar pag 1
$pdf->AddPage();
$html = '

<h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;UTP</h2>
Matricula: '.$matricula.'<br>
Nombre del alumno: <br>
Carrera: <br> 
Genero: <br>

<br>
<table border="1" style="text-align: center;">
            <thead>
                <tr style="color:white; background-color:#005195; text-align: center;">
                    <th>Curso</th>
                    <th>Módulo</th>
                    <th>Aprobado</th>
                    <th>Cuatrimestre</th>
                    <th>Carrera</th>
                    <th>Instructor</th>
                    <th>Fecha Captura</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>'.$curso.'</td>
                    <td>'.$modulo.'</td>
                    <td>'.$apr.'</td>
                    <td>'.$grupo.'</td>
                    <td>'.$car.'</td>
                    <td>'.$ins.'</td>
                    <td>'.$fech.'</td>
                </tr>
            </tbody>
        </table>
';
//escribe el texto en la hoja
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

$pdf->image($filename, 150, 220);
 
//terminar el pdf
$pdf->Output('kiuvox.pdf', 'I');
?>