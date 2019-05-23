'use strict';

angular.module ('mangasekai.manage', [])
.constant ('EDITOR_MODE_NEW_SERIE', 'newSeries')
.config (['$routeProvider', function ($routeProvider)
{
    $routeProvider
        .when ('/manage/', {
            controller: 'SeriesController',
            templateUrl : '/app/modules/manage/series.html'
        });
}])
.controller ('SeriesController', ['$http', '$scope', 'API', 'EDITOR_MODE_NEW_SERIE', function ($http, $scope, API, EDITOR_MODE_NEW_SERIE)
{
    $scope.list = {pagination: {}};
    $scope.newSerie = function ()
    {
        $scope.$broadcast ('SetEditorMode', EDITOR_MODE_NEW_SERIE);
        $scope.$broadcast ('DisplayEditor');
    };

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
.controller ('EditorController', ['$http', '$scope', 'API', 'EDITOR_MODE_NEW_SERIE', function ($http, $scope, API, EDITOR_MODE_NEW_SERIE)
{
    $scope.serie = {
        Name: '',
        Description: '',
        Chapters: []
    };
    $scope.mode = EDITOR_MODE_NEW_SERIE;
    $scope.$on ('SetEditorMode', function (ev, mode)
    {
        $scope.setMode (mode);
    });
    $scope.$on ('DisplayEditor', function ()
    {
        $scope.showModal ();
    });

    $scope.setMode = function (mode)
    {
        $scope.mode = mode;

        if ($scope.mode == EDITOR_MODE_NEW_SERIE)
        {
            angular.extend ($scope.serie, {
                Name: 'Bakuman',
                Description: '',
                Chapters: []
            });
        }
    };
    $scope.showModal = function ()
    {
        angular.element ('#editor').modal ();
    };
    $scope.addChapter = function ()
    {
        let chapterNumber = 1;

        if ($scope.serie.Chapters.length > 0)
        {
            chapterNumber = $scope.serie.Chapters [$scope.serie.Chapters.length - 1].Number + 1;
        }

        $scope.serie.Chapters.push ({Name: '', Number: chapterNumber});
    };
}]);
