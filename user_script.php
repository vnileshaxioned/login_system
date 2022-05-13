<?php

session_start();
require_once('database/database_connection.php');
require_once('function/query_function.php');
require_once('function/validate_function.php');

$message = "";

if (isset($_POST['user_register'])) {
    $name = validateInput($_POST['name']);
    $email = validateInput($_POST['email']);
    $phone_num = validateInput($_POST['phone_num']);
    $gender = validateInput($_POST['gender']);
    $pass = validateInput($_POST['pass']);
    $cpass = validateInput($_POST['c_pass']);
    $f_name = validateInput($_FILES['file']['name']);
    $f_size = validateInput($_FILES['file']['size']);
    $type = validateInput(strtolower(pathinfo($f_name,PATHINFO_EXTENSION)));
    $temp_name = validateInput($_FILES['file']['tmp_name']);
    $password_encrypt = md5($pass);
    $path = "upload/".$f_name;
    
    $name_error = fieldRequired($name);
    $email_error = fieldRequired($email);
    $phone_num_error = fieldRequired($phone_num);
    $gender_error = fieldRequired($gender);
    $pass_error = fieldRequired($pass);
    $cpass_error = fieldRequired($cpass);
    $name_check = validateFormat("/^[a-zA-Z ]*$/", $name, "only characters are allowed");
    $email_check = validateFormat("/^[a-z0-9\.]+@[a-z]+\.(\S*[a-z])$/", $email, "Invalid email format");
    $phone_num_check = phoneNumber($phone_num);
    $check_pass = checkPass($pass, $cpass);
    $check_file = checkFile($f_size, $type);

    if (!($name_error
    || $email_error
    || $email_check
    || $phone_num_error
    || $phone_num_check
    || $gender_error
    || $pass_error
    || $cpass_error
    || $check_pass
    || $check_file)) {

        try {
            $email_exist = $conn->prepare(selectQuery('users').' WHERE email = ?');
            $email_exist->bind_param('s', $email);
            $email_exist->execute();
            $email_exist = $email_exist->get_result()->fetch_assoc();

            if ($email_exist > 0) {
                throw new Exception ("Email already exist");
            } else {
                $values = array($name, $email, $phone_num, $gender, $password_encrypt, $f_name);
                $query = $conn->prepare(insertQuery('users', 'name', 'email', 'phone_number', 'gender', 'password', 'profile_image')." VALUES (?, ?, ?, ?, ?, ?)");
                $query->bind_param('ssssss', ...$values);

                if ($query->execute()) {
                    $moved = move_uploaded_file($temp_name, $path);
                    $_SESSION['success'] = "Registration successful";
                    header('Location: login.php');
                } else {
                    throw new Exception ("User detail not inserted");
                }
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
    }
    $conn->close();
}

if (isset($_POST['user_login'])) {
    $email = validateInput($_POST['email']);
    $password = validateInput($_POST['pass']);
    
    $email_error = fieldRequired($email);
    $pass_error = fieldRequired($password);

    if (!($email_error || $pass_error)) {
        try{
            $login_query = $conn->prepare(selectQuery('users')." WHERE email = ? AND password = ?");
            $login_query->bind_param('ss', $email, md5($password));
            $login_query->execute();
            $user = $login_query->get_result()->fetch_assoc();

            if ($user > 0) {
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['profile_image'] = $user['profile_image'];

                header('Location: dashboard.php');
            } else {
                throw new Exception("Invalid email and password");
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
    }
    $conn->close();
}

if (isset($_POST['user_logout'])) {
    session_destroy();
    header('Location: login.php');
}

?>