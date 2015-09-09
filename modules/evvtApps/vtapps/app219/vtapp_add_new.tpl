<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-{$CALENDAR_LANG}.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<script src="modules/Project/Project.js"></script>
    
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

function add_new(index,mod,mod_rel,id,rel_id,columns)
{
    var req='';
    var val='';
    var fldname='';
    var arr =columns;
    var onsave_primary='';
    for(i=0;i<arr.length;i++){
        
        fldname=arr[i]['fieldname'];
        if(fldname=='taxclass')
         {  
            if(document.getElementsByName('tax1_check').item(0).checked)
            {req+='tax1;';
            val+=document.getElementsByName('tax1').item(0).value+';'; }
        
            if(document.getElementsByName('tax2_check').item(0).checked)
            {req+='tax2;';
            val+=document.getElementsByName('tax2').item(0).value+';'; }
        
            if(document.getElementsByName('tax3_check').item(0).checked)
            {req+='tax3;';
            val+=document.getElementsByName('tax3').item(0).value+';'; }
        }
        else 
        {if (fldname=='imagename') 
            {
            fldname='file_1';
            arr[i]['fieldname']='imagename';
            }
            
            req+=arr[i]['fieldname']+';';
            val+=document.getElementsByName(fldname).item(0).value+';'; 
        }
    }//alert('index.php?module=evvtApps&action=evvtAppsAjax&file=kendo_content&kaction=save&id={/literal}{$id}{literal}&related_id={/literal}{$related_id}{literal}&req='+req+'&val='+val+'&module_selected={/literal}{$MODULE}{literal}');
 $.post('index.php?module=evvtApps&action=evvtAppsAjax&file=kendo_content&kaction=save&id='+id+'&related_id='+rel_id+'&req='+req+'&val='+val+'&module_selected='+mod,
          {mod:''},
          function(data,status){
              alert('Salvato');
              var dialog = $('#{/literal}{$appid}{literal}').data("kendoWindow");
              if(index==1)
              { onsave_primary='{/literal}{$onsave_primary}{literal}';}
              else if(index==2)
              {onsave_primary='{/literal}{$onsave_secondary}{literal}';}
              
              if(onsave_primary=='autoshut')
              {dialog.close(); }
              else if(onsave_primary=='autoreopen')
              {dialog.refresh('index.php?module=evvtApps&action=evvtAppsAjax&file=vtappaction&vtappaction=getContent&class=albana&appid=16&op=1');}
             //else
             //cancel_return(index,mod,mod_rel,id,rel_id);
            
                            });
return false;
}

function cancel_return(index,mod,mod_rel,id,rel_id)
{ 
 //alert(rel_id+' '+mod_rel+' '+mod+' '+id);
 //alert('index.php?module=evvtApps&action=evvtAppsAjax&file=kendo_content&kaction=display&module_selected='+mod_rel+'&id='+rel_id+'&module_related='+mod+'&index='+index);
 if(rel_id !='')
 {
    $.post('index.php?module=evvtApps&action=evvtAppsAjax&file=kendo_content&kaction=display&module_selected='+mod_rel+'&id='+rel_id+'&module_related='+mod+'&index='+index,
          {add_document:'{/literal}{$add_document}{literal}',
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
  else{
      $.post('index.php?module=evvtApps&action=evvtAppsAjax&file=kendo_content&kaction=display&module_selected='+mod+'&id='+id+'&module_related='+mod_rel+'&index='+index,
          {add_document:'{/literal}{$add_document}{literal}',
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
}
{/literal}
</script>
<script>
        var fieldname = new Array({$VALIDATION_DATA_FIELDNAME})
        var fieldlabel = new Array({$VALIDATION_DATA_FIELDLABEL})
        var fielddatatype = new Array({$VALIDATION_DATA_FIELDDATATYPE})
</script>
<form name="EditView" method="POST" onSubmit="return false;" ENCTYPE="multipart/form-data">
    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="small lvt" style='margin-top:5px;' >
    
    <tr><td >
    
    <div class="col-md-6" style="width:100%;" >
    <!--notification start-->
    <section class="panel">
        <header class="panel-heading">
            Informazioni da compilare <span class="tools pull-right">
            
            </span>
        </header>
<div class="panel-body" >
{assign var="nr" value='8'}
{foreach key=header item=data from=$BASBLOCKS} 
      
       {foreach key=label item=subdata from=$data}
	
        
	{foreach key=mainlabel item=maindata from=$subdata}
            
            
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
              
		{include file='vtapp_form_EditViewUI.tpl'}
                
                {assign var='nr' value=$nr+1}
	{/foreach}
     
{/foreach}

   {/foreach}
   </div>
         <p align="center"><button type=\"submit\" class="btn btn-info"
                     onclick='add_new("{$index}","{$MODULE}","{$MODULE_RELATED}","{$id}","{$related_id}",{$columns2});'>+ Salva</button>
               &nbsp;<button type=\"button\" class="btn btn-danger"
                     onclick='cancel_return("{$index}","{$MODULE}","{$MODULE_RELATED}","{$id}","{$related_id}");'>+ Cancella</button></p>
                     <br/>
    
</div> 
    </section>
    <!--notification end-->
</div>
            
</td>

</tr>

</table>  
 <script>
    {literal}
jQuery(document).ready(function () {
    jQuery("#imagename").kendoUpload({ 
  async: {
        
        saveUrl: "index.php?module=evvtApps&action=evvtAppsAjax&file=kendo_content&kaction=upload",
        autoUpload: true
    }

 });


});
{/literal}
</script>
{*<script id="template" type="text/x-kendo-template">

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
*}    

</form>
