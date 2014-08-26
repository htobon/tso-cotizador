<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Administración de Clientes</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <button class="btn btn-outline btn-default" type="button" data-toggle="modal" ui-sref='gestionarClientes' data-target="#modal" rel="add">Agregar Cliente</button>

                    <label> Vendores registran clientes ? </label>
                    <div class="btn-group btn-toggle"> 
                        <button class="btn btn-default">SI</button>
                        <button class="btn btn-primary active">NO</button>
                    </div>


                </div>
                <div class="panel-body">
                    <div class="table-responsive">                       
                        <table class="table table-striped table-bordered table-hover" id="clientes"></table>
                    </div>
                    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
                </div>
            </div> 
        </div>
        <div class="col-lg-4">

        </div>
    </div>
</div>
