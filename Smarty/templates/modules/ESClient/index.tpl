{*<!--
 *************************************************************************************************
 * Copyright 2015 OpenCubed -- This file is a part of OpenCubed coreBOS customizations.
 * You can copy, adapt and distribute the work under the "Attribution-NonCommercial-ShareAlike"
 * Vizsage Public License (the "License"). You may not use this file except in compliance with the
 * License. Roughly speaking, non-commercial users may share and modify this code, but must give credit
 * and share improvements. However, for proper details please read the full License, available at
 * http://vizsage.com/license/Vizsage-License-BY-NC-SA.html and the handy reference for understanding
 * the full license at http://vizsage.com/license/Vizsage-Deed-BY-NC-SA.html. Unless required by
 * applicable law or agreed to in writing, any software distributed under the License is distributed
 * on an  "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the
 * License terms of Creative Commons Attribution-NonCommercial-ShareAlike 3.0 (the License).
 *************************************************************************************************
 *  Module       : ESClient
 *  Version      : 5.5.0
 *  Author       : OpenCubed.
 *************************************************************************************************/
-->*}
<script src="modules/ESClient/media/js/jquery.min.js"></script>
		<script src="modules/ESClient/media/js/jquery-ui.min.js"></script>
		<script src="modules/ESClient/media/js/ui/jquery.ui.core.js"></script>
		<script src="modules/ESClient/media/js/jquery.themeswitcher.js"></script>
		<script src="modules/ESClient/media/js/jquery.multiselect.js"></script>
		<script src="modules/ESClient/media/js/jquery.dataTables.js"></script>
		<script src="modules/ESClient/media/js/underscore-min.js"></script>
		<script src="modules/ESClient/media/js/TableTools.js"></script>		
		<script src="modules/ESClient/media/js/jquery.multiselect.filter.js"></script>
		<script src="modules/ESClient/media/js/backbone-min.js" ></script>
		<script src="modules/ESClient/media/js/pretty-json-min.js" ></script>		
		<script src="modules/ESClient/media/js/jquery.switchButton.js"></script>
		<script src="modules/ESClient/media/js/esclient.js"></script>
		<script src="modules/ESClient/config.js"></script>
		
		<style type="text/css" title="currentStyle">
			@import "modules/ESClient/media/css/jquery.multiselect.css";
			@import "modules/ESClient/media/css/dataTables.tableTools.css";
			@import "modules/ESClient/media/css/jquery.multiselect.filter.css";
			@import "modules/ESClient/media/css/jquery.switchButton.css";
			@import "modules/ESClient/media/css/pretty-json.css";
			@import "modules/ESClient/media/css/demo_table.css";

               </style>
     {literal}
<style>

element.style {
    position: absolute;
}

.topRight
{
    position: absolute;
    right: 0;
    top: 18%;
}

textarea
{
	width: 620px;
	height: 50px;
	border: 1px solid #cccccc;
	padding: 5px;
	font-family: Tahoma, sans-serif;
	font-size: 100%;
	background-image: url(bg.gif);
	background-position: bottom right;
	background-repeat: no-repeat;
} 

div.wrapper {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    width: 300px;
}

h2
{
	background-color:#FDF5CE;
	color:#1C94C4;
}

</style>		
 {/literal}
</head>
<script>
     {literal}
         function uploadelastic(){
           var sel=document.getElementById('files').value;
             $.ajax({
            type: "POST",
            data: "&files="+sel,
            url: "index.php?module=ESClient&action=ESClientAjax&file=uploadelastic",
            success: function(result){
            if(result=='1')
                alert("File Successfully uploaded to Elastic");
            else alert("Something went wrong");
            }
        });

         }
$(document).ready(function () {
 
   $("#tabs" ).tabs({ active: 0 });
	var ul = $( "#tabs" ).find( "ul" );
	if (Config.SHOW_JSON_RESULS )
	{
		$( "<li id='json-tab-li'><a href='#json-tab'>JSON Results</a></li>" ).appendTo( ul );
	}
	if (Config.SHOW_MAPPING_INFO )
	{
		$( "<li id='mapping-tab-li'><a href='#mapping-tab'>Mapping Info</a></li>" ).appendTo( ul );
	}	
	$( "#tabs" ).tabs( "refresh" );
   
   $("#sortOrder").switchButton({
		  on_label: 'Asc',
		  off_label: 'Desc',
		  checked: true
		});
   
   $("#lowerCaseExpandedTerms").switchButton({
		  on_label: 'True',
		  off_label: 'False',
		  checked: Config.EXPAND_LOWERCASE_TERMS
		});
	$("#mappingFormatJSON").switchButton({
		  on_label: 'JSON',
		  off_label: 'Tabular',
		  checked: true
		});
	$("#mappingFormatJSON").change(function() {
		showMapping();
	});	
   $("#useLucene").switchButton({
		  on_label: 'Lucene',
		  off_label: 'DSL',
		  checked: Config.USE_LUCENE_QUERY_TYPE
		});
	
   $("#showJsonResults").switchButton({
		  on_label: 'True',
		  off_label: 'False',
		  checked: Config.SHOW_JSON_RESULS
		});	
   $("#showMappingInfo").switchButton({
		  on_label: 'True',
		  off_label: 'False',
		  checked: Config.SHOW_MAPPING_INFO
		});		
   $("#indexDrop").switchButton({
		  on_label: 'True',
		  off_label: 'False',
		  checked: Config.ENABLE_INDEX_DROP
		});	

   $("#analyzeWildcard").switchButton({
		  on_label: 'True',
		  off_label: 'False',
		  checked: Config.ANALYZE_WILDCARD
		});		

  $("#showJsonResults").change(function() {
    if(this.checked) 
	{
		var ul = $( "#tabs" ).find( "ul" );
		$( "<li id='json-tab-li'><a href='#json-tab'>JSON Results</a></li>" ).appendTo( ul );
	}
	else
	{
		$( "#json-tab-li" ).remove();
	}
	$( "#tabs" ).tabs( "refresh" );
	});
  $("#showMappingInfo").change(function() {
    if(this.checked) 
	{
		var ul = $( "#tabs" ).find( "ul" );
		$( "<li id='mapping-tab-li'><a href='#mapping-tab'>Mapping Info</a></li>" ).appendTo( ul );
	}
	else
	{
		$( "#mapping-tab-li" ).remove();
	}
	$( "#tabs" ).tabs( "refresh" );
	});		
   $("#switcher").themeswitcher({
     imgpath: "modules/ESClient/media/images/",
     loadTheme: Config.THEME
     });

    $("body").css("fontSize", "75%");
	$("button, input:submit, input:button").button();
	$('#index').change(function() {
		loadTypesForIndex($(this).val());
	});
	$('#MappingIndex').change(function() {
		loadMappingTypesForIndex($(this).val());
	});
	$('#indexTypes').change(function() {
		var coldData = setAutoCompleteForQuery();
		populateSearchFields(coldData);
		populateSortFields(coldData);
	});
	$("#location").val( Config.CLUSTER_URL );
	$("#size").val( Config.SIZE );
	$("#from").val( Config.FROM );
	
	$("#searchType").multiselect({
	   multiple: false,
	   show: ['slide', 500],
	   minWidth: 160,
	   selectedList: 1	   
	});
	$("#searchType option[value='" + Config.SEARCH_TYPE + "']").attr("selected", 1);
	$("#searchType").multiselect("refresh");
	
	$("#defOperator").multiselect({
	   multiple: false,
	   show: ['slide', 500],
	   minWidth: 160,
	   selectedList: 1	   
	});
	$("#defOperator option[value='" + Config.DEFAULT_OPERATOR + "']").attr("selected", 1);
	$("#defOperator").multiselect("refresh");
	$("#index").multiselect({
	   noneSelectedText: "{/literal}{'chooseindex'|@getTranslatedString:'chooseindex'}{literal}",
	   multiple: true,
	   show: ['slide', 500],
	   minWidth: 250,
	   selectedList: 2
	  }).multiselectfilter();
	$("#MappingIndex").multiselect({
	   noneSelectedText: "{/literal}{'chooseindex'|@getTranslatedString:'chooseindex'}{literal}",
	   multiple: false,
	   show: ['slide', 500],
	   minWidth: 250,
	   selectedList: 2
	  }).multiselectfilter();	  
	$("#indexTypes").multiselect({
	   noneSelectedText: "{/literal}{'choosetype'|@getTranslatedString:'choosetype'}{literal}",
	   multiple: false,
	   show: ['slide', 500],
	   minWidth: 250,
	   selectedList: 1
	  }).multiselectfilter();
	$("#MappingIndexTypes").multiselect({
	   noneSelectedText: "{/literal}{'choosetype'|@getTranslatedString:'choosetype'}{literal}",
	   multiple: false,
	   show: ['slide', 500],
	   minWidth: 250,
	   selectedList: 1
	  }).multiselectfilter();	  
	$("#indexFields").multiselect({
	   noneSelectedText: "{/literal}{'searchfields'|@getTranslatedString:'searchfields'}{literal}",
	   multiple: true,
	   show: ['slide', 500],
	   minWidth: 250
	  }).multiselectfilter();
	$("#sortFields").multiselect({
	   noneSelectedText: "{/literal}{'sortfields'|@getTranslatedString:'sortfields'}{literal}",
	   multiple: false,
	   show: ['slide', 500],
	   minWidth: 250,
	   selectedList: 1
	  }).multiselectfilter();	  
	

   $( "#dialog" ).dialog({ autoOpen: false, show: 'slide', 
    buttons: [
        {
            id: "button-cancel",
            text: "Cancel",
            click: function() {
                $(this).dialog("close");
            }
        },
        {
            id: "button-ok",
            text: "Ok",
            click: function() {
				$( this ).attr("disabled", true).addClass("ui-state-disabled");
                deleteRows();
				$(this).dialog("close");
            }
        }
    ]
			 
   });	

   $( "#rowView" ).dialog({ autoOpen: false, show: 'slide', 
    buttons: [
        {
            id: "button-ok",
            text: "Ok",
            click: function() {
				$(this).dialog("close");
            }
        },
       {
            id: "button-edit",
            text: "Edit",
            click: function() {
				$(this).dialog("close");
				showEditableJSON(this);
            }
        }		
    ]
   });
   
    $( "#deleteByQuery" ).dialog({ autoOpen: false, 
	show: {
				effect: "blind",
				duration: 1000
			},
			hide: {
				effect: "blind",
				duration: 1000
			},	
    buttons: [
        {
            id: "button-cancel",
            text: "Cancel",
            click: function() {
				$(this).dialog("close");
            }
        },
       {
            id: "button-delete",
            text: "Delete",
            click: function() {
				$(this).dialog("close");
				validateQueryAndDeleteRowsByQuery();
            }
        }		
    ]
     }); 
    $( "#deleteIndex" ).dialog({ autoOpen: false, 
	show: {
				effect: "blind",
				duration: 1000
			},
			hide: {
				effect: "blind",
				duration: 1000
			},	
    buttons: [
        {
            id: "button-cancel",
            text: "Cancel",
            click: function() {
				$(this).dialog("close");
            }
        },
       {
            id: "button-delete",
            text: "Delete",
            click: function() {
				$(this).dialog("close");
				dropIndexOrType();
            }
        }		
    ]
     }); 
	 
	$("#luceneEscape").click(function(){
		escapeLuceneChars();
	});

	setQueryLabel();	
});

 {/literal}
</script>

<div id="header" style="margin-top:3%;margin-left:2%;">
<div id="switcher" class="topRight"></div>
<table width="70%" border="0">
		<tr>
			<td align="left">
				<div class="ui-widget">
					<div class="ui-state-highlight ui-corner-all">
						<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;" ></span>
							<button id="refreshConn" onclick="refreshConnection()" style="display: none; float: right; width: 25; height: 27px; margin-left: .5em;" title="Reconnect and refresh" >
								<img src="modules/ESClient/media/images/refresh.png" height="17" width="20">
							</button>						
							<div id="cluster"><strong>{'notconnected'|@getTranslatedString:'notconnected'}</strong></div>
						</p>
					</div>
				</div>			
			</td>
		</tr>
</table>
</div> <!-- /#header -->

<div id="tabs" style="margin-left:2%;margin-right:2%;">
  <ul>
    <li><a href="#config-tab">{'Configuration'|@getTranslatedString:'Configuration'}</a></li>
    <li><a href="#search-tab">{'Search'|@getTranslatedString:'Search'}</a></li>
    <li><a href="#upload-tab">{'Upload'|@getTranslatedString:'Upload'}</a></li>
  </ul>	
    <div id='upload-tab'>{'choosefile'|@getTranslatedString:'choosefile'}: {$sel} &nbsp;&nbsp;<input type="button" name="button" value="{'Upload'|@getTranslatedString:'Upload'}" onclick="uploadelastic()"></div>
<div id="search-tab">
<table width="70%" border="0">
		<tr>
			<td	colspan="2" align="left">
					<div>
						  <select id="index" onChange="" title="Select Index" class="multiselect indexClass" multiple="multiple"></select>
					</div>
			</td>
			<td  colspan="2" align="left">	
						<div id="dropIndexDiv">
							<button style="width: 140px; height: 30px;" id="dropIndex" onclick="confirmIndexDelete()">{'drop'|@getTranslatedString:'drop'}</button>
						</div>						
			</td>			
		</tr>
		<tr>
			<td colspan="2" align="left">	
					<div>
						  <select id="indexTypes" onChange="" title="Select Type" class="multiselect indexTypesClass" multiple="multiple"></select>
					</div>					
			</td>
			<td  colspan="2" align="left">	
						<div>
							<button  style="width: 140px; height: 30px;" id="delete" onclick="confirmDelete()">{'deleteid'|@getTranslatedString:'deleteid'}</button>
						</div>						
			</td>
		</tr>
		<tr>
			<td colspan="1" align="left" >	
					<div>
						  <select id="indexFields" onChange="" title="Select Fields" class="multiselect" multiple="multiple"></select>
					</div>					
			</td>
			<td colspan="1" align="left" >	
					<div style="float: left; margin-left: 7em;" >
						  <select id="sortFields" onChange="" title="Select Fields" class="multiselect" multiple="multiple"></select>
					</div>
					<div style="float: right; margin-right: 3em; margin-top: 0.3em;">
						  <input type="checkbox" id="sortOrder" class="ui-state-highlight ui-corner-all">
					</div>					
			</td>
			<td colspan="2" align="right" >	
					
			</td>				
		</tr>
		<tr>
			<td colspan="4">
					<div>
						  <label for="query"><strong>{'routing'|@getTranslatedString:'routing'}:</strong></label>
					</div>
			</td>
		</tr>		
		<tr>
			<td colspan="2">
					<div>
						  <input type="text" id="routingKey" style="width: 375px" />
					</div>
			</td>
			<td  colspan="2" align="left">	
						<div>
							<button  style="width: 140px; height: 30px;" id="queryDelete" onclick="confirmDeleteByQuery()">{'deletequery'|@getTranslatedString:'deletequery'}</button>
						</div>						
			</td>			
		</tr>		
		<tr>
			<td colspan="4">
					<div>
						  <label id="querySyntax"></label>
					</div>
			</td>
		</tr>		
		<tr>
			<td colspan="2" align="left">
					<div>
						  <textarea name="query" id="query" >*</textarea>
						  <button id="luceneEscape" style="width: 50px; height: 30px;" title="Escape Lucene characters" ><img src="modules/ESClient/media/images/LuceneEscape.png" style="width: 25px; height: 25px;"></button>
					</div>
			</td>
			<td  colspan="2" align="left">	
					<div>
						<button style="width: 140px; height: 30px;" id="search" onclick="validateQueryAndSearch()">{'searchindex'|@getTranslatedString:'searchindex'}</button>
					</div>						
			</td>				
		</tr>		
	</table>			

<div id="dialog" title="Please confirm!" class="textColor">
	This is the default dialog which is useful for displaying information.
</div>
<div id="rowView" title="JSON data"></div>
<div id="deleteByQuery" title="Confirm DELETE" ></div>
<div id="deleteIndex" title="Confirm DELETE" ></div>

<div id="rowUpdate" title="Edit data" style="display: none; width: 700px; height: 400px;">
         <div>
               <textarea cols="70" rows="300" style="width: 650px; height: 275px;" id="editRow"></textarea>
         </div>
</div>


<div id="totalResults" style:"color:#1C94C4"></div>
	<div class="widget-content">
		<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
				<thead>
				</thead>
				<tbody>
				</tbody>								
		</table>
	</div>						
</div>
<div id="config-tab">
<table width="50%" border="0">
		<tr>
			<td	colspan="1" width="5%" align="left">
					<div>
						  <strong>Cluster:</strong>
					</div>
			</td>
			<td	colspan="1" width="75%" align="left">
					<div>
						  <input type="text" id="location" class="ui-state-highlight ui-corner-all" style="width: 375px" />
					</div>
			</td>
			<td	colspan="1" width="5%" align="left">
					<div>&nbsp;</div>
			</td>			
			<td  colspan="1" width="15%" align="left">	
					<div>
						<button id="connect" onclick="connectToES()">Connect</button>
					</div>					
			</td>			
		</tr>
		<tr>
			<td colspan="2" align="left"  width="30%">	
					<div>
						  <label><strong>{'From'|@getTranslatedString:'From'}:</strong></label>
					</div>					
			</td>
			<td	colspan="1" width="5%" align="left">
					<div>&nbsp;</div>
			</td>			
			<td colspan="1" align="left"  width="65%">	
					<div>
						  <input type="input" id="from"  class="ui-state-highlight ui-corner-all">
					</div>					
			</td>		
		</tr>		
		<tr>
			<td colspan="2" align="left"  width="30%">	
					<div>
						  <label><strong>{'Size'|@getTranslatedString:'Size'}:</strong></label>
					</div>					
			</td>
			<td	colspan="1" width="5%" align="left">
					<div>&nbsp;</div>
			</td>			
			<td colspan="2" align="left"  width="65%">	
					<div>
						  <input type="input" id="size"  class="ui-state-highlight ui-corner-all">
					</div>					
			</td>		
		</tr>
		<tr>
			<td colspan="2" align="left"  width="30%">	
					<div>
						  <label><strong>{'Searchtype'|@getTranslatedString:'Searchtype'}:</strong></label>
					</div>					
			</td>
			<td	colspan="1" width="5%" align="left">
					<div>&nbsp;</div>
			</td>			
			<td colspan="2" align="left"  width="65%">	
					<div>
						 <select id="searchType" multiple="multiple">
							<option value="query_then_fetch">query_then_fetch</option>
							<option value="query_and_fetch">query_and_fetch</option>
							<option value="dfs_query_then_fetch">dfs_query_then_fetch</option>
							<option value="dfs_query_and_fetch">dfs_query_and_fetch</option>
						</select>
					</div>					
			</td>		
		</tr>
		<tr>
			<td colspan="2" align="left"  width="30%">	
					<div>
						  <label><strong>{'defaultop'|@getTranslatedString:'defaultop'}:</strong></label>
					</div>					
			</td>	
			<td	colspan="1" width="5%" align="left">
					<div>&nbsp;</div>
			</td>			
			<td colspan="2" align="left"  width="65%">	
					<div>
						 <select id="defOperator" multiple="multiple">
							<option value="AND">AND</option>
							<option value="OR">OR</option>
						</select>
					</div>					
			</td>		
		</tr>		
		<tr>
			<td colspan="2" align="left"  width="30%">	
					<div>
						  <label><strong>{'lowercase'|@getTranslatedString:'lowercase'}:</strong></label>
					</div>					
			</td>
			<td	colspan="1" width="5%" align="left">
					<div>&nbsp;</div>
			</td>			
			<td colspan="1" align="right"  width="65%">	
					<div>
						  <input type="checkbox" id="lowerCaseExpandedTerms">
					</div>					
			</td>		
		</tr>
		<tr>
			<td colspan="2" align="left"  width="30%">	
					<div>
						  <label><strong>{'analyze'|@getTranslatedString:'analyze'}:</strong></label>
					</div>					
			</td>
			<td	colspan="1" width="5%" align="left">
					<div>&nbsp;</div>
			</td>		
			<td colspan="1" align="right"  width="65%">	
					<div>
						  <input type="checkbox" id="analyzeWildcard">
					</div>					
			</td>		
		</tr>		
		<tr>
			<td colspan="2" align="left"  width="30%">	
					<div>
						  <label><strong>{'Querytype'|@getTranslatedString:'Querytype'}:</strong></label>
					</div>					
			</td>
			<td	colspan="1" width="5%" align="left">
					<div>&nbsp;</div>
			</td>			
			<td colspan="1" align="right"  width="65%">	
					<div>
						  <input type="checkbox" id="useLucene" onchange="setQueryLabel()">
					</div>					
			</td>		
		</tr>
		<tr>
			<td colspan="2" align="left"  width="30%">	
					<div>
						  <label><strong>{'Enableindex'|@getTranslatedString:'Enableindex'}:</strong></label>
					</div>					
			</td>
			<td	colspan="1" width="5%" align="left">
					<div>&nbsp;</div>
			</td>			
			<td colspan="1" align="right"  width="65%">	
					<div>
						  <input type="checkbox" id="indexDrop" onchange="showDropIndex()">
					</div>					
			</td>		
		</tr>
		<tr>
			<td colspan="2" align="left"  width="30%">	
					<div>
						  <label><strong>{'showjson'|@getTranslatedString:'showjson'}:</strong></label>
					</div>					
			</td>
			<td	colspan="1" width="5%" align="left">
					<div>&nbsp;</div>
			</td>			
			<td colspan="1" align="right"  width="65%">	
					<div>
						  <input type="checkbox" id="showJsonResults">
					</div>					
			</td>		
		</tr>
		<tr>
			<td colspan="2" align="left"  width="30%">	
					<div>
						  <label><strong>{'showmapping'|@getTranslatedString:'showmapping'}:</strong></label>
					</div>					
			</td>
			<td	colspan="1" width="5%" align="left">
					<div>&nbsp;</div>
			</td>			
			<td colspan="1" align="right"  width="65%">	
					<div>
						  <input type="checkbox" id="showMappingInfo">
					</div>					
			</td>		
		</tr>		
		
</table>
</div>
<div id="json-tab" style="height:500px; display: none; overflow:scroll">
	<div id="jsonResults">{'jsonres'|@getTranslatedString:'jsonres'}</div>
</div>
<div id="mapping-tab" style="height:500px; display: none; overflow:scroll" >
	<div style="width: 25%; float:left; text-align: left;">
		<table border="0">
			<tr>
				<td	colspan="2" align="left">
						<div>
							  <select id="MappingIndex" onChange="" title="Select Index" class="multiselect indexClass" multiple="multiple"></select>
						</div>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="left">	
						<div>
							  <select id="MappingIndexTypes" onChange="" title="Select Type" class="multiselect indexTypesClass" multiple="multiple"></select>
						</div>					
				</td>
			</tr>
			<tr>
				<td colspan="1" align="left">	
						<div>
							  <button id="showMapping" onclick="showMapping()">Show Mapping</button>
						</div>					
				</td>
				<td colspan="1" align="right">
					<div>
					    <input type="checkbox" id="mappingFormatJSON">
					</div>
				</td>				
			</tr>
		</table>
	</div>

	<div id="mapping" style="width: 75%; float:right; text-align: left;">
		<div id="mappingJson"></div>
		<div id="mappingTableDiv" class="widget-content">
		<strong>Tabular format displays only most commonly used mapping attributes</strong>
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="mappingTable">
					<thead>
					</thead>
					<tbody>
					</tbody>								
			</table>
		</div>
	</div>
</div>
	