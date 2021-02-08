<?php
     session_name("bd_tareas_login");
     session_start();
     session_destroy();
     header("Location: ../index.php");
     exit;
?>
