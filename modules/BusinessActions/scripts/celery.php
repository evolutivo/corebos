<?php
function celery($request){
$rec=$request["recordid"];
$setype=getSalesEntityType($rec);
//Master signature id
$id=419;
$r=$rec.'##'.$id;
putenv('PYTHONPATH=/home/lorida/virtualenvs/api/lib/python2.7/site-packages:/home/lorida/virtualenvs/api/lib/python2.7/site-packages:');
//$ip='193.182.16.151';
//$port='8080';
//$us='root';
//$pass='58g1matZSf5LHu';
//if (!function_exists("ssh2_connect")) die("function ssh2_connect doesn't exist");
//// log in at server1.example.com on port 22
//if(!($con = ssh2_connect($ip, $port))){
//} else {
//    // try to authenticate with username root, password secretpassword
//    if(!ssh2_auth_password($con, $us, $pass)) {
//    } else {
//
//$rec=$_REQUEST['record'];
//echo ssh2_exec("python  /var/www/html/python4celery/addtasks2.py $rec");
//
//}
//    }
$url=shell_exec("python  /var/www/html/python4celery/addtasks2.py $r");
$response['linkurl']="index.php?module=$setype&action=celeryoutput&output=".$url;
return $response;

}