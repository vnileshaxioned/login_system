<?php

session_start();
require_once('../database/database_connection.php');
require_once('../utilities/query.php');
require_once('../utilities/validate.php');
require_once('../utilities/restriction.php');

$message = "";

if (isset($_POST['admin_login'])) {
    $email = validateInput($_POST['email']);
    $password = validateInput($_POST['pass']);
    $remember_me = validateInput($_POST['remember']);
    $password_encrypt = sha1($password);
    
    $email_error = fieldRequired($email);
    $pass_error = fieldRequired($password);

    if (!($email_error || $pass_error)) {
        try{
            $columns = array('email', 'password');
            $values = array($email, $password_encrypt);
            $user = fetchUser('admin', $conn, 'ss', $columns, $values);

            if ($user > 0) {
                $_SESSION['id'] = $user['id'];

                if (!empty($remember_me)) {
                    setcookie('email', $email, time() + 60 * 60);
                    setcookie('password', $password, time() + 60 * 60);
                } else {
                    if (isset($_COOKIE['email'])) {
                        setcookie('email', $email, time() - 60 * 60);
                        if (isset($_COOKIE['password'])) {
                            setcookie('password', $password, time() - 60 * 60);
                        }
                    }
                    header('Location: dashboard.php');
                }
            } else {
                throw new Exception("Invalid email and password");
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
    }
    $conn->close();
}

if (isset($_POST['user_update'])) {
    $id = validateInput($_POST['user_id']);
    $name = validateInput($_POST['name']);
    $email = validateInput($_POST['email']);
    $phone_num = validateInput($_POST['phone_number']);
    $gender = validateInput($_POST['gender']);

    $file_name = validateInput($_FILES['file']['name']);
    $file_size = validateInput($_FILES['file']['size']);
    $type = validateInput(strtolower(pathinfo($file_name,PATHINFO_EXTENSION)));
    $temp_name = validateInput($_FILES['file']['tmp_name']);
    $path = "upload/".$file_name;
    
    try {
        $check_file = checkFile($file_size, $type);
        $name_error = fieldRequired($name);
        $email_error = fieldRequired($email);
        $phone_num_error = fieldRequired($phone_num);
        $gender_error = fieldRequired($gender);
        $name_check = validateFormat("/^[a-zA-Z ]*$/", $name, "only characters are allowed");
        $email_check = validateFormat("/^[a-z0-9\.]+@[a-z]+\.(\S*[a-z])$/", $email, "Invalid email format");
        $phone_num_check = phoneNumber($phone_num);
        $name_check_error = $name_error ? $name_error : $name_check;
        $phone_error = $phone_num_error ? $phone_num_error : $phone_num_check;
        $error = "name=$name_check_error&phone_number=$phone_error&gender=$gender_error&file=$check_file";
        if (!($name_error
        || $name_check
        || $email_error
        || $email_check
        || $phone_num_error
        || $phone_num_check
        || $gender_error
        || $check_file)) {

            $email_exist = fetchUser('users', $conn, 's', 'email', $email);
            $f_name = $email_exist['profile_image'];

            if ($email_exist > 0) {
                if ($file_name != '') {
                    if (updateUser('users', $conn,'' , '',$name, $email, $phone_num, $gender, $file_name, $id)) {
                        $moved = move_uploaded_file($temp_name, $path);
                        $_SESSION['user_updated'] = "Update successful";
                        header('Location: users_list.php');
                    } else {
                        throw new Exception ("User detail ok not updated");
                    }
                } else {
                    if (updateUser('users', $conn,'' , '',$name, $email, $phone_num, $gender, $f_name, $id)) {
                        $_SESSION['user_updated'] = "Update successful";
                        header('Location: users_list.php');
                    } else {
                        throw new Exception ("User detail not updated");
                    }
                }
            } else {
                throw new Exception ("You are not allow to change email");
            }
        } else {
            header("Location: edit_user.php?id=$id&$error");
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: edit_user.php?id=$id");
    }
    $conn->close();
}

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    try {
        if (deleteQuery('users', $conn, $id)) {
            $_SESSION["user_updated"] = "User deleted successful";
        } else {
            throw new Exception ("User not deleted");
        }
    } catch (Exception $e) {
        $_SESSION['user_updated'] = $e->getMessage();
        header("Location: users_list.php");
    }
}

if (isset($_POST['admin_logout'])) {
    session_destroy();
    header('Location: login.php');
}

?>