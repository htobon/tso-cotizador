<?php

$valid_exts = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
$max_size = 200 * 1024; // max file size
$path = __DIR__ . "/../../../tmp/"; // upload directory

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['photo'])) {
        // get uploaded file extension
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        // looking for format and size validity
        if (in_array($ext, $valid_exts) AND $_FILES['photo']['size'] < $max_size) {
            $file = uniqid() . '.' . $ext;
            //$path = $path . uniqid(). '.' .$ext;
            $path = $path . $file;
            // move uploaded file from temp to uploads directory
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $path)) {

                echo "<img height='100' width='500' src='http://" . $_SERVER['SERVER_NAME'] . "/tmp/" . $file . "' id='firma_digital_{$file}' />";
            }
        } else {
            echo 'Invalid file!';
        }
    } else {
        echo 'File not uploaded!';
    }
} else {
    echo 'Bad request!';
}
?>