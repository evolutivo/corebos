angular.module('demoApp').run(function(editableOptions) {
    editableOptions.theme = 'bs3'; // bootstrap3 theme. Can be also 'bs2', 'default'
});
angular.module('demoApp').controller('detailViewng', function($scope,$filter,$sce) {
for(detail in blocks){
    for(var i=0;i<blocks[detail].length;i++){
        var data=blocks[detail][i];
        for(datum in data ){
            var info=data[datum];
            if (info==null) continue;
            var keyid=info['ui'];
            var keyval=info['value'];
            var keyfldname=info['fldname'];
            var keyoptions=info['options'];
            if(keyid=='56' && keyval=='si'){
                $scope[keyfldname]=true;
            }
            else if(keyid=='56' && keyval=='no'){
                $scope[keyfldname]=false;
            }
            else if(keyid=='33'){
                var t= keyfldname+'_values';
                 $scope[t] = [];
                 $scope[keyfldname]=[];
                 for(var arr=0;arr< keyoptions.length;arr++){
                     if(keyoptions[arr][2]=='selected'){
                        $scope[keyfldname].push(arr[1]);
                        $scope[t].push({value:keyoptions[arr][1], text:keyoptions[arr][0], "v":keyoptions[arr][2]}); 
                     }
                     else{
                        $scope[t].push({value:keyoptions[arr][1], text:keyoptions[arr][0]});
                     }
                 }
            }
            else if(keyid=='15' || keyid=='16' || keyid=='31' || keyid=='32' || keyid=='1613'){
                var t= keyfldname+'_values';
                 $scope[t] = [];
                 for(var arr=0;arr< keyoptions.length;arr++){
                     if(keyoptions[arr][2]==='selected'){
                        $scope[keyfldname]=keyoptions[arr][1];
                        $scope[t].push({value:keyoptions[arr][1], text:keyoptions[arr][0], "v":keyoptions[arr][2]}); 
                     }
                     else{
                        $scope[t].push({value:keyoptions[arr][1], text:keyoptions[arr][0]});
                     }
                 }
            }
            else{
                $scope[keyfldname]= decodeEntities(keyval);
            }

        }
    }
}

function decodeEntities(encodedString) {
    var textArea = document.createElement('textarea');
    textArea.innerHTML = encodedString;
    return textArea.value;
}
$scope.showValue = function(fld) {
        var ret='Empty';
        if($scope[fld] != '')
            ret = $scope[fld];
        return ret;
  };
$scope.showPicklist = function(fld) {
        var t= fld+'_values';
        var ret='Not Set';
        angular.forEach($scope[t], function(value, key){ 
            if(value.value==$scope[fld]){ 
              ret=value.text;  
            }
        });
        return ret;
  };
  
  $scope.showPicklistMulti = function(fld) {
        var t= fld+'_values';
        var ret='';
        angular.forEach($scope[t], function(value, key){ 
            angular.forEach($scope[fld], function(valuefld, keyfld){ 
                if(value.value==valuefld){ 
                  ret+=(ret == '' ? value.text : ', '+value.text);  
                }
            });
        });
        return (ret == '' ? 'Not Set' : ret);
  };
  
    $scope.show_logic = function(fld) {
        var ret=true;
        var fieldname='';
        for(var mapid=0;mapid<mapFieldDep.length;mapid++)
        {
            var map =mapFieldDep[mapid];
            var target_roles=new Array(map['target_roles']);
            var conditionResp='';
            var c_resp=0;
            if (map['respfield']!=null)
            {
                c_resp=map['respfield'].length;
            }
            for(var resp_key=0; resp_key<c_resp;resp_key++){
               var resp_item=map['respfield'][resp_key];
               var resp_values=new Array(map['respvalue'][resp_key]);
               if(resp_key !=0) conditionResp +=' && ';
               if(map['comparison'][resp_key] == '==')
                   conditionResp += resp_values.indexOf('"'+$scope[resp_item]+'"')!=-1;
               else
                   conditionResp += resp_values.indexOf('"'+$scope[resp_item]+'"')==-1; 
            }
           for(var map_key=0;map_key<map['targetfield'].length;map_key++){
               var map_item = map['targetfield'][map_key];
               if(fld==map_item && map['action'][map_key]=='hide' && target_roles.indexOf(CurrRole)!=-1){
                   ret=false;
                   fieldname=fld;
               } 
               else if(fld==map_item && map['action'][map_key]=='hide' && eval(conditionResp)){
                   ret=false;
                   fieldname=fld;
               }
               else if(fld==map_item && map['action'][map_key]=='show' && eval(conditionResp)){
                   ret=true;
                   fieldname=fld;
               }
               else if(fld==map_item && fieldname!=fld && fieldname!='' ){
                   ret=false;
                   fieldname=fld;
               }
            }
        }
        
        return ret;
      };
      
    $scope.editable_logic = function(fld) {
        var ret=true;
        for(var mapid=0;mapid<mapFieldDep.length;mapid++)
        {
            var map =mapFieldDep[mapid];
            var target_roles=new Array(map['target_roles']);
            var target_profiles=new Array(map['target_profiles']);
            var curr_prof_in_excluded=false;
            for(var prof_key=0;prof_key<CurrProfiles.length;prof_key++){
                var prof_item=CurrProfiles[prof_key];
                if(target_profiles.indexOf(prof_item)!=-1)
                    curr_prof_in_excluded=true;
            }
            var conditionResp='';
            var c_resp=0;
            if (map['respfield']!=null)
            {
                c_resp=map['respfield'].length;
            }
            for(var resp_key=0; resp_key<c_resp;resp_key++){
               var resp_item=map['respfield'][resp_key];
               var resp_values=new Array(map['respvalue'][resp_key]);
               if(resp_key !=0) conditionResp +=' && ';
               if(map['comparison'][resp_key] == '==')
                   conditionResp += resp_values.indexOf('"'+$scope[resp_item]+'"')!=-1;
               else
                   conditionResp += resp_values.indexOf('"'+$scope[resp_item]+'"')==-1; 
            }
            for(var map_key=0;map_key<map['targetfield'].length;map_key++){
               var map_item = map['targetfield'][map_key];
               if(fld==map_item && map['action'][map_key]=='readonly' && target_roles.indexOf(CurrRole)!=-1){
                   ret=false;
               } 
               else if(fld==map_item && map['action'][map_key]=='readonly' && curr_prof_in_excluded){
                   ret=false;
               }
               else if(fld==map_item && map['action'][map_key]=='readonly' && eval(conditionResp) ){
                   ret=false;
               }
           }
        }           
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
	}
	else if(globaluitype == 33)
	{
	  tagValue = val_data.join(" |##| ");
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