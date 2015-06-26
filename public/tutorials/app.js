angular.module('tutorials',['ngRoute','tutorials.tutorialService','tutorials.tutorial'])
.config(['$routeProvider',function($routeProvider){
    $routeProvider
        .when('/:tutorialId',{
            controller:"TutorialController",
            templateUrl:'tutorial/tutorial.html'
        })
}])
.controller('MainController',['$scope','TutorialService','$routeParams',function($scope,Tutorials, $routeParams){

    Tutorials.all().then(function(tutorials){
        $scope.tutorials = tutorials;
    });

    $scope.selectedTutorial = null;
    $scope.selectTutorial = function(tutorial)
    {
        $scope.selectedTutorial = tutoral;
    }
    $scope.isSelectedTutorial = function(tutorial)
    {
        return tutorial == Tutorials.selected;
    }

}]);
