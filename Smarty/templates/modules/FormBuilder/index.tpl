<link rel="stylesheet" href="Smarty/angular/material/angular-material.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=RobotoDraft:300,400,500,700,400italic">
<link rel="stylesheet" href="Smarty/bower_components/angular-material/angular-material.css"/>
<script src="Smarty/bower_components/angular/angular.js"></script>
<script src="Smarty/bower_components/angular-animate/angular-animate.js"></script>
<script src="Smarty/bower_components/angular-aria/angular-aria.js"></script>
<script type="text/javascript" src="Smarty/bower_components/angular-material/angular-material.js"></script>

<script  src="Smarty/angular/ng-table.js"></script>
<link rel="stylesheet" href="Smarty/angular/ng-table.css" />
<script src="Smarty/angular/ui-bootstrap-tpls-0.9.0.js"></script>
<link rel="stylesheet" type="text/css" href="Smarty/angular/bootstrap.min.css"/>
<script src="Smarty/angular/angular-multi-select.js"></script>  
<link rel="stylesheet" href="Smarty/angular/angular-multi-select.css">

<table width=96% align=center border="0" ng-app="FormBuilderApp" ng-cloak style="padding:10px;">
    <tr><td style="height:2px"><br/><br/></td></tr>
    <tr>
        <td style="padding-left:20px;padding-right:50px" class="moduleName" nowrap colspan="2">
            Form Builder
        </td>
     </tr>
     <tr>  
	<td class="showPanelBg" valign="top" style="padding:30px;" width=96%>
            <div  layout="column" class="demo" >
                {include file="modules/FormBuilder/ngFormCreator.html"}
            </div>
        </td>
     </tr>
</table>