<?php
session_start(); // Start de sessie
session_unset(); // Verwijder alle sessievariabelen
session_destroy(); // Zeg de sessie definitief vaarwel

// Redirect naar de loginpagina
header("Location: login.php");
exit;
