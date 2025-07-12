<?php
session_start();
if (isset($_SESSION["cliente_id"])) {
    echo "ok";
} else {
    echo "no";
}
?>