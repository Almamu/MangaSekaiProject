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
.directive ('pageUpload', [function ()
{
    return {
        scope: {callback: '=', index: '='},
        link: function (scope, element, attrs)
        {
            element.bind ('change', function (ev)
            {
                console.log (ev.target.files [0]);

                let fileReader = new FileReader ();
                fileReader.onloadend = function ()
                {
                    scope.callback (scope.index, fileReader.result);
                };

                fileReader.readAsDataURL(ev.target.files [0]);
            });
        }
    }
}])
.controller ('SeriesController', ['$http', '$scope', 'API', function ($http, $scope, API)
{
    $scope.list = {pagination: {}};
    $scope.newSerie = function ()
    {
        $scope.$broadcast ('DisplayCreationWindow');
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
.controller ('CreateNewSerieController', ['$http', '$rootScope', '$scope', 'API', 'EDITOR_MODE_NEW_SERIE', function ($http, $rootScope, $scope, API, EDITOR_MODE_NEW_SERIE)
{
    $scope.selectModeManual = function ()
    {
        $scope.hideModal ();

        $rootScope.$broadcast ('SetEditorMode', EDITOR_MODE_NEW_SERIE);
        $rootScope.$broadcast ('DisplayEditor');
    };
    $scope.selectModeScrapper = function ()
    {
        $scope.hideModal ();

        $rootScope.$broadcast ('DisplayDiscovery');
    };
    $scope.$on ('DisplayCreationWindow', function ()
    {
        $scope.showModal ();
    });
    $scope.showModal = function ()
    {
        angular.element ('#creator').modal ('show');
    };
    $scope.hideModal = function ()
    {
        angular.element ('#creator').modal ('hide');
    };
}])
.controller ('DiscoveryController', ['$http', '$scope', 'API', function ($http, $scope, API)
{
    $scope.files = [];
    $scope.directory = '';

    $scope.$on ('DisplayDiscovery', function ()
    {
        $scope.showModal ();
    });
    $scope.showModal = function ()
    {
        angular.element ('#discovery').modal ('show');

        requestFolders ();
    };

    $scope.clickFolderEntry = function (file)
    {
        $scope.files = [];

        if ($scope.directory.lastIndexOf ('/') == ($scope.directory.length - 1))
            $scope.directory += file.name;
        else
            $scope.directory += '/' + file.name;

        requestFolders ();
    };

    function requestFolders ()
    {
        $http.post (API ('files'), {directory: $scope.directory}).then (
            function (result)
            {
                $scope.directory = result.data.directory;
                $scope.files = result.data.contents;

                if ($scope.directory != '/')
                {
                    $scope.files.push ({name: '..', type: 'dir'});
                }
            }
        );
    }
}])
.controller ('EditorController', ['$http', '$scope', 'API', 'EDITOR_MODE_NEW_SERIE', function ($http, $scope, API, EDITOR_MODE_NEW_SERIE)
{
    $scope.serie = {
        Name: '',
        Description: '',
        Chapters: []
    };
    $scope.chapterSelected = {};
    $scope.mode = EDITOR_MODE_NEW_SERIE;
    $scope.screen = "list";
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
        angular.element ('#editor').modal ('show');
    };
    $scope.addChapter = function ()
    {
        let chapterNumber = 1;

        if ($scope.serie.Chapters.length > 0)
        {
            chapterNumber = $scope.serie.Chapters [$scope.serie.Chapters.length - 1].Number + 1;
        }

        $scope.serie.Chapters.push ({Name: '', Number: chapterNumber, Pages: []});
    };
    $scope.editChapter = function (index)
    {
        $scope.screen = 'edit';
        $scope.chapterSelected = $scope.serie.Chapters [index];
    };
    $scope.removeChapter = function (index)
    {
        $scope.serie.Chapters.splice (index, 1);
    };
    $scope.listChapters = function ()
    {
        $scope.screen = 'list';
    };

    function recalculatePages ()
    {
        let index = 0;

        angular.forEach ($scope.chapterSelected.Pages, function (page)
        {
            page.number = ++index;
        });
    }

    $scope.addPage = function ()
    {
        $scope.chapterSelected.Pages.push ({data: '', number: 0});

        recalculatePages ();
    };
    $scope.removePage = function (index)
    {
        $scope.chapterSelected.Pages.splice (index, 1);
        recalculatePages ();
    };
    $scope.updatePageContent = function (index, content)
    {
        console.log (index);

        $scope.chapterSelected.Pages [index].data = content;
        $scope.$apply ();
    };
}]);
