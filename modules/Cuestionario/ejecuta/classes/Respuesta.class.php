<?php

Class Respuesta {

	private $revisionid; //relacion con Cuestionario.cuestionarioid
	private $preguntasid; //Pregunta.preguntasid;
	private $question; //Pregunta.question
	private $categoriapregunta; //pregunta.categoriapregunta
	private $subcategoriapregunta; //pregunta.subcategoriapregunta
	private $respondida; //
	private $respuesta; //texto capturado o por defecto con pregunta.yes
	private $respuestaid; //Si|No|NoA|Text
	private $yes_points;
	private $no_points;
	private $modificado;
	
	static private $db;

	const RESPONDIDA = 1;
	const SINRESPONDER = 0;



	public function __construct(array $aData = null){
		if(isset($aData)){
			foreach($aData as $propiedad => $v){
				if(property_exists($this,$propiedad)){
					$this->$propiedad = $v;
				}
			}
			$this->modificado = true;
		}
	}

	public function __call($metodo, $argumento = null) {
		$prefijo = strtolower(substr($metodo, 0, 3));
		$propiedad = strtolower(substr($metodo, 3));
		if (empty($prefijo) || empty($propiedad)) {return;}

		//		 echo $metodo.'   '.$prefijo.'    '.$propiedad; die();
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


	public function _save(){

		if(is_null($this->db)){
			$db = $this->db = new CuestionarioDAO;
		}else{
			$db = self::db;
		}
		$db->saveRespuesta($this);
	}

}
?>