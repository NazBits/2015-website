angular.module('iClinic.antivirus',['iClinic.antivirusService'])
.controller('AntivirusController',['$scope','AntivirusService',function($scope, AVService){

    $scope.loading = true;
    AVService.all().then(function(data){
        $scope.loading = false;
        $scope.avs = data;
    });

    $scope.vote = function(av){
        AVService.voteUp(av);
    };

}]);
