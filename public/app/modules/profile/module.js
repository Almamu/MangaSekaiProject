'use strict';

angular.module ('mangasekai.profile', [
    'ngStorage'
])
.config (['$routeProvider', function ($routeProvider)
{
    $routeProvider.when (
        '/profile', {
            controller: 'ProfileController',
            templateUrl: '/app/modules/profile/profile.html'
    });
}])
.controller ('ProfileController', ['$http', '$scope', 'API', function ($http, $scope, API)
{
    $scope.passwordChangeData = {OldPassword: '', NewPassword: ''};

    $http.get (API ('user')).then (
        function (result)
        {
            angular.extend ($scope.passwordChangeData, result.data);
        }
    );

    $scope.passwordChange = function ()
    {
        if ($scope.passwordChangeData.NewPassword == $scope.passwordChangeData.OldPassword)
        {
            alert ("The old and new passwords are the same");
            return;
        }

        let data = {Username: $scope.passwordChangeData.Username, OldPassword: $scope.passwordChangeData.OldPassword};

        if ($scope.passwordChangeData.NewPassword != '')
        {
            data.NewPassword = $scope.passwordChangeData.NewPassword;
        }

        $http.post (API ('user'), data).then (
            function (result)
            {
                // show success message
                angular.element ('#successModal').modal ();
            },
            function ()
            {
                // show failure message
                angular.element ('#failModal').modal ();
            }
        );
    };
}]);