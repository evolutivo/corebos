<?php


Class Cuestionario {

	private $cuestionarioid;
	private $name;
	private $estadocuestionario;
	private $note;
	private $description;
	private $categorias = array();
	private $respuestas = array();
	private $db;

	public function __construct(array $aData = null){
		if(isset($aData)){
			foreach($aData as $propiedad => $v){
				if(property_exists($this,$propiedad)){
					$this->$propiedad = $v;
				}
			}
		}

		$this->preguntas = self::getPreguntasCuestionario();
		$this->respuestas = self::getRespuestasCuestionario($this->cuestionarioid);
	}


	/**
	 * Funcion que recibe un id de cuestionario y devuelve si
	 * existe el cuestionario o no.
	 *
	 */

	public function existeCuestionario($idcuestionario){
		return CuestionarioDAO::existeCuestionario($idcuestionario);
	}

	public function getCuestionario($idcuestionario){
		$db = new CuestionarioDAO;
		return new Cuestionario($db->getCuestionario($idcuestionario));
	}

	public function getCuestionariobyRev($idrevision){
		$db = new CuestionarioDAO;
		return new Cuestionario($db->getCuestionariobyRev($idrevision));
	}

	public function getPreguntasCuestionario(){
		if(is_null($this->db)){
			$db = $this->db =  new CuestionarioDAO;
		}else{
			$db = $this->db;
		}
		$aResultado = $db->getPreguntasCuestionario($this->cuestionarioid);
		$aPreguntas = array();
		foreach($aResultado as $aData){
			$aPreguntas[] = new Pregunta($aData);
		}
			
		$this->preguntas = $aPreguntas;
		return $aPreguntas;
	}

	public function getCategorias(){
		$db = new CuestionarioDAO;
		$aResultado = $db->getCategoriasCuestionario($this->cuestionarioid);
		$aCategorias = array();
		foreach($aResultado as $aData){
			$aCategorias[] =  new Categoria($aData); 
		}
		return $aCategorias;
	}

	static public function getRespuestasCuestionario($idcuestionario){
		$db = new CuestionarioDAO;
		$aResultado = $db->getRespuestasCuestionario($idcuestionario);
		$aRespuestas = array();
		foreach($aResultado as $aData){
			$aRespuestas[$aData['preguntasid']] = new Respuesta($aData);
		}
		return $aRespuestas;
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