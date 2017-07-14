<!DOCTYPE html>
<html>
    <head>
       aaaaaaaaaaa <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="http://cdnjs.cloudflare.com/ajax/libs/angular.js/1.4.2/angular.js"></script>
        <link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js" type="text/javascript"></script>
        <link rel="stylesheet" href="modules/EtherCalc/css/elestilo.css">
        <script src="modules/EtherCalc/js/ng-table.js"></script>
         <script type="text/javascript" src="modules/EtherCalc/js/angular-resource.min.js"></script>
 <script type="text/javascript" src="modules/EtherCalc/js/angular-route.min.js"></script>
        <script src="modules/EtherCalc/js/app.js" type="text/javascript"></script>
    </head>
    <body ng-app="ngTableTutorial">

        <div ng-controller="tableController">
        <div ng-show="frameName==''">
        <p style="font-size:60px;margin-left:35%"> EtherCalc</p>
 <table ng-table="usersTable" class="table1">
   <tr ng-repeat="user in data">
   <td ><input type="checkbox" ng-checked="user.isRowSelected" ng-click="toggleSelection(user)" /></td>
       <td data-title="'Id'" >
          {literal} {{user.id}} {/literal}
       </td>
       <td data-title="'Spreadsheet'" >
           {literal}{{user.name}}{/literal}
       </td>
       <td data-title="'Nome Riferente'" >
           {literal}{{user.namecrm}}{/literal}
       </td> 
       <td data-title="'Created Time'" >
           {literal}{{user.createdtime}}{/literal}
       </td>       
       <!--<td data-title="'Country'">
           {literal}{{user.country}}{/literal}
       </td>    
       <td data-title="'IP'" >
           {literal}{{user.ip_address}}{/literal}
       </td>--> 
     
   </tr>
</table>
<div style="font-size:20px;">
 <button ng-click="createspreadsheet()" class="btn-primary">
   Create Spreadsheet
</button>
</div>
</div>
<div >
<div ng-show="frameName1==''" style="height:170%">
 <iframe width="100%" height="70%" name="{literal}{{frameName1}}{/literal}" ng-src="{literal}{{trustSrcurl(frameUrl1)}}{/literal}"></iframe>
 </div>
<div ng-show="frameName!=''" style="height:170%">
<iframe width="100%" height="70%" name="{literal}{{frameName}}{/literal}" ng-src="{literal}{{trustSrcurl(frameUrl)}}{/literal}"></iframe>
 <div style="font-size:20px;">
 <button ng-click="showlist(data)" class="btn-primary">
  Show List
</button>
<button ng-click="updatedata(ethid)" class="btn-primary">
Update Data
</button>
</div>
 </div>
 </div>
        </div>
    </body>
</html>