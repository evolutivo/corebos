{php}
global $current_user;
$roleid=$current_user->roleid;
$this->assign('CurrRole',$roleid);
{/php}
<script>
{literal}
angular.module('demoApp').run(function(editableOptions) {
    editableOptions.theme = 'bs3'; // bootstrap3 theme. Can be also 'bs2', 'default'
});
{/literal}
{foreach key=header item=detail from=$BLOCKS}
{foreach item=detail from=$detail}
{foreach key=label item=data from=$detail}
            {assign var=keyid value=$data.ui}
            {assign var=keyfldname value=$data.fldname}
        {if $keyid eq '15' || $keyid eq '16' || $keyid eq '31' || $keyid eq '32'} 
                             
            angular.module('demoApp').filter('{$keyfldname}_filter', function() {ldelim}
              return function({$keyfldname}_values {$MAP_RESPONSIBILE_FIELDS2}) {ldelim}
                var filterEvent = [];
                var count_false_condition=0;
                  for (var i = 0;i < {$keyfldname}_values.length; i++){ldelim}   
                  {if isset($MAP_PCKLIST_TARGET)} {* control to avoid errors for  modules not having BR*}
                  {if $keyfldname|in_array:$MAP_PCKLIST_TARGET}
                      {foreach key=mapid item=map from=$MAP_FIELD_DEPENDENCY}
                          {ldelim}
                          var condition='';
                          
                           {foreach key=map_key item=map_item from=$map.respfield}
                            {ldelim}
                                
                                if({$map_key} !=0) condition +=' && ';
                                condition +=' {$map_item} == "{$map.respvalue[$map_key]}"  ';
                                {rdelim}
                           {/foreach}
                                if( eval(condition))
                                    {ldelim}
                                      {foreach key=map_key item=map_item from=$map.target_picklist_values}
                                         {ldelim}
                                             if ({$keyfldname}_values[i]['text']=='{$map_item}' )
                                                {ldelim}
                                                  filterEvent.push({$keyfldname}_values[i]);
                                                {rdelim}
                                         {rdelim}
                                      {/foreach}
                                    {rdelim}
                                  else
                                    {ldelim}
                                            //count_false_condition++;
                                            //if(count_false_condition=={$MAP_FIELD_DEPENDENCY|@count})
                                            //{ldelim}
                                            //    filterEvent.push({$keyfldname}_values[i]);  
                                            //{rdelim}
                                    {rdelim}
                          {rdelim}  
                        {/foreach}
                      
                  {else}
                  filterEvent.push({$keyfldname}_values[i]);
                  {/if}
              {else}
                  filterEvent.push({$keyfldname}_values[i]);
              {/if}
              {rdelim}
                  //filterEvent.$stateful = true;
                  return filterEvent;
              {rdelim};
            {rdelim}); 
      
             {/if}
     {/foreach}
{/foreach} 
{/foreach}   
angular.module('demoApp').controller('detailViewng', function($scope,$filter,$sce) {ldelim}

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
            {assign var=_readonly value=$data.readonly}
             
             {if $keyid eq '56' && $keyval eq 'si'}
                 $scope.{$keyfldname}=true;
             {elseif $keyid eq '56' && $keyval eq 'no'}
                 $scope.{$keyfldname}=false;
             {elseif $keyid eq '15' || $keyid eq '16' || $keyid eq '31' || $keyid eq '32'} 
                 
                 var t= '{$keyfldname}'+'_values';
                 $scope[t] = [];
                 {foreach item=arr from=$keyoptions}
                            {if $arr[0] eq $APP.LBL_NOT_ACCESSIBLE}
                                $scope[t].push({ldelim}value:"{$arr[1]}", text:"{$arr[0]}"{rdelim});
                            {elseif $arr[2] eq 'selected'}
                                $scope.{$keyfldname}="{$arr[1]}";
                                $scope[t].push({ldelim}value:"{$arr[1]}", text:"{$arr[0]}", "v":"{$arr[2]}"{rdelim});
                            {else}
                                $scope[t].push({ldelim}value:"{$arr[1]}", text:"{$arr[0]}"{rdelim});
                            {/if}
                {/foreach}
            {elseif $keyid neq '22' &&  $keyid neq '19'  &&  $keyid neq '20' &&  $keyid neq '21' && $keyfldname neq "" && $keyfldname neq 'description' && $keyfldname neq 'msgdescription' && $keyfldname neq 'content'  && $keyfldname neq 'bodymessage_msg' && $keyfldname neq 'budymessage'}
              $scope.{$keyfldname}= $sce.trustAsHtml("{$keyval|html_entity_decode:1:"UTF-8"|replace:'"':"'"|replace:'&quot;':"'"|replace:'&amp;':"&"|replace:'<br/>':""|replace:'
':" "} ");
            {/if}
        {/foreach}
{/foreach} 
{/foreach}
 {literal} 
    $scope.showValue = function(fld) {
        var ret='Empty';
        if($scope[fld] != ' ')
            ret = $scope[fld];
        return ret;
  };
  
    $scope.showPicklist = function(fld) {
        var t= fld+'_values';
        var ret='Not Set';
        var selected = $filter('filter')($scope[t], {value: $scope[fld]});
        return ($scope[fld] && selected.length) ? selected[0].text : 'Not set';
  };
  
    $scope.show_logic = function(fld) {
        var ret=true;
        {/literal} 
        {foreach key=mapid item=map from=$MAP_FIELD_DEPENDENCY}
            var target_roles=new Array({$map.target_roles});
           {foreach key=map_key item=map_item from=$map.targetfield}
           if(fld=='{$map_item}' && '{$map.action[$map_key]}'=='hide' && target_roles.indexOf('{$CurrRole}')!=-1)
            {ldelim}
                ret=false;
            {rdelim}
           else if(fld=='{$map_item}' && '{$map.action[$map_key]}'=='hide' && $scope.{$map.respfield[0]}=='{$map.respvalue[0]}')
            {ldelim}
                ret=false;
            {rdelim}
           {/foreach}
        {/foreach}
            
        {literal}
        return ret;
      };
      
    $scope.editable_logic = function(fld) {
        var ret=true;
        {/literal} 
        {foreach key=mapid item=map from=$MAP_FIELD_DEPENDENCY}
            var target_roles=new Array({$map.target_roles});
           {foreach key=map_key item=map_item from=$map.targetfield}
           if(fld=='{$map_item}' && '{$map.action[$map_key]}'=='readonly' && target_roles.indexOf('{$CurrRole}')!=-1 )
            {ldelim}
                ret=false;
            {rdelim}
           else if(fld=='{$map_item}' && '{$map.action[$map_key]}'=='readonly' && $scope.{$map.respfield[0]}=='{$map.respvalue[0]}')
            {ldelim}
                ret=false;
            {rdelim}
           {/foreach}
        {/foreach}
            
        {literal}
    return ret;
   }
   
$scope.checkName = function(fieldLabel,fieldName,val_data,crmId,module,uitype) {
    var f_length=fieldName+'_length';
    var length=$scope[f_length];
    var actual_length=val_data.length;
     if (length!='' && length!=undefined && actual_length != length) {
     return fieldLabel+" should be "+length+" characters";
    } 
    
    if(val_data==true) val_data=1;
    else if(val_data==false) val_data=0;
  
        if(uitype == 53)
	{
		if(typeof(document.DetailView.assigntype[0]) != 'undefined')
		{
			var assign_type_U = document.DetailView.assigntype[0].checked;
			var assign_type_G = document.DetailView.assigntype[1].checked;
		}else
		{
			var assign_type_U = document.DetailView.assigntype.checked;
		}
		if(assign_type_U == true)
		{
			var txtBox= 'txtbox_U'+fieldLabel;
		}
		else if(assign_type_G == true)
		{
			var txtBox= 'txtbox_G'+fieldLabel;
			var group_id = encodeURIComponent($(txtBox).options[$(txtBox).selectedIndex].text); 
			var groupurl = "&assigned_group_id="+group_id+"&assigntype=T"
		}

	}
	else if(uitype == 15 || uitype == 16)
	{
            var tagValue= val_data;
            //alert(val_data);
         //var txtBox= "txtbox_"+ fieldLabel;
         //var not_access =document.getElementById(txtBox);
         //pickval = not_access.options[not_access.selectedIndex].value;
         //       if(pickval == alert_arr.LBL_NOT_ACCESSIBLE)
         //       {
          //              document.getElementById(editArea).style.display='none';
          //              document.getElementById(dtlView).style.display='block';
          //              itsonview=false; //to show the edit link again after hiding the editdiv.
          //              return false;
          //      }
	}
	else if(globaluitype == 33)
	{
	  var txtBox= "txtbox_"+ fieldLabel;
	  var oMulSelect = $(txtBox);
	  var r = new Array();
	  var notaccess_label = new Array();
	  for (iter=0;iter < oMulSelect.options.length ; iter++)
	  {
      	      if (oMulSelect.options[iter].selected)
		{
			r[r.length] = oMulSelect.options[iter].value;
			notaccess_label[notaccess_label.length] = oMulSelect.options[iter].text;
		}
      	  }
	}else
	{
		var tagValue= val_data;
	}
	
	var popupTxt= "popuptxt_"+ fieldLabel;      
	var hdTxt = "hdtxt_"+ fieldLabel;
        
//return $scope.doformValidation_ng(fieldName,val_data);
	if($scope.doformValidation_ng(fieldName,val_data) != true)
	{
		return $scope.doformValidation_ng(fieldName,val_data);
	}

        if($("vtbusy_info"))
	$("vtbusy_info").style.display="inline";
//	var isAdmin = document.getElementById("hdtxt_IsAdmin").value; 


	var data = "file=DetailViewAjax&module=" + module + "&action=" + module + "Ajax&record=" + crmId+"&recordid=" + crmId ;
	data = data + "&fldName=" + fieldName + "&fieldValue=" + encodeURIComponent(tagValue) + "&ajxaction=DETAILVIEW";
	if(module == 'Users') {
		data += "&form_token=" + (document.getElementsByName('form_token')[0].value);
	}
	new Ajax.Request(
		'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: data,
                        onComplete: function(response) {
							if(response.responseText.indexOf(":#:FAILURE")>-1) {
	          					alert(alert_arr.ERROR_WHILE_EDITING);
	          				}
	          				else if(response.responseText.indexOf(":#:ERR")>-1) {
								alert_str = response.responseText.replace(":#:ERR","");
	          					alert(alert_str);
	           					$("vtbusy_info").style.display="none";
	          				}
	          				else if(response.responseText.indexOf(":#:SUCCESS")>-1) {
								//For HD & FAQ - comments, we should empty the field value
								if((module == "HelpDesk" || module == "Faq") && fieldName == "comments") {
									var comments = response.responseText.replace(":#:SUCCESS","");
									if(getObj("comments_div") != null) getObj("comments_div").innerHTML = comments;
									if(getObj(dtlView) != null) getObj(dtlView).innerHTML = "";
									if(getObj("comments") != null) getObj("comments").value = "";
								}
	           					$("vtbusy_info").style.display="none";
							}
						}
                }
	);
	
}
$scope.emptyCheck_ng= function(fldName,fldLabel) {
	        if ($scope[fldName].length==0) {
			return false;
		}
		else{
			return true
		}
	
}

   
$scope.doformValidation_ng = function(fieldName,val_data) {
	
	for (var i=0; i<fieldname.length; i++) {
		var f=fieldname[i];
                //if(f==fieldName && val_data.length==0)
               // {
                //    return fieldlabel[i]+alert_arr.CANNOT_BE_EMPTY;
                //}
		//else
                //alert(val_data);
                if(f==fieldName && val_data.length==0)
		{
			var type=fielddatatype[i].split("~")
                        if (type[1]=="M") {
                            //return fieldname[i]+' '+fieldlabel[i]+$scope.emptyCheck_ng(fieldname[i],fieldlabel[i]);
				//if (!$scope.emptyCheck_ng(fieldname[i],fieldlabel[i]))
                                  //  {
                                        return fieldlabel[i]+alert_arr.CANNOT_BE_EMPTY;
                                    //  }
			}
			switch (type[0]) {
				case "O"  :
					break;
				case "V"  :
					break;
				case "C"  :
					break;
				case "DT" :
					if (getObj(fieldname[i]) != null && getObj(fieldname[i]).value.replace(/^\s+/g, '').replace(/\s+$/g, '').length!=0)
					{
						if (type[1]=="M")
							if (!emptyCheck_ng(fieldname[2],fieldlabel[i],getObj(type[2]).type))
								return false

						if(typeof(type[3])=="undefined") var currdatechk="OTH"
						else var currdatechk=type[3]

						if (!dateTimeValidate(fieldname[i],type[2],fieldlabel[i],currdatechk))
							return false
						if (type[4]) {
							if (!dateTimeComparison(fieldname[i],type[2],fieldlabel[i],type[5],type[6],type[4]))
								return false

						}
					}
					break;
				case "D"  :
					if (getObj(fieldname[i]) != null && getObj(fieldname[i]).value.replace(/^\s+/g, '').replace(/\s+$/g, '').length!=0)
					{
						if(typeof(type[2])=="undefined") var currdatechk="OTH"
						else var currdatechk=type[2]

						if (!dateValidate(fieldname[i],fieldlabel[i],currdatechk))
							return false
						if (type[3]) {
							if(gVTModule == 'SalesOrder' && fieldname[i] == 'end_period'
								&& (getObj('enable_recurring') == null || getObj('enable_recurring').checked == false)) {
								continue;
							}
							if (!dateComparison(fieldname[i],fieldlabel[i],type[4],type[5],type[3]))
								return false
						}
					}
					break;
				case "T"  :
					if (getObj(fieldname[i]) != null && getObj(fieldname[i]).value.replace(/^\s+/g, '').replace(/\s+$/g, '').length!=0)
					{
						if(typeof(type[2])=="undefined") var currtimechk="OTH"
						else var currtimechk=type[2]

						if (!timeValidate(fieldname[i],fieldlabel[i],currtimechk))
							return false
						if (type[3]) {
							if (!timeComparison(fieldname[i],fieldlabel[i],type[4],type[5],type[3]))
								return false
						}
					}
					break;
				case "I"  :
					if (getObj(fieldname[i]) != null && getObj(fieldname[i]).value.replace(/^\s+/g, '').replace(/\s+$/g, '').length!=0)
					{
						if (getObj(fieldname[i]).value.length!=0)
						{
							if (!intValidate(fieldname[i],fieldlabel[i]))
								return false
							if (type[2]) {
								if (!numConstComp(fieldname[i],fieldlabel[i],type[2],type[3]))
									return false
							}
						}
					}
					break;
				case "N"  :
				case "NN" :
					if (getObj(fieldname[i]) != null && getObj(fieldname[i]).value.replace(/^\s+/g, '').replace(/\s+$/g, '').length!=0)
					{
						if (getObj(fieldname[i]).value.length!=0)
						{
							if (typeof(type[2])=="undefined") var numformat="any"
							else var numformat=type[2]
							if(type[0]=="NN")
							{
								if (!numValidate(fieldname[i],fieldlabel[i],numformat,true))
									return false
							}
							else if (!numValidate(fieldname[i],fieldlabel[i],numformat))
								return false
							if (type[3]) {
								if (!numConstComp(fieldname[i],fieldlabel[i],type[3],type[4]))
									return false
							}
						}
					}
					break;
				case "E"  :
					if (getObj(fieldname[i]) != null && getObj(fieldname[i]).value.replace(/^\s+/g, '').replace(/\s+$/g, '').length!=0)
					{
						if (getObj(fieldname[i]).value.length!=0)
						{
							var etype = "EMAIL";
							if (!patternValidate(fieldname[i],fieldlabel[i],etype))
								return false;
						}
					}
					break;
			}
			//start Birth day date validation
			if(fieldname[i] == "birthday" && getObj(fieldname[i]).value.replace(/^\s+/g, '').replace(/\s+$/g, '').length!=0 )
			{
				var now =new Date()
				var currtimechk="OTH"
				var datelabel = fieldlabel[i]
				var datefield = fieldname[i]
				var datevalue =getObj(datefield).value.replace(/^\s+/g, '').replace(/\s+$/g, '')
				if (!dateValidate(fieldname[i],fieldlabel[i],currdatechk))
				{
					try {
						getObj(datefield).focus()
					} catch(error) { }
					return false
				}
				else
				{
					datearr=splitDateVal(datevalue);
					dd=datearr[0]
					mm=datearr[1]
					yyyy=datearr[2]
					var datecheck = new Date()
					datecheck.setYear(yyyy)
					datecheck.setMonth(mm-1)
					datecheck.setDate(dd)
					if (!compareDates(datecheck,datelabel,now,"Current Date","L"))
					{
						try {
							getObj(datefield).focus()
						} catch(error) { }
						return false
					}
				}
			}
		//End Birth day
		}

	}
	return true;
}

   });
{/literal}
</script>