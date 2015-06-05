angular.module('iClinic',['ngRoute','iClinic.new','iClinic.issues','iClinic.antivirus','iClinic.excerptFilter'])
.config(['$routeProvider',function($routeProvider){
    $routeProvider
        .when('/new',{
            controller:'NewIssueController',
            templateUrl:'new/new.html'
        })
        .when('/issues',{
            controller:'IssuesController',
            templateUrl:'issues/issues.html'
        })
        .when('/antivirus',{
            controller:'AntivirusController',
            templateUrl:'antivirus/antivirus.html'
        })
        .otherwise({
            redirectTo:'/issues'
        });
}])
.controller('RootController',['$scope','$location', function($scope,$location){
    $scope.page = $location.path();
}]);
