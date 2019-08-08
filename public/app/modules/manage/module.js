'use strict';

angular.module ('mangasekai.manage', [])
.constant ('EDITOR_MODE_NEW_SERIE', 'newSeries')
.config (['$routeProvider', function ($routeProvider)
{
    $routeProvider
        .when ('/manage/', {
            controller: 'SettingsController',
            templateUrl : '/app/modules/manage/settings.html'
        });
}])
.controller ('SettingsController', ['$http', '$scope', 'API', 'AuthenticationService', function ($http, $scope, API, AuthenticationService)
{
    $scope.folders = [];
    $scope.userlist = [];
    $scope.admins = [];
    $scope.ourUsername = AuthenticationService.getSession ().username;

    $scope.saveFolders = function ()
    {
        $http.post (API ('settings'), {name: 'scanner_dirs', value: $scope.folders});
    };

    $scope.removeFolder = function (index)
    {
        $scope.folders.splice (index, 1);

        // save change
        $scope.saveFolders ();
    };

    $scope.showFolderDialog = function ()
    {
        $scope.$broadcast ('DisplayDiscovery', 'Please select the folder where your mangas are');
    };

    $scope.showUserSelectDialog = function ()
    {
        angular.element ('#userselect').modal ('show');
    };

    $scope.saveUsers = function ()
    {
        $http.post (API ('settings'), {name: 'administrator_users', value: $scope.admins});
    };

    $scope.removeUser = function (index)
    {
        $scope.admins.splice (index, 1);

        $scope.saveUsers ();
    };

    $scope.addUser = function (userid)
    {
        angular.element ('#userselect').modal ('hide');

        // first check if the user exists
        for (let key in $scope.admins)
        {
            if ($scope.admins [key] == userid)
                return;
        }

        $scope.admins.push (userid);
        $scope.saveUsers ();
    };

    $http.get (API ('settings') + '?name=' + encodeURIComponent ('scanner_dirs')).then (
        function (result)
        {
            $scope.folders = result.data.Value;
        }
    );

    $http.get (API ('user/list')).then (
        function (result)
        {
            $scope.userlist = result.data;
        }
    );

    $http.get (API ('settings') + '?name=' + encodeURIComponent ('administrator_users')).then (
        function (result)
        {
            $scope.admins = result.data.Value;
        }
    );

    let folderSelectedEvent = $scope.$on ('FolderSelected', function (ev, folder)
    {
        // add the folder the list
        $scope.folders.push (folder);
        $scope.saveFolders ();
    });

    $scope.$on ('$destroy', function ()
    {
        folderSelectedEvent ();
    });
}])
.controller ('DiscoveryController', ['$http', '$scope', 'API', function ($http, $scope, API)
{
    $scope.files = [];
    $scope.directory = '';
    $scope.title = '';

    $scope.$on ('DisplayDiscovery', function (ev, title)
    {
        $scope.title = title;
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

    $scope.selectCurrentFolder = function ()
    {
        $scope.$emit ('FolderSelected', $scope.directory);
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
}]);
