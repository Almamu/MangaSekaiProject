'use strict';

angular.module ('mangasekai.loader', [])
.constant ('MANGASEKAI_LOADER_START_EVENT', 'mangaSekaiLoadingStart')
.constant ('MANGASEKAI_LOADER_FINISH_EVENT', 'mangaSekaiLoadingFinished')
.factory ('fullpageLoader', [
'$q', '$rootScope', 'MANGASEKAI_LOADER_FINISH_EVENT', 'MANGASEKAI_LOADER_START_EVENT',
function ($q, $rootScope, MANGASEKAI_LOADER_FINISH_EVENT, MANGASEKAI_LOADER_START_EVENT)
{
    let requestQueue = 0;

    return {
        request: function (config)
        {
            if (requestQueue === 0)
            {
                $rootScope.$broadcast (MANGASEKAI_LOADER_START_EVENT);
            }

            requestQueue ++;

            return config || $q.when (config);
        },
        response: function (config)
        {
            requestQueue --;

            if (requestQueue === 0)
            {
                $rootScope.$broadcast (MANGASEKAI_LOADER_FINISH_EVENT);
            }

            return config || $q.when (config);
        }
    }
}])
.config (['$httpProvider', function ($httpProvider)
{
    $httpProvider.interceptors.push ('fullpageLoader');
}])
.directive ('loader', [
'MANGASEKAI_LOADER_FINISH_EVENT', 'MANGASEKAI_LOADER_START_EVENT',
function (MANGASEKAI_LOADER_FINISH_EVENT, MANGASEKAI_LOADER_START_EVENT)
{
    return {
        restrict: 'E',
        replace: true,
        template: '' +
            '<div class="ion-loader">' +
            '   <svg class="ion-loader-circle">' +
            '       <circle class="ion-loader-path" cx="50%" cy="50%" r="20" fill="none" stroke-miterlimit="10"/>' +
            '   </svg>' +
            '</div>',
        link: function (scope, element)
        {
            angular.element (element).addClass ('ion-hide');

            scope.$on (MANGASEKAI_LOADER_START_EVENT, function ()
            {
                angular.element (element).toggleClass ('ion-show ion-hide');
            });

            scope.$on (MANGASEKAI_LOADER_FINISH_EVENT, function ()
            {
                angular.element (element).toggleClass ('ion-hide ion-show');
            });
        }
    }
}]);
