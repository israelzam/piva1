<?php
class alumnoSAIIUT
{
    public $matricula = "";
    public $nombre = "Alumno";
    public $app = "Ap. Paterno";
    public $apm = "Ap. Materno";
    public $carrera = "Tecnologias de la Informacion";
    public $sexo = "Genero";
    
    function __construct($matricula)
    {
        $this->matricula = $matricula;
    }
}

class InstructorPIVA
{
    function __construct($DSInstructor, $otra_cosa1, $otra_cosa2)
    {
        
    }
}

class grupoPIVA
{
    public $DSClave;
    public $nombre_curso;
    public $DSPeriodo;
    public $DSInstructor;
    
    function __construct($nose, $nose1, $nose2, $nose3, $nose4)
    {
        
    }
}

class encuestaSatisfaccion
{
    public $folio;
    
    function __construct($matricula, $DSClave)
    {
        
    }
}

class periodoGlobal
{
    public $nombre = "SEP-DIC 2016";
}

function dame_valor($global,$carreras,$DSCarrera,$numero)
{
    if($global=="global")
    {
        return "TICS";
    }
    else
    {
        return "Instructor";
    }
}


?>