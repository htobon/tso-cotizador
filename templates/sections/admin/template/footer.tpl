</div>

<!-- Core Scripts - Include with every page -->
<script src="{$smarty.const.SMARTY_ROOT_URI}/js/admin/jquery-1.10.2.js"></script>
<script src="{$smarty.const.SMARTY_ROOT_URI}/js/admin/bootstrap.min.js"></script>
<script src="{$smarty.const.SMARTY_ROOT_URI}/js/admin/plugins/metisMenu/jquery.metisMenu.js"></script>

<!-- Page-Level Plugin Scripts - Dashboard -->
<script src="{$smarty.const.SMARTY_ROOT_URI}/js/admin/plugins/morris/raphael-2.1.0.min.js"></script>
<script src="{$smarty.const.SMARTY_ROOT_URI}/js/admin/plugins/morris/morris.js"></script>
<script src="{$smarty.const.SMARTY_ROOT_URI}/js/admin/plugins/dataTables/jquery.dataTables.js"></script>
<script src="{$smarty.const.SMARTY_ROOT_URI}/js/admin/plugins/dataTables/dataTables.bootstrap.js"></script>

<!-- SB Admin Scripts - Include with every page -->
<script src="{$smarty.const.SMARTY_ROOT_URI}/js/admin/sb-admin.js"></script>
<script src="{$smarty.const.SMARTY_ROOT_URI}/js/admin/admin.js"></script>

<!-- Page-Level Demo Scripts - Dashboard - Use for reference -->
<script src="{$smarty.const.SMARTY_ROOT_URI}/js/admin/demo/dashboard-demo.js"></script>

<!-- Angular JS -->
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.1.5/angular.min.js"></script>
<script src="//angular-ui.github.io/ui-router/release/angular-ui-router.js"></script>
<script>

    var myApp = angular.module('myApp', ['ui.router']);

    myApp.config(function($stateProvider, $urlRouterProvider) {
        //
        // For any unmatched url, redirect to /state1
        $urlRouterProvider.otherwise("/");
        //
        // Now set up the states
        $stateProvider
                .state('cotizacionesGeneradas', {
                    url: "/cotizacionesGeneradas",
                    templateUrl: "cotizacionesGeneradas.tpl"
                })
                .state('state1.list', {
                    url: "/list",
                    templateUrl: "partials/state1.list.html",
                    controller: function($scope) {
                        $scope.items = ["A", "List", "Of", "Items"];
                    }
                })
                .state('state2', {
                    url: "/state2",
                    templateUrl: "partials/state2.html"
                })
                .state('state2.list', {
                    url: "/list",
                    templateUrl: "partials/state2.list.html",
                    controller: function($scope) {
                        $scope.things = ["A", "Set", "Of", "Things"];
                    }
                })
    });
</script>


</body>

</html>
