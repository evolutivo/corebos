<?php

Class CuestionarioDAO extends PDO{

	private $_dbConnection;

	static private function _connect() {
		$d=getcwd();
		chdir(APP_PATH.'../../..');
		include ("config.inc.php");
		chdir($d);
		$conexion = new PDO('mysql:host='.$dbconfig['db_server'].';port='.$dbconfig['db_port'].'; dbname='.$dbconfig['db_name'],$dbconfig['db_username'],$dbconfig['db_password'],array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $conexion;
	}

	public function __construct(){
		if (is_null($this->_dbConnection)) {
			$this->_dbConnection = self :: _connect();
		}
	}

	public function getCuestionario($idcuestionario){
		$sql = "Select * from vtiger_cuestionario WHERE cuestionarioid = ?";
		$stmt = $this->_dbConnection->prepare($sql);
		$stmt->execute(array($idcuestionario));
		return $stmt->fetch();
	}

	public function getCuestionariobyRev($idrevision){
		$sql = "Select * from vtiger_revision WHERE revisionid = ?";
		$stmt = $this->_dbConnection->prepare($sql);
		$stmt->execute(array($idrevision));
		return $stmt->fetch();
	}

	public function getCategoriasCuestionario($idcuestionario){
		$sql = "Select distinct(categoria) as nombre from vtiger_cuestiones  c
        WHERE cuestionarioid = ? order by c.categoria";
		$stmt = $this->_dbConnection->prepare($sql);
		$stmt->execute(array($idcuestionario));
		return $stmt->fetchAll();
	}
	public function getSubcategorias($idcuestionario, $categoria){
		$sql = "Select distinct(subcategoria) as nombre from vtiger_cuestiones  c
        WHERE cuestionarioid = ? and categoria = ? order by c.subcategoria";
		$stmt = $this->_dbConnection->prepare($sql);
		$stmt->execute(array($idcuestionario, $categoria));
		return $stmt->fetchAll();
	}

	public function getPreguntasBySubcategoria($idcuestionario, $categoria, $subcategoria){
		$sql = "Select * from vtiger_cuestiones  c
        left join vtiger_preguntas d
        on c.preguntasid = d.preguntasid
        WHERE cuestionarioid = ? and categoria = ? and subcategoria = ? order by c.cuestionesid";
		//echo $sql;
		//echo '<br>'.$idcuestionario.'<br>'.$categoria.'<br>'.$subcategoria;die();
		$stmt = $this->_dbConnection->prepare($sql);
		$stmt->execute(array($idcuestionario, $categoria, $subcategoria));
		return $stmt->fetchAll();
	}

	public function getPreguntaByID($idpregunta, $db){
		$sql = "Select * from vtiger_preguntas WHERE preguntasid = ?";
        $stmt = $db->_dbConnection->prepare($sql);
        $stmt->execute(array($idpregunta));
        return $stmt->fetch();
	}
	
	public function getPreguntasCuestionario($idcuestionario){
		$sql = "Select * from vtiger_cuestiones  c
		left join vtiger_preguntas d
		on c.preguntasid = d.preguntasid
		WHERE cuestionarioid = ? order by c.cuestionesid";
		$stmt = $this->_dbConnection->prepare($sql);
		$stmt->execute(array($idcuestionario));
		return $stmt->fetchAll();
	}
	public function getRespuestasCuestionario($idcuestionario){
		$sql = "Select * from pregunta_revision  c WHERE revisionid = ? order by preguntasid";
		$stmt = $this->_dbConnection->prepare($sql);
		$stmt->execute(array($idcuestionario));
		return $stmt->fetchAll();
	}

	static public function existeCuestionario($idcuestionario){
		$conexion = self :: _connect();
		$sql = "Select cuestionarioid from vtiger_cuestionario WHERE cuestionarioid = ?";
		$stmt = $conexion->prepare($sql);
		$stmt->execute(array($idcuestionario));
		return ($stmt->rowCount() == 0)? false: true;

	}

	public function saveRespuesta(Respuesta $oRespuesta){

		try{
			$sql= "SHOW COLUMNS FROM pregunta_revision";
			$stmt= $this->_dbConnection->prepare($sql);
			$stmt->execute();
			$columnas = $stmt->fetchAll();
			foreach($columnas as $k => $v) {
				if($oRespuesta->isPropiedad($v[0])){
					eval('$aResultados[$v[0]]= $oRespuesta->get'.$v[0].'();');
				}
			}

			$sql = "DELETE FROM pregunta_revision WHERE revisionid = ? and preguntasid = ?";
			$stmt= $this->_dbConnection->prepare($sql);
			$stmt->execute(array($oRespuesta->getRevisionid(), $oRespuesta->getPreguntasid()));
				
			$sql = "INSERT INTO pregunta_revision (".implode(', ', array_keys($aResultados)).") VALUES (:".implode(', :', array_keys($aResultados)).") ";
			$stmt= $this->_dbConnection->prepare($sql);
			$stmt->execute($aResultados);
			return true;
			//die();

		}catch (Exception $e){
			throw new DBException ('Error al ingresar la respuesta en la base de datos:'.$e->getMessage(), 8004);
		}

	}


}
?>