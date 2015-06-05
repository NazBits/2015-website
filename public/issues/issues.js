angular.module('iClinic.issues',['ui.bootstrap','iClinic.issueService'])
.controller('IssuesController',['$scope','$filter','$modal','IssueService',function($scope, $filter, $modal,IssueService){


    $scope.loading = true;

    IssueService.all().then(function(data)
    {
        $scope.issues = data;
        $scope.loading = false;
    });

    $scope.vote = function(issue)
    {
        IssueService.voteUp(issue);
    }

    $scope.open = function(issue)
    {
        var modal = $modal.open({
            animation:true,
            templateUrl:'issues/issue-modal.html',
            controller:'IssueModalController',
            scope:$scope,
            backdrop:true,
            resolve:{
                issue:function(){
                    return issue;
                }
            }

        })
    }

}])
.controller('IssueModalController',['$scope','$modalInstance','issue',function($scope,$modalInstance, issue){
    $scope.issue = issue;
}]);
