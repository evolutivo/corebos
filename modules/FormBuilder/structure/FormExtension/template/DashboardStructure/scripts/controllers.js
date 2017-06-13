/* Controllers */

angular.module('Form.controllers', [])
.controller('InitialController',  function ($scope,$http,ngTableParams,FormService,$location) {
    var record=document.getElementById('record').value;console.log(record);
    var masterModule=document.getElementById('masterModule').value;
    var onOpenView=document.getElementById('onOpenView').value;
    var module=document.getElementById('module').value;
    var urlRoot="index.php?module="+module+"&action="+module+"Ajax";
    var urlParams="&masterRecord="+record+"&masterModule="+masterModule;
    
    FormService.setConfigured(record,masterModule,onOpenView,module);

    $http.get(urlRoot+"&file=operations&kaction=getEntities&view=initial"+urlParams).
            success(function(data, status) {
              if(onOpenView!=''){
                  $scope.view=onOpenView; 
              }
              else{
                  $scope.view=data.settings.onopen;
              }  
              $location.path('/'+$scope.view);
    });
    
})
.controller('CreateController',  function ($scope,$http,$interval,$mdSidenav,$mdBottomSheet,$mdpDatePicker,$mdpTimePicker,ngTableParams,FormService,$filter,$location,$mdDialog,$timeout,$q) {
    $scope.steps = [];
    $scope.step = 0;
    $scope.stepname='';
    $scope.name = '';       
    $scope.wizard = {};
    $scope.answers = {};
    $scope.processing=false;
    $scope.mandatory_fields={};
    $scope.ui10Readable={};
    $scope.MAP_PCKLIST_TARGET=[];
    $scope.map_field_dep={};
    $scope.disableLogic={};
    $scope.disableOptionLogic={};
    $scope.extActions={};
    $scope.beforeSaveActions={};
    $scope.executingAction={state:false};
    $scope.executingSubmit={state:false};
    $scope.stateActions=[];
    $scope.loading=true;
    $scope.refersModule={};
    $scope.ActualRefersModule={};
    $scope.uiEvoReadable = [];
    $scope.records_Documents={};  
    $scope.currentErrorStatus={};
    $scope.currentErrorMessage={};
    $scope.bachToDashb='';
    $scope.megatext='';
    $scope.tabIdReloadAgain='';
    $scope.currentPage='';
    $scope.queryResults = {};
    $scope.selectedItem={};
    $scope.cachedmap={};
    $scope.searchValue={};
    $scope.isNotValidAutocomplete={};
    $scope.userCreation=false;
    
    var record=document.getElementById('record').value;
    if(record==null){record='';alert('alb');}
    var masterModule=document.getElementById('masterModule').value;
    var onOpenView=document.getElementById('onOpenView').value;
    $scope.module=document.getElementById('module').value;
    var urlRoot="index.php?module="+$scope.module+"&action="+$scope.module+"Ajax";
    var urlParams="&masterRecord="+record+"&masterModule="+masterModule;
    FormService.setConfigured(record,masterModule,onOpenView,$scope.module);
    $scope.OutsideData=document.getElementById('OutsideData').value;
    var temp=$scope.OutsideData.split('&');
    $scope.assigned_userMap='';
    angular.forEach(temp, function(value, key) {
            var arr=value.split('=');
            $scope.answers[arr[0]]=arr[1];
            if(arr[0]==='returnDashb'){
                $scope.bachToDashb=arr[1];
            }
            else if(arr[0]==='megatext'){
                $scope.megatext=arr[1];
            }
            else if(arr[0]==='tabIdReloadAgain'){
                $scope.tabIdReloadAgain=arr[1];
            }
            else if(arr[0]==='currentPage'){
                $scope.currentPage=arr[1];
            }
            else if(arr[0].indexOf('assigned_user_id')!==false){
                $scope.assigned_user_id=arr[1];
            }
    });
    
    $scope.backDash = function (megatext) {
        
        window.location.href='index.php?module='+$scope.bachToDashb+'&megatext='+$scope.megatext+'&tabIdReloadAgain='+$scope.tabIdReloadAgain+'&currentPage='+$scope.currentPage+'&action=index';
    };
    
    
    $scope.retrieveInfo = function (id) {
        urlParams="&masterRecord="+id+"&masterModule="+masterModule;
        $http.post(urlRoot+"&file=operations&kaction=retrieveInfo"+urlParams)
               .success(function(data, status) {
                   $scope.answers=data;
        });
    };   
    $http.get(urlRoot+"&file=operations&kaction=getFieldDependency"+urlParams).
        success(function(data, status) {
            $scope.map_field_dep = data.all_field_dep;
            $scope.MAP_RESPONSIBILE_FIELDS = data.MAP_RESPONSIBILE_FIELDS;
            $scope.MAP_RESPONSIBILE_FIELDS3 = data.MAP_RESPONSIBILE_FIELDS3;
            $scope.MAP_RESPONSIBILE_FIELDS2 = data.MAP_RESPONSIBILE_FIELDS2;
            $scope.MAP_PCKLIST_TARGET = data.MAP_PCKLIST_TARGET;
        });
        
    $scope.processFields = function (blocks) {
        for (var m = 0; m < blocks.length; m++) {
            var block=blocks[m];
            var array_date = ['5','6','23','70'];
            for (var i = 0; i < block.rows.length; i++) {
                for (var j = 0; j < block.rows[i].length; j++) {
                    var fldname = block.rows[i][j];
                    var uitype = $scope.getFieldType(fldname,$scope.steps[$scope.step]['fields']['info']);
                    if (uitype == '10' || uitype == '98') {
                        var moduleRef;
                        if(uitype === '10')
                        {
                            moduleRef = $scope.getFieldModuleRef(fldname,$scope.steps[$scope.step]['fields']['info']);
                        }
                        else if(uitype === '98'){
                            moduleRef = new Array('Role');
                        }
                        $scope.ActualRefersModule[fldname]=moduleRef[0];
                        $scope.refersModule[fldname]=moduleRef;
                        $scope.getUi10Readable(moduleRef[0], $scope.answers[fldname], fldname);
                        $scope.getUi10List(fldname,moduleRef[0]);                        
                    }
                    else if (uitype == '53') {
                        $scope.answers[fldname]=document.getElementById('LoggedUser').value; 
                        var entity=$scope.steps[$scope.step]['entityname'];
                        var assFldName=fldname+'_'+entity;
                        if($scope.answers[assFldName]==='' || $scope.answers[assFldName]===undefined){
                            $scope.answers[fldname+'_'+entity]=document.getElementById('LoggedUser').value; 
                        }
                    } 
                    else if (uitype == '111') {
                        $scope.answers[fldname]=document.getElementById('LoggedUser').value;                         
                    }
                    else if(array_date.indexOf(uitype)!==-1 && $scope.answers[fldname] !=='' && $scope.answers[fldname] !== undefined){
                          var dt=new Date($scope.answers[fldname]);
                          $scope.answers[fldname]=dt;
                    }
                    else if (uitype == '1025') {
                        var moduleRef = $scope.getFieldModuleRef(fldname,$scope.steps[$scope.step]['fields']['info']);
                        $scope.ActualRefersModule[fldname]=moduleRef[0];
                        $scope.refersModule[fldname]=moduleRef;
                        $scope.getUiEvoReadable($scope.answers[fldname], fldname);
                        $scope.getUiEvoList(fldname);                        
                    }
                    else if (uitype == '1026'){
                       if($scope.answers[fldname]!=undefined){
                           $scope.selectedItem[fldname]=$scope.answers[fldname];
                       }
                  }
                }
            }
        }
    };
        
    $scope.getFieldType = function (fieldname,modulefields) {
        var ret = '';
        var found = $filter('getArrayElementById')(modulefields, fieldname, 'fieldname');
        if (found != '' && found != undefined && found != null)
                ret = found.uitype;
        return ret;
    };
    $scope.getFieldModuleRef = function (fieldname,modulefields) {
        var ret = '';
        var found = $filter('getArrayElementById')(modulefields, fieldname, 'fieldname');
        if (found != '' && found != undefined && found != null)
                ret = found.options;
        return ret;
    };
    $scope.getFieldLabel = function(field, modulefields) {
        var ret = '';
        var found = $filter('getArrayElementById')(modulefields, field, 'fieldname');
        if (found != '' && found != undefined && found != null)
            ret = found.fieldlabel;
        return ret;
    };
    $scope.getFieldOptions = function(field, modulefields) {
        var ret = '';
        var found = $filter('getArrayElementById')(modulefields, field, 'fieldname');
        if (found != '' && found != undefined && found != null)
            ret = found.options;
        return ret;
    };
    $scope.isFirstStep = function () {
        return $scope.step === 0;
    };

    $scope.isLastStep = function () {
        return $scope.step === ($scope.steps.length - 1);
    };

    $scope.isCurrentStep = function (step) {
        return $scope.step === step;
    };

    $scope.setCurrentStep = function (step,name) {
        $scope.step = step;
        $scope.stepname=name;
    };

    $scope.getCurrentStep = function () {
        return $scope.step;
    };
    
    $scope.getNextLabel = function () {
        return ($scope.isLastStep()) ? 'Submit' : 'Next';
    };

    $scope.handlePrevious = function () {
        $scope.step -= ($scope.isFirstStep()) ? 0 : 1;
        $scope.retrieveActions('InCreateWidget');
    };

    $scope.processFieldsCreate = function () {
        $scope.temp={};
        angular.forEach($scope.answers, function(value, key) {
            var fldname = key;
            var uitype='';
            angular.forEach($scope.steps, function(value, key) {
                if(uitype==='')
                    uitype = $scope.getFieldType(fldname,value['fields']['info']);
            });                  
            var array_date = ['5','6','23','70'];
            $scope.temp[fldname]=$scope.answers[fldname];
            if(array_date.indexOf(uitype)!==-1){ // field date type
                $scope.temp[fldname]=moment($scope.answers[fldname]).format('YYYY-MM-DD');
                $scope.temp[fldname+'_time']=moment($scope.answers[fldname]).format('H:m');
            }
        });
        if($scope.ConfigEntities.Users!=undefined){
            $scope.answers['user_password']=($scope.answers['user_password']===undefined ? '' : $scope.answers['user_password']); 
            $scope.answers['confirm_password']=($scope.answers['confirm_password']===undefined ? '' : $scope.answers['confirm_password']); 
            if(trim($scope.answers['user_password']) !== trim($scope.answers['confirm_password']))
            {
                alert("The password does't match");
                angular.element('#user_password').focus();
                $scope.userCreation=true; 
                $scope.defer.resolve();  
                return $scope.temp;
            }
            var user_name = ($scope.answers['user_name']!==undefined ? $scope.answers['user_name'] : '');
            var verify = $scope.verify_data($scope.answers);
            var status = CharValidation(user_name,'name');
            if(verify){
                if(status){
                    $http.post('index.php?module=Users&action=UsersAjax&file=Save&ajax=true&dup_check=true&userName='+user_name
                        ,{})
                           .success(function(data, status) {
                                if(data.indexOf('SUCCESS') > -1)
                                {
                                    $scope.userCreation=false;
                                }
                                else {
                                   $scope.userCreation=true;
                                   alert(data);
                               }
                               $scope.defer.resolve();  
                        });
                    }
                else{
                    alert(alert_arr.NO_SPECIAL+alert_arr.IN_USERNAME);
                    $scope.userCreation=true;
                    $scope.defer.resolve();  
                }
            }
            else {
                $scope.userCreation=true;
                $scope.defer.resolve();  
            }
            $scope.temp['user_role']=$scope.answers['roleid'];
            $scope.temp['roleid']=$scope.answers['roleid'];
        }
        else{
            $scope.defer.resolve();    
        }
        return $scope.temp;
    };
    
    $scope.set_fieldfocus = function(errorMessage,oMiss_field){
                    alert("Mancano dei campi obbligatori: " + errorMessage);
                    angular.element('#'+oMiss_field).focus();
    }
    //Check for special character
    $scope.checkSpecialChar = function(passwordValue) {
            var i=0;
            var ch='';
            while (i <= passwordValue.length) {
                    var character = passwordValue.charAt(i);
                    if ((character == ".")||(character =="!")||(character =="?")||(character ==",")||(character ==";")||(character =="-")||(character =="@")||(character =="#")){
                            return true;
                    }
                    i++;
            }
            return false;
    }
    //check for number
    $scope.checkNumber = function(passwordValue) {
            var i=0;
            while (i < passwordValue.length){
                    var character = passwordValue.charAt(i);
                    if (!isNaN(character)){
                            return true;
                    }
                    i++;
            }
            return false;
    }
    //Check for lowercase character
    $scope.checkLower = function(passwordValue) {
            var i=0;
            while (i < passwordValue.length) {
                    var character = passwordValue.charAt(i);
                    if (character == character.toLowerCase()){
                            return true;
                    }
                    i++;
            }
            return false;
    }
    //Check for capital
    $scope.checkCapital = function(passwordValue) {
            var i=0;
            while (i < passwordValue.length) {
                    var character = passwordValue.charAt(i);
                    if (character == character.toUpperCase()) {
                            return true;
                    }
                    i++;
            }
            return false;
    }
    $scope.passwordChecker = function(form) {
            //New Password Value
            var passwordValue = form.user_password;
            //Length Password
            var passwordLength = (passwordValue.length);
            //alert("Length: " + passwordLength);
            //Capital?
            var containsCapital = $scope.checkCapital(passwordValue);
            //alert("Capital: " + containsCapital);
            //Lower?
            var containsLower = $scope.checkLower(passwordValue);
            //alert("Lower: " + containsLower);
            //Number?
            var containsNumber = $scope.checkNumber(passwordValue);
            //alert("number: " + containsNumber);
            //Special Char?
            var containsSpecialChar = $scope.checkSpecialChar(passwordValue);
            //alert("Special Char:" + containsSpecialChar);

            //COMPLEX PASSWORD: Minimum 8 characters, and three of the four conditions needs to be ok --> Capital, Lowercase, Special Character, Number
            if(passwordLength < 8) {
                    return false;
            } else {
                    //Combination Match All
                    if((containsNumber == true)&&(containsCapital == true)&&(containsLower == true)&&(containsSpecialChar == true)) {
                            return true;
                    } else {
                            //Combination 1
                            if((containsNumber == true)&&(containsCapital == true)&&(containsLower == true)) {
                                    return true;
                            } else {
                                    //Combination 2
                                    if((containsCapital == true)&&(containsLower == true)&&(containsSpecialChar == true)) {
                                            return true;
                                    } else {
                                            //Combination 3
                                            if((containsLower == true)&&(containsSpecialChar == true)&&(containsNumber == true)) {
                                                    return true;
                                            } else {
                                                    //Combination 4
                                                    if((containsNumber == true)&&(containsCapital == true)&&(containsSpecialChar == true)) {
                                                            return true;
                                                    } else {
                                                            return false;
                                                    }
                                            }
                                    }
                            }
                    }
            }
    }
    $scope.verify_data = function(form) {
            var isError = false;
            var errorMessage = "";


            var oField_miss;
            if (form.email1 == "" || form.email1==undefined) {
                    isError = true;
                    errorMessage += "\nEmail";
                    oField_miss = 'email1';
            }
            if (form.roleid == "" || form.roleid==undefined) {
                    isError = true;
                    errorMessage += "\nNome Ruolo";
                    oField_miss ='roleid';
            }
            if (form.last_name == "" || form.last_name ==undefined) {
                    isError = true;
                    errorMessage += "\nCognome";
                    oField_miss ='last_name';
            }

            if (form.user_password == "" || form.user_password ==undefined) {
                    isError = true;
                    errorMessage += "\nPassword";
                    oField_miss ='user_password';
            }
            if (form.confirm_password == "" || form.confirm_password ==undefined) {
                    isError = true;
                    errorMessage += "\nConferma Password";
                    oField_miss ='confirm_password';
            }
            var passwordOK = $scope.passwordChecker(form);
            if(!passwordOK){
                isError = true;
                errorMessage += '\nRequisiti Password\n----------------------------------------\n';
                errorMessage +=  'Obbligatori:\n~ ';
                errorMessage +=  'Minimo 8 caratteri\n\n';
                errorMessage +=  'LA PASSWORD CONTIENE ALMENO UNO DI QUESTI 3 GRUPPI DI CARATTERI:\n~ ';
                errorMessage +=  'Minimo una lettera maiuscola:\n~ ';
                errorMessage +=  'Minimo una lettera minuscola:\n~ ';
                errorMessage +=  'Minimo un numero:\n~ ';
                errorMessage +=  'Minimo un carattere speciale ! ? , ; - @ #\n';
            } 

            if (form.user_name == "" || form.user_name ==undefined) {
                    isError = true;
                    errorMessage += "\nNome Utente";
                    oField_miss ='user_name';
            }

            if (isError == true) {
                    $scope.set_fieldfocus(errorMessage,oField_miss);
                    return false;
            }
            form.email1 = form.email1;
            if (form.email1 != "" && !/^[a-zA-Z0-9]+([!"#$%&'()*+,./:;<=>?@\^_`{|}~-]?[a-zA-Z0-9])*@[a-zA-Z0-9]+([\_\-\.]?[a-zA-Z0-9]+)*\.([\-\_]?[a-zA-Z0-9])+(\.?[a-zA-Z0-9]+)?$/.test(form.email1)) {
                    alert("Indirizzo di Email non valido.");
                    angular.element('#email1').focus();
                    return false;
            }
            return true;
    };
    $scope.handleNext = function () {
        $scope.processing=true;
        if ($scope.isLastStep()) {            
            var mandatoryActions=$scope.getStateActions();
            if(mandatoryActions){
                var answers =$scope.answers;
                $scope.defer = $q.defer();
                var temp=$scope.processFieldsCreate();
                $scope.defer.promise.then(function(){
                var mandatoryLogic=$scope.mandatoryLogic();
                var pattern =$scope.isValidatedForm();
                if(mandatoryLogic && pattern){
                    if($scope.isValidAutocomplete()){
                    $scope.executingSubmit.state=true;
                    $http.post(urlRoot+"&file=operations&kaction=saveEntities"+urlParams
                        ,{data: temp,config:$scope.ConfigEntities,steps:$scope.steps,settings:$scope.settings,documents:$scope.records_Documents})
                           .success(function(data, status) {
                                   //$scope.retrieveInfo(data); 
                                   var masterModule=$scope.settings.onsave;
                                   $scope.masterId=data[masterModule]; 
                                   urlParams="&masterRecord="+$scope.masterId+"&masterModule="+masterModule;
                                   document.getElementById('record').value=$scope.masterId;
                                   var urlPoint='index.php?module='+$scope.module+'&record='+$scope.masterId+'&action=index&onOpenView=detail';
                                   if($scope.answers['origin_form']!='' && $scope.answers['origin_form']!=undefined && $scope.answers['origin_form']!==$scope.module){
                                       urlPoint='index.php?module='+$scope.answers['origin_form']+'&record='+$scope.answers['return_id']+'&action=index&onOpenView=detail';
                                   }
                                   else{
                                       if($scope.settings.onsave=='Restart'){
                                           window.location.href='index.php?module='+$scope.module+'&action=index&onOpenView=create';
                                       }
                                       else{
                                           $scope.view='detail';
                                       }
                                       //$location.path('/'+$scope.view);
                                   }
                                   $scope.retrieveAfterSaveActions(urlPoint+'&'+$scope.OutsideData);
                                   $scope.processing=false;
                        }).error(function(data, status) {
                            $scope.executingSubmit.state=false;
                        });
                    }else{ $scope.processing=false; }
                }else{ $scope.processing=false; }
                });
            }else{ $scope.processing=false; }
        } else {
            var mandatoryActions=$scope.getStateActions();
            if(mandatoryActions){
                var mandatoryLogic=$scope.mandatoryLogic();
                var pattern =$scope.isValidatedForm();
                if(mandatoryLogic && pattern){
                  if($scope.isValidAutocomplete()){
                    $scope.step += 1;
                    $scope.processFields($scope.steps[$scope.step]['fields']['blocks']);
                    $scope.retrieveActions('InCreateWidget');
                  }
                }
            } 
            $scope.processing=false;
        }
    };
    
    $scope.retrieveActions = function (action_type) {
        $http.post(urlRoot+"&file=operations&kaction=retrieveActions&actualStep="+$scope.step+urlParams+"&action_type="+action_type
                ,{steps:$scope.steps})
               .success(function(data, status) {
                   $scope.extActions=data;
//                   if($scope.stateActions[$scope.step]!==undefined){
                       $scope.stateActions[$scope.step]=$scope.extActions;
//                   }
                   $scope.loading=false;
                   $scope.retrieveBeforeSaveActions('BeforeSave');
        });
    };
    $scope.retrieveBeforeSaveActions = function (action_type) {
        $http.post(urlRoot+"&file=operations&kaction=retrieveActions&actualStep="+$scope.step+urlParams+"&action_type="+action_type
                ,{steps:$scope.steps})
               .success(function(data, status) {
                   $scope.beforeSaveActions=data;
        });
    };
    $scope.retrieveAfterSaveActions = function (urlPoint) {
        var action_type='AfterSave';
        var count =0;
        $http.post(urlRoot+"&file=operations&kaction=retrieveActions&actualStep="+$scope.step+urlParams+"&action_type="+action_type
                ,{steps:$scope.steps})
               .success(function(data, status) {
                    angular.forEach(data, function(value, key) {
                        count++;
                        $scope.executeAction(value.actionid,value.output_type);
                    });
                    if(count===0 || count===data.length){
                        window.location.href=urlPoint;
                }
        });
    };
    $scope.piva_act=false;
    $scope.getStateActions = function () {
        var retVal=true;
        if($scope.answers['module']=='masteraccountform'){
            if( ($scope.answers['master_pi']!=undefined && $scope.answers['master_pi']!=='' && $scope.piva_act===false)){
                alert('Non si puo procedere senza validare l\'anagrafica!\n\
Cliccare sull\'azione sotto alla sinistra.');                
                retVal= false;
                return retVal;
            }
            else if( $scope.answers['cod_fisc']!==undefined && $scope.answers['cod_fisc']!=='' && $scope.piva_act===false){
                alert('Non si puo procedere senza validare l\'anagrafica!\n\
Cliccare sull\'azione sotto alla sinistra.');
                retVal= false;
                return retVal;
            }            
        }
        angular.forEach($scope.stateActions[$scope.step], function(value, key) {
            if(value.mandatory_action=='1' && (value.executed==undefined || value.executed==0) ){
                alert('Non si puo procedere senza validare.\n\
 Cliccare sull\'azione sotto alla sinistra.');
                retVal= false;
                return retVal;
            }
        });
        if(!retVal) return false;
        angular.forEach($scope.beforeSaveActions, function(value, key) {
            retVal=$scope.executeAction(value.actionid,'html');
            console.log(retVal);
            if(retVal===false){
                return retVal;
            }
        });       
        
        return retVal;
    };
    
    $scope.setStateActions = function (actionid) {
        angular.forEach($scope.stateActions[$scope.step], function(value, key) {
            if(value.actionid==actionid){
               $scope.stateActions[$scope.step][key]['executed']=1; 
            }
        });
    };


    //autocomplete
 $scope.isValidAutocomplete = function (){
       var ret=true;
       for(var fld in $scope.selectedItem){
            if($scope.selectedItem[fld]==null){
              $scope.isNotValidAutocomplete[fld]=true;
              ret=false;
              break; 
            }
            else{
               $scope.isNotValidAutocomplete[fld]=false; 
            }
       }
       return ret;
    };

    $scope.querySearch = function (query,fld) {
        if($scope.queryResults[fld]==undefined){
            $scope.queryResults[fld]=[];
        }
        var results = query ? $scope.queryResults[fld].filter($scope.createFilterFor(query,fld)) : $scope.queryResults[fld], deferred;
        deferred = $q.defer();
        $http.post(urlRoot+"&file=operations&kaction=retrieveAutoCompleteData&field="+fld+"&val="+encodeURIComponent(query[fld])+"&cachedmap="+encodeURIComponent(JSON.stringify($scope.cachedmap[fld])))
               .success(function(data, status) {
                    $scope.queryResults[fld] = data.resdata;
                    if(data.cachedmap!=undefined){
                        $scope.cachedmap[fld]=data.cachedmap;
                    }
                    deferred.resolve($scope.queryResults[fld]);
           }).error(function (data, status) {
        });


        return deferred.promise;
    };

    $scope.createFilterFor = function (query,fld) {
        var lowercaseQuery = angular.lowercase(query);
        return function filterFn(it) {
            if(it[fld]!=undefined){
              return (it[fld].indexOf(lowercaseQuery) === 0);
            }
            else{
                return false;
            }
        };
    };

    $scope.searchValueChange = function (text,fld,item) {
        if (text !== $scope.selectedItem[fld] && $scope.selectedItem[fld]!=='') {
          if($scope.cachedmap[fld]!=undefined){
          angular.forEach($scope.cachedmap[fld]['fieldlist'], function(value, key) {
            $scope.answers[value]='';
          });
         }
        }
    };

    $scope.selectedItemChange = function (item,fld) {

        if (item!=undefined) {
            if(item[fld]!=undefined){
              $scope.selectedItem[fld]=item[fld];
            }
          angular.forEach(item, function(value, key) {
            $scope.answers[key]=value;
          });
          
        }
    };

    $scope.selectedItemChangeBG = function(fld){
        $scope.selectedItem[fld]=$scope.answers[fld];
    }

    //end autocomplete
    
    $scope.openBottomSheet = function() {
        $mdDialog.show({
          controller: DocumentController,
          clickOutsideToClose: false,          
          templateUrl: 'Smarty/templates/modules/'+$scope.module+'/views/Documents.html',
          resolve: {
            records_Documents :function () {
                return $scope.records_Documents;
            },
            entityname :function () {
               return $scope.steps[$scope.step]['entityname'];
            }
          }
        });
   };
   function DocumentController($scope, $mdDialog,records_Documents,entityname) {
       
        $scope.cancel = function() {
            $mdDialog.cancel();            
        };
        
        $scope.doc_name='';
        $scope.attach='';
        $scope.recordsSize=records_Documents[entityname].length;
        $scope.tableParamsDocuments = new ngTableParams({
            page: 1, // show first page
            count: 5 // count per page
        }, {
            total: records_Documents[entityname].length, // length of data
            counts: [], // hide page counts control
            getData: function($defer, params) {
                var filteredData = params.filter() ?
                    $filter('filter')(records_Documents[entityname], params.filter()) :
                    records_Documents[entityname];
                var orderedData = params.sorting() ?
                    $filter('orderBy')(filteredData, params.orderBy()) :
                    records_Documents[entityname];
            records_Documents=records_Documents;
                params.total(orderedData.length); // set total for recalc pagination
                $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count()));
            }
        });
        //an array of files selected
        $scope.files = [];
        //listen for the file selected event
        $scope.$on("fileSelected", function (event, args) {
            $scope.$apply(function () {            
                //add the file object to the scope's files collection
                $scope.files.push(args.file);
            });
        });

        $scope.saveDoc= function () {
            if($scope.doc_name!==''){
            var docName='&name='+$scope.doc_name;
            $http({
                method: 'POST',
                url: urlRoot+"&file=operations&kaction=saveDocument"+urlParams+docName,
                headers: { 'Content-Type': undefined },
                transformRequest: function (data) {
                    var formData = new FormData();
                    for (var i = 0; i < data.files.length; i++) {
                        formData.append("filename" , data.files[i]);
                    }
                    return formData;
                },
                data: { files: $scope.files}
            })
                .success(function(data, status, headers, config) {
                      if($scope.files.length>0){
                          var size=$scope.files.length;
                          $scope.fl=$scope.files[size-1].name;
                      }
                      var arr={'name':$scope.doc_name,'file':$scope.fl,'id':data};
                      $scope.attach='';
                      $scope.doc_name='';
                      records_Documents[entityname].push(arr);
                      $scope.recordsSize=records_Documents[entityname].length;
                      $scope.tableParamsDocuments.reload();
                 });
             }
        };
          $scope.answer = function(answer) {
            $mdDialog.hide(answer);
          };
    }
    
   $scope.initDocuments = function() {
       angular.forEach($scope.steps, function(value, key) {
           var entityname=value['entityname'];
            $scope.records_Documents[entityname]=[];
        });
        console.log($scope.records_Documents);
    };
           
    $scope.toggleRight = function() {
        // Show the dialog
        $scope.executingAction.state=false;
        $mdDialog.show({
          clickOutsideToClose: false,
          templateUrl: 'PrimoSguardo',
          controller:PrimoSguardoController,
          resolve: {
            answers :function () {
                return $scope.answers;
            },
            outputActionInfo :function () {
                return $scope.outputActionInfo;
            },
            selectedItem : function () {
            	return $scope.selectedItem;
            },
            automatic : function () {
            	return $scope.automatic;
            }
          }
      });
      };
    function PrimoSguardoController($scope, $mdDialog,answers,outputActionInfo,selectedItem,automatic) {
    
        $scope.outputActionInfo=outputActionInfo;
        $scope.close = function () {
            answers['ma_validationstatus']='Validato Non Accettato';
            answers['ma_validationdate']='';
            $mdDialog.cancel(); 
        };
        $scope.accept = function () {
            var focusrecord = outputActionInfo;
            for (var i=0;i<focusrecord.values.length;i++) {
                if(focusrecord.toupdate[i]==1 || focusrecord.toupdate[i]=='1'){
                    var tempVal=focusrecord.values[i];
                    var tempCol=focusrecord.columns[i];
                    answers[tempCol] = tempVal;
                }
                if(focusrecord.columns[i]=='description')
                {
                    answers['description_MasterAccount']=focusrecord.values[i];
                }
            }
            automatic(focusrecord.columns[0]);
            answers['ma_validationstatus']='Validato Accettato';
            answers['ma_validationdate']=new Date();
            answers['ma_validationdate_time']=moment(new Date()).format('H:m');
            if(answers['citta_ma']!=undefined){
                selectedItem['citta_ma']=answers['citta_ma'];
            }
            if(answers['comune_sede_operativa']!=undefined){
                selectedItem['comune_sede_operativa']=answers['comune_sede_operativa'];
            }
            $mdDialog.cancel(); 
        };
    }
    $scope.executeAction = function (actionid,output_type) {
        
        var toReturn=true;
        $scope.executingAction.state=true;
        var arr_sel = $scope.masterId; 
        $scope.answers['origin_form']=$scope.module;
        var values=$scope.answers;
        var outputType='html';
        var senddata = {
            'recordid': arr_sel,
            'return_id': $scope.answers['return_id'],
            'piva':$scope.answers['master_pi'],
            'codicefisc':$scope.answers['cod_fisc']
        };
        if (values != undefined) {
            values = angular.extend(values, senddata);
        } else {
            values = senddata;
        }
        //values = senddata; 
            try{ 
                if(output_type=='Confirm'){               
                        $timeout(function () {
                            var resp=runJSONAction(actionid, encodeURIComponent(JSON.stringify(values)), outputType);
                            $scope.piva_act=true;
                            $scope.outputActionInfo=JSON.parse(resp);
                            var focusrecord = $scope.outputActionInfo;
                            //$scope.outputActionInfo.resperror===0 || $scope.outputActionInfo.resperror==="0"
                            if(focusrecord.values!==undefined){
                                for (var i=0;i<focusrecord.values.length;i++) {
                                    if(focusrecord.values[i]===false){
                                        $scope.outputActionInfo.values[i]='';
                                    }
                                }
                            }
                            $scope.toggleRight();
                        }, 1000);    
            }
            else if(output_type=='html'){
                var resp=runJSONAction(actionid, encodeURIComponent(JSON.stringify(values)), outputType);
                $scope.outputActionInfo=JSON.parse(resp);
                if($scope.outputActionInfo.status!==undefined){
                    if($scope.outputActionInfo.status==='false' || $scope.outputActionInfo.status===false || $scope.outputActionInfo.status===0){
                        alert($scope.outputActionInfo.message);                                
                        toReturn= false;
                    }
                }
                $scope.executingAction.state=false;
            }
            else{
                var resp=runJSONAction(actionid, encodeURIComponent(JSON.stringify(values)), output_type);
                $scope.executingAction.state=false;
            }
            }catch(e){
                console.log(e);
              }
            $scope.setStateActions(actionid);
            return toReturn;        
    };
    
    $scope.save_repeat = function (answers,currStep) {
        console.log($scope.steps[currStep]);console.log(answers);
        $http.post(urlRoot+"&file=operations&kaction=saveEntity"+urlParams
            ,{data: answers,config:$scope.steps[currStep],settings:$scope.settings})
                .success(function(data, status) {
                  alert(data);
        });
        
    };
    
    
    $scope.label = 'Azioni';
    $scope.topDirections = ['left', 'up'];
    $scope.bottomDirections = ['down', 'right'];
    $scope.isOpen = false;
    $scope.availableModes = ['md-fling', 'md-scale'];
    $scope.selectedMode = 'md-scale';
    $scope.availableDirections = ['up', 'down', 'left', 'right'];
    $scope.selectedDirection = 'right'; 
    $scope.selectedDirectionDetail = 'down'; 
    
    $scope.showLogicRow = function(row) {
        var ret = false;
        row.forEach(function(entry) {
            if ($scope.showLogic(entry)) {
                ret = true;
            }
        });
        return ret;
    };
    $scope.showLogic = function(field) {
        var ret = true;
        var fieldname = '';
        var disable = '';
        var currRole=document.getElementById('RoleId').value;
        var currProfiles=document.getElementById('Profiles').value;
        
        angular.forEach($scope.map_field_dep, function(value, key) {
            var conditionResp = '';
            var intersectProfile=false;
            for(var prof_c=0;prof_c<currProfiles.length;prof_c++){
                if(value.target_profiles.indexOf(currProfiles[prof_c])!==-1)
                {
                    intersectProfile=true;break;
                }
            }  
            if (value.targetfield.indexOf(field) != -1 && (value.target_mode==='CreateView' || value.target_mode==='')) {
                angular.forEach(value.respfield, function(resp_val, resp_val_key) {
                    var resp_value = value.respvalue_portal[resp_val_key];
                    var comparison = value.comparison[resp_val_key];
                    if (resp_val_key !== 0) {
                        if (comparison === 'empty' || comparison === 'notempty')
                            conditionResp += ' || ';
                        else
                            conditionResp += ' && ';
                    }                    
                    if (comparison === 'empty')
                        conditionResp += $scope.answers[resp_val] === '' || $scope.answers[resp_val] === undefined;
                    else if (comparison === 'notempty')
                        conditionResp += $scope.answers[resp_val] !== '' && $scope.answers[resp_val] !== undefined;
                    else if(resp_val === ''){
                        conditionResp += true;
                    }
                    else {
                        var currVal=$scope.answers[resp_val];
                        if(currVal===true) currVal='1';if(currVal===false || currVal==undefined) currVal='0';
                        conditionResp += resp_value.indexOf($scope.answers[resp_val]) !== -1;
                    }
                });
                
                angular.forEach(value.targetfield, function(target_fld, target_fld_key) {
                    if (field == target_fld && value.action[target_fld_key] == 'hide' && eval(conditionResp)) {
                        ret = false;
                        fieldname = field;
                    } else if (field == target_fld && value.action[target_fld_key] == 'show' && eval(conditionResp)) {
                        ret = true;
                        fieldname = field;
                    }else if (field == target_fld && value.action[target_fld_key] == 'readonly') {
                        if(eval(conditionResp) && value.target_roles.length>0){
                            if(value.target_roles.indexOf(currRole)!==-1){
                                $scope.disableLogic[target_fld]=true;
                                disable=field;
                            }
                        }
                        else if(eval(conditionResp) && value.target_profiles.length>0){
                            if(intersectProfile){
                                $scope.disableLogic[target_fld]=true;
                                disable=field;
                            }
                        }
                        else if(eval(conditionResp)){
                            $scope.disableLogic[target_fld]=true;
                            disable=field;
                        }
                        else if(disable!==field){
                            $scope.disableLogic[target_fld]=false;
                        }
                    }
                    else if (field == target_fld && value.action[target_fld_key] == 'readonlyoption') {
                        var tg_array = value.targetvalue[target_fld_key].split(",");
                        if(eval(conditionResp) && value.target_roles.length>0){
                            if(value.target_roles.indexOf(currRole)!==-1){
                                for(var tg_val in tg_array){
                                    $scope.disableOptionLogic[target_fld+'_'+tg_array[tg_val]]=true;
                                }
                                disable=field;
                            }
                        }
                        else if(eval(conditionResp) && value.target_profiles.length>0){
                            if(intersectProfile){
                                for(var tg_val in tg_array){
                                    $scope.disableOptionLogic[target_fld+'_'+tg_array[tg_val]]=true;
                                }
                                disable=field;
                            }
                        }
                        else if(eval(conditionResp)){
                                for(var tg_val in tg_array){
                                    $scope.disableOptionLogic[target_fld+'_'+tg_array[tg_val]]=true;
                                }
                                disable=field;
                        }
                        else if(disable!==field){
                                for(var tg_val in tg_array){
                                    $scope.disableOptionLogic[target_fld+'_'+tg_array[tg_val]]=false;
                                }
                        }
                    }
                    else if (field == target_fld && fieldname != field && value.action[target_fld_key] == 'hide') {
                        ret = false;
                        fieldname = field;
                    }
                    if (value.automatic[target_fld_key] !== '') {
                        if(eval(conditionResp) )
                        {
                            $scope.answers[target_fld]=value.automatic[target_fld_key];
                        }
                    }
                });
            }
        });
        
        return ret;
    };
    $scope.fieldCalc = function() {
        
        if( ($scope.answers['formato']!=undefined && $scope.answers['formato']!='') &&
            ($scope.answers['pagina']!=undefined && $scope.answers['pagina']!='') &&
            ($scope.answers['sezione_adv']!=undefined && $scope.answers['sezione_adv']!='') &&
            ($scope.answers['click_url']!=undefined && $scope.answers['click_url']!='') &&
            ($scope.answers['quantita']!=undefined && $scope.answers['quantita']!='') &&
            ($scope.answers['geo_target']!=undefined && $scope.answers['geo_target']!='')
          ){
            $scope.answers['net_net']='300';
            $scope.answers['sconto']='10';
        }
    
    };
    $scope.mandatoryLogic = function() {
        var ret = true;
        var fieldname = '';
        var blocks = [];
        angular.forEach($scope.map_field_dep, function(value, key) {
            var keepGoing = true;
            angular.forEach(value.targetfield, function(target_fld, target_fld_key) {
                var inside=$scope.insideStep(target_fld);
                if ((value.mandatory[target_fld_key] === 'mandatory' || value.patterns[target_fld_key].hasOwnProperty('pattern'))
                        && inside) {
                    var conditionResp = '';
                    angular.forEach(value.respfield, function(resp_val, resp_val_key) {
                        var resp_value = value.respvalue_portal[resp_val_key];
                        var comparison = value.comparison[resp_val_key];
                        if (resp_val_key !== 0) {
                            if (comparison === 'empty' || comparison === 'notempty')
                                conditionResp += ' || ';
                            else
                                conditionResp += ' && ';
                        }
                        if (comparison === 'empty')
                            conditionResp += true;//($scope.answers[resp_val] === '' || $scope.answers[resp_val] === undefined);
                        else if (comparison === 'notempty')
                            conditionResp += true;//($scope.answers[resp_val] !== '' && $scope.answers[resp_val] !== undefined);
                        else if(resp_val === ''){
                            conditionResp += true;
                        }else {
                            conditionResp += resp_value.indexOf($scope.answers[resp_val]) != -1;
                        }
                    });
                    if (eval(conditionResp)  && value.mandatory[target_fld_key] === 'mandatory'
                            && ($scope.answers[target_fld] == undefined || $scope.answers[target_fld]==='' || $scope.answers[target_fld]==='false' || $scope.answers[target_fld]===false) 
                            ) {
                        ret = false;
                        var mand = target_fld + '_mandText';
                        $scope[mand] = true;
                        var field_data = {
                            name: target_fld
                        };
                        blocks.push(field_data);
                    }
                    if (eval(conditionResp)  && value.patterns[target_fld_key].hasOwnProperty('pattern')) 
                    {
                        var pattern=value.patterns[target_fld_key]['pattern'];
                        var messagetext=value.patterns[target_fld_key]['messagetext'];
                        try {
                            var patterncf= new RegExp(pattern);
                            var isvalidcf= patterncf.test($scope.answers[target_fld]);
                            if(!isvalidcf){
                               $scope.currentErrorMessage[target_fld]=messagetext;
                               $scope.currentErrorStatus[target_fld]=true;    
                            }
                            else{
                                $scope.currentErrorStatus[target_fld]=false;
                                }
                        } catch(e) {
                            console.log(e);
                        }
                    }
                }
            });
        });
        $scope.mandatory_fields = blocks;
        return ret;
    };

    $scope.getMandatoryText = function(fld,fieldsinfo) {
        var mand = fld + '_mandText';
        var text = '';
        if ($scope[mand]) {
            text = $scope.getModuleLabel(fld,fieldsinfo) + ' is mandatory';
        }
        for (var i = $scope.mandatory_fields.length - 1; i >= 0; i--) {
            var fld_mand = $scope.mandatory_fields[i].name;
            var temp_mand = fld_mand + '_mandText';
            if ($scope.answers[fld_mand] !== undefined && $scope.answers[fld_mand] !== '' && $scope.answers[fld_mand]!=='false' && $scope.answers[fld_mand]!==false) {
                $scope.mandatory_fields.splice(i, 1);
                $scope[temp_mand] = false;
            }
        }
        return text;
    };
    
    $scope.automatic = function(field) {
        //$scope.mandatoryLogic();
        angular.forEach($scope.map_field_dep, function(value, key) {
            var conditionResp = '';
            var when_cond=(value.automatic.indexOf(field) !== -1 || value.automatic_copy.indexOf(field) !== -1 
                        || value.automatic_fieldname.indexOf(field) !== -1);
            if (when_cond && (value.target_mode==='CreateView' || value.target_mode==='')) {
                angular.forEach(value.respfield, function(resp_val, resp_val_key) {
                    var resp_value = value.respvalue_portal[resp_val_key];
                    var comparison = value.comparison[resp_val_key];
                    if (resp_val_key !== 0) {
                        if (comparison === 'empty' || comparison === 'notempty')
                            conditionResp += ' || ';
                        else
                            conditionResp += ' && ';
                    }                    
                    if (comparison === 'empty')
                        conditionResp += $scope.answers[resp_val] === '' || $scope.answers[resp_val] === undefined;
                    else if (comparison === 'notempty')
                        conditionResp += $scope.answers[resp_val] !== '' && $scope.answers[resp_val] !== undefined;
                    else {
                        conditionResp += resp_value.indexOf($scope.answers[resp_val]) != -1;
                    }
                });
                angular.forEach(value.targetfield, function(target_fld, target_fld_key) {
                    
                    if (value.automatic_copy[target_fld_key] !== '') {  
                        var origin_fld=value.automatic_copy[target_fld_key];
                        $scope.answers[target_fld]=$scope.answers[origin_fld];
                    }
                    if (value.automatic_fieldname[target_fld_key] !== '') {
                        if(eval(conditionResp) )
                        {
                            $scope.answers[target_fld]=value.automatic_fieldname[target_fld_key];
                        }
                    }
                });
            }
        });
        if($scope.ConfigEntities.Users!==undefined &&  field==='tipo_utente'){
            $http.post(urlRoot+"&file=operations&kaction=retrieveUserName&tipoutente="+$scope.answers['tipo_utente']
            ,{})
               .success(function(data, status) {
//                    if(data.name!=='AL0001'){
                        $scope.answers['employeename']=data.name;
                        $scope.answers['first_name']=data.name;
//                    }
            });
        }
    };
    
    $scope.isValidatedForm = function(){
        var ret=true;
        if($scope.userCreation){
            ret=false;
        }
       for(var fld in $scope.currentErrorStatus){
        if($scope.insideStep(fld)){
         if($scope.currentErrorStatus[fld]===true){
            ret=false;
            break;
         }
        }
       }
       return ret;
    };
    
    $scope.insideStep = function(fld) {
        var label=false;
        var entname = $scope.steps[$scope.step]['entityname'];
        if(fld.indexOf('_'+entname)!==-1 && entname!=''){
            label=true;
        }
        angular.forEach($scope.steps[$scope.step]['fields']['info'], function(value, key) {
            if(value.fieldname==fld){
                label= true;
            }
        });
        return label;
    };
    
    $scope.getModuleLabel = function(fld,step_fields) {
        var label='';
        angular.forEach(step_fields, function(value, key) {
            if(value.fieldname==fld){
                label=value.fieldlabel;
                return label;
            }
        });
        return label;
    };
    
    $scope.getUi10Readable = function(module, curr_ui10, label) {
        var value='Empty';var kaction='';
        if(curr_ui10!=='' && curr_ui10!=undefined){
            if(label=='roleid'){
                kaction='retrieveRole';
            }
            else{
                kaction='retrieveInfoSpecific';
            }
            var urlParamsSpecific="&recordSpecific="+curr_ui10;
            $http.post(urlRoot+"&file=operations&kaction="+kaction+urlParamsSpecific)
                   .success(function(data, status) {
                       value=data['list_link_field'];
                       $scope.ui10Readable[label] = value;
            });
        }  
        else
            $scope.ui10Readable[label] = value;
    };
    
    $scope.reloadRef = function(fld) {
        var name='tableParams'+fld;  
        $scope[name].reload();
        $scope[name].page(1); //Add this to go to the first page in the new pagging
    };
    
    $scope.searchText = {
                    $: ''
            }; 
    $scope.getUi10List = function(fld,moduleName) {
        var pageItems=5;
         
        $scope.$watch("searchText.$", function() {
            $scope[name].reload();
            $scope[name].page(1); //Add this to go to the first page in the new pagging
        });
        var name='tableParams'+fld;
        $scope[name] = new ngTableParams({
            page: 1,            // show first page
            count: 5  // count per page
        }, {
           counts: [], 
            getData: function($defer, params) {
                var moduleName=$scope.ActualRefersModule[fld]; 
                var jsonFilter={};
                var currentPage=params.page()-1;
                var term = $scope.searchText.$;
                var limit = ' limit '+currentPage*pageItems+','+pageItems;
                $http.post("index.php?module=Utilities&action=UtilitiesAjax&file=ExecuteFunctions&functiontocall=getReferenceAutocomplete"
                        +'&term='+term+'&filter=contains&limit='+limit+'&searchinmodule='+moduleName
                    ,{data: ''})
                    .success(function(data, status) {
                        $scope.myItemsTotalCount=data[0].totalRec;
                        $scope.optionList=data;
                        var orderedData = $scope.optionList;
                          params.total($scope.myItemsTotalCount);
                          if (currentPage == 0) {
                                params.settings().startItemNumber = 1;
                            }
                            else {
                                params.settings().startItemNumber = currentPage * params.settings().countSelected + 1;
                            }
                            params.settings().endItemNumber = params.settings().startItemNumber +$scope.myItemsTotalCount - 1;
                          $defer.resolve(orderedData);
                });
            }
        });
    };
    $scope.refUpdated = function(item, model, label) {
        $scope.ui10Readable[label] = item.crmname;
        $scope.answers[label] = item.crmid.split('x')[1];
    };
    
    $scope.getUiEvoReadable = function(curr_ui10, label) {
        $scope.uiEvoReadable[label] = [];
    };
    
    $scope.getUiEvoList = function(fld) {
        var pageItems=5;
        $scope.searchTextEvo = {
                $: ''
            };                
        $scope.$watch("searchTextEvo.$", function() {
            $scope[name].reload();
            $scope[name].page(1); //Add this to go to the first page in the new pagging
        });
        var name='tableParamsEvo'+fld;
        $scope[name] = new ngTableParams({
            page: 1,            // show first page
            count: 5  // count per page

        }, {
           counts: [], 
            getData: function($defer, params) {
                var formato=$scope.answers['formato'];
                var moduleName='cities';//$scope.ActualRefersModule[fld]; 
                var jsonFilter={};
                var currentPage=params.page()-1;
                var term = $scope.searchTextEvo.$;
                var limit = ' limit '+currentPage*pageItems+','+pageItems;
                $http.post("index.php?module=Utilities&action=UtilitiesAjax&file=ExecuteFunctions&functiontocall=getReferenceAutocomplete"
                        +'&term='+term+'&filter=contains&limit='+limit+'&searchinmodule='+moduleName
                    ,{data: ''})
                    .success(function(data, status) {
                        $scope.myItemsTotalCount=data[0].totalRec;
                        $scope.optionList=data;
                        var orderedData = $scope.optionList;
                          params.total($scope.myItemsTotalCount);
                          if (currentPage == 0) {
                                params.settings().startItemNumber = 1;
                            }
                            else {
                                params.settings().startItemNumber = currentPage * params.settings().countSelected + 1;
                            }
                            params.settings().endItemNumber = params.settings().startItemNumber +$scope.myItemsTotalCount - 1;
                          $defer.resolve(orderedData);
                });
            }
        });
    };
    $scope.refEvoRemoved = function(chip,index,label){
        var format=new Array();
        angular.forEach($scope.uiEvoReadable[label], function(value, key) {
            var actual_temp=value.crmid+'##'+value.crmname+'##'+value.count+'##'+value.max;
            format.push(actual_temp);
        });
        $scope.answers[label] = format.join(";");
    };
     $scope.refEvoUpdated = function(item, model, label) {
        var temp=item.crmid.split('x');
        var checkExists=false;
        angular.forEach($scope.uiEvoReadable[label], function(value, key) {
            if(value.crmid==temp[1]){
                var cnt=value.count+1;
                var arr={
                    'crmid':temp[1],
                    'crmname':item.crmname,
                    'max':0,
                    'count':cnt
                };
                $scope.uiEvoReadable[label][key]=arr;
                checkExists=true;
            }
        });
        if(!checkExists){
            var arr={
                'crmid':temp[1],
                'crmname':item.crmname,
                'max':0,
                'count':1
            };
            $scope.uiEvoReadable[label].push(arr);
        }
        var format=new Array();
        angular.forEach($scope.uiEvoReadable[label], function(value, key) {
            var actual_temp=value.crmid+'##'+value.crmname+'##'+value.count+'##'+value.max;
            format.push(actual_temp);
        });
        $scope.answers[label] = format.join(";");
    };
    
        $http.get(urlRoot+"&file=operations&kaction=getEntitiesSession&view=create"+urlParams).
            success(function(data, status) {
                //var t =document.getElementById('ENTITIES').value;
                var getEntities=data;
                $scope.steps = getEntities.steps;
                $scope.processFields($scope.steps[$scope.step]['fields']['blocks']);
                $scope.ConfigEntities = getEntities.ConfigEntities;
                $scope.settings=getEntities.settings;
    //              if($scope.settings.onsave=='Restart'){
    //                  angular.forEach($scope.ConfigEntities, function(value, key) {
    //                        var fldname = key;
    //                  });
    //              }
    //              masterModule=$scope.masterModule=$scope.settings.onsave;
    //              console.log($scope.ConfigEntities);console.log($scope.masterModule);
    //              $scope.masterId=$scope.ConfigEntities[0]['savedid'];
                $scope.retrieveActions('InCreateWidget');
                $scope.initDocuments();
              //$scope.retrieveInfo($scope.masterId);
    });
    
})
.controller('DetailController',  function ($scope,$http,$mdDialog,$mdSidenav,$mdpDatePicker,$mdpTimePicker,ngTableParams,FormService,$location,$filter,$timeout,$q) {
    
    $scope.steps = [];
    $scope.step = 0;
    $scope.processing=false;
    $scope.mandatory_fields=[];
    $scope.MAP_PCKLIST_TARGET=[];
    $scope.answers={};
    $scope.answersOld={};
    $scope.confirmEdit={};
    $scope.disableLogic={};
    $scope.disableOptionLogic={};
    $scope.ui10Readable={};
    $scope.relatedmodules={};
    $scope.linktype='PORTALSV';
    $scope.refersModule={};
    $scope.ActualRefersModule={};
    $scope.fatherInfo={};
    $scope.answersFather={};
    $scope.gettingEntities=true;
    $scope.loadingNgBlock=false;
    $scope.uiEvoReadable=[];
    $scope.formRef={};
    $scope.FatherDetailViewBlocks={};
    $scope.executingAction={state:false};
    $scope.bachToDashb='';
    $scope.megatext='';
    $scope.tabIdReloadAgain='';
    $scope.currentPage='';
    $scope.queryResults = {};
    $scope.selectedItem={};
    $scope.cachedmap={};
    $scope.searchValue={};
    $scope.searchValueFather={};
    $scope.selectedItemFather={};
    $scope.queryResultsFather = {};
    $scope.isNotValidAutocomplete = {};
    $scope.currentErrorStatus={};
    $scope.currentErrorMessage={};
    
    var record=document.getElementById('record').value;
    var masterModule=$scope.masterModule=document.getElementById('masterModule').value;
    var onOpenView=document.getElementById('onOpenView').value;
    $scope.module=document.getElementById('module').value;
    var urlRoot="index.php?module="+$scope.module+"&action="+$scope.module+"Ajax";
    var urlParams="&masterRecord="+record+"&masterModule="+masterModule;
    FormService.setConfigured(record,masterModule,onOpenView,$scope.module);
    $scope.OutsideData=document.getElementById('OutsideData').value;
    var temp=$scope.OutsideData.split('&');
    angular.forEach(temp, function(value, key) {
            var arr=value.split('=');
            $scope.answers[arr[0]]=arr[1];
            if(arr[0]==='returnDashb'){
                $scope.bachToDashb=arr[1];
            }
            else if(arr[0]==='megatext'){
                $scope.megatext=arr[1];
            }
            else if(arr[0]==='tabIdReloadAgain'){
                $scope.tabIdReloadAgain=arr[1];
            }
            else if(arr[0]==='currentPage'){
                $scope.currentPage=arr[1];
            }
    });
    
    $scope.backDash = function (megatext) {
        
        window.location.href='index.php?module='+$scope.bachToDashb+'&megatext='+$scope.megatext+'&tabIdReloadAgain='+$scope.tabIdReloadAgain+'&currentPage='+$scope.currentPage+'&action=index';
    };
    
    $scope.retrieveInfo = function (id) {
        urlParams="&masterRecord="+id+"&masterModule="+masterModule;
        $http.post(urlRoot+"&file=operations&kaction=retrieveInfo"+urlParams)
               .success(function(data, status) {
                    $scope.answers=data;
                    $scope.answers['id']=id;
                    var primaryMod=data.record_module;
                    FormService.setConfigured(id,primaryMod,onOpenView,$scope.module);
                    var widgets=new Array('DETAILVIEWWIDGETFORM','DETAILVIEWBASICFORM','DETAILVIEWBASICFORM2');
                    FormService.getRelatedModules(widgets).then(function(response) {
                            $scope.relatedmodules =response.data;
                            if($scope.relatedmodules.DETAILVIEWWIDGETFORM.length>0){
                                $scope.SummaryWidget =$scope.relatedmodules.DETAILVIEWWIDGETFORM[0];
                                $scope.showNgBlockWidget($scope.SummaryWidget['linklabel'],'DETAILVIEWWIDGETFORM',$scope.SummaryWidget['type']);
                            }
                    });
                    $scope.processFields($scope.DetailViewBlocks['info'],$scope.answers);      
                    //$scope.gettingEntities=false;
        });
    }; 
    $scope.retrieveInfoSpecific = function (id) {
        var urlParamsSpecific="&recordSpecific="+id;
        if(id!=='' && id!==0 && id!==undefined && id !==null && id !=='0'){
            $http.post(urlRoot+"&file=operations&kaction=retrieveInfoSpecific"+urlParamsSpecific)
                   .success(function(data, status) {
                       return data;         
            });
        }
    };  
    $scope.relmodule = $scope.module;
    $scope.form_name='';
    
    $scope.showNgBlockWidget = function(ngblockid,linktype,ngblockType) {
        $scope.relmodule = ngblockid;    
        $scope.linktype=linktype;
        $scope.ngblockType=ngblockType;
        $scope.loadingNgBlock=true;
        if(ngblockType==='Table'){
            var nm='tableParams'+ngblockid;
            $scope[nm] = new ngTableParams({
                page: 1,            // show first page
                count: 5  // count per page
            }, {
               filterDelay: 0,counts: [], 
                getData: function($defer, params) { 
                    var pageItems=5;
                    var currentPage=params.page()-1;
                    var limit = ' limit '+currentPage*pageItems+','+pageItems;
                    var where = params.filter();
                    FormService.doGetRelatedRecords(ngblockid,limit,where).then(function(response) {
                        $scope.relRecordList = response.data.records;
                        $scope.fields = response.data.fields;
                        $scope.myItemsTotalCount=response.data.totalRec;
                        $scope.form_name=response.data.form_name;
                        $scope.action_name=response.data.action_name;
                        $scope.loadingNgBlock=false;

                        var orderedData = $scope.relRecordList;
                        var filteredData = params.filter() ?
                                $filter('filter')(orderedData, params.filter()) :
                                orderedData;
                        var orderedData = filteredData;
                        params.total($scope.myItemsTotalCount);
                        if (currentPage == 0) {
                            params.settings().startItemNumber = 1;
                        }
                        else {
                            params.settings().startItemNumber = currentPage * params.settings().countSelected + 1;
                        }
                        params.settings().endItemNumber = params.settings().startItemNumber +$scope.myItemsTotalCount - 1;
                        $defer.resolve(orderedData);
                    });
                }
            }); 
        }
        else if (ngblockType==='FATHERDV'){  
            var ngblockid='&ngblockid='+ngblockid;
            $http.post(urlRoot+"&file=operations&kaction=getFatherDetailViewBlocks"+urlParams+ngblockid
                    ,{config:$scope.ConfigEntities}).
            success(function(data, status) {
                $scope.FatherDetailViewBlocks = data.FatherDetailViewBlocks;
                $scope.FatherId = data.FatherId;
                var urlTemp="&recordSpecific="+$scope.FatherId;
                if($scope.FatherId!=='' && $scope.FatherId!==undefined){
                    $http.post(urlRoot+"&file=operations&kaction=retrieveInfoSpecific"+urlTemp)
                           .success(function(data, status) {
                               $scope.answersFather=data;
                               $scope.processFields($scope.FatherDetailViewBlocks['info'],$scope.answersFather);
                               $scope.loadingNgBlock=false;
                    });
                }
                else{
                    $scope.loadingNgBlock=false;
                }
            });
        }
        else{
            $scope.loadingNgBlock=false;
        }
    };
    $scope.downloadfile = function(path) {
        window.open(path, '_blank');
    };
    $http.get(urlRoot+"&file=operations&kaction=getFieldDependency"+urlParams).
        success(function(data, status) {
            $scope.map_field_dep = data.all_field_dep;
            $scope.MAP_RESPONSIBILE_FIELDS = data.MAP_RESPONSIBILE_FIELDS;
            $scope.MAP_RESPONSIBILE_FIELDS3 = data.MAP_RESPONSIBILE_FIELDS3;
            $scope.MAP_RESPONSIBILE_FIELDS2 = data.MAP_RESPONSIBILE_FIELDS2;
            $scope.MAP_PCKLIST_TARGET = data.MAP_PCKLIST_TARGET;
        });
        
//    $http.get(urlRoot+"&file=operations&kaction=getEntities&view=detail"+urlParams).
//            success(function(data, status) {
//              $scope.ConfigEntities = data.ConfigEntities;
//              $scope.settings=data.settings;
//              $scope.masterModule=$scope.settings.onsave;
//              $scope.masterId=$scope.ConfigEntities[$scope.masterModule]['savedid']; 
//              $scope.DetailViewBlocks = data.DetailViewBlocks;
//              $scope.retrieveInfo($scope.masterId);
//              $scope.retrieveDetailRecords();
//              $scope.gettingEntities=false;
//    });
    
    $scope.label = 'Azioni';
    $scope.isOpen = false;
    $scope.selectedMode = 'md-fling';
    $scope.selectedDirectionDetail = 'down'; 
    
    $scope.processFields = function (info,answers) {
        angular.forEach(answers, function(value, key) {
            var fldname = key;
            var uitype='';
            uitype = $scope.getFieldType(fldname,info); 
            var array_date = ['5','6','23','70'];
            if (uitype == '10') {                        
                var moduleRef = $scope.getFieldModuleRef(fldname,info);
                $scope.ActualRefersModule[fldname]=moduleRef[0];
                $scope.formRef[fldname]=$scope.getFieldModuleFormRef(fldname,info);
                $scope.refersModule[fldname]=moduleRef;
                $scope.getUi10Readable(moduleRef[0],answers[fldname], fldname);
                $scope.getUi10List(fldname,moduleRef[0]);     
                $scope.answersOld[fldname+'_Readable']=$scope.ui10Readable[fldname];
            }
            else if (uitype == '1025') {
                $scope.getUiEvoReadable($scope.answers[fldname], fldname);
                $scope.getUiEvoList(fldname);                        
            }
            else if(array_date.indexOf(uitype)!==-1){ // field date type
                if(answers[fldname+'_time']!==undefined && answers[fldname+'_time']!=='' )
                      answers[fldname]=answers[fldname]+' '+ answers[fldname+'_time'];
                answers[fldname]=new Date(answers[fldname]);
            }
            else if(uitype == '56'){ 
                answers[fldname]=(answers[fldname]==='1' ? true : false);
            }
            else if (uitype == '1026'){
                if($scope.answers[fldname]!=undefined){
                    $scope.selectedItem[fldname]=$scope.answers[fldname];
                }
                if($scope.answersFather[fldname]!=undefined){
                    $scope.selectedItemFather[fldname]=$scope.answersFather[fldname];
                }
                        
            }
            $scope.answersOld[fldname]=$scope.answers[fldname];
        });
    };
    $scope.getFieldType = function (fieldname,modulefields) {
        var ret = '';
        var found = $filter('getArrayElementById')(modulefields, fieldname, 'fieldname');
        if (found != '' && found != undefined && found != null)
                ret = found.uitype;
        return ret;
    };
    $scope.getFieldModuleFormRef = function (fieldname,modulefields) {
        var ret = {};
        var found = $filter('getArrayElementById')(modulefields, fieldname, 'fieldname');
        if (found != '' && found != undefined && found != null)
                ret = {'ui10FormName':found.ui10FormName,
                       'ui10ActionName':found.ui10ActionName
                      };
        return ret;
    };
    $scope.getFieldModuleRef = function (fieldname,modulefields) {
        var ret = '';
        var found = $filter('getArrayElementById')(modulefields, fieldname, 'fieldname');
        if (found != '' && found != undefined && found != null)
                ret = found.options;
        return ret;
    };
    $scope.getFieldLabel = function(field, modulefields) {
        var ret = '';
        var found = $filter('getArrayElementById')(modulefields, field, 'fieldname');
        if (found != '' && found != undefined && found != null)
            ret = found.fieldlabel;
        return ret;
    };
    $scope.getFieldOptions = function(field, modulefields) {
        var ret = '';
        var found = $filter('getArrayElementById')(modulefields, field, 'fieldname');
        if (found != '' && found != undefined && found != null)
            ret = found.options;
        return ret;
    };
    $scope.getUi10Readable = function(module, curr_ui10, label) {
        var value='Empty';
        if(curr_ui10!=='' && curr_ui10!==0 && curr_ui10!=='0'){
            var urlParamsSpecific="&recordSpecific="+curr_ui10;
            $http.post(urlRoot+"&file=operations&kaction=retrieveInfoSpecific"+urlParamsSpecific)
                   .success(function(data, status) {
                       value=data['list_link_field'];
                       $scope.ui10Readable[label] = value;
            });
        }  
        else
            $scope.ui10Readable[label] = value;
    };
    
    $scope.reloadRef = function(fld) {
        var name='tableParams'+fld;  
        $scope[name].reload();
        $scope[name].page(1); //Add this to go to the first page in the new pagging
    };
    $scope.searchText = {
                    $: ''
            }; 
    $scope.getUi10List = function(fld,moduleName) {
        var pageItems=5;    
        $scope.$watch("searchText.$", function() {
            $scope[name].reload();
            $scope[name].page(1); //Add this to go to the first page in the new pagging
        });
        var name='tableParams'+fld;
        $scope[name] = new ngTableParams({
            page: 1,            // show first page
            count: 5  // count per page
        }, {
           counts: [], 
            getData: function($defer, params) {
                var moduleName=$scope.ActualRefersModule[fld]; 
                var jsonFilter={};
                var currentPage=params.page()-1;
                var term = $scope.searchText.$;
                var limit = ' limit '+currentPage*pageItems+','+pageItems;
                $http.post("index.php?module=Utilities&action=UtilitiesAjax&file=ExecuteFunctions&functiontocall=getReferenceAutocomplete"
                        +'&term='+term+'&filter=contains&limit='+limit+'&searchinmodule='+moduleName
                    ,{data: ''})
                    .success(function(data, status) {
                        $scope.myItemsTotalCount=data[0].totalRec;
                        $scope.optionList=data;
                        var orderedData = $scope.optionList;
                          params.total($scope.myItemsTotalCount);
                          if (currentPage == 0) {
                                params.settings().startItemNumber = 1;
                            }
                            else {
                                params.settings().startItemNumber = currentPage * params.settings().countSelected + 1;
                            }
                            params.settings().endItemNumber = params.settings().startItemNumber +$scope.myItemsTotalCount - 1;
                          $defer.resolve(orderedData);
                });
            }
        });
    };
    $scope.refUpdated = function(item, model, label) {
        $scope.ui10Readable[label] = item.crmname;
        $scope.answers[label] = item.crmid.split('x')[1];
        $scope.updateEntity(label);        
    };
    $scope.goToui10 = function(path) {
        window.open(path, '_blank');
    };
    $scope.getUiEvoReadable = function(curr_ui10, label) {
        var value='Empty';
        $scope.uiEvoReadable[label]=[];
        if(curr_ui10!=='' && curr_ui10!==0 && curr_ui10!=='0'){
            var temp=curr_ui10.split(';');
            angular.forEach(temp, function(value, key) {
                var nodesEvo=value.split('##');
                if(nodesEvo[0]!==''){
                    var urlParamsSpecific="&recordSpecific="+nodesEvo[0];
                    $http.post(urlRoot+"&file=operations&kaction=retrieveInfoSpecific"+urlParamsSpecific)
                           .success(function(data, status) {
                               value=data['list_link_field'];
                               var arr={
                                    'crmid':parseInt(nodesEvo[0]),
                                    'crmname':nodesEvo[1],
                                    'max':parseInt(nodesEvo[3]),
                                    'count':parseInt(nodesEvo[2])
                                };
                               $scope.uiEvoReadable[label].push(arr);
                    });
                }
            });
        }  
        else
            $scope.uiEvoReadable[label] = [];
    };
    
    $scope.getUiEvoList = function(fld) {
        var pageItems=5;
        $scope.searchTextEvo = {
                $: ''
            };                
        $scope.$watch("searchTextEvo.$", function() {
            $scope[name].reload();
            $scope[name].page(1); //Add this to go to the first page in the new pagging
        });
        var name='tableParamsEvo'+fld;
        $scope[name] = new ngTableParams({
            page: 1,            // show first page
            count: 5  // count per page

        }, {
           counts: [], 
            getData: function($defer, params) {
                var formato=$scope.answers['formato'];
                var moduleName='cities';//$scope.ActualRefersModule[fld]; 
                var jsonFilter={};
                var currentPage=params.page()-1;
                var term = $scope.searchTextEvo.$;
                var limit = ' limit '+currentPage*pageItems+','+pageItems;
                $http.post("index.php?module=Utilities&action=UtilitiesAjax&file=ExecuteFunctions&functiontocall=getReferenceAutocomplete"
                        +'&term='+term+'&filter=contains&limit='+limit+'&searchinmodule='+moduleName
                    ,{data: ''})
                    .success(function(data, status) {
                        $scope.myItemsTotalCount=data[0].totalRec;
                        $scope.optionList=data;
                        var orderedData = $scope.optionList;
                          params.total($scope.myItemsTotalCount);
                          if (currentPage == 0) {
                                params.settings().startItemNumber = 1;
                            }
                            else {
                                params.settings().startItemNumber = currentPage * params.settings().countSelected + 1;
                            }
                            params.settings().endItemNumber = params.settings().startItemNumber +$scope.myItemsTotalCount - 1;
                          $defer.resolve(orderedData);
                });
            }
        });
    };
    $scope.refEvoRemoved = function(chip,index,label){
        var format=new Array();
        angular.forEach($scope.uiEvoReadable[label], function(value, key) {
            var actual_temp=value.crmid+'##'+value.crmname+'##'+value.count+'##'+value.max;
            format.push(actual_temp);
        });
        $scope.answers[label] = format.join(";");
        $scope.updateEntity(label);  
    };
     $scope.refEvoUpdated = function(item, model, label) {
        var temp=item.crmid.split('x');
        var checkExists=false;
        angular.forEach($scope.uiEvoReadable[label], function(value, key) {
            if(value.crmid==temp[1]){
                var cnt=value.count+1;
                var arr={
                    'crmid':temp[1],
                    'crmname':item.crmname,
                    'max':0,
                    'count':cnt
                };
                $scope.uiEvoReadable[label][key]=arr;
                checkExists=true;
            }
        });
        if(!checkExists){
            var arr={
                'crmid':temp[1],
                'crmname':item.crmname,
                'max':0,
                'count':1
            };
            $scope.uiEvoReadable[label].push(arr);
        }
        var format=new Array();
        angular.forEach($scope.uiEvoReadable[label], function(value, key) {
            var actual_temp=value.crmid+'##'+value.crmname+'##'+value.count+'##'+value.max;
            format.push(actual_temp);
        });
        $scope.answers[label] = format.join(";");
        $scope.updateEntity(label);  
    };

    
    $scope.showLogicRow = function(row) {
        var ret = false;
        row.forEach(function(entry) {
            if ($scope.showLogic(entry)) {
                ret = true;
            }
        });
        return ret;
    };
    $scope.showLogic = function(field) {
        var ret = true;
        var fieldname = '';
        var disable = '';var confirm = '';
        var currRole=document.getElementById('RoleId').value;
        var currProfiles=document.getElementById('Profiles').value;
        
        angular.forEach($scope.map_field_dep, function(value, key) {
            var conditionResp = '';
            var intersectProfile=false;
            for(var prof_c=0;prof_c<currProfiles.length;prof_c++){
                if(value.target_profiles.indexOf(currProfiles[prof_c])!==-1)
                {
                    intersectProfile=true;break;
                }
            }                        
            if (value.targetfield.indexOf(field) != -1 && (value.target_mode==='DetailView' || value.target_mode==='')) {
                angular.forEach(value.respfield, function(resp_val, resp_val_key) {
                    var resp_value = value.respvalue_portal[resp_val_key];
                    var comparison = value.comparison[resp_val_key];
                    if (resp_val_key !== 0) {
                        if (comparison === 'empty' || comparison === 'notempty')
                            conditionResp += ' || ';
                        else
                            conditionResp += ' && ';
                    }                    
                    if (comparison === 'empty')
                        conditionResp += $scope.answers[resp_val] === '' || $scope.answers[resp_val] === undefined;
                    else if (comparison === 'notempty')
                        conditionResp += $scope.answers[resp_val] !== '' && $scope.answers[resp_val] !== undefined;
                    else if(resp_val === ''){
                        conditionResp += true;
                    }else {
                        var currVal=$scope.answers[resp_val];
                        if(currVal===true) currVal='1';if(currVal===false || currVal==undefined) currVal='0';
                        conditionResp += resp_value.indexOf($scope.answers[resp_val]) !== -1;
                    }
                });
                
                angular.forEach(value.targetfield, function(target_fld, target_fld_key) {
                    if (field == target_fld && value.action[target_fld_key] == 'hide' && eval(conditionResp)) {
                        ret = false;
                        fieldname = field;
                    } else if (field == target_fld && value.action[target_fld_key] == 'show' && eval(conditionResp)) {
                        ret = true;
                        fieldname = field;
                    }else if (field == target_fld && value.action[target_fld_key] == 'readonly') {
                        if(eval(conditionResp) && value.target_roles.length>0){
                            if(value.target_roles.indexOf(currRole)!==-1){
                                $scope.disableLogic[target_fld]=true;
                                disable=field;
                            }
                        }
                        else if(eval(conditionResp) && value.target_profiles.length>0){
                            if(intersectProfile){
                                $scope.disableLogic[target_fld]=true;
                                disable=field;
                            }
                        }
                        else if(eval(conditionResp)){
                            $scope.disableLogic[target_fld]=true;
                            disable=field;
                        }
                        else if(disable!==field){
                            $scope.disableLogic[target_fld]=false;
                        }
                    }
                    else if (field == target_fld && value.action[target_fld_key] == 'readonlyoption') {
                        var tg_array = value.targetvalue[target_fld_key].split(",");
                        if(eval(conditionResp) && value.target_roles.length>0){
                            if(value.target_roles.indexOf(currRole)!==-1){
                                for(var tg_val in tg_array){
                                    $scope.disableOptionLogic[target_fld+'_'+tg_array[tg_val]]=true;
                                }
                                disable=field;
                            }
                        }
                        else if(eval(conditionResp) && value.target_profiles.length>0){
                            if(intersectProfile){
                                for(var tg_val in tg_array){
                                    $scope.disableOptionLogic[target_fld+'_'+tg_array[tg_val]]=true;
                                }
                                disable=field;
                            }
                        }
                        else if(eval(conditionResp)){
                                for(var tg_val in tg_array){
                                    $scope.disableOptionLogic[target_fld+'_'+tg_array[tg_val]]=true;
                                }
                                disable=field;
                        }
                        else if(disable!==field){
                                for(var tg_val in tg_array){
                                    $scope.disableOptionLogic[target_fld+'_'+tg_array[tg_val]]=false;
                                }
                        }
                    }
                    else if (field == target_fld && fieldname != field && value.action[target_fld_key] == 'hide') {
                        ret = false;
                        fieldname = field;
                    }
                    if(field == target_fld && value.confirm_editability[target_fld_key] == 'confirm'){
                        if(eval(conditionResp) && value.target_roles.length>0){
                            if(value.target_roles.indexOf(currRole)!==-1){
                                $scope.confirmEdit[target_fld]=true;
                                confirm=field;
                            }
                        }
                        else if(eval(conditionResp) && value.target_profiles.length>0){
                            if(intersectProfile){
                                $scope.confirmEdit[target_fld]=true;
                                confirm=field;
                            }
                        }
                        else if(eval(conditionResp)){
                                $scope.confirmEdit[target_fld]=true;
                                confirm=field;
                        }
                        else if(confirm!==field){
                            $scope.confirmEdit[target_fld]=false;
                        }
                    }
                    
                });
            }
        });
        
        return ret;
    };
    
    $scope.fDepFather = function(){
        var moduleFather='masteraccountform';
        var ret=false;
        $http.get(urlRoot+"&file=operations&kaction=getFieldDependencyFather"+urlParams+'&moduleFather='+moduleFather).
            success(function(data, status) {
                $scope.map_field_depFather = data.all_field_dep;
                ret=$scope.mandatoryLogic($scope.map_field_depFather,$scope.answersFather);
                console.log('ketu');
                return ret;
            });
            
    };  
    $scope.mandatoryLogic = function(fDep,answers,type) {
        var ret = true;
        var fieldname = '';
        var blocks = [];
        angular.forEach(fDep, function(value, key) {
            var keepGoing = true;
            angular.forEach(value.targetfield, function(target_fld, target_fld_key) {
                if ((value.mandatory[target_fld_key] === 'mandatory' || value.patterns[target_fld_key].hasOwnProperty('pattern'))
                        ) {
                    var conditionResp = '';
                    angular.forEach(value.respfield, function(resp_val, resp_val_key) {
                        var resp_value = value.respvalue_portal[resp_val_key];
                        var comparison = value.comparison[resp_val_key];
                        if (resp_val_key !== 0) {
                            if (comparison === 'empty' || comparison === 'notempty')
                                conditionResp += ' || ';
                            else
                                conditionResp += ' && ';
                        }
                        if (comparison === 'empty')
                            conditionResp += true;//($scope.answers[resp_val] === '' || $scope.answers[resp_val] === undefined);
                        else if (comparison === 'notempty')
                            conditionResp += true;//($scope.answers[resp_val] !== '' && $scope.answers[resp_val] !== undefined);
                        else if(resp_val === ''){
                            conditionResp += true;
                        }else {
                            conditionResp += resp_value.indexOf(answers[resp_val]) != -1;
                        }
                    });
                    var source=(type==='master' ? $scope.DetailViewBlocks['info'] : $scope.FatherDetailViewBlocks['info']);
                    var ftype=$scope.getFieldType(target_fld,source);
                    if (eval(conditionResp)  && value.mandatory[target_fld_key] === 'mandatory'
                            && (answers[target_fld] == undefined || answers[target_fld]==='' || answers[target_fld]==='false' || answers[target_fld]===false) 
                            && ftype!=='') {
                        ret = false;
                        var mand = target_fld + '_mandText';
                        $scope[mand] = true;
                        var field_data = {
                            name: target_fld
                        };
                        blocks.push(field_data);
                    }
                    if (eval(conditionResp)  && value.patterns[target_fld_key].hasOwnProperty('pattern')
                            && ftype!=='') 
                    {
                        var pattern=value.patterns[target_fld_key]['pattern'];
                        var messagetext=value.patterns[target_fld_key]['messagetext'];
                        try {
                            var patterncf= new RegExp(pattern);
                            var isvalidcf= patterncf.test(answers[target_fld]);
//                            if(target_fld=='cap_sede_operativa' || target_fld=='cap_ma') {
//                                console.log(isvalidcf);
//                                console.log(answers[target_fld]);
//                                console.log(target_fld);
//                            }
                            if(!isvalidcf){
                               $scope.currentErrorMessage[target_fld]=messagetext;
                               $scope.currentErrorStatus[target_fld]=true;    
                            }
                            else{
                                $scope.currentErrorStatus[target_fld]=false;
                                }
                        } catch(e) {
                            console.log(e);
                        }
                    }
                }
            });
        });
        $scope.mandatory_fields[type]=blocks;
        //$scope.mandatory_fields = blocks;
        console.log($scope.mandatory_fields);
        return ret;
    };

    $scope.getMandatoryText = function(fld,fieldsinfo,answers,type) {
        var mand = fld + '_mandText';
        var text = '';
        if($scope.mandatory_fields[type]!==undefined){
            for (var i = $scope.mandatory_fields[type].length - 1; i >= 0; i--) {
                var fld_mand = $scope.mandatory_fields[type][i].name;
                var temp_mand = fld_mand + '_mandText';
                if (answers[fld_mand] !== undefined && answers[fld_mand] !== '' 
                        && answers[fld_mand]!=='false' && answers[fld_mand]!==false
                        ) {
                    $scope.mandatory_fields[type].splice(i, 1);
                    $scope[temp_mand] = false;
                }

            }
            if ($scope[mand]) {
                text = $scope.getModuleLabel(fld,fieldsinfo) + ' is mandatory';
            }
        }
        return text;
    };
    
    $scope.isValidatedForm = function(){
        var ret=true;
       for(var fld in $scope.currentErrorStatus){
         if($scope.currentErrorStatus[fld]===true){
            ret=false;
            break;
         }
         else{
             $scope.currentErrorMessage[fld]='';
         }
       }
       return ret;
    };
    
    $scope.getModuleLabel = function(fld,step_fields) {
        var label='';
        angular.forEach(step_fields, function(value, key) {
            if(value.fieldname==fld){
                label=value.fieldlabel;
                return label;
            }
        });
        return label;
    };
    
    /**
     * Show the dialog view 
     */
    $scope.openDialog = function($event) {
        // Show the dialog
        $mdDialog.show({
          clickOutsideToClose: true,
          controller: function($mdDialog) {
            // Save the clicked item
            this.item = 'test';
            // Setup some handlers
            this.close = function() {
              $mdDialog.cancel();
            };
            this.submit = function() {
              $mdDialog.hide();
            };
          },
          controllerAs: 'dialog',
          templateUrl: 'dialog.html',
          targetEvent: $event
        });
      };
    $scope.storeDataModel={};
    $scope.retrieveDetailRecords = function () { // the initial detailview to show details according to FormConfig
        $scope.tableParamsDetail = new ngTableParams({
            page: 1,            // show first page
            count: 5  // count per page
        }, {
           counts: [5,15], 
            getData: function($defer, params) {
                if($scope.masterId!==''){
                    urlParams="&masterRecord="+$scope.masterId+"&masterModule="+masterModule;
                }
                $http.get(urlRoot+"&file=operations&kaction=retrieveDetailRecords"+urlParams).
                    success(function(data, status) {
                      var orderedData =$scope.storeDataModel= data;
                      params.total(data.length);
                      $defer.resolve(orderedData.slice((params.page() - 1) * params.count(),params.page() * params.count()));
                });
            }
        });
    };
      
    $scope.processFieldsUpdate = function (wizardfield,DetailViewBlocks,answers) {
        $scope.temp={};
        $scope.temp['id']=answers['id'];
        var fldname = wizardfield;
        var uitype = $scope.getFieldType(fldname,DetailViewBlocks);                    
        var array_date = ['5','6','23','70'];
        $scope.temp[fldname]=answers[fldname];
        if(array_date.indexOf(uitype)!==-1){ // field date type
            $scope.temp[fldname]=moment(answers[fldname]).format('YYYY-MM-DD');
            $scope.temp[fldname+'_time']=moment(answers[fldname]).format('H:m');
        }
        return $scope.temp;
    };
    
    $scope.updateEntity = function (wizardfield) {
        if($scope.module=='testacc' || $scope.module=='masteraccountform'){
            if($scope.answersFather['venditore_ma']==='' || $scope.answersFather['venditore_ma']===' ' || $scope.answersFather['venditore_ma']===undefined
                    || $scope.answersFather['user_ma']==='' || $scope.answersFather['user_ma']===' ' || $scope.answersFather['user_ma']===undefined){
                $scope.answersFather['venditore_ma'] = document.getElementById('LoggedUserName').value;
                $scope.answersFather['user_ma'] = document.getElementById('LoggedUserName').value;
                var temp={'id':$scope.answersFather['record_id'],
                            'venditore_ma':document.getElementById('LoggedUserName').value,
                            'user_ma':document.getElementById('LoggedUserName').value};
                $http.post(urlRoot+"&file=operations&kaction=updateFather"+urlParams
                    ,{data: temp})
                        .success(function(data, status) {   
                });

            }
            if($scope.answers['venditore_accounts']==='' || $scope.answers['venditore_accounts']===' ' || $scope.answers['venditore_accounts']===undefined
                    || $scope.answers['usercrm_accounts']==='' || $scope.answers['usercrm_accounts']===' ' || $scope.answers['usercrm_accounts']===undefined){
                $scope.answers['venditore_accounts'] = document.getElementById('LoggedUserName').value;
                $scope.answers['usercrm_accounts'] = document.getElementById('LoggedUserName').value;
                var temp={'id':$scope.answers['id'],
                            'venditore_accounts':document.getElementById('LoggedUserName').value,
                            'usercrm_accounts':document.getElementById('LoggedUserName').value};
                $http.post(urlRoot+"&file=operations&kaction=updateEntity"+urlParams
                    ,{data: temp,config:$scope.ConfigEntities[$scope.masterModule],settings:$scope.settings})
                        .success(function(data, status) {
                });

            }
        }
        if($scope.confirmEdit[wizardfield]){
            if(confirm('Sei sicuro di procedere con la modifica?')){
                var temp=$scope.processFieldsUpdate(wizardfield,$scope.DetailViewBlocks['info'],$scope.answers);      
                $http.post(urlRoot+"&file=operations&kaction=updateEntity"+urlParams
                    ,{data: temp,config:$scope.ConfigEntities[$scope.masterModule],settings:$scope.settings})
                        .success(function(data, status) {
                  
                });
                $scope.answersOld[wizardfield]=$scope.answers[wizardfield];
                if($scope.answersOld[wizardfield+'_Readable']!=='' && $scope.answersOld[wizardfield+'_Readable']!==undefined)
                {
                    $scope.answersOld[wizardfield+'_Readable']=$scope.ui10Readable[wizardfield];
                }
            }
            else{
                $scope.answers[wizardfield]=$scope.answersOld[wizardfield];
                if($scope.answersOld[wizardfield+'_Readable']!=='' && $scope.answersOld[wizardfield+'_Readable']!==undefined)
                {
                    $scope.ui10Readable[wizardfield]=$scope.answersOld[wizardfield+'_Readable'];
                }
            }
        }
        else{
            var temp=$scope.processFieldsUpdate(wizardfield,$scope.DetailViewBlocks['info'],$scope.answers);      
            $http.post(urlRoot+"&file=operations&kaction=updateEntity"+urlParams
                ,{data: temp,config:$scope.ConfigEntities[$scope.masterModule],settings:$scope.settings})
                    .success(function(data, status) {
            }); 
        }        
    };
    
    $scope.updateFather = function (wizardfield) {
        $scope.answersFather['id']=$scope.answersFather['record_id'];
        if($scope.module=='testacc' || $scope.module=='masteraccountform'){
            if($scope.answersFather['venditore_ma']==='' || $scope.answersFather['venditore_ma']===' ' || $scope.answersFather['venditore_ma']===undefined
                    || $scope.answersFather['user_ma']==='' || $scope.answersFather['user_ma']===' ' || $scope.answersFather['user_ma']===undefined){
                $scope.answersFather['venditore_ma'] = document.getElementById('LoggedUserName').value;
                $scope.answersFather['user_ma'] = document.getElementById('LoggedUserName').value;
                var temp={'id':$scope.answersFather['record_id'],
                            'venditore_ma':document.getElementById('LoggedUserName').value,
                            'user_ma':document.getElementById('LoggedUserName').value};
                $http.post(urlRoot+"&file=operations&kaction=updateFather"+urlParams
                    ,{data: temp})
                        .success(function(data, status) {   
                });

            }
            if($scope.answers['venditore_accounts']==='' || $scope.answers['venditore_accounts']===' ' || $scope.answers['venditore_accounts']===undefined
                    || $scope.answers['usercrm_accounts']==='' || $scope.answers['usercrm_accounts']===' ' || $scope.answers['usercrm_accounts']===undefined){
                $scope.answers['venditore_accounts'] = document.getElementById('LoggedUserName').value;
                $scope.answers['usercrm_accounts'] = document.getElementById('LoggedUserName').value;
                var temp={'id':$scope.answers['id'],
                            'venditore_accounts':document.getElementById('LoggedUserName').value,
                            'usercrm_accounts':document.getElementById('LoggedUserName').value};
                $http.post(urlRoot+"&file=operations&kaction=updateEntity"+urlParams
                    ,{data: temp,config:$scope.ConfigEntities[$scope.masterModule],settings:$scope.settings})
                        .success(function(data, status) {
                });

            }
        }
        var temp=$scope.processFieldsUpdate(wizardfield,$scope.FatherDetailViewBlocks['info'],$scope.answersFather);    
        $http.post(urlRoot+"&file=operations&kaction=updateFather"+urlParams
            ,{data: temp})
                .success(function(data, status) {
                  
        });        
    };
    
    $scope.toggleRight = function() {
        // Show the dialog
        $scope.executingAction.state=false;
        $mdDialog.show({
          clickOutsideToClose: false,
          templateUrl: 'PrimoSguardo',
          controller:PrimoSguardoController,
          resolve: {
            answers :function () {
                return $scope.answers;
            },
            outputActionInfo :function () {
                return $scope.outputActionInfo;
            },
            updateEntity :function () {
                return $scope.updateEntity;
            },
            selectedItem : function () {
            	return $scope.selectedItem;
            },
            selectedItemFather : function () {
            	return $scope.selectedItemFather;
            }
          }
      });
      };
    function PrimoSguardoController($scope, $mdDialog,answers,outputActionInfo,updateEntity,selectedItem,selectedItemFather) {
    
        $scope.outputActionInfo=outputActionInfo;
        $scope.close = function () {
            answers['ma_validationstatus']='Validato Non Accettato';
            answers['ma_validationdate']='';
            updateEntity('ma_validationstatus');updateEntity('ma_validationstatus');
            $mdDialog.cancel(); 
        };
        $scope.accept = function () {
            var focusrecord = outputActionInfo;
            for (var i=0;i<focusrecord.values.length;i++) {
                if(focusrecord.toupdate[i]==1 || focusrecord.toupdate[i]=='1'){
                    var tempVal=focusrecord.values[i];
                    var tempCol=focusrecord.columns[i];
                    answers[tempCol] = tempVal;
                    updateEntity(tempCol);
                }
                if(focusrecord.columns[i]=='description')
                {
                    answers['description_MasterAccount']=focusrecord.values[i];
                }
            }
            answers['ma_validationstatus']='Validato Accettato';
            answers['ma_validationdate']=new Date();
            if(answers['citta_ma']!=undefined){
                selectedItem['citta_ma']=answers['citta_ma'];
                selectedItemFather['citta_ma']=answers['citta_ma'];
            }
            if(answers['comune_sede_operativa']!=undefined){
                selectedItem['comune_sede_operativa']=answers['comune_sede_operativa'];
            }
            updateEntity('ma_validationstatus');updateEntity('ma_validationstatus');
            $mdDialog.cancel(); 
        };
    }

            //autocomplete
    $scope.querySearch = function (query,fld,father) {
        if(father=='father'){
          if($scope.queryResultsFather[fld]==undefined){
            $scope.queryResultsFather[fld]=[];
        }
        var results = query ? $scope.queryResultsFather[fld].filter($scope.createFilterFor(query,fld)) : $scope.queryResultsFather[fld], deferred;
        deferred = $q.defer();
        $http.post(urlRoot+"&file=operations&kaction=retrieveAutoCompleteData&field="+fld+"&val="+encodeURIComponent(query[fld])+"&cachedmap="+encodeURIComponent(JSON.stringify($scope.cachedmap[fld])))
               .success(function(data, status) {
                    $scope.queryResultsFather[fld] = data.resdata;
                    if(data.cachedmap!=undefined){
                        $scope.cachedmap[fld]=data.cachedmap;
                    }
                    deferred.resolve($scope.queryResultsFather[fld]);
           }).error(function (data, status) {

        });


        return deferred.promise;
        }
        else{
        if($scope.queryResults[fld]==undefined){
            $scope.queryResults[fld]=[];
        }
        var results = query ? $scope.queryResults[fld].filter($scope.createFilterFor(query,fld)) : $scope.queryResults[fld], deferred;
        deferred = $q.defer();
        $http.post(urlRoot+"&file=operations&kaction=retrieveAutoCompleteData&field="+fld+"&val="+encodeURIComponent(query[fld])+"&cachedmap="+encodeURIComponent(JSON.stringify($scope.cachedmap[fld])))
               .success(function(data, status) {
                    $scope.queryResults[fld] = data.resdata;
                    if(data.cachedmap!=undefined){
                        $scope.cachedmap[fld]=data.cachedmap;
                    }
                    deferred.resolve($scope.queryResults[fld]);
           }).error(function (data, status) {

        });


        return deferred.promise;
        }
    };

    $scope.createFilterFor = function (query,fld) {
        var lowercaseQuery = angular.lowercase(query);
        return function filterFn(it) {
            if(it[fld]!=undefined){
              return (it[fld].indexOf(lowercaseQuery) === 0);
            }
            else{
                return false;
            }
        };
    };

    $scope.searchValueChange = function (text,fld,father) {
        /*if(father=='father'){
          if (text !== $scope.selectedItemFather[fld] && $scope.selectedItemFather[fld]!=='') {
          if($scope.cachedmap[fld]!=undefined){
          angular.forEach($scope.cachedmap[fld]['fieldlist'], function(value, key) {
            $scope.answersFather[value]='';
          });
         }
        }
        }
        else{
        if (text !== $scope.selectedItem[fld] && $scope.selectedItem[fld]!=='') {
          if($scope.cachedmap[fld]!=undefined){
          angular.forEach($scope.cachedmap[fld]['fieldlist'], function(value, key) {
            $scope.answers[value]='';
          });
         }
        }
      }*/   
    };

    $scope.selectedItemChange = function (item,fld,father) {

       if(father=='father'){
          if (item!=undefined && item!=null) {
	         if(typeof item == 'object'){
		          angular.forEach(item, function(value, key) {
		                $scope.answersFather[key]=value;
		                $scope.updateFather(key);
		          });
	         }
        }
        if($scope.selectedItemFather[fld]==null){
              $scope.isNotValidAutocomplete[fld]=true; 
            }
        else{
            $scope.isNotValidAutocomplete[fld]=false; 
        }
       }
       else{
          if (item!=undefined && item!=null) {
            if(typeof item == 'object'){
		          angular.forEach(item, function(value, key) {
		              $scope.answers[key]=value;
		              $scope.updateEntity(key);
		          });
            }    
        }
        if($scope.selectedItem[fld]==null){
              $scope.isNotValidAutocomplete[fld]=true;
            }
        else{
            $scope.isNotValidAutocomplete[fld]=false; 
        }
       }
      
    };



    $scope.selectedItemChangeBG = function(fld){
        $scope.selectedItem[fld]=$scope.answers[fld];
    }

    //end autocomplete
    
    $scope.executeAction = function (actionid,output_type) {
        
        
        var arr_sel = ''; 
        var values=$scope.answers;
        var outputType='html';
        var senddata = {
            'recordid': $scope.masterId,
            'origin_form': $scope.module
        };
        if($scope.module=='testacc' || $scope.module=='masteraccountform'){
            if($scope.answers['venditore_accounts']==='' || $scope.answers['venditore_accounts']===' ' || $scope.answers['venditore_accounts']===undefined
                    || $scope.answers['usercrm_accounts']==='' || $scope.answers['usercrm_accounts']===' ' || $scope.answers['usercrm_accounts']===undefined){
                $scope.answers['venditore_accounts'] = document.getElementById('LoggedUserName').value;
                $scope.answers['usercrm_accounts'] = document.getElementById('LoggedUserName').value;
                $scope.updateEntity('venditore_accounts');$scope.updateEntity('usercrm_accounts');
            }
            if($scope.answersFather['venditore_ma']==='' || $scope.answersFather['venditore_ma']===' ' || $scope.answersFather['venditore_ma']===undefined
                    || $scope.answersFather['user_ma']==='' || $scope.answersFather['user_ma']===' ' || $scope.answersFather['user_ma']===undefined){
                $scope.answersFather['venditore_ma'] = document.getElementById('LoggedUserName').value;
                $scope.answersFather['user_ma'] = document.getElementById('LoggedUserName').value;
                $scope.updateFather('venditore_ma');$scope.updateFather('user_ma');
            }
        }
        if(actionid==='910302' || actionid==='910302'){
            var mandatoryLogicMaster=$scope.mandatoryLogic($scope.map_field_dep,$scope.answers,'master');
            var moduleFather='masteraccountform';
            var ret=false;
            $http.get(urlRoot+"&file=operations&kaction=getFieldDependencyFather"+urlParams+'&moduleFather='+moduleFather).
                success(function(data, status) {
                    $scope.map_field_depFather = data.all_field_dep;
                    var mandatoryLogicFather=true;
                    ret=$scope.mandatoryLogic($scope.map_field_depFather,$scope.answersFather,'father');
                    mandatoryLogicFather= ret;
                    var pattern =$scope.isValidatedForm();

                if(mandatoryLogicMaster && mandatoryLogicFather && pattern){ 
            //        if (values != undefined) {
            //            values = angular.extend(values, senddata);
            //        } else {
            //            values = senddata;
            //        }
                    values = senddata;
                    if(output_type=='Link'){
                        var resp=runJSONAction(actionid, encodeURIComponent(JSON.stringify(values)), output_type); 
                        window.open(resp,'_self');
                    }
                    else if(output_type=='Confirm'){ 
                      try{ 
                            $scope.executingAction.state=true;
                            $timeout(function () {
                                    var resp=runJSONAction(actionid, encodeURIComponent(JSON.stringify(values)), outputType);
                                    $scope.piva_act=true;
                                    $scope.outputActionInfo=JSON.parse(resp);
                                    var focusrecord = $scope.outputActionInfo;
                                    //$scope.outputActionInfo.resperror===0 || $scope.outputActionInfo.resperror==="0"
                                    if(focusrecord.values!==undefined){
                                        for (var i=0;i<focusrecord.values.length;i++) {
                                            if(focusrecord.values[i]===false){
                                                $scope.outputActionInfo.values[i]='';
                                            }
                                        }
                                    }
                                    $scope.toggleRight();
                                }, 1000);
                        }
                        catch(e){
                            console.log(e);
                        }    
                    }
                    else{
                        var resp=runJSONAction(actionid, encodeURIComponent(JSON.stringify(values)), output_type); 
                    }
                    var widgets=new Array('DETAILVIEWBASICFORM');
                        FormService.getRelatedModules(widgets).then(function(response) {
                                $scope.relatedmodules['DETAILVIEWBASICFORM'] =response.data['DETAILVIEWBASICFORM'];
                    });
                }
                else{
                    alert('Prego controllare i valori dei campi');
                }
            });
    }
    else{
        values = senddata;
        if(output_type=='Link'){
            var resp=runJSONAction(actionid, encodeURIComponent(JSON.stringify(values)), output_type); 
            window.open(resp,'_self');
        }
        else if(output_type=='Confirm'){ 
          try{ 
                $scope.executingAction.state=true;
                $timeout(function () {
                        var resp=runJSONAction(actionid, encodeURIComponent(JSON.stringify(values)), outputType);
                        $scope.piva_act=true;
                        $scope.outputActionInfo=JSON.parse(resp);
                        var focusrecord = $scope.outputActionInfo;
                        //$scope.outputActionInfo.resperror===0 || $scope.outputActionInfo.resperror==="0"
                        if(focusrecord.values!==undefined){
                            for (var i=0;i<focusrecord.values.length;i++) {
                                if(focusrecord.values[i]===false){
                                    $scope.outputActionInfo.values[i]='';
                                }
                            }
                        }
                        $scope.toggleRight();
                    }, 1000);
            }
            catch(e){
                console.log(e);
            }    
        }
        else{
            var resp=runJSONAction(actionid, encodeURIComponent(JSON.stringify(values)), output_type); 
        }
        var widgets=new Array('DETAILVIEWBASICFORM');
            FormService.getRelatedModules(widgets).then(function(response) {
                    $scope.relatedmodules['DETAILVIEWBASICFORM'] =response.data['DETAILVIEWBASICFORM'];
        });
    }
    };
    
    $scope.tableRowExpanded = false;
    $scope.tableRowIndexCurrExpanded = "";
    $scope.tableRowIndexPrevExpanded = "";
    $scope.storeIdExpanded = "";
    $scope.dayDataCollapse = [false, false, false, false, false];

    $scope.dayDataCollapseFn = function () {
        for (var i = 0; $scope.relRecordList.length - 1; i += 1) {
            $scope.dayDataCollapse.append('false');
        }
    };
    $scope.selectTableRow = function (index, storeId) {
    if ($scope.dayDataCollapse === 'undefined') {
        $scope.dayDataCollapse = $scope.dayDataCollapseFn();
    } else {

        if ($scope.tableRowExpanded === true && $scope.tableRowIndexCurrExpanded === "" && $scope.storeIdExpanded === "") {
            $scope.tableRowIndexPrevExpanded = "";
            $scope.tableRowExpanded = false;
            $scope.tableRowIndexCurrExpanded = index;
            $scope.storeIdExpanded = storeId;
            $scope.dayDataCollapse[index] = true;
        } else if ($scope.tableRowExpanded === false) {
            if ($scope.tableRowIndexCurrExpanded === index && $scope.storeIdExpanded === storeId) {
                $scope.tableRowExpanded = true;
                $scope.tableRowIndexCurrExpanded = "";
                $scope.storeIdExpanded = "";
                $scope.dayDataCollapse[index] = false;
            } else {
                $scope.tableRowIndexPrevExpanded = $scope.tableRowIndexCurrExpanded;
                $scope.tableRowIndexCurrExpanded = index;
                $scope.storeIdExpanded = storeId;
                $scope.dayDataCollapse[$scope.tableRowIndexPrevExpanded] = false;
                $scope.dayDataCollapse[$scope.tableRowIndexCurrExpanded] = true;
            }
        }
    }
};
                
    $http.get(urlRoot+"&file=operations&kaction=getEntitiesSession&view=detail"+urlParams).
            success(function(data, status) {
              var getEntities=data;//JSON.parse(document.getElementById('ENTITIES').value);            
              $scope.ConfigEntities = getEntities.ConfigEntities;
              $scope.settings=getEntities.settings;
              $scope.masterModule=$scope.settings.onsave;
              $scope.masterId=$scope.ConfigEntities[$scope.masterModule]['savedid']; 
              $scope.DetailViewBlocks = getEntities.DetailViewBlocks;
              $scope.retrieveInfo($scope.masterId);
              //$scope.retrieveDetailRecords();
              $scope.gettingEntities=false;
              
    });
    
})
.controller('KanbanController', ['$scope','$rootScope','$http', 'BoardService','BoardManipulator','FormService', function ($scope,$rootScope,$http, BoardService,BoardManipulator,FormService) {

    $scope.recordID=FormService.getMasterRecord();
    $scope.fill_sortable=function () {
        $http.get('index.php?module=Utilities&action=UtilitiesAjax&kaction=retrieveProcessFlow&file=get_substatuses&id='+$scope.recordID).success(function(data) {
          $scope.arr=data;
          $scope.kanbanBoard = BoardService.kanbanBoard($scope.arr);  
             
        });
    };
    $scope.processflowselected=function(pfid,cpfid){ 
        document.getElementById('selectedprocessflow').setAttribute('value',pfid);
        document.getElementById('currentprocessflow').setAttribute('value',cpfid);
    }
    $scope.fill_sortable();            
    
    $scope.$on('handleBroadcast', function() {
        $http.get('index.php?module=Utilities&action=UtilitiesAjax&kaction=retrieveProcessFlow&file=get_substatuses&id='+$scope.recordID).success(function(data) {
            $scope.arr=data;
            $scope.kanbanBoard = BoardService.kanbanBoard($scope.arr);  
        });
    });  
    
    $scope.isCollapsed = true;
    $scope.kanbanSortOptions = {
        //restrict move across columns. move only within column.
        /*accept: function (sourceItemHandleScope, destSortableScope) {
         return sourceItemHandleScope.itemScope.sortableScope.$id !== destSortableScope.$id;
         },*/
        itemMoved: function (event) {
          //event.source.itemScope.modelValue.status = event.dest.sortableScope.$parent.column.name;
        },
        orderChanged: function (event) {
        },
        containment: '#board'
    };

    $scope.removeCard = function (column, card) {
        BoardService.removeCard($scope.kanbanBoard, column, card);
    };

    $scope.addNewCard = function (column) {
        BoardService.addNewCard($scope.kanbanBoard, column);
    };
}])
.service('BoardService', ['$injector','$modal','$mdDialog','BoardManipulator', function ($injector,$modal,$mdDialog, BoardManipulator) {

  return {
    removeCard: function (board, column, card) {
      if (confirm('Are You sure to Delete?')) {
        BoardManipulator.removeCardFromColumn(board, column, card);
      }
    },

    addNewCard: function (board, column) {
      var modalInstance =  $mdDialog.show({
          clickOutsideToClose: true,
          controller: 'NewCardController',
          templateUrl: 'Smarty/angular/sortable/views/partials/newCard.html',
          
          resolve: {
              column: function () {              
                return column;
              }
            }
        });
      modalInstance.then(function (cardDetails) {
          
          BoardManipulator.prepForBroadcast();
     
    //$scope.fill_sortable ();   
        if (cardDetails) {
          BoardManipulator.addCardToColumn(board, cardDetails.column, cardDetails.title, cardDetails.details,cardDetails.ptname,cardDetails.pfid,cardDetails.currentid);
        }
      });
    },
    kanbanBoard: function (board) {
      var kanbanBoard = new Board(board.name, board.numberOfColumns);
      angular.forEach(board.columns, function (column) {
        BoardManipulator.addColumn(kanbanBoard, column.name);
        angular.forEach(column.cards, function (card) {
          BoardManipulator.addCardToColumn(kanbanBoard, column, card.title, card.details,card.ptname,card.pfid,card.currentid);
        });
      });
      return kanbanBoard;
    },
    sprintBoard: function (board) {
      var sprintBoard = new Board(board.name, board.numberOfColumns);
      angular.forEach(board.columns, function (column) {
        BoardManipulator.addColumn(sprintBoard, column.name);
      });
      angular.forEach(board.backlogs, function (backlog) {
        BoardManipulator.addBacklog(sprintBoard, backlog.title);
        angular.forEach(backlog.phases, function (phase) {
          BoardManipulator.addPhaseToBacklog(sprintBoard, backlog.title, phase);
          angular.forEach(phase.cards, function (card) {
            BoardManipulator.addCardToBacklog(sprintBoard, backlog.title, phase.name, card);
          });
        });

      });
      return sprintBoard;
    }
  };
}])
.controller('NewCardController', ['$http','$rootScope','$injector','$scope','$timeout','$mdDialog', 'column' , function ($http,$rootScope,$injector,$scope,$timeout,$mdDialog, column) {
  
  function initScope(scope) {
    scope.columnName = column.name;
    scope.status= '';
    scope.title = '';
    scope.details = '';
    scope.dynamic = 50;
    var nextsubstatus=column.cards[0].ptname;
    var pfid=column.cards[0].pfid;
    
     console.log(column);
     $timeout(function () {
        }, 1000);
       $timeout(function () {
           function rendiconta(){
                $http.post('index.php?module=Utilities&action=UtilitiesAjax&kaction=rendiconta&file=get_substatuses&id='+document.getElementsByName('record').item(0).value+'&pfid='+pfid+'&next_sub='+nextsubstatus).success(function(data) {
                      location.reload();
                });
            };
            rendiconta();
            $mdDialog.close();
    }, 1000);
       
  }

  $scope.addNewCard = function () {
    if (!this.newCardForm.$valid) {
      return false;
    }
    $modalInstance.close({title: this.title, casepf: this.casepf , details: this.details});
  };

  $scope.close = function () {
    $modalInstance.close();
  };

  initScope($scope);

}])
.filter('getArrayElementById', function() {
    return function(input, idvalue, idprop) {
      if (idprop === undefined) idprop = 'id';
      var i = 0,
        len = input.length;
      for (; i < len; i++) {
        if (input[i][idprop] == idvalue) {
          return input[i];
        }
      }
      return null;
    };
  })
.filter('getPickListDep', function() {
return function(input,map_field_dep,columns,MAP_PCKLIST_TARGET,moduleData) {
  var i = 0,
    len = input.length;
  var records = new Array();
  for (; i < len; i++) {
      if(MAP_PCKLIST_TARGET.indexOf(columns)!==-1){
          angular.forEach(map_field_dep, function(value, key) {
              if(value.target_picklist.indexOf(columns)!==-1){
                  var conditionResp = '';
                  angular.forEach(value.respfield, function(resp_val, resp_val_key) {
                        var resp_value = value.respvalue_portal[resp_val_key];
                        var comparison = value.comparison[resp_val_key];
                        if (resp_val_key !== 0 ){
                            if (comparison === 'empty' || comparison === 'notempty')
                                conditionResp += ' || ';
                            else
                                conditionResp += ' && ';
                        }
                        if (comparison === 'empty')
                          conditionResp += (moduleData[resp_val] === '' || moduleData[resp_val] === undefined);
                        else if (comparison === 'notempty')
                          conditionResp += (moduleData[resp_val] !== '' && moduleData[resp_val] !== undefined);
                        else{
                          conditionResp += (resp_value.indexOf(moduleData[resp_val])!= -1 && moduleData[resp_val]!=undefined);
                        }
                  });
                  if ( eval(conditionResp) ) 
                  {
                      angular.forEach(value.target_picklist_values[columns], function(targ_val, targ_key) {
                         if(input[i][0]==targ_val){
                              records.push(input[i]);
                          }       
                      });
                  }
              }
        });
      }
      else{
          records.push(input[i]);
      }
  }
  return records;
};
})
.directive('input', [function() {
    return {
        restrict: 'E',
        require: '?ngModel',
        link: function(scope, element, attrs, ngModel) {
            if (
                   'undefined' !== typeof attrs.type
                && 'number' === attrs.type
                && ngModel
            ) {
                ngModel.$formatters.push(function(modelValue) {
                    return Number(modelValue);
                });

                ngModel.$parsers.push(function(viewValue) {
                    return Number(viewValue);
                });
            }
        }
    }
}])
.directive('fileUpload', function () {
    return {
        scope: true,        //create a new scope
        link: function (scope, el, attrs) {
            el.bind('change', function (event) {
                var files = event.target.files;
                //iterate files since 'multiple' may be specified on the element
                for (var i = 0;i<files.length;i++) {
                    //emit event upward
                    scope.$emit("fileSelected", { file: files[i] });
                }                                       
            });
        }
    };
  });
