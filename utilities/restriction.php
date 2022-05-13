<?php

function notLogin($session) {
    if (!isset($_SESSION["$session"])) {
        header('Location: login.php');
    }
}

function isLogin($session) {
    if (isset($_SESSION["$session"])) {
        header('Location: dashboard.php');
    }
}

?>