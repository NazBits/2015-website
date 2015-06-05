angular.module('iClinic.new',['iClinic.issueService'])
.controller('NewIssueController', ['$scope', '$location','IssueService', function($scope,$location,IssueService){

    $scope.description = "";

    $scope.submitIssue = function()
    {
        var issue = {'description':$scope.description};
        IssueService.submit(issue).then(function(data){
            $scope.description = "";
            $location.path('/issues');
        });

    }

}]);
