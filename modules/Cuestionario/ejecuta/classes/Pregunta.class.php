<?php

Class Pregunta {
	
	private $preguntasid; 
	private $question;
	private $categoriapregunta;
	private $subcategoriapregunta;
	private $estadopregunta;
	private $yes; 
	private $no;
	private $yes_points;
	private $no_points;
	private $description;
	
	
	public function __construct(array $aData = null){
        if(isset($aData)){
            foreach($aData as $propiedad => $v){
                if(property_exists($this,$propiedad)){
                    $this->$propiedad = $v;
                }
            }
        }
    }
	
    public function getPregunta($idpregunta){
    	$db = new CuestionarioDAO;
    	return new Pregunta(CuestionarioDAO::getPreguntaByID($idpregunta,$db));
    }
    
    public function __toString(){
    	return $this->question;
    }
    
    public function __call($metodo, $argumento = null) {
        $prefijo = strtolower(substr($metodo, 0, 3));
        $propiedad = strtolower(substr($metodo, 3));
        if (empty($prefijo) || empty($propiedad)) {return;}

        if(!property_exists($this,$propiedad)){
            throw new Exception ('El metodo solicitado no existe:'.$metodo.'()');
            return;
        }
        if ($prefijo == "get" && isset($this->$propiedad)) {return $this->$propiedad;}
        if ($prefijo == "set") {
            $this->$propiedad = $argumento[0];
            $this->modificado = true;
        }
    }
    
    public function isPropiedad(string $sPropiedad){
        if(property_exists($this,$sPropiedad)){return true; }else{ return false;}
    }
    
    
	
	
	
	
}
?>