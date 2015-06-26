angular.module('tutorials.tutorial',['tutorials.tutorialService'])
.controller('TutorialController',['$scope','TutorialService','$routeParams', function($scope, Tutorials, $routeParams){
    Tutorials.all().then(function(tutorials){
        $scope.tutorial = Tutorials.byId($routeParams.tutorialId);
        $scope.selectedLesson = $scope.tutorial.lessons[0];
    });

    $scope.page = 'info';
    $scope.view = function(page)
    {
        $scope.page = page;
    };


    $scope.select = function(lesson)
    {
        $scope.selectedLesson = lesson;
    }

    $scope.isSelected = function(lesson)
    {
        return lesson == $scope.selectedLesson;
    }

    $scope.lessonPage = 'topics';
    

}]);
