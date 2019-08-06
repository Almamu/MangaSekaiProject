'use strict';

angular.module ('mangasekai', [
    'ngAnimate',
    'ngRoute',
    'ngStorage',
    'angularLazyImg',
    'ngImageAppear',
    'angularMoment',
    'hc.marked',
    'mangasekai.loader',
    'mangasekai.login',
    'mangasekai.dashboard',
    'mangasekai.viewer',
    'mangasekai.manage'
])
.config (['$routeProvider', 'markedProvider', function ($routeProvider, markedProvider)
{
    $routeProvider.otherwise ({redirectTo: '/login/'});
    markedProvider.setOptions ({gfm: true, breaks: true});
}])
.service ('API', [function ()
{
    return function (endpoint)
    {
        return '/api/index.php/' + endpoint.trim () + '/';
    }
}]);
