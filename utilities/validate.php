<?php

function validateInput($data) {
    $data = trim($data);
    $data = htmlspecialchars($data);
    $data = stripslashes($data);
    return $data;
}

function fieldRequired($data) {
    if (empty($data)) {
        return "Field is required";
    }
}

function validateFormat($pattern, $data, $message) {
    if (!preg_match($pattern, $data)) {
        return $message;
    }
}

function checkPass($pass, $cpass) {
    $password_check = validateFormat("/^\S*(?=\S*[A-Z])(?=\S*[a-z])(?=\S*[0-9])(?=\S*[@$])\S*$/", $pass, "Uppercase, lowercase, numbers and @, $ characters needed");
    if ($pass !== $cpass) {
        return "Password not match";
    } elseif ($password_check) {
        return $password_check;
    } else {
        if (strlen($pass) <= 6) {
            return "Password must be greater than 6 characters";
        }
    }
}

function phoneNumber($data) {
    $check_number = validateFormat("/^[6-9]+[0-9]*$/", $data, "Only numbers are allowed and start with 6,7,8 and 9");
    if ($check_number) {
        return $check_number;
    } else {
        if (strlen($data) != 10) {
            return "maximum 10 digits are allowed";
        }
    }
}

function checkFile($size, $type) {
    if ($type != 'jpg' && $type != 'jpeg' && $type != 'png' && $type != '') {
        return "File should be in jpg, jpeg and png format allowed";
    } else {
        if ($size > 1000000) {
            return "File is less than or equal to 1mb are allowed";
        }
    }
}

?>