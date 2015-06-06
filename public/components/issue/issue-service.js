angular.module('iClinic.issueService',['iClinic.requestService'])
.service('IssueService',['$q','RequestService',function($q,Request){

    this.issues = [];
    this.loaded = false;
    var scope = this;

    this.submit = function(issue)
    {
        var deferred = $q.defer();
        Request.get('issue',{'description':issue.description}).then(function(data){
            scope.issues.unshift(data);
            deferred.resolve(data);
        },function(error){
            deferred.reject(error);
        });

        return deferred.promise;
    }

    this.all = function()
    {
        var deferred = $q.defer();
        if(!this.loaded){
            this.load().then(function(data){
                deferred.resolve(scope.issues);
            });
        }
        else {
            deferred.resolve(scope.issues);
        }
        return deferred.promise;
    }

    this.voteUp = function(issue)
    {
        Request.get('issue-vote',{'id':issue.id}).then(function(data){
            issue.votes = data.votes;
        });
    }



    this.load = function()
    {
        var deferred = $q.defer();
        Request.get('issues',{}).then(function(data){
            scope.issues = data;
            scope.loaded = true;
            deferred.resolve(data);
        });
        return deferred.promise;
    }


}]);
