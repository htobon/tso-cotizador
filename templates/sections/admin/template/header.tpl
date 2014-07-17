<!DOCTYPE html>
<html ng-app="myApp">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Admin TSO Mobile</title>

        <!-- Core CSS - Include with every page -->
        <link href="{$smarty.const.SMARTY_CSS_URI}/admin/bootstrap.min.css" rel="stylesheet">
        <link href="{$smarty.const.SMARTY_ROOT_URI}/font-awesome/css/font-awesome.css" rel="stylesheet">

        <!-- Page-Level Plugin CSS - Dashboard -->
        <link href="{$smarty.const.SMARTY_CSS_URI}/admin/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
        <link href="{$smarty.const.SMARTY_CSS_URI}/admin/plugins/timeline/timeline.css" rel="stylesheet">
        <link href="{$smarty.const.SMARTY_CSS_URI}/admin/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">

        <!-- SB Admin CSS - Include with every page -->
        <link href="{$smarty.const.SMARTY_CSS_URI}/admin/sb-admin.css" rel="stylesheet">
        <link href="{$smarty.const.SMARTY_CSS_URI}/jquery.validity.css" rel="stylesheet" />  

        



    </head>

    <body>

        <div id="wrapper">

            <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="admin.php">TSO Mobile</a>
                </div>


                <ul class="nav navbar-top-links navbar-right">

                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="/logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                            </li>
                        </ul>

                    </li>

                </ul>


                <div class="navbar-default navbar-static-side" role="navigation">
                    <div class="sidebar-collapse">
                        <ul class="nav" id="side-menu">
                            <li>
                                <a href="#" ui-sref="dashboard"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-wrench fa-fw"></i> Parametrizacion <span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="#" ui-sref="usuarios" >Usuarios</a>
                                    </li>
                                    <li>
                                        <a href="#" ui-sref="accesorios" >Accesorios</a>
                                    </li>
                                    <li>
                                        <a href="#" ui-sref="unidad_gps" >Unidades GPS</a>
                                    </li>
                                    <li>
                                        <a href="#" ui-sref="contratos" >Contratos</a>
                                    </li>
                                    <li>
                                        <a href="#" ui-sref="meses" >Duraciones Contrato</a>
                                    </li>
                                    <li>
                                        <a href="#" ui-sref="planes">Planes</a>
                                    </li>
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                            <li>
                                <a href="#" ui-sref="clientes" ><i class="fa fa-user fa-fw"></i> Clientes</a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Reportes<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="#" ui-sref="cotizacionesGeneradas" >Cotizaciones Generadas</a>
                                    </li>                                    
                                </ul>
                            </li>
                            <li>
                                <a href="#" ui-sref="archivos"><i class="fa fa-files-o fa-fw"></i> Descargar CSV</a>                                
                            </li>
                        </ul>

                    </div>

                </div>

            </nav>
