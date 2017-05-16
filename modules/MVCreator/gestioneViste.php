<?php 
include "modules/MVCreator/dbclass.php";
$connect = new MysqlClass();
$connect->connettiMysql();
echo makeDbList();
echo "<div id='divManage'>";
echo "<select id='selViews' name='selViews' size='10' ></select>";
echo "<div id='buttonsManage'><button class='pulsante' id='updateView' value='Aggiorna vista' onclick='updateView()'>Aggiorna vista</button>";   
echo "<button class='pulsante' id='deleteVista' value='EliminaVista' onclick='deleteView()'>Cancella Vista</button></div>";
echo "<div id='resultView'>";
echo "<div class='subTitleDiv'>Reporting message</div>";
echo "<textarea id='textmessage' readonly></textarea>";
    

echo "</div></div>";
$connect->disconnettiMysql();

function makeDbList() {
    $res = mysql_query("SHOW DATABASES");
    $i=0;
    while ($row = mysql_fetch_assoc($res)){
        $dbList[$i]=$row['Database'];
        $i++;
    }
    $picklist="<div class='selDataBase' id='selDbViews'>";
    $picklist=$picklist."<select class='dbList' id='dbListViews' name='dbListViews' onchange='selDBViews()' >";
    $picklist=$picklist."<option selected='selected' disabled='disabled'>Selezionare il database:</option>";
    for($j=0; $j<count($dbList); $j++) {  
        $picklist=$picklist."<option id=\"".$dbList[$j]."\">".$dbList[$j]."</option>\"";
    } 
    $picklist=$picklist."</select></div>";
    return  $picklist;
    }
      
?>