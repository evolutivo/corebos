
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
 $.post('index.php?module=evvtApps&action=evvtAppsAjax&file=kendo_content_merkur&kaction=update&id='+{/literal}{$id}{literal}+'&req='+req+'&val='+val,
          {mod:''},
          function(data,status){
              alert('Update finito');
            //$('#div_kendo').html(data);
          });   
}

function add_related_module(index,mod,mod_rel,id)
{ //alert('index.php?module=evvtApps&action=evvtAppsAjax&file=kendo_content_merkur&kaction=add_new&type=edit&module_related='+document.getElementById('module_selected').value+'&module_selected={/literal}{$MODULE_RELATED}{literal}'+'&related_id={/literal}{$id}{literal}');
 $.post('index.php?module=evvtApps&action=evvtAppsAjax&file=kendo_content_merkur&kaction=add_new&module_related='+mod+'&module_selected='+mod_rel+'&related_id='+id+'&index='+index,
          {profile:'{/literal}{$Profile}{literal}',
              appid:'{/literal}{$appid}{literal}'
                       },
          function(data,status){
          document.getElementById('div_kendo'+index).style.display='block'; 
          //document.getElementById('div_kendo_2').style.display='none'; 
           $('#div_kendo'+index).html(data);
          });   
    
}

function add_related_document(index,mod,mod_rel,id)
{ //alert('index.php?module=evvtApps&action=evvtAppsAjax&file=kendo_content_merkur&kaction=add_new&type=edit&module_related='+document.getElementById('module_selected').value+'&module_selected={/literal}{$MODULE_RELATED}{literal}'+'&related_id={/literal}{$id}{literal}');
 $.post('index.php?module=evvtApps&action=evvtAppsAjax&file=kendo_content_merkur&kaction=add_new&type=edit&module_selected=Documents&id=&index='+index+'&module_related='+mod+'&related_id='+id,
          {onsave_secondary:'{/literal}{$onsave_secondary}{literal}',
           onsave_primary:'{/literal}{$onsave_primary}{literal}',
           add_document:'{/literal}{$add_document}{literal}',
           edit_this:'{/literal}{$edit_this}{literal}',
           go_to_dtlview:'{/literal}{$go_to_dtlview}{literal}',
           profile:{/literal}{$Profile}{literal},
           appid:'{/literal}{$appid}{literal}'
           },
          function(data,status){
          document.getElementById('div_kendo'+index).style.display='block'; 
          //document.getElementById('div_kendo_2').style.display='none'; 
           $('#div_kendo'+index).html(data);
          });   
    
}

function edit_actual_record(index,mod,mod_rel,id)
{ //alert('index.php?module=evvtApps&action=evvtAppsAjax&file=kendo_content_merkur&kaction=add_new&type=edit&module_related='+document.getElementById('module_selected').value+'&module_selected={/literal}{$MODULE_RELATED}{literal}'+'&related_id={/literal}{$id}{literal}');
 $.post('index.php?module=evvtApps&action=evvtAppsAjax&file=kendo_content_merkur&kaction=add_new&type=edit&module_related='+mod_rel+'&module_selected='+mod+'&id='+id+'&index='+index,
          {onsave_secondary:'{/literal}{$onsave_secondary}{literal}',
           onsave_primary:'{/literal}{$onsave_primary}{literal}',
           add_document:'{/literal}{$add_document}{literal}',
           edit_this:'{/literal}{$edit_this}{literal}',
           go_to_dtlview:'{/literal}{$go_to_dtlview}{literal}',
           profile:{/literal}{$Profile}{literal},
           appid:'{/literal}{$appid}{literal}'
           },
          function(data,status){
          document.getElementById('div_kendo'+index).style.display='block'; 
          //document.getElementById('div_kendo_2').style.display='none'; 
           $('#div_kendo'+index).html(data);
          });   
    
}
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
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="small lvt" style='margin-top:5px;'>
    
    <tr><td style='vertical-align: top;'>
    <div class="col-md-6" style="width:100%;">
    <!--notification start-->
    <section class="panel">
        <header class="panel-heading">
            Informazioni  <span class="tools pull-right">
                {if  $add_document eq true}
                    <a href="javascript:;" onclick='add_related_document("{$index}","{$MODULE}","{$MODULE_RELATED}","{$id}");'><i title="Aggiungi Documento" class="fa fa-file-o"></i></a>
                {/if}
                {if  $edit_this eq true}
                    <a href="javascript:;" onclick='edit_actual_record("{$index}","{$MODULE}","{$MODULE_RELATED}","{$id}");'><i title="Modifica {$APP.$MODULE}" class="fa fa-edit"></i></a>
                {*<a href="javascript:;" onclick='add_related_module("{$index}","{$MODULE}","{$MODULE_RELATED}","{$id}");'><i title="Aggiungi {$APP.$MODULE_RELATED}" class="fa fa-plus"></i></a>*}
                {/if}
                {if  $go_to_dtlview eq true}
                <a href="index.php?module={$MODULE}&action=DetailView&record={$id}" ><i title="Vista dettagliata" class="fa fa-info"></i></a>
                {/if}
            </span>
        </header>
        <div class="panel-body" >
        {foreach key=header item=detail from=$BLOCKS}
           
                   {foreach item=detail from=$detail}

                        {foreach key=label item=data from=$detail}
                           {assign var=keyid value=$data.ui}
                           {assign var=keyval value=$data.value}
                           {assign var=keytblname value=$data.tablename}
                           {assign var=keyfldname value=$data.fldname}
                           {assign var=keyfldid value=$data.fldid}
                           {assign var=keyoptions value=$data.options}
                           {assign var=keysecid value=$data.secid}
                           {assign var=keyseclink value=$data.link}
                           {assign var=keycursymb value=$data.cursymb}
                           {assign var=keysalut value=$data.salut}
                           {assign var=keyaccess value=$data.notaccess}
                           {assign var=keycntimage value=$data.cntimage}
                           {assign var=keyadmin value=$data.isadmin}
                           {assign var=display_type value=$data.displaytype}
                           {assign var=ll value=$data.ll}
                           {assign var=relmod value=$data.relmod}
                           {assign var=relval value=$data.relval}							  
                {*<!-- //NABLACOM BEGIN ADVANCED UI WITH VIEW ONLY FIELDS-->*}
                                {assign var="add_property_to_field" value=$data.add_property_to_field} 
            {math equation="abs(x)" x=$nr-4 assign="mod"} 
            
            {if $mod mod 4 eq '0' }
                  {assign var="class" value='class="alert alert-info"'}
                  {assign var="image_class" value='class="fa fa-envelope-o"'}  
              {elseif  $mod mod 4=='1' }
                  {assign var="class" value='class="alert alert-danger"'}
                  {assign var="image_class" value='class="fa fa-facebook"'}  
              {elseif  $mod mod 4=='2'  }
                  {assign var="class" value='class="alert alert-success"'}
                  {assign var="image_class" value='class="fa fa-comments-o"'}
              {elseif  $mod mod 4=='3'  }
                  {assign var="class" value='class="alert alert-warning"'}
                  {assign var="image_class" value='class="fa fa-bell-o"'}  
              {/if}
              {if $label neq ''}
		{include file='vtapp_form_DetailViewUI.tpl'}
                {/if}
                
                {assign var='nr' value=$nr+1}

                    {/foreach}

                 {/foreach}
                                                              
            {/foreach}
        </div>
    </section>
    <!--notification end-->
</div>
            
</td>

</tr>

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