<link href="modules/evvtApps/vtapps/bs3/css/bootstrap.min.css" rel="stylesheet">
    <link href="modules/evvtApps/vtapps/js/jquery-ui/jquery-ui-1.10.1.custom.min.css" rel="stylesheet">
    <link href="modules/evvtApps/vtapps/css/bootstrap-reset.css" rel="stylesheet">
    <link href="modules/evvtApps/vtapps/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="modules/evvtApps/vtapps/js/jvector-map/jquery-jvectormap-1.2.2.css" rel="stylesheet">
    <link href="modules/evvtApps/vtapps/css/clndr.css" rel="stylesheet">
    <!--clock css-->
    <link href="modules/evvtApps/vtapps/js/css3clock/css/style.css" rel="stylesheet">
    <!--Morris Chart CSS -->
    <link rel="stylesheet" href="modules/evvtApps/vtapps/js/morris-chart/morris.css">
    <link rel="stylesheet" href="modules/evvtApps/vtapps/js/scripts.js">
    <!-- Custom styles for this template -->
    <link href="modules/evvtApps/vtapps/css/style.css" rel="stylesheet">
    <link href="modules/evvtApps/vtapps/css/style-responsive.css" rel="stylesheet"/>
    
    <script>
{literal}
function showHideStatus(sId,anchorImgId,sImagePath)
{
	oObj = eval(document.getElementById(sId));
	if(oObj.style.display == 'block')
	{
		oObj.style.display = 'none';
		eval(document.getElementById(anchorImgId)).src =  'themes/images/inactivate.gif';
		eval(document.getElementById(anchorImgId)).alt = 'Display';
		eval(document.getElementById(anchorImgId)).title = 'Display';
	}
	else
	{
		oObj.style.display = 'block';
		eval(document.getElementById(anchorImgId)).src = 'themes/images/activate.gif';
		eval(document.getElementById(anchorImgId)).alt = 'Hide';
		eval(document.getElementById(anchorImgId)).title = 'Hide';
	}
}

function add_new()
{
    var req='';
    var val='';
    var fldname='',uitype='';
    var uis=['10'];
    var arr ={/literal}{$columns2}{literal};
    for(i=0;i<arr.length;i++){
        //alert(arr[i]['fieldname']);
        fldname=arr[i]['fieldname'];
        uitype=arr[i]['uitype'];
        
        if(uis.indexOf(uitype)!=-1)
        { 
            
            req+=arr[i]['value_ui10']+';';
            val+=document.getElementById(arr[i]['value_ui10']).value+';';
        }
        else
        {
           req+=arr[i]['fieldname']+';';
        val+=document.getElementById(fldname).value+';'; 
        }
        
    }//alert('index.php?module=evvtApps&action=evvtAppsAjax&file=kendo_content&kaction=save&id='+{/literal}{$id}{literal}+'&req='+req+'&val='+val);
 $.post('index.php?module=evvtApps&action=evvtAppsAjax&file=kendo_content&kaction=save&id='+{/literal}{$id}{literal}+'&req='+req+'&val='+val,
          {mod:''},
          function(data,status){
              alert('Aggiunto con successo');
            //$('#div_kendo').html(data);
          });   
return false;}
var template = kendo.template($("#template").html());
var dataSource = new kendo.data.DataSource({
    data: {/literal}{$columns2}{literal},
    change: function() { // subscribe to the CHANGE event of the data source
        $("#nuovo").html(kendo.render(template, this.view())); // populate the table
    }
});
dataSource.read();

{/literal}
</script>
<form name="EditView" method="POST" onSubmit="return false;">
<script id="template" type="text/x-kendo-template">

<div #= class1 # >
    <span class="alert-icon"><i #= image_class #></i></span>
    <div class="notification-info" style="height:50px;">
        <ul class="clearfix notification-meta">
            <li class="pull-left notification-sender"><span><a ></a></span> #= field #</li>
           
        </ul>
        <p><input type="text"  name="#= fieldname #" id="#= fieldname #"
          #= class1 # style="height:2px;border-style:solid;border-width:1px;" >#= select #</p>
          <input type="hidden"  name="#= value_ui10 #" id="#= value_ui10 #" value="">
    </div>
</div> 

</script>

<table width="100%" cellspacing="0" cellpadding="0" border="0" class="small lvt" >
    <tr><td ><br/><br/></td></tr>
    <tr><td >
    
    <div class="col-md-6" style="width:50%;" >
    <!--notification start-->
    <section class="panel">
        <header class="panel-heading">
            Nuova Chiamata <span class="tools pull-right">
            
            </span>
        </header>
        
        <div class="panel-body" id="nuovo">
            
            </div>
        
       
        <p align="center"><button type=\"button\" class="btn btn-info"
                     onclick='add_new();'>+ Aggiungi nuova Chiamata</button></p>
                     <br/>
    
</div> 
    </section>
    <!--notification end-->
</div>
            
</td>

</tr>

</table>
</form>