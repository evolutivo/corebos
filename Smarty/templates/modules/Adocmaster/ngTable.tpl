{ngtabadoc}
<link rel="stylesheet" href="modules/Adocmaster/angulareditable/css/bootstrap.min.css">
<link rel="stylesheet" href="modules/Adocmaster/angulareditable/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="modules/Adocmaster/angulareditable/ng-table.css">

<!--<h1>Table with pagination</h1>-->
<table class="small" border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td colspan="4" class="dvInnerHeader">
	<div style="float: left; font-weight: bold;">
	<div style="float: left;">
	<a href="javascript:showHideStatus('tbl{$UIKEY}','aid{$UIKEY}','$IMAGE_PATH');"><img id="aid{$UIKEY}" src="{'activate.gif'|@vtiger_imageurl:$THEME}" style="border: 0px solid rgb(0, 0, 0);" alt="Hide" title="Hide"></a>
	</div><b>&nbsp;{$WIDGET_TITLE}</b></div>
</td>
</tr>

</table>
<div ng-controller="DemoCtrl" >
<!--{literal}{{getTotal()}}{/literal}
{literal}{{getTax()}}{/literal}
{literal}{{getTotal2()}}{/literal}-->
<!-- <input auto  ng-model="selected">
 selected = {literal}{{selected}}{/literal}-->
<!--  <input width="20%" type="text" data-ng-model="question_handset" list="phones" class="form-control">
<datalist id="phones">
<option  data-ng-repeat="ttl in titles" value="{literal}{{ttl}}{/literal}">
</datalist>
selected: {literal}{{question_handset}}{/literal}-->
{ngtabadoc}
<div id='adocactionbar' style='width:100%'>
<span>
<br> <button type="button" ng-click="addAdocdetail2(user.quantity2);">{$MOD.NGADDBUTTON}</button>&nbsp;&nbsp;
{$MOD.NGPRODUCTINPUT}&nbsp;<input type="text" id="adoc_product2_display" ng-model="user.adoc_product2_display" name="adoc_product2_display">
	<input type="hidden" value="{literal}{{user.adoc_product2}}{/literal}" id="adoc_product2" name="adoc_product2" >
	<img src="themes/softed/images/select.gif"  alt="Select" title="Select" LANGUAGE=javascript  onclick='return window.open("index.php?module=Products&action=Popup&html=Popup_picker&form=vtlibPopupView&forfield=adoc_product2&srcmodule=","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
&nbsp;&nbsp;{$MOD.NGQUANTITYINPUT}&nbsp; <input id="quantity2" type="text" ng-model="user.quantity2">
</span><span style="float: right">
{$MOD.NGGROUPBY}&nbsp;
<select ng-model="groupby">
	<option value="---">{$MOD.NGNONE}</option>
	<option value="adoc_product_display">{$MOD.NGPRODUCT}</option>
	<option value="quantity">{$MOD.NGQUANTITY}</option>
	<option value="price">{$MOD.NGPRICE}</option>
</select>
<br><br>
</span>
</div>
<!--<p><strong>Filter:</strong> {literal}{{tableParams.filter()|json}}{/literal}-->
<!--another comment-->
<!--<pre>Value: = {literal}{{user.adoc_product}}{/literal}</pre>-->
    <!--<p><strong>Page:</strong> {literal}{{tableParams.page()}}{/literal}
    <p><strong>Count per page:</strong> {literal}{{tableParams.count()}}{/literal}-->
<!--<button ng-click="{literal}tableParams.sorting({}){/literal}" class="btn btn-default pull-right">Clear sorting</button>-->
    <!--<p><strong>Sorting:</strong> {literal}{{tableParams.sorting()|json}}{/literal}-->

        <table ng-table="tableParams"  class="table">
            <tr>
            <td style="width:5%">{$MOD.NGADOCDETAILNO}</td>
            <td style="width:5%">{$MOD.NGNRLINE}</td>
            <td style="width:15%">{$MOD.NGPRODUCT}</td>
            <td style="width:5%">{$MOD.NGQUANTITY}</td>
            <td style="width:5%">{$MOD.NGPRICE}</td>
            <td style="width:5%">{$MOD.NGADOCPRICE}</td>
            <td style="width:10%">{$MOD.NGADOCDTOTAL}</td>
            <td style="width:10%">{$MOD.NGADOCDTAX}</td>
            <td style="width:10%">{$MOD.NGTOTAL}</td>
            <td style="width:10%" colspan=2>{$MOD.NGACTIONS}</td>
            </tr>
          <!--  <tr ng-repeat="user in $data">
                <td data-title="'AdocdetailNo'" sortable="'name'">
                  <a href="index.php?module=Adocdetail&action=DetailView&record={literal}{{user.adocdetailid}}{/literal}">  {literal}{{user.name}}{/literal}</a>
                </td>
                <td data-title="'Nr Line'" sortable="'age'">
                   {literal} {{user.age}} {/literal}
                </td>
                <td data-title="'Product'" sortable="'accountname'">
                  <a href ="index.php?module=Products&action=DetailView&record={literal}{{user.productid}}{/literal}"> {literal} {{user.accountname}} {/literal}</a>
                </td>
                <td data-title="'Quantity'" sortable="'quantity'">
                   {literal} {{user.quantity}} {/literal}
                </td>
                 <td data-title="'Price'" sortable="'price'">
                   {literal} {{user.price}} {/literal}
                </td>
                <td data-title="'Riferimento'" sortable="'riferimento'">
                   {literal} {{user.riferimento}} {/literal}
                </td>
                <td data-title="'Stock'" sortable="'stock'">
                 <a href="index.php?module=Stock&action=DetailView&record={literal}{{user.stockid}}{/literal}">  {literal} {{user.stock}} {/literal}</a>
                </td>
                
            </tr>
        </table>-->
       <tbody ng-repeat="group in $groups">
        <tr class="ng-table-group">
            <td colspan="{literal}{{$columns.length}}{/literal}">
                <a href="" ng-click="group.$hideRows = !group.$hideRows">
<span class="glyphicon" ng-class="{literal}{ 'glyphicon-chevron-right': group.$hideRows, 'glyphicon-chevron-down': !group.$hideRows }{/literal}"></span>
<strong>{literal}{{ group.value }}{/literal}</strong>
</a>
            </td>
        </tr>
        <tr ng-hide="group.$hideRows" ng-repeat="user in group.data" >
           <td data-title="'AdocdetailNo'" sortable="'name'">
                  <a href="index.php?module=Adocdetail&action=DetailView&record={literal}{{user.adocdetailid}}{/literal}">  {literal}{{user.name}}{/literal}</a>
                </td>
                <td data-title="'Nr Line'" sortable="'age'">
                 <span ng-if="!user.$edit">{literal} {{user.age}} {/literal}</span>
                <div ng-if="user.$edit"><input class="form-control" type="text" ng-model="user.age" /></div>
                </td>
         <!--       <td data-title="'Codice_Articolo'" sortable="'codice_articolo'">
                 {literal} {{user.codice_articolo}} {/literal}
                </td>-->
                <!--<td data-title="'PCDescriptionName'" sortable="'pcdetailsname'">
                   {literal} {{user.pcdetailsname}} {/literal}
                </td>
                <td data-title="'Parti Price'" sortable="'pcprice'">
                 <span ng-if="!user.$edit">  {literal} {{user.pcprice}} {/literal}</span>
                 <div ng-if="user.$edit"><input class="form-control" type="text" ng-model="user.pcprice" /></div>
                </td>
                <td data-title="'Parti Quantity'" sortable="'pcquantity'">
                   {literal} {{user.pcquantity}} {/literal}
                </td>
                  <td data-title="'Parti Total'" sortable="'pctotal'">
                   {literal} {{user.pcprice*user.pcquantity}} {/literal}
                </td>-->
                <td data-title="'Product'" >
               <span ng-if="!user.$edit">
                   {literal}{{user.adoc_product_display}}{/literal}</span>
                <div ng-if="user.$edit">
                  <input type="text" id="adoc_product_display" ng-model="user.adoc_product_display" name="adoc_product_display">
                   <input type="hidden" value="{literal}{{user.adoc_product}}{/literal}" id="adoc_product" name="adoc_product" >
                    <img src="themes/softed/images/select.gif"  alt="Select" title="Select" LANGUAGE=javascript  onclick='return window.open("index.php?module=Products&action=Popup&html=Popup_picker&form=vtlibPopupView&forfield=adoc_product&srcmodule=","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                </div>
                </td>
                <td data-title="'Quantity'" sortable="'quantity'">
                  <span ng-if="!user.$edit">  {literal} {{user.quantity}} {/literal}</span>
                   <div ng-if="user.$edit"><input class="form-control" type="text" ng-model="user.quantity" /></div>
                </td>
                 <td data-title="'Price'" sortable="'precio'">
                 {literal} {{user.precio}} {/literal}
                 <input type="hidden" value="{literal}{{user.precio}}{/literal}" id="precio">
                </td>
                <td data-title="'AdocPrice'" sortable="'price'">
                 <span ng-if="!user.$edit">{literal} {{user.price}} {/literal}</span>
                 <div ng-if="user.$edit"><input class="form-control" type="text" ng-model="user.price"/></div>
                </td>
                 <td data-title="'Adocdtotal'" sortable="'adocdtotal'">
                   {literal} {{(user.quantity*user.price)+(user.quantity*user.price*user.vat)| number:2}} {/literal}
                </td>
               <td data-title="'Adocdtax'" sortable="'adocdtax'">
                   {literal} {{user.quantity*user.price*user.vat |number:2}} {/literal}
                   <input type="hidden" value="{literal}{{user.adocdtax}}{/literal}" id="adocdtax">
                   <input type="hidden" value="{literal}{{user.vat}}{/literal}" id="vat">
                </td>
               <!-- <td data-title="'Adocdtotalamount'" sortable="'adocdtotalamount'">
                   {literal} {{user.adocdtotalamount| number:2}} {/literal}
                </td>-->
               <td data-title="'Total'" sortable="'total'">
                 {literal} {{user.quantity*user.price |number:2}} {/literal}
                </td>
                 <td data-title="'Actions'">
                <a ng-if="!user.$edit" href="" class="btn btn-default btn-xs" ng-click="user.$edit = true">{$MOD.NGEDIT}</a>
                <a ng-if="user.$edit" href="" class="btn btn-primary btn-xs" ng-click="user.$edit = false;setEditId(user.discount,user.price,user.pcdetailsid,user.pcquantity,user.pcprice,user.age,user.quantity,user.adocdetailid,user.adocmasterid,user.newtax,user.newadoctotal,user.newadoctotalamount,user.productid,user.adoc_product);">{$MOD.NGSAVE}</a>
                <a ng-if="user.$edit" href="" class="btn btn-primary btn-xs" ng-click="user.$edit = false;">{$MOD.NGCANCEL}</a>
            </td>
             <td>
                [<a href ng:click="removeItem($index,user.adocdetailid,user.adocmasterid)">X</a>]
            </td>
        </tr>
        </tbody>
    </table>
<script>
{literal}
	var kURL = "module=Adocmaster&action=AdocmasterAjax&file=ngEdit";
	var kURL2 = "module=Adocmaster&action=AdocmasterAjax&file=ngGetPrice"
	// alert(prova7);
	var record=document.getElementsByName('record').item(0).value;
	//alert(record);
	var app = angular.module('demoApp').
	controller('DemoCtrl', function($scope, $filter,$http,ngTableParams,$sce) {
       { 
             //var names = ["john", "bill", "charlie", "robert", "alban", "oscar", "marie", "celine", "brad", "drew", "rebecca", "michel", "francis", "jean", "paul", "pierre", "nicolas", "alfred", "gerard", "louis", "albert", "edouard", "benoit", "guillaume", "nicolas", "joseph"];
             //$scope.names=names;
             $scope.titles ={/literal}{$prodiri}{literal}
             //var names = ["john", "bill", "charlie", "robert", "alban", "oscar", "marie", "celine", "brad", "drew", "rebecca", "michel", "francis", "jean", "paul", "pierre", "nicolas", "alfred", "gerard", "louis", "albert", "edouard", "benoit", "guillaume", "nicolas", "joseph"];
             //$scope.names=names;
             $scope.titles ={/literal}{$prodiri}{literal}

       $scope.groupby='---';
                  $http.get('index.php?'+kURL+'&kaction=retrieve1&record='+record).
                    success(function(data, status) {
           // alert('hello');
                     
                        $scope.myData = data;  
          $scope.gridOptions = { 
        data: 'myData',
       
        columnDefs: [{field: 'name'}]
        };
    })
        }

            $scope.tableParams = new ngTableParams({
                page: 1,            // show first page
                count: 10 ,// count per page
                sorting: {
                    name: 'asc'     // initial sorting
                }
            }, {
                  getData: function($defer, params) {

                   // $defer.resolve(data.slice((params.page() - 1) * params.count(), params.page() * params.count()));
                $http.get('index.php?'+kURL+'&kaction=retrieve1&record='+record).
                    success(function(data, status) {
           // alert('hello');
                      var orderedData = data;
                      
                      params.total(data.length);
                      $defer.resolve(orderedData.slice((params.page() - 1) * params.count(),params.page() * params.count()));
                      var orderedData = data;
                      params.total(data.length);
                      $defer.resolve(orderedData.slice((params.page() - 1) * params.count(),params.page() * params.count()));
    })
        }
            });
             $scope.$watch('groupby', function(value){
                        $scope.tableParams.settings().groupBy = value;
                        console.log('Scope Value', $scope.groupby);
                        console.log('Watch value', this.last);
                        console.log('new table',$scope.tableParams);
                        $scope.tableParams.reload();
                    });
                         $http.post('index.php?'+kURL+'&kaction=update&record='+record+'&shembulli='+record
                )
                .success(function(data, status) {
                      $scope.tableParams.reload();
                 });
                   $scope.setEditId =  function(discount,price,pcdetailsid,pcquantity,pcprice,age,quantity,adocdetailid,adocmasterid,newtax,newadoctotal,newadoctotalamount,productid) {
             $http.post('index.php?'+kURL+'&record='+record+'&kaction=update&stato='+age+'&sasia='+quantity+'&adocdetailid2='+adocdetailid+'&adocmasterid2='+adocmasterid+'&newtax2='+newtax+'&newadoctotal2='+newadoctotal+'&newadoctotalamount2='+newadoctotalamount+'&productid2='+productid+'&product2='+document.getElementById('adoc_product').value+'&pcprice2='+pcprice+'&pcquantity2='+pcquantity+'&pcdetailsid2='+pcdetailsid+'&price2='+price+'&discount2='+discount+'&totiduhur='+$scope.getTotal()+'&taxiduhur='+$scope.getTax()+'&totali3='+$scope.getTotal2()
                )
                .success(function(data, status) {
           // alert('savingadaf');
                      $scope.tableParams.reload();
                 });
        }
              $scope.setEditId2 =  function(discount,price,pcdetailsid,pcquantity,pcprice,age,quantity,adocdetailid,adocmasterid,newtax,newadoctotal,newadoctotalamount,productid) {
             $http.post('index.php?'+kURL+'&record='+record+'&kaction=update&stato='+age+'&sasia='+quantity+'&adocdetailid2='+adocdetailid+'&adocmasterid2='+adocmasterid+'&newtax2='+newtax+'&newadoctotal2='+newadoctotal+'&newadoctotalamount2='+newadoctotalamount+'&productid2='+productid+'&product2='+document.getElementById('adoc_product').value+'&pcprice2='+pcprice+'&pcquantity2='+pcquantity+'&pcdetailsid2='+pcdetailsid+'&price2='+price+'&discount2='+discount+'&totiduhur='+$scope.getTotal()+'&taxiduhur='+$scope.getTax()+'&totali3='+$scope.getTotal2()
                )
                .success(function(data, status) {
        //  alert('savingadaf2');
                      $scope.tableParams.reload();
                     
                 });
        }
         $scope.addAdocdetail = function(quantity2) {
      $scope.tableParams.reload();
                  $http.get('index.php?'+kURL2+'&record='+record+'&kaction=doc1&sot='+quantity2+'&sot2='+document.getElementById('adoc_product2').value).
                    success(function(data, status) {
                     $scope.prova1 =data;
                     //$scope.prova2=data;
                    //alert('getsuccess');
                    //alert($scope.prova2);
                    //alert($scope.prova2);
                          $scope.myData.push({name: 'Adocnew',age:-1,quantity: document.getElementById('quantity2').value,adoc_product_display:document.getElementById('adoc_product2_display').value,adoc_product:document.getElementById('adoc_product2').value,precio:$scope.prova1,price:$scope.prova1,vat:$scope.prova2});
                          $http.post('index.php?'+kURL+'&record='+record+'&kaction=adding&ageadding=-1&quantityadding=5&productadding=22063&sot='+quantity2+'&sot2='+document.getElementById('adoc_product2').value+'&sot5='+$scope.prova1
                )
                .success(function(data, status) {
          // alert('adding');
          //alert('success');
                      $scope.tableParams.reload();
                 });
                 });
    };
   $scope.addAdocdetail2 = function(quantity2) {
       //$scope.tableParams.reload();
       $http.get('index.php?'+kURL+'&kaction=adding&record='+record+'&quantity='+document.getElementById('quantity2').value+'&adocpd='+document.getElementById('adoc_product2_display').value+'&adocp='+document.getElementById('adoc_product2').value).
                    success(function(data, status) {
           $scope.tableParams.reload();
    })
   };
     $scope.removeItem = function(index,adocdetailid,adocmasterid) {
         var deleteconfirmation=confirm("do you really want to delete this adocdetail?");
         if(deleteconfirmation == true){
        $scope.myData.splice(index, 1);
        $scope.tableParams.reload();
        $http.post('index.php?'+kURL+'&kaction=delete&adocdelete='+adocdetailid+'&totdelete='+$scope.getTotal()+'&adocmasterdelete='+adocmasterid
                )
              .success(function(data, status) {
              $scope.tableParams.reload();
                 });}
    }
     $scope.getTotal=function(){
       var total = 0;
        angular.forEach($scope.myData, function(user) {
            total=total+(user.quantity*user.price);
           // alert(total);
        })
       return total;
 }
     $scope.getTax=function(){
       var total = 0;
        angular.forEach($scope.myData, function(user) {
            total=total+(user.quantity*user.price*user.vat);
           // alert(total);
        })
       return total;
 }
 $scope.getTotal2=function(){
       var total = 0;
        angular.forEach($scope.myData, function(user) {
            total=total+(user.quantity*user.price)+(user.quantity*user.price*user.vat);
           // alert(total);
        })
       return total;
 }
 $scope.provat=function(){
     alert('hello');
 }
 $scope.timeout=function() {
    var names = ["john", "bill", "charlie", "robert", "alban", "oscar", "marie", "celine", "brad", "drew", "rebecca", "michel", "francis", "jean", "paul", "pierre", "nicolas", "alfred", "gerard", "louis", "albert", "edouard", "benoit", "guillaume", "nicolas", "joseph"];
    return {
        restrict : 'A',
        require : 'ngModel',
        link : function(scope, iElement, iAttrs) {
            iElement.autocomplete({
                source: names,
                select: function() {
                    $timeout(function() {
                      iElement.trigger('input');
                    }, 0);
                }
            });
    }
    };
}
        })

     

{/literal}
</script>

</div>
