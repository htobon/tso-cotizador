

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Cotizaciones Generadas.</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Reporte Cotizaciones Generadas.
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">                    
                    <div class="table-responsive">   
                        <div class="row">
                            <div class="form-group col-xs-6 col-sm-3">
                                <label>Vendedor</label>
                                <select class="form-control" id="vendedores">
                                    <option value=""> -- Todos -- </option>
                                </select>
                            </div>
                            <div class="form-group col-xs-6 col-sm-3">
                                <label>Fecha Inicial</label>
                                <input class="form-control" id="fecha_inicial" data-date-format="yyyy-mm-dd" >
                            </div>
                            <div class="form-group col-xs-6 col-sm-3">
                                <label>Fecha Final</label>
                                <input class="form-control" id="fecha_final" data-date-format="yyyy-mm-dd">
                            </div>
                            <div class="form-group col-xs-6 col-sm-3">                                
                                <button type="button" class="btn btn-primary" style="margin-top: 24px;" id="filtrar_reporte">Enviar</button>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered table-hover" id="cotizaciones">
                            <tfoot>
                                <tr>
                                    <th colspan="5" style="text-align:right">Total:</th>
                                    <th class="text-center">$</th>
                                    <th class="text-center">$</th>
                                    <th class="text-center">$</th>
                                    <th class="text-center">$</th>
                                    <th class="text-center">$</th>
                                    <th class="text-center">$</th>
                                    <th class="text-center">$</th>
                                    <th class="text-center"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.panel-body -->
            </div> 
        </div>
        <div class="col-lg-4">

        </div>
    </div>
</div>
