/*jshint undef: false, unused: false, indent: 2*/
/*global angular: false */

'use strict';

angular.module('demoApp').service('BoardDataFactory', function () {
//alert(objectValue);
  return {
//    kanban: {
//      "name": "Kanban Board",
//      "numberOfColumns": 2,
//      "columns": [
//            {"name": "Ideas", "cards": [
//              {"title": "Come up with a POC for new ProjectCome up with a POC for new ProjectCome up with a POC for new ProjectCome up with a POC for new ProjectCome up with a POC for new ProjectCome up with a POC for new Project"
//                  ,"status": "test1"}
//            ]},
//            {"name": "Not started", "cards": [
//              {"title": "Explore new IDE for Development",
//                "details": "Testing Card Details","status": "test1"}
//            ]},
//        {"name": "Ideas2", "cards": [
//              {"title": "Come up with a POC for new Project"
//                  ,"status": "test1"}
//            ]},
//            {"name": "Not started2", "cards": [
//              {"title": "Explore new IDE for Development",
//                "details": "Testing Card Details","status": "test1"}
//            ]}
//      ]}
////    ,
    kanban2: {
      "name": "Kanban Board",
      "numberOfColumns": 2,
      "columns": [
          {"opsion":[
            {"name": "Ideas", "cards": [
              {"title": "Come up with a POC for new Project"
                  ,"status": "test1"}
            ]},
            {"name": "Not started", "cards": [
              {"title": "Explore new IDE for Development",
                "details": "Testing Card Details","status": "test1"}
            ]}
      ]},
      {"opsion":[
            {"name": "Ideas", "cards": [
              {"title": "Come up with a POC for new Project"
                  ,"status": "test1"}
            ]},
            {"name": "Not started", "cards": [
              {"title": "Explore new IDE for Development",
                "details": "Testing Card Details","status": "test1"}
            ]}
      ]}
      ]
    },
    sprint: {
      "name": "Sprint Board",
      "numberOfColumns": 3,
      "columns": [
        {"name": "Not started"},
        {"name": "In progress"},
        {"name": "Done"}
      ],
      "backlogs": [
        {"title": "Come up with a POC for new Project",
          "details": "backlog id 1",
          "phases": [
            {"name": "Not started",
              "cards": [
                {"title": "Explore new IDE for Development",
                  "details": "Testing Card Details",
                  "status": "Not started"},
                {"title": "Get new resource for new Project",
                  "details": "Testing Card Details",
                  "status": "Not started"}
              ]},
            {"name": "In progress",
              "cards": [
                {"title": "Develop ui for tracker module",
                  "details": "Testing Card Details",
                  "status": "In progress"},
                {"title": "Develop backend for plan module",
                  "details": "Testing Card Details",
                  "status": "In progress"},
                {"title": "Test user module",
                  "details": "Testing Card Details",
                  "status": "In progress"}
              ]},
            {"name": "Done",
              "cards": [
                {"title": "End to End Testing for user group module",
                  "details": "Testing Card Details",
                  "status": "Done"},
                {"title": "CI for user module",
                  "details": "Testing Card Details",
                  "status": "Done"}
              ]}
          ]
        },
        {
          "title": "Design new framework for reporting module",
          "details": "backlog id 2",
          "phases": [
            {"name": "Not started",
              "cards": [
                {"title": "Explore new Framework",
                  "details": "Testing Card Details",
                  "status": "Not started"},
                {"title": "Get new Testing License",
                  "details": "Testing Card Details",
                  "status": "Not started"}
              ]},
            {"name": "In progress",
              "cards": [
                {"title": "Develop ui using Angular",
                  "details": "Testing Card Details",
                  "status": "In progress"},
                {"title": "Develop backend with NodeJS",
                  "details": "Testing Card Details",
                  "status": "In progress"}
              ]},
            {"name": "Done",
              "cards": [
                {"title": "Explore High charts",
                  "details": "Testing Card Details",
                  "status": "Done"}
              ]}
          ]
        }
      ]
    }
  };
});


