angular.module('iClinic.antivirusService',['iClinic.requestService'])
.service('AntivirusService',['$q','RequestService',function($q, Request){

    this.antiviruses = [];
    this.loaded = false;

    var scope = this;

    this.voteUp = function(av)
    {
        var deferred = $q.defer();
        Request.get('av-vote',{'id':av.id}).then(function(data){
            av.votes = data.votes;
        });
        return deferred.promise;
    };


    this.all = function()
    {
        var deferred = $q.defer();
        if(!this.loaded){
            this.load().then(function(data){
                deferred.resolve(scope.antiviruses);
            });
        }
        else {
            deferred.resolve(scope.antiviruses);
        }

        return deferred.promise;
    };

    this.load = function()
    {
        var deferred = $q.defer();
        Request.get('avs',{}).then(function(data){
            scope.antiviruses = data;
            scope.loaded = true;
            deferred.resolve(data);
        });
        return deferred.promise;
    };

    this.load();

}]);
