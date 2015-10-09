/*************************************************************************************************
 * Copyright 2014 JPL TSolucio, S.L. -- This file is a part of TSOLUCIO coreBOS Customizations.
* Licensed under the vtiger CRM Public License Version 1.1 (the "License"); you may not use this
* file except in compliance with the License. You can redistribute it and/or modify it
* under the terms of the License. JPL TSolucio, S.L. reserves all rights not expressly
* granted by the License. coreBOS distributed by JPL TSolucio S.L. is distributed in
* the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
* warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Unless required by
* applicable law or agreed to in writing, software distributed under the License is
* distributed on an "AS IS" BASIS, WITHOUT ANY WARRANTIES OR CONDITIONS OF ANY KIND,
* either express or implied. See the License for the specific language governing
* permissions and limitations under the License. You may obtain a copy of the License
* at <http://corebos.org/documentation/doku.php?id=en:devel:vpl11>
 *  Module       : EntittyLog
 *  Version      : 5.4.0
 *  Author       : LoggingConf
 *************************************************************************************************/


function OpenSelectModule(){
$('undermodules').style.display='block';
$('getmodules').style.display='block';
$('closegetmodule').style.display='block';
new Ajax.Request(
        'index.php',
        {
            queue: {
                position: 'end',
                scope: 'command'
            },
            method: 'post',
            postBody:"module=LoggingConf&action=LoggingConfAjax&file=GetModules",
            onComplete: function(data) {
                var response=data.responseText;
                $('showmodules').innerHTML=response;
               
            }
        }
        );
}
 function saveit(){
	$("status").style.display="inline";
	var moduleval=$('Screen').value;
        var values=new Array();
        var values1=new Array();
        var relmodule=new Array();
        var chks = document.getElementsByName('fieldstobelogged'+moduleval+'[]');
     var j=0;
        for (var i = 0; i < chks.length; i++)
        {
        if (chks[i].checked)
            {
                 
              values[j]=chks[i].value;         
              j++;
            }                
        }
        
     var selmod;
     var j1=0;
     var k=0;
      var chks1;
        for (var i = 0; i < chks.length; i++)
        { 
             chks1 = document.getElementsByName('fieldselastic'+moduleval+i);

     selmod = document.getElementById('modulerel'+moduleval+chks[i].value+i+'[]');

        if (chks1.item(0)!=null && chks1.item(0).checked)
            {
                 
              values1[j1]=chks1.item(0).value;         
              j1++;
            }
        if(selmod!=null)
        {
            relmodule[k]=selmod.value;
            k++;
        }
        }
  var relmodule1=relmodule.join(";")
if(document.getElementById("entitylog"+moduleval)!=null){
        var elog=document.getElementById("entitylog"+moduleval).checked;
        var denorm=document.getElementById("denorm"+moduleval).checked;
        var norm=document.getElementById("norm"+moduleval).checked;
        var indextype=document.getElementById("indextype"+moduleval).value;
        if(document.getElementsByName('brinlog').item(0)!=undefined)
        var brelastic=document.getElementsByName('brinlog').item(0).value;
       }
	new Ajax.Request(
		'index.php',
		{queue: {position: 'end', scope: 'command'},
			method: 'post',
			postBody: 'module=LoggingConf&action=LoggingConfAjax&file=UpdateLoggingConfiguration&Screen='+moduleval+'&fieldstobeloggedModule='+serialize(values)+"&elog="+elog+"&denorm="+denorm+"&norm="+norm+'&fieldselasticModule='+serialize(values1)+"&relmodule="+relmodule1+'&indextype='+indextype+'&brelastic='+brelastic,
			onComplete: function(response) {                           
				
                                window.location="index.php?action=index&module=LoggingConf&fld_module="+moduleval;
			}
		}
	);

}
function addModuleToLog()
{   
var tabidsvalues='';
var chks = document.getElementsByName('tabids[]');
for (var i = 0; i < chks.length; i++)
{
if (chks[i].checked)
    {
        if (tabidsvalues=='')
            tabidsvalues+=chks[i].value;
        else   tabidsvalues+='-'+chks[i].value;
    }


} 
    new Ajax.Request(
        'index.php',
        {
            queue: {
                position: 'end',
                scope: 'command'
            },
            method: 'post',
            postBody:"module=LoggingConf&action=LoggingConfAjax&file=AddModuleToLog&tabidvalues="+tabidsvalues,
            onComplete: function(data) {                
                updateModules();
                hide('undermodules');
                hide('getmodules');            

            }
        }
        );

}
function updateModules()
{
    new Ajax.Request(
        'index.php',
        {
            queue: {
                position: 'end',
                scope: 'command'
            },
            method: 'post',
            postBody:"module=LoggingConf&action=LoggingConfAjax&file=GetModules&which=LoggedModules",
            onComplete: function(data) {
                var response=data.responseText;                
                $('Screen').innerHTML=response;

            }
        }
        );
}
function serialize (txt) {
	switch(typeof(txt)){
	case 'string':
		return 's:'+txt.length+':"'+txt+'";';
	case 'number':
		if(txt>=0 && String(txt).indexOf('.') == -1 && txt < 65536) return 'i:'+txt+';';
		return 'd:'+txt+';';
	case 'boolean':
		return 'b:'+( (txt)?'1':'0' )+';';
	case 'object':
		var i=0,k,ret='';
		for(k in txt){

			if(!isNaN(k)) k = Number(k);
			ret += serialize(k)+serialize(txt[k]);
			i++;
		}
		return 'a:'+i+':{'+ret+'}';
	default:
		return 'N;';
		alert('var undefined: '+typeof(txt));return undefined;
	}
}
