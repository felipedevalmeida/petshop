<?php
// index.php
session_start();
if (!isset($_SESSION['vet_id'])) {
    header("Location: login.php");
    exit;
}

// Carrega a view principal
require_once("views/principal.php");
