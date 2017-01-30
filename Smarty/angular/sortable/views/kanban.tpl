<input type="hidden" id="questionids" ng-model="data" value='' /> 
<div ng-model="kanbanBoard" id="board" ng-controller="KanbanController">  
    <input type="hidden" id="selectedprocessflow" ng-model="data" value=''/>
    <input type="hidden" id="currentprocessflow" ng-model="data" value=''/>
    <input type='hidden' id='selectedcolumn' ng-model='data' value=''/>
    <input type='hidden' id='nextcase' ng-model='data' value=''/>
    <div id="columns" class="row">
        <accordion close-others="true" >
            <table width='100%' >
              <tr ng-repeat="column in kanbanBoard.columns" ng-init="sec_col=$index+1 ">
                  <td ng-if="$index%2==0" style="padding-right:7px;" width="65%" >
                        <div class="column"  >
                            <div class="columnHeader">
                               <center> <span >{{column.name}} </span></center>
                            </div>
                            <ul class="cards card-list"  ng-model="column.cards"> 
                             <a ng-if="$index%2==0" title="Cambia Stato" ng-click="addNewCard(kanbanBoard.columns[sec_col]);processflowselected(card.pfid,card.currentid);" > 
                                 <img src='Smarty/angular/bootstrap/img/green_arrow.png' style="float: right; margin-right: 30px;"> </a><br/>
                           <!--<br/>  <i class=" glyphicon-arrow-right addNewCard" style="color: #005c83;" ng-repeat="card in column.cards">{{card.status}}</i>             
                             <span style="color: #005c83; margin-left: 20%;" ng-repeat="card in column.cards">{{card.ptname}}</span>
                                <li class="card" ng-repeat="card in column.cards" ng-init="tmp=true" ng-include="'Smarty/angular/sortable/views/partials/card.html'">
                                </li>
                              --> 
                            </ul>                                 
                        </div>  
                    </td>
              <td ng-if="$index%2==0 && kanbanBoard.columns[sec_col].name " width="35%" style="height:100%;vertical-align: top;">
                        <div class="column" style="height:100%">
                            <div class="columnHeader">
                                <span>{{kanbanBoard.columns[sec_col].name}} </span>

                            </div>
                            <!--<ul  class="cards card-list"  ng-model="kanbanBoard.columns[sec_col].cards">
                                <li class="card" ng-repeat="card in kanbanBoard.columns[sec_col].cards"  ng-include="'Smarty/angular/sortable/views/partials/card.html'">
                                </li>
                            </ul>-->
                        </div>  
                    </td>
                    
              
              </tr >
              </table>  
         </accordion>
    </div>
</div> 