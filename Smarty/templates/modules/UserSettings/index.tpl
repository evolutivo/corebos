<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tbody><tr>
        <td valign="top"><img src="{'showPanelTopLeft.gif'|@vtiger_imageurl:$THEME}"></td>
	<td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
	<br>
	<div align=center>
	<table border=0 cellspacing=0 cellpadding=20 width=90% class="settingsUI">
	<tr>
		<td>
		<table border=0 cellspacing=0 cellpadding=0 width=100% height=400px>
			<tr>
				<td class="settingsTabHeader" height="50px">
					Users &amp; Access Management
				</td>
			</tr>
			<tr>
				<td class="settingsIconDisplay small">
					<table border=0 cellspacing=0 cellpadding=10 width=100%>
						<tr>
						{foreach from=$ITEMS key=id item=i}
							<td width=25% valign=top>				
							<table border=0 cellspacing=0 cellpadding=5 width=100%>
								<tr>
									<td rowspan=2 valign=top>
										<a href="{$i.link}">
											<img src="themes/images/{$i.img}" width="48" height="48" border=0 title="{$i.label}">
										</a>
									</td>
									<td class=big valign=top>
										<a href="{$i.link}">
											{$i.label}
										</a>
									</td>
								</tr>
								<tr>
									<td class="small" valign=top>
										{$i.description}
									</td>
								</tr>
							</table>
							</td>
						{/foreach}
							</tr><tr>
						</table>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>