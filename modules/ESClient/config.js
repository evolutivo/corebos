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
 *  Module       : ESClient
 *  Version      : 5.4.0
 *  Author       : OpenCubed
 *************************************************************************************************/
var Config = {
   'CLUSTER_URL':'http://193.182.16.34:9200', 
   'THEME':'Pepper Grinder',
   'SEARCH_TYPE':'query_then_fetch',
   'FROM':0,
   'DEFAULT_OPERATOR':'OR',

   // result size
   'SIZE':50,   
   
   //Control lowercasing of search terms when running wildcard searches on non-analyzed fields
   'EXPAND_LOWERCASE_TERMS':true,
   
   //Control wildcard and prefix queries to be analyzed or not
   'ANALYZE_WILDCARD':false,
   
   //Query format to use while searching or delete by query. defaults to Lucene
   'USE_LUCENE_QUERY_TYPE':true,
   
   //Set to true if you want capability of dropping the index or mapping.
   'ENABLE_INDEX_DROP':false,
   
   //Enable displaying JSON results. This will slow down the response time. Result size recommended is 10 results
   'SHOW_JSON_RESULS':false,
   
   //Enable inspecting the mapping for index type
   'SHOW_MAPPING_INFO':false
}
