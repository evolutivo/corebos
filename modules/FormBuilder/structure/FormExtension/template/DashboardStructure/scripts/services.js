/* Form Service */
'use strict';
angular.module('Form.services', [])
    .factory('FormService', function($http) {

        var record = '';
        var module = '';
        var masterModule = '';
        var onOpenView='';
        var urlRoot='';
        var urlParams='';

        var FormService = {};
        FormService.setConfigured = function(recordParam,masterModuleParam,onOpenView,module) {
            record=recordParam;
            masterModule=masterModuleParam;
            onOpenView=onOpenView;
            module = module;
            urlRoot="index.php?module="+module+"&action="+module+"Ajax";
            urlParams="&masterRecord="+record+"&masterModule="+masterModule;
        };
        
        FormService.getMasterRecord= function() {
            return record;
        };
        
        FormService.getMasterModule= function() {
            return masterModule;
        };
        
        FormService.getRelatedModules= function(widgetsType) {
            return $http.post(urlRoot+"&file=operations&kaction=getRelatedModules"+urlParams
                    ,{data: widgetsType});
        };
        
        FormService.doGetRelatedRecords= function(ngblockid,limit,where) {
            var relParams='&ngblockid='+ngblockid+'&limit='+limit;
            return $http.post(urlRoot+"&file=operations&kaction=doGetRelatedRecords"+urlParams+relParams
                    ,{data: where});
        };
        
        return FormService;
    });
