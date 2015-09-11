<!DOCTYPE html>
<html>
    <head>
<link rel="stylesheet" type="text/css" href="modules/Pivottable/pivottable-master/examples/pivot.css">
        <script type="text/javascript" src="modules/Pivottable/pivottable-master/examples/d3.v3.min.js"></script>
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <script type="text/javascript" src="modules/Pivottable/pivottable-master/examples/jquery-1.8.3.min.js"></script>
       
        <script type="text/javascript" src="modules/Pivottable/pivottable-master/examples/jquery-ui-1.9.2.custom.min.js"></script>
        <script type="text/javascript" src="modules/Pivottable/pivottable-master/examples/jquery.csv-0.71.min.js"></script>
        <script type="text/javascript" src="modules/Pivottable/pivottable-master/examples/pivot.js"></script>
        <script type="text/javascript" src="modules/Pivottable/pivottable-master/examples/gchart_renderers.js"></script>
        <script type="text/javascript" src="modules/Pivottable/pivottable-master/examples/d3_renderers.js"></script>
     
    
    </head>
    <style>
        * {font-family: Verdana;}
        .node {
          border: solid 1px white;
          font: 10px sans-serif;
          line-height: 12px;
          overflow: hidden;
          position: absolute;
          text-indent: 2px;
        }
    </style>
    <body>
        <?php
echo '<form method="POST" action=""><input type="submit" name="sub" value="Refresh"></form>';
if(isset($_REQUEST['sub'])){
include_once("modules/Pivottable/pivotfunc.php");
include_once("modules/Reports/Reports.php");
include("modules/Reports/ReportRun.php");
global $adb;
global $adb,$current_user,$php_max_execution_time;

$indextype='adocdetailed82e71';
 $query=sqltojson($indextype,$reportid);
//echo $query;
  createjson($query);
}        ?>
    <script type="text/javascript">
                    google.load("visualization", "1", {packages:["corechart", "charteditor"]});

       $(function(){
            var derivers = $.pivotUtilities.derivers;

            $.getJSON("report.json", function(mps) {
                $("#output").pivotUI(mps, { 
renderers: $.extend(
                        $.pivotUtilities.renderers, 
                        $.pivotUtilities.gchart_renderers, 
                        $.pivotUtilities.d3_renderers
                        ),
//                        derivedAttributes: {
//                            "Age Bin": derivers.bin("adocdetail_price", 10),
//                            "Gender Imbalance": function(mp) {
//                                return mp["Gender"] == "Male" ? 1 : -1;
//                            }
//                        },
                    rows: ["adocdetailnameAdocdetail"],
                    cols: ["productnameProducts1"],
//cols:[""],                        
rendererName: "Table"
                });
            });
         });
    </script>


        <div id="output" style="margin: 30px;"></div>

    </body>
</html>
