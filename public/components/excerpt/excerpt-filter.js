angular.module('iClinic.excerptFilter',[])
.filter('excerpt', function(){
    return function(text)
    {
        return s(text).prune(210).value();
    }
});
