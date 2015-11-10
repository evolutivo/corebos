<table ng-controller="ng_EvoFields" ng-table="tableParams" class="table  table-bordered table-responsive" width=100% >
    <tr>
        <td colspan="4">
             <button class="btn btn-primary" ng-click="open2(new_user,'add')">Add New Evo Field</button>
        </td>
    </tr>
    <tr style="background-color:#c9dff0">
        <th style="align:center">
        </th> <th style="text-align: center">
            Name
        </th>
        <th style="text-align: center">
            Module
        </th>
    </tr> 
    <tr ng-repeat="user in $data" >
          <td>                
              <table>
              <tr>
                  <th>
                      <img ng-if="!user.$edit" width="20" height="20" ng-click="open2(user,'edit')" src="themes/images/editfield.gif" /> 
                      <a ng-click="open2(user,'edit')">Edit</a> 
                  </th>
                  <th>
                      <img ng-if="!user.$edit" width="20" height="20" ng-click="delete_record2(user)" src="themes/images/delete.gif" />
                      <a ng-click="delete_record2(user)">Delete</a>
                  </th>   
              </tr>             
              </table>
          </td>
          <td> 
              {literal}{{user.fieldname}} {/literal}                                                         
          </td> 
          <td> 
              {literal}{{user.modulename}}{/literal}
          </td>
    </tr>
</table>
          