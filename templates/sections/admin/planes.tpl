<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Administración de Planes</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <button class="btn btn-outline btn-default" type="button" data-toggle="modal" ui-sref='gestionarPlanes' data-target="#modal" rel="add">Agregar Plan</button>
                </div>
                <div class="panel-body">
                    <div class="alert alert-success alert-dismissable hidden" id="msj_success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>                    
                    </div>
                    <div class="table-responsive">                       
                        <table class="table table-striped table-bordered table-hover" id="planes"></table>
                    </div>
                    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
                </div>
            </div> 
        </div>
        <div class="col-lg-4">

        </div>
    </div>
</div>