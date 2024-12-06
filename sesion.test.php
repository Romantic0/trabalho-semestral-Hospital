<?php
session_start();
$_SESSION['test'] = "Sessão funcionando!";
echo $_SESSION['test'];
?>
