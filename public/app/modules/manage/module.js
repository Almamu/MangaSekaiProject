'use strict';

angular.module ('mangasekai.manage', [])
.config (['$routeProvider', function ($routeProvider)
{
    $routeProvider
        .when ('/manage/', {
            controller: 'SeriesController',
            templateUrl : '/app/modules/manage/series.html'
        });
}])
.controller ('SeriesController', ['$http', '$scope', 'API', function ($http, $scope, API)
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
}]);
