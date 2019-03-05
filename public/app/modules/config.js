'use strict';

angular.module ('mangasekai', [
    'ngRoute',
    'ngStorage',
    'mangasekai.login',
    'mangasekai.dashboard'
])
.config (['$routeProvider', function ($routeProvider)
{
    $routeProvider.otherwise ({redirectTo: '/login/'});
}])
.service ('API', [function ()
{
    return function (endpoint)
    {
        return '/api/index.php/' + endpoint.trim () + '/';
    }
}]);