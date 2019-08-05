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
'$http', '$location', '$routeParams', '$scope', '$window', 'API', 'AuthenticationService',
function ($http, $location, $routeParams, $scope, $window, API, AuthenticationService)
{
    $scope.loaded = 'loading';
    $scope.pages = [];
    $scope.serie = {};
    $scope.chapter = {};
    $scope.next = null;
    $scope.previous = null;
    $scope.token = encodeURIComponent (AuthenticationService.getToken ());
    $scope.lastPageNumber = -1;
    $scope.scrollPending = false;

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

    function viewerLoaded ()
    {
        // add the serie to the tracker
        $http.post (API ('track/series'), {serieid: $routeParams.serie});
        // check if the user is on a specific page
        $http.get (API ('track/series/' + $routeParams.serie + '/chapters/' + $routeParams.chapter)).then (
            function (result)
            {
                if ("Page" in result.data)
                {
                    $scope.lastPageNumber = result.data.Page;
                    // scroll to the specified page
                    let pages = angular.element ('.page-container');
                    angular.forEach (pages, function (element)
                    {
                        let imageURL = angular.element (element).find ('img').attr ('lazy-img');
                        let pageNumber = imageURL.match (/\/api\/index.php\/chapter\/[0-9]+\/page\/([0-9]+)\//);

                        if (pageNumber.length < 2)
                            return;

                        pageNumber = parseInt (pageNumber [1]);

                        if (pageNumber == $scope.lastPageNumber)
                        {
                            // scroll to this page
                            $window.scrollTo (0, angular.element (element).offset ().top);
                        }
                    });
                }
                else
                    // add the chapter to the tracker
                    $http.post (API ('track/series/' + $routeParams.serie + '/chapters'), {chapterid: $routeParams.chapter, page: 0});

            }
        );

        // bind scroll events
        angular.element ($window).bind ('scroll', function ()
        {
            let minPosition =  angular.element ($window).scrollTop ();
            let pages = angular.element ('.page-container');
            let selectedPage = -1;

            for (let i = 0; i < pages.length; i ++)
            {
                if (angular.element (pages [i]).offset ().top < minPosition)
                    selectedPage = i;
            }

            // make sure there is at least one page already visible
            if (selectedPage == -1)
                return;

            let imageURL = angular.element (pages [selectedPage]).find ("img").attr ('lazy-img');
            let pageNumber = imageURL.match (/\/api\/index.php\/chapter\/[0-9]+\/page\/([0-9]+)\//);

            // make sure the page number was properly parsed
            if (pageNumber.length < 2)
                return;

            pageNumber = parseInt (pageNumber [1]);

            // only update when needed
            if (pageNumber <= $scope.lastPageNumber)
                return;

            $scope.lastPageNumber = pageNumber;

            if (selectedPage == (pages.length - 1) && $scope.next)
                $http.post (API ('track/series/' + $routeParams.serie + '/chapters'), {chapterid: $scope.next.Id, page: 0});

            // update read page for current chapter to last page
            $http.post (API ('track/series/' + $routeParams.serie + '/chapters'), {chapterid: $routeParams.chapter, page: pageNumber});
        });
    }

    $http.get (API ('series/' + $routeParams.serie + '/chapter/' + $routeParams.chapter)).then (
        function (result)
        {
            $scope.pages = result.data.pages;
            $scope.serie = result.data.serie;
            $scope.chapter = result.data.chapter;
            $scope.next = result.data.next;
            $scope.previous = result.data.previous;
            $scope.loaded = 'ok';

            // load read status after the pages have been loaded
            viewerLoaded ();
        },
        function ()
        {
            $scope.loaded = 'error';
        }
    );
}]);