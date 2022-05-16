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
    
    $email_error = fieldRequired($email);
    $pass_error = fieldRequired($password);

    if (!($email_error || $pass_error)) {
        try{
            $login_query = $conn->prepare(selectQuery('admin')." WHERE email = ? AND password = ?");
            $login_query->bind_param('ss', $email, md5($password));
            $login_query->execute();
            $user = $login_query->get_result()->fetch_assoc();

            if ($user > 0) {
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['profile_image'] = $user['profile_image'];

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

    $name_error = fieldRequired($name);
    $email_error = fieldRequired($email);
    $phone_num_error = fieldRequired($phone_num);
    $gender_error = fieldRequired($gender);
    $name_check = validateFormat("/^[a-zA-Z ]*$/", $name, "only characters are allowed");
    $email_check = validateFormat("/^[a-z0-9\.]+@[a-z]+\.(\S*[a-z])$/", $email, "Invalid email format");
    $phone_num_check = phoneNumber($phone_num);

    if (!($name_error
    || $email_error
    || $email_check
    || $phone_num_error
    || $phone_num_check
    || $gender_error)) {

        try {
            if (updateUser('users', $conn,'' ,'' , '',$name, $email, $phone_num, $gender, $id)) {
                $_SESSION['user_updated'] = "Update successful";
                header('Location: users_list.php');
            } else {
                throw new Exception ("User detail not updated");
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
    }
    $conn->close();
}

if (isset($_POST['user_delete'])) {
    $id = validateInput($_POST['user_id']);

    $query = $conn->prepare("DELETE FROM users WHERE id = ?");
    $query->bind_param("i", $id);
    try {
        if ($query->execute()) {
            $_SESSION["user_deleted"] = "User deleted successful";
            header('Location: users_list.php');
        } else {
            throw new Exception ("User not deleted");
        }
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}

if (isset($_POST['admin_logout'])) {
    session_destroy();
    header('Location: login.php');
}

?>