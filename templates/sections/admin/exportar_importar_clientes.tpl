<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Importación / Exportación de Clientes</h1>
        </div>
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Importar Clientes
                </div>

                <form id="upload_csv" action="csvupload.php" method="POST" enctype="multipart/form-data">    

                    <div class="panel-body" id="">
                        <div class="form-group">
                            <label>Seleccione el archivo a importar. </label>
                            <input type="file" name="file" type="file" accept=".csv" >
                        </div> 
                        <button type="submit" class="btn btn-primary btn-lg btn-block" style="margin-top: 10px;" id="importarClientes">Importar Clientes</button>                        
                    </div>
                </form>
            </div>


        </div>

        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Exportar Clientes
                </div>

                <div class="panel-body" id="">
                    <div class="form-group">
                        <label>Presione Para Generar el listado de clientes </label>
                    </div> 
                    <button type="button" class="btn btn-success btn-lg btn-block" style="margin-top: 44px;" id="exportarClientes">Exportar Listado Clientes</button>
                    <p class="help-block" id="msj_error2"></p>
                </div>
            </div>
        </div>

    </div>   
</div>