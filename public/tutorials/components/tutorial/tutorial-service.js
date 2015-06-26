angular.module('tutorials.tutorialService',[])
.service('TutorialService',['$http', '$q',function($http, $q){
    this.loaded = false;
    this.loading = false;
    this.haltedPromises = [];
    this.tutorials = [];
    this.selected = null;

    var scope = this;
    var baseUrl = "./data/";

    this.url = function(el){
        return baseUrl + el;
    };

    this.jsonUrl = function(key)
    {
        return this.url(key + ".json");
    };



    this.all = function()
    {
        var deferred = $q.defer();
        if(this.loaded){
            deferred.resolve(this.tutorials);
        }
        else {
            var found = 0;
            var total = 0;
            if(this.loading){
                //a request is already pending, avoid concurrent Requests
                //to the same resource.
                //resolve these promises when the current request returns
                var halted = $q.defer();
                this.haltedPromises.push(halted);
                return halted.promise;
            }
            this.loading = true;
            $http.get(this.url("_index.json")).success(function(index){
                total = index.length;
                _.each(index, function(key){
                    var tutorial;
                    $http.get(scope.jsonUrl(key)).success(function(data){
                        tutorial = data;
                        tutorial.id = key;
                        scope.tutorials.push(tutorial);

                        ++found;
                        if(found == total){
                            //loaded all tutorials
                            scope.loaded = true;
                            deferred.resolve(scope.tutorials);
                            //resolve all halted promises
                            _.each(scope.haltedPromises, function(halted){
                                halted.resolve(scope.tutorials);
                            });
                        }
                    });
                });
            });
        }
        return deferred.promise;
    };

    this.byId = function(id)
    {
        return _.findWhere(this.tutorials, {id:id});
    }

    this.select = function(tutorial)
    {
        this.selected = tutorial;
    }

}]);
