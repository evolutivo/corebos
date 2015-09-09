{literal}
<div id="grid"></div>
<div style="font-weight:bold;">
<input type="radio" name="vtappListActiveAccounts" value="future" onclick='vtappaactl_setaccountview("future");' {/literal}{$actFuturoChk}/>{$actFuturo}{literal}
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="vtappListActiveAccounts" value="past" onclick='vtappaactl_setaccountview("past");' {/literal}{$actPasadoChk}/>{$actPasado}{literal}
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="vtappListActiveAccounts" value="sincita" onclick='vtappaactl_setaccountview("sincita");' {/literal}{$sinCitaChk}/>{$sinCitas}{literal}</div>
<script>
	function vtappaactl_setaccountview(direction) {
		$.ajax({
			  url: 'index.php?'+evvtURLp+'&vtappaction=dovtAppMethod&vtappmethod=savepgvars&class={/literal}{$appClass}{literal}&appid={/literal}{$appId}{literal}&direction='+direction,
			  success: function(){
				$('#vtapp{/literal}{$appId}{literal}').data('kendoWindow').refresh()
			  }
		});
	}
    $("#grid").kendoGrid({
        dataSource: {
	        type: "json",
	        transport: {
	            read: {
		            url: 'index.php?'+evvtURLp+'&vtappaction=dovtAppMethod&vtappmethod=getListElements&class={/literal}{$appClass}{literal}&appid={/literal}{$appId}{literal}',
		            dataType: "json"
	            }
	        },
	        schema: {
	       	    data: "results",
	       	    total: "total"
	        },
	        pageSize: 25,
	        serverPaging: true,
	        serverFiltering: false,
	        serverSorting: true
        },
        height: 450,
        filterable: false,
        sortable: {
	        mode: "multiple",
	        allowUnsort: true
        },
        pageable: true,
        columns: [
{/literal}
	{foreach item=data from=$kendocols name=kcols}
        {ldelim}
            field: "{$data.field}",
            filterable: false,
            title: "{$data.title}",
            encoded: {$data.encoded} 
        {rdelim}{if not $smarty.foreach.kcols.last},{/if}
    {/foreach}
{literal}
        ]
    });
</script>
{/literal}
