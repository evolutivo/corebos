<?php

Class Categoria {

	private $nombre;
	private $subcategorias=array();
	private $db;


	public function __construct(array $aData = null){
		if(isset($aData)){
			foreach($aData as $propiedad => $v){
				if(property_exists($this,$propiedad)){
					$this->$propiedad = $v;
				}
			}
		}
//		$this->subcategorias = $this->getSubcategorias();
	}
	
	public function __toString(){
		return $this->nombre;
	}

	public function getSubcategorias($idcuestionario){
		$db = new CuestionarioDAO;
		$aResultado = $db->getSubcategorias($idcuestionario,$this->nombre);
        $aSubcategorias = array();
        foreach($aResultado as $aData){
            $aSubcategorias[] = new Subcategoria($aData);
        }
        $this->subcategorias = $aSubcategorias;
        return $aSubcategorias;
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