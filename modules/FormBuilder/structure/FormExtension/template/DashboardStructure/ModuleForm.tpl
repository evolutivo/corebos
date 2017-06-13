<input type="hidden" name="record" id="record" value="{$record}"/>
<input type="hidden" name="masterModule" id="masterModule" value="{$masterModule}"/>
<input type="hidden" name="module" id="module" value="{$MODULE}"/>
<input type="hidden" name="onOpenView" id="onOpenView" value="{$onOpenView}"/>
<input type="hidden" name="RoleId" id="RoleId" value="{$RoleId}"/>
<input type="hidden" name="Profiles" id="Profiles" value={$Profiles|@json_encode} />
<input type="hidden" name="OutsideData" id="OutsideData" value="{$OutsideData}" />
<input type="hidden" name="ENTITIES" id="ENTITIES" value={$ENTITIES} />
<input type="hidden" name="LoggedUser" id="LoggedUser" value={$LoggedUser} />
<input type="hidden" name="LoggedUserName" id="LoggedUserName" value={$LoggedUserName} />
<div ng-view></div>        