<?php

function deleteQuery($table, $conn, $id) {
    $query = $conn->prepare("DELETE FROM $table WHERE id = ?");
    $query->bind_param('i', $id);
    return $query->execute();
}

function insertQuery($table, ...$columns) {
    if ($columns) {
        $column = implode(", ", $columns);
        return "INSERT INTO $table ($column)";
    } else {
        return "INSERT INTO $table";
    }
}

function updateUser($table, $conn, $id, $password = '', ...$columns) {
    if ($password > 0) {
        $query = $conn->prepare("UPDATE $table SET password = ? WHERE id = ?");
        $query->bind_param('si', sha1($password), $id);
        return $query->execute();
    } else {
        $query = $conn->prepare("UPDATE $table SET name = ?, email = ?, phone_number = ?, gender = ?, profile_image = ? WHERE id = ?");
        $query->bind_param('sssssi', ...$columns);
        return $query->execute();
    }
}

function fetchUser($table, $conn, $type = '', $columns = '', $values = '') {
    if ($values > 0) {
        if (is_array($columns)) {
            foreach ($columns as $column) {
                $conditions[] = "$column = ?";
            }
            $conditions = implode(" AND ", $conditions);
        } else {
            $conditions = "$columns = ?";
        }
        $query = $conn->prepare("SELECT * FROM $table WHERE $conditions");
        if (is_array($values)) {
            $query->bind_param($type, ...$values);
        } else {
            $query->bind_param($type, $values);
        }
        $query->execute();
        return $query->get_result()->fetch_assoc();
    } else {
        return $conn->query("SELECT * FROM $table");
    }
}

?>