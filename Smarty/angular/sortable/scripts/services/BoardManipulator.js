/*jshint undef: false, unused: false, indent: 2*/
/*global angular: false */


'use strict';

angular.module('demoApp').factory('BoardManipulator', function ($http,$rootScope) {
    var new_data='';
    
    function broadcastItem() {
         $rootScope.$broadcast('handleBroadcast');
    }
  return {
      
    new_data:new_data,
    
    prepForBroadcast :function() {
        //$http.get('index.php?module=Project&action=ProjectAjax&file=gettaskss').success(function(data) {  
          //  alert(data);
        // new_data= data;
         broadcastItem();
      //});
        
    },
    
    broadcastItem : broadcastItem,
    
      addColumn: function (board, columnName) {
      board.columns.push(new Column(columnName));
    },

    addCardToColumn: function (board, column, cardTitle, details,ptname,pfid,currentid) {
      angular.forEach(board.columns, function (col) {
        if (col.name === column.name) {
          col.cards.push(new Card(cardTitle, column.casepf, details, ptname,pfid,currentid));
        }
      });
    },
    removeCardFromColumn: function (board, column, card) {
      angular.forEach(board.columns, function (col) {
        if (col.name === column.name) {
          col.cards.splice(col.cards.indexOf(card), 1);
        }
      });
    },
    addBacklog: function (board, backlogName) {
      board.backlogs.push(new Backlog(backlogName));
    },

    addPhaseToBacklog: function (board, backlogName, phase) {
      angular.forEach(board.backlogs, function (backlog) {
        if (backlog.name === backlogName) {
          backlog.phases.push(new Phase(phase.name));
        }
      });
    },

    addCardToBacklog: function (board, backlogName, phaseName, task) {
      angular.forEach(board.backlogs, function (backlog) {
        if (backlog.name === backlogName) {
          angular.forEach(backlog.phases, function (phase) {
            if (phase.name === phaseName) {
              phase.cards.push(new Card(task.title, task.status, task.details, task.ptname, task.pfid,task.currentid));
            }
          });
        }
      });
    }
    
    
  };
});
