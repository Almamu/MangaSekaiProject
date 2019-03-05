'use strict';

angular.module ('mangasekai.dashboard', [])
.config (['$routeProvider', function ($routeProvider)
{
    $routeProvider.when (
        '/dashboard/', {
            controller: 'DashboardController',
            templateUrl: '/app/modules/dashboard/dashboard.html'
        }
    ).when (
        '/serie/:id/', {
            controller: 'SerieController',
            templateUrl: '/app/modules/dashboard/serie.html'
        }
    );
}])
.directive ('mangaEntry', [function ()
{
    return {
        restrict: 'E',
        replace: true,
        scope: {
            entry: '='
        },
        templateUrl: '/app/modules/dashboard/serie-entry.html'
    }
}])
.directive ('chapterEntry', [function ()
{
    return {
        restrict: 'E',
        replace: true,
        scope: {
            entry: '='
        },
        templateUrl: '/app/modules/dashboard/chapter-entry.html'
    }
}])
.controller ('DashboardController', ['$http', '$scope', 'API', function ($http, $scope, API)
{
    $scope.list = {pagination: {}};

    $http.get (API ('series')).then (
        function (result)
        {
            if (result.data.count > 0)
            {
                $scope.list.pagination = result.data;
            }
        }
    );
}])
.controller ('SerieController', ['$http', '$routeParams', '$scope', 'API', function ($http, $routeParams, $scope, API)
{
    $scope.list = {pagination: {}, info: {}};

    $http.get (API ('series/' + $routeParams.id)).then (
        function (result)
        {
            $scope.list.info = result.data;
        }
    );

    $http.get (API ('series/' + $routeParams.id + '/chapters')).then (
        function (result)
        {
            if (result.data.count > 0)
            {
                $scope.list.pagination = result.data;
            }
        }
    )
}]);