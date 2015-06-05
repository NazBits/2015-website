angular.module('iClinic.requestService',[])
.service('RequestService',['$http','$q',function($http, $q){

    var URL = "handle.php";

    this.handleResponse = function(promise){
        var deferred = $q.defer();
        promise.success(function(response){
            if(response.success){
                deferred.resolve(response.data);
            }
            else deferred.reject(response);
        }).error(function(response){
            deferred.reject(response.error);
        })

        return deferred.promise;
    }

    this.post = function(request, data){
        promise = $http.post(URL + "?request="+request, data);
        return this.handleResponse(promise);
    }

    this.get = function(request, data){
        var url = URL + "?request="+request;
        for(var key in data){
            if(data.hasOwnProperty(key)){
                url += "&" + key + "=" + data[key];
            }
        }
        promise = $http.get(url);
        return this.handleResponse(promise);
    }

}]);
