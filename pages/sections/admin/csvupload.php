<?php

$valid_exts = array('csv'); // valid extensions
$path = __DIR__ . "/../../../tmp/"; // upload directory

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($_FILES['file'])) {
        // get uploaded file extension
        $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

        $file = uniqid() . '.' . $ext;
        //$path = $path . uniqid(). '.' .$ext;
        $path = $path . $file;
        // move uploaded file from temp to uploads directory
        if (move_uploaded_file($_FILES['file']['tmp_name'], $path)) {

            $fila = 1;
            if (($gestor = fopen($path, "r")) !== false) {
                while (($datos = fgetcsv($gestor, 1024, ",")) !== false) {

                    print_r($datos);

                    /* $numero = count($datos);
                      echo "<p> $numero de campos en la línea $fila: <br /></p>\n";
                      $fila++;
                      for ($c = 0; $c < $numero; $c++) {
                      //echo $datos[$c] . "<br />\n";
                      } */
                }
                fclose($gestor);
            }
        }
    } else {
        echo 'Archivo NO subido';
    }
} else {
    echo 'Bad request!';
}
?>