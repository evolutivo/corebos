<?php

echo '<strong>CELERY OUTPUT:</strong><br><br>';
$arr=str_replace(Array('{','}',"u'",'u"',"'",'"'),Array('','','','','',''),explode(", u'",$_REQUEST['output']));
echo '<table width="100%" style="border-style:solid;border-color:grey" border=1>';
foreach($arr as $key=>$val)
{
$value=explode(":",$val);
echo '<tr><td>'.$value[0].'</td><td>'.$value[1].'</td></tr>';
}
echo '</table>';