'use strict';

angular.module ('mangasekai', [
    'ngRoute',
    'ngStorage',
    'angularMoment',
    'mangasekai.loader',
    'mangasekai.login',
    'mangasekai.dashboard',
    'mangasekai.viewer'
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
