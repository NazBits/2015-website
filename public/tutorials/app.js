angular.module('tutorials',['ngRoute','tutorials.tutorialService','tutorials.tutorial'])
.config(['$routeProvider',function($routeProvider){
    $routeProvider
        .when('/:tutorialId',{
            controller:"TutorialController",
            templateUrl:'tutorial/tutorial.html'
        })
}])
.controller('MainController',['$scope','TutorialService',function($scope,TutorialService){
    $scope.tutorials = TutorialService.tutorials;
}]);
