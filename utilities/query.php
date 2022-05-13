<?php

function selectQuery($table, ...$columns) {
    if ($columns) {
        $column = implode(", ", $columns);
        return "SELECT $column FROM $table";
    } else {
        return "SELECT * FROM $table";
    }
}

function insertQuery($table, ...$columns) {
    if ($columns) {
        $column = implode(", ", $columns);
        return "INSERT INTO $table ($column)";
    } else {
        return "INSERT INTO $table";
    }
}

?>