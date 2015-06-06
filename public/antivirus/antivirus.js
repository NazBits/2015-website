angular.module('iClinic.antivirus',['iClinic.antivirusService'])
.controller('AntivirusController',['$scope','AntivirusService',function($scope, AVService){

    $scope.loading = true;
    AVService.all().then(function(data){
        $scope.loading = false;
        $scope.avs = data;
        console.log($scope.avs);
    });

    $scope.vote = function(av){
        console.log('av',av);
        AVService.voteUp(av);
    };

}]);
