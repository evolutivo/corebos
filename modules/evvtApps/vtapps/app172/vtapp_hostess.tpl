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
    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]>
    <script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
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

function save_changes()
{
    var req='';
    var val='';
    var fldname
    var arr ={/literal}{$columns2}{literal};
    for(i=0;i<arr.length;i++){
        //alert(arr[i]['fieldname']);
        fldname=arr[i]['fieldname'];
        req+=arr[i]['fieldname']+';';
        val+=document.getElementById(fldname).value+';';
    }
 $.post('index.php?module=evvtApps&action=evvtAppsAjax&file=kendo_content&kaction=update&id='+{/literal}{$id}{literal}+'&req='+req+'&val='+val,
          {mod:''},
          function(data,status){
              alert('Update finito');
            //$('#div_kendo').html(data);
          });   
}

var template = kendo.template($("#template").html());
var dataSource = new kendo.data.DataSource({
    data: {/literal}{$columns1}{literal},
    change: function() { // subscribe to the CHANGE event of the data source
        $("#accounts1").html(kendo.render(template, this.view())); // populate the table
    }
});
dataSource.read();

/*var template2 = kendo.template($("#template2").html());
var dataSource2 = new kendo.data.DataSource({
    data: {/literal}{$columns2}{literal},
    change: function() { // subscribe to the CHANGE event of the data source
        $("#accounts2").html(kendo.render(template2, this.view())); // populate the table
    }
});
dataSource2.read();*/
{/literal}
</script>
<script id="template" type="text/x-kendo-template">
<div #= class1 # >
    <span class="alert-icon"><i #= image_class #></i></span>
    <div class="notification-info">
        <ul class="clearfix notification-meta">
            <li class="pull-left notification-sender" style="font-size:12px;"><span><a ></a></span> #= field # </li>
            <li class="pull-right notification-time">#= value #</li>
        </ul>
        <p><b>#= value #</b></p>
    </div>
</div> 
    
</script>
<script id="template2" type="text/x-kendo-template">
<div #= class1 # style="height:82px;" >
    <span class="alert-icon"><i #= image_class #></i></span>
    <div class="notification-info">
        <ul class="clearfix notification-meta">
            <li class="pull-left notification-sender"><span><a ></a></span> #= field #</li>
            <li class="pull-right notification-time">#= value #</li>
        </ul>
        <p><input type="text" value='#= value #' name="#= fieldname #" id="#= fieldname #"
          #= class1 # style="height:2px;border-style:solid;border-width:1px;" ></p>
    </div>
</div> 
    
</script>
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="small lvt" >
    <tr><td ><br/><br/></td></tr>
    <tr><td >
    
    <div class="col-md-6" style="width:100%;">
    <!--notification start-->
    <section class="panel">
        <header class="panel-heading">
            Informazioni invitato <span class="tools pull-right">
            <a href="javascript:;" class="fa fa-chevron-down"></a>
            <a href="javascript:;" class="fa fa-cog"></a>
            <a href="javascript:;" class="fa fa-times"></a>
            </span>
        </header>
        <div class="panel-body" id="accounts1">
            
            </div>
    </section>
    <!--notification end-->
</div>
            
</td>

</tr>

<br/><br/>
{*<tr><td >
    
    <div class="col-md-6" style="width:100%;">
    <!--notification start-->
    <section class="panel">
        <header class="panel-heading">
            Informazioni da compilare <span class="tools pull-right">
            <a href="javascript:;" onclick='save_changes();'><img src='themes/images/disk.svg' width='30' height='30' /></a>
            <a href="javascript:;" class="fa fa-chevron-down"></a>
            <a href="javascript:;" class="fa fa-cog"></a>
            <a href="javascript:;" class="fa fa-times"></a>
            </span>
        </header>
        <div class="panel-body" id="accounts2">
            
            </div>
    </section>
    <!--notification end-->
</div>
            
</td>

</tr>*}
</table>