
    <link href="modules/ElasticSearch/assets/css/app.min.css" rel="stylesheet">
    <link href="modules/ElasticSearch/assets/css/c3-0.4.7.min.css" rel="stylesheet">

<div ng-app="demoApp">

{*<div class="container" ng-show="alerts" ng-controller="NotificationCtrl">
    <div class="row" ng-show="alerts">
        <div class="alert alert-danger" ng-repeat="alert in alerts">{literal}{{alert.message}}{/literal}</div>
    </div>
</div>*}

<div class="container">
     <navbar >
  </navbar>
    <div class="row" ng-controller="SearchCtrl">
        <div class="row" ng-init="init()">
    <div class="col-md-12">
        <div class="alert alert-info">
            <p>Using this page you can query your indexes like a search box on a website would do. First you need to
                configure the title and description of your search results. You can store and load a search. You can also do an advanced search, this way you can select fields and query based on those fields.</p>
            <button class="btn btn-link" ng-click="isCollapsed = !isCollapsed"><span
                    class="glyphicon glyphicon-cog"></span> Configure fields to show  <span
                    class="glyphicon glyphicon-chevron-down" ng-show="isCollapsed"></span><span class="glyphicon glyphicon-chevron-left" ng-hide="isCollapsed"></span></button>
            <div collapse="isCollapsed">
                <form role="form">
                    <div class="form-group">
                        <label>Title</label>
                        <select class="form-control" ng-model="configure.title"
                                ng-options="key as value.forPrint for (key,value) in fields">
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <select class="form-control" ng-model="configure.description"
                                ng-options="key as value.forPrint for (key,value) in fields">
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12" ng-show="configError">
        <div class="alert alert-danger">{literal}{{configError}}{/literal}</div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <label class="checkbox">
            <input class="checkbox" type="checkbox" ng-model="search.doAdvanced">Do advanced search
        </label>

        <div>
            <span>Aggregations</span>
        <button class="btn btn-info btn-sm" ng-click="openDialog()" title="Add new Aggregation">
            <span class="glyphicon glyphicon-plus-sign glyphicon-white"></span>
        </button>
        </div>
        <div ng-repeat="aggregation in search.aggs">{literal}{{aggregation.name}}: {{aggregation.field}} ({{aggregation.aggsType}}){/literal}
            <button class="btn btn-link" ng-click="removeAggregateField(aggregation.name)" title="Remove aggregate Field">
                <span class="glyphicon glyphicon-remove"></span>
            </button>
        </div>

    </div>
    <div class="col-md-8" ng-hide="search.doAdvanced">
        <form class="form-inline">
            <div class="col-xs-4">
                <input class="form-control" type="search" ng-model="search.simple" autofocus="true"
                       placeholder="Type your search string"/>
            </div>
            <button class="btn btn-primary" ng-click="restartSearch()"><span class="glyphicon glyphicon-search glyphicon-white"></span> Search</button>
            <label class="checkbox">
                <input class="checkbox" type="checkbox" ng-model="search.details"> Show details
            </label>
            <button class="btn btn-sm btn-default" ng-click="saveQuery()"><span class="glyphicon glyphicon-share"></span> save</button>
            <button class="btn btn-sm btn-default" ng-click="loadQuery()"><span class="glyphicon glyphicon-download-alt"></span> load</button>
        </form>
    </div>
    <div class="col-md-8" ng-show="search.doAdvanced">
        <form class="form-inline">
            <div>
                <label>Choose field</label>
                <select class="form-control" ng-model="search.advanced.newField"
                        ng-options="key as value.forPrint for (key,value) in fields">
                    <option value="">-- chose field --</option>
                </select>
                <label>Enter text</label>
                <div class="col-xs-4">
                    <input class="form-control" type="search" ng-model="search.advanced.newText" autofocus="true"
                           placeholder="Type your search string"/>
                </div>
                <button class="btn btn-info" ng-click="addSearchField()"><span class="glyphicon glyphicon-plus-sign glyphicon-white"></span> Add</button>
            </div>
            <div>
                <div ng-repeat="searchField in search.advanced.searchFields">
                   {literal} {{searchField.field}} = {{searchField.text}} {/literal}<button class="btn btn-link" ng-click="removeSearchField($index)"><span
                        class="glyphicon glyphicon-remove"></span></button>
                </div>
                <br/>

                <div>
                    <button class="btn btn-primary" ng-click="restartSearch()"><span class="glyphicon glyphicon-search glyphicon-white"></span> Search</button>
                    <button class="btn btn-sm btn-default" ng-click="saveQuery()"><span class="glyphicon glyphicon-share"></span> save</button>
                    <button class="btn btn-sm btn-default" ng-click="loadQuery()"><span class="glyphicon glyphicon-download-alt"></span> load</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="row" ng-show="metaResults.failedShards">
    <div class="col-md-12">
        <h3>Errors</h3>
        <div class="alert alert-danger" ng-repeat="error in metaResults.errors">
           {literal} {{error}}{/literal}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <h3>Results</h3>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div ng-repeat="(key,value) in aggs">
            <div><strong>{literal}{{key}}{/literal}</strong></div>
            <div ng-repeat="bucket in value.buckets">
                <div ng-show="search.aggs[key].aggsType === 'term'">
                    <button class="btn btn-link" ng-show="checkSelectedAggregate(key,bucket.key)" ng-click="removeFilter(key,bucket.key)">{literal}{{bucket.key}} ({{bucket.doc_count}}){/literal} <span class="glyphicon glyphicon-remove"></span></button>
                    <button ng-hide="checkSelectedAggregate(key,bucket.key)" class="btn btn-link"
                          ng-click="addFilter(search.aggs[key].name,bucket.key)">{literal}{{bucket.key}} ({{bucket.doc_count}}){/literal}</button>
                </div>
                <div ng-show="search.aggs[key].aggsType === 'histogram'">
                    <button class="btn btn-link" ng-show="checkSelectedAggregate(key,bucket.key)" ng-click="removeFilter(key,bucket.key)">{literal}{{bucket.key}} ({{bucket.doc_count}}){/literal} <span class="glyphicon glyphicon-remove"></span></button>
                    <button ng-hide="checkSelectedAggregate(key,bucket.key)" class="btn btn-link"
                          ng-click="addFilter(key,bucket.key)">{literal}{{bucket.key}} ({{bucket.doc_count}}){/literal}</button>
                </div>
                <div ng-show="search.aggs[key].aggsType === 'range'">
                    <button class="btn btn-link" ng-show="checkSelectedRangeAggregate(key,bucket.from,bucket.to)" ng-click="removeRangeFilter(key,bucket.from,bucket.to)">{literal}{{bucket.from}}-{{bucket.to}} ({{bucket.doc_count}}){/literal} <span class="glyphicon glyphicon-remove"></span></button>
                    <button ng-hide="checkSelectedRangeAggregate(key,bucket.from,bucket.to)" class="btn btn-link"
                          ng-click="addRangeFilter(key,bucket.from,bucket.to)">{literal}{{bucket.from}}-{{bucket.to}} ({{bucket.doc_count}}){/literal}</button>
                </div>
                <div ng-show="search.aggs[key].aggsType === 'datehistogram'">
                    <button class="btn btn-link" ng-show="checkSelectedAggregate(key,bucket.key)"  ng-click="removeFilter(key,bucket.key)">{literal} {{bucket.key_as_string | date:'medium'}} ({{bucket.doc_count}}){/literal} <span
                    class="glyphicon glyphicon-remove"></span></button>
                    <button ng-hide="checkSelectedAggregate(key,bucket.key)" class="btn btn-mini btn-link"
                            ng-click="addFilter(key,bucket.key)">{literal}{{bucket.key_as_string | date:'medium'}}
                        ({{bucket.doc_count}}){/literal}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8" ng-show="totalItems>0">
        <pagination boundary-links="true" total-items="totalItems" ng-model="currentPage" class="pagination-sm"
                    max-size="maxSize" ng-change="changePage()" previous-text="&lsaquo;" next-text="&rsaquo;"
                    first-text="&laquo;" last-text="&raquo;"></pagination>

        <div class="well" ng-show="tokensPerField">
            <table>
                <thead>
                <tr>
                    <th>Field</th>
                    <th>Value</th>
                    <th>Terms</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="field in tokensPerField">
                    <td>{literal}{{field.field}}{/literal}</td>
                    <td>{literal}{{field.value}}{/literal}</td>
                    <td><span ng-repeat="token in field.tokens">&quot;{literal}{{token.token}}{/literal}&quot;&nbsp;</span></td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="3">
                        <button class="btn btn-default" ng-click="tokensPerField=undefined">Close</button>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>

        <div ng-repeat="result in results.hits">
            <div><a ng-click="showAnalysis(result._index,result._type,result._id)">Show terms</a>&nbsp;<span
                    class="text-info">{literal}{{result.fields[configure.title]}}{/literal}</span><span class="text-warning"
                                                                                        ng-show="search.details">    index: {literal}{{result._index}} - score: {{result._score}} - type: {{result._type}}{/literal}</span>
            </div>
            <div class="well-small">
               {literal} {{result.fields[configure.description]}}{/literal}
            </div>
        </div>
        <br/>
        <pre>Page:{literal} {{currentPage}} / {{numPages}}{/literal}</pre>
    </div>
</div>

    </div>
</div>
</div>
{*</div>*}
<!--/.fluid-container-->

<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<script src="modules/ElasticSearch/assets/js/elasticsearch-gui.min.js"></script>
