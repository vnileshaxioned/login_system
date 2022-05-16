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

function updateUser($table, $conn, $image, $id, $password = 0, ...$columns) {
    if ($columns) {
        $query = $conn->prepare("UPDATE $table SET name = ?, email = ?, phone_number = ?, gender = ? WHERE id = ?");
        $query->bind_param('ssssi', ...$columns);
        return $query->execute();
    } elseif ($password) {
        $query = $conn->prepare("UPDATE $table SET password = ? WHERE id = ?");
        $query->bind_param('si', md5($password), $id);
        return $query->execute();
    } else {
        return $conn->query("UPDATE $table SET profile_image = '$image' WHERE id = $id");
    }
}

function fetchUser($table, $conn, $type, $column, $value = '') {
    if ($value > 0) {
        $query = $conn->prepare("SELECT * FROM $table WHERE $column = ?");
        $query->bind_param($type, $value);
        $query->execute();
        return $query->get_result();
        // echo $type;
    } else {
        return $conn->query("SELECT * FROM $table");
    }
}

?>