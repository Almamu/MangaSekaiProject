'use strict';

angular.module ('mangasekai.login', [
    'ngStorage'
])
.config (['$routeProvider', '$httpProvider', function ($routeProvider, $httpProvider)
{
    $routeProvider.when (
        '/login', {
            controller: 'LoginController',
            templateUrl: '/app/modules/login/login.html'
        }
    );

    $httpProvider.interceptors.push ('authenticationCheck');
}])
.factory ('authenticationCheck', [
'$q', '$location',
function ($q, $location)
{
    return {
        response: function (config)
        {
            if (config.headers ('Content-Type') == 'application/json' && 'data' in config && 'code' in config.data && config.data.code == 100)
                $location.path ('/login/');

            return config || $q.when (config);
        },
        responseError: function (rejection)
        {
            if (rejection.headers ('Content-Type') == 'application/json' && 'data' in rejection && 'code' in rejection.data && rejection.data.code == 100)
                $location.path ('/login/');

            return $q.reject (rejection);
        }
    };
}])
.run (['AuthenticationService', function (AuthenticationService)
{
    AuthenticationService.init ();
}])
.service ('AuthenticationService', ['$http', '$localStorage', '$q', 'API', 'moment', function ($http, $localStorage, $q, API, moment)
{
    function setupHttpHeaders ()
    {
        $http.defaults.headers.common ['Authorization'] = 'Bearer ' + $localStorage.session.token;
    }

    return {
        init: function ()
        {
            if (!this.hasSession ())
                return;

            setupHttpHeaders ();
        },
        login: function (username, password)
        {
            let authorizationHeader = 'Basic ' + btoa (encodeURIComponent(username) + ':' + encodeURIComponent(password));

            return $http.get (API ('login'), {headers: {'Authorization': authorizationHeader}}).then (
                function (result)
                {
                    if (!'token' in result.data)
                        return $q.reject ();

                    // save token
                    $localStorage.session = result.data;
                    // then setup the needed headers
                    setupHttpHeaders ();
                    // finally continue the callback hierarchy
                    return result.data.token;
                },
                function ()
                {
                    return $q.reject ();
                }
            )
        },
        hasSession: function ()
        {
            if (! ('session' in $localStorage))
                return false;

            if (!('token' in $localStorage.session))
                return false;

            if (!('expire_time' in $localStorage.session))
                return false;

            return moment.utc ().isBefore (moment.unix ($localStorage.session.expire_time));
        },
        getToken: function ()
        {
            if (this.hasSession () == true)
            {
                return $localStorage.session.token;
            }

            return '';
        }
    }
}])
.controller ('LoginController', ['$location', '$scope', 'AuthenticationService', function ($location, $scope, AuthenticationService)
{
    if (AuthenticationService.hasSession ())
    {
        $location.path ('/dashboard/');
        return;
    }

    $scope.model = {username: '', password: ''};
    $scope.lastRequest = {error: false};

    $scope.performLogin = function ()
    {
        AuthenticationService.login ($scope.model.username, $scope.model.password).then (
            function (token)
            {
                // login okay
                $location.path ('/dashboard/');
            },
            function ()
            {
                $scope.lastRequest.error = true;
            }
        )
    };
}]);
