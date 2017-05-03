<script>

jQuery( document ).ready(function() {

var reportdataSource = new kendo.data.DataSource({
  serverFiltering: true,
  transport: {
    read: {
      url: "index.php?module=MVCreator&action=MVCreatorAjax&file=populateReport",
      dataType: "json",
    }
  }
});

// jQuery("#report").kendoDropDownList({
//              autoBind: false,
//              optionLabel: "Select Report...",
//              dataTextField: "reportValue",
//              dataValueField: "reportId",
//              dataSource:reportdataSource
//}).data("kendoDropDownList");

var report = jQuery("#report").kendoDropDownList({
    autoBind: false,
    cascadeFrom: "report",
    optionLabel: "Select group...",
    dataTextField: "groupValue",
    dataValueField: "groupId",
    dataSource: {
         transport: {
           read: {
               url:"index.php?module=MVCreator&action=MVCreatorAjax&file=populateReport",
               dataType: "json",
           }
         },
       serverFiltering: true,
    }
}).data("kendoDropDownList");
});
</script>
<?php
$data =  '<br> <br><br> <br>';
$data.='<div  name="crea" id ="crea">';
$data.='Nome tabella  <input class="k-input" style="margin-right: 30px;" type="text" value="" name="nometab"  id="nometab"> ';
$data.='Report <input  id="report" style="margin-right: 30px;" name="report"/>';
$data.='Groupby <input  id="groupby" style="margin-right: 30px;" name="groupby"/>';
$data.='<input type="button" name="createRaport" id="createRaport" style="margin-right: 30px;" onclick="createRaprtTable();" class="k-button" value="Crea tabella" class="crmbutton edit small">';
$data.='<div id="screated"> </div>';
$data.='</div>';
echo $data;
?>