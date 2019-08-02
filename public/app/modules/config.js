'use strict';

angular.module ('mangasekai', [
    'ngAnimate',
    'ngRoute',
    'ngStorage',
    'angularLazyImg',
    'ngImageAppear',
    'angularMoment',
    'mangasekai.loader',
    'mangasekai.login',
    'mangasekai.dashboard',
    'mangasekai.viewer',
    'mangasekai.manage'
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
