'use strict';

angular.module ('mangasekai.viewer', [])
.config (['$routeProvider', function ($routeProvider)
{
    $routeProvider.when (
        '/serie/:serie/chapter/:chapter/', {
            controller: 'ViewerController',
            templateUrl: '/app/modules/viewer/viewer.html'
        }
    );
}])
.controller ('ViewerController', [
'$http', '$location', '$routeParams', '$scope', 'API',
function ($http, $location, $routeParams, $scope, API)
{
    $scope.loaded = 'loading';
    $scope.pages = [];
    $scope.serie = {};
    $scope.chapter = {};
    $scope.next = null;
    $scope.previous = null;

    $scope.goBack = function ()
    {
        $location.path ('/serie/' + $routeParams.serie);
    };

    $scope.nextChapter = function ()
    {
        if ($scope.next)
        {
            $location.path ('/serie/' + $scope.next.IdSeries + '/chapter/' + $scope.next.Id);
        }
    };

    $scope.previousChapter = function ()
    {
        if ($scope.previous)
        {
            $location.path ('/serie/' + $scope.previous.IdSeries + '/chapter/' + $scope.previous.Id);
        }
    };

    $http.get (API ('series/' + $routeParams.serie + '/chapter/' + $routeParams.chapter)).then (
        function (result)
        {
            $scope.pages = result.data.pages;
            $scope.serie = result.data.serie;
            $scope.chapter = result.data.chapter;
            $scope.next = result.data.next;
            $scope.previous = result.data.previous;
            $scope.loaded = 'ok';
        },
        function ()
        {
            $scope.loaded = 'error';
        }
    );
}]);