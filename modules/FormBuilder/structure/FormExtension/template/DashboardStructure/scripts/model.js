/*jshint undef: false, unused: false, indent: 2*/
/*global angular: false */

'use strict';
function Board(name, numberOfColumns) {
  return {
    name: name,
    numberOfColumns: numberOfColumns,
    columns: [],
    backlogs: []
  };
}

function Column(name) {
  return {
    name: name,
    cards: []
  };
}

function Backlog(name) {
  return {
    name: name,
    phases: []
  };
}

function Phase(name) {
  return {
    name: name,
    cards: []
  };
}

function Card(title, status, details, ptname,pfid,currentid) {
  this.title = title;
  this.status = status;
  this.details = details;
  this.ptname=ptname;
  this.pfid=pfid;
  this.currentid=currentid;
  return this;
}
