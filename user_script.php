<?php

session_start();
require_once('database/database_connection.php');
require_once('utilities/query.php');
require_once('utilities/validate.php');
require_once('utilities/restriction.php');

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
    $password_encrypt = sha1($pass);

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
            $email_exist = fetchUser('users', $conn, 's', 'email', $email);
            
            if ($email_exist > 0) {
                throw new Exception ("Email already exist");
            } else {
                $query = $conn->prepare(insertQuery('users', 'name', 'email', 'phone_number', 'gender', 'password', 'profile_image')." VALUES (?, ?, ?, ?, ?, ?)");
                $query->bind_param('ssssss', $name, $email, $phone_num, $gender, $password_encrypt, $f_name);
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
    $remember_me = validateInput($_POST['remember']);
    
    $email_error = fieldRequired($email);
    $pass_error = fieldRequired($password);

    if (!($email_error || $pass_error)) {
        try{
            $columns = array('email', 'password');
            $values = array($email, sha1($password));
            $user = fetchUser('users', $conn, 'ss', $columns, $values);
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

            if ($id == $_SESSION['id']) {
                $email_exist = fetchUser('users', $conn, 's', 'email', $email);
                $f_name = $email_exist['profile_image'];

                if ($email_exist > 0) {
                    if ($file_name != '') {
                        if (updateUser('users', $conn,'' , '',$name, $email, $phone_num, $gender, $file_name, $id)) {
                            $moved = move_uploaded_file($temp_name, $path);
                            $_SESSION['user_updated'] = "Update successful";
                            header('Location: dashboard.php');
                        } else {
                            throw new Exception ("User detail ok not updated");
                        }
                    } else {
                        if (updateUser('users', $conn,'' , '',$name, $email, $phone_num, $gender, $f_name, $id)) {
                            $_SESSION['user_updated'] = "Update successful";
                            header('Location: dashboard.php');
                        } else {
                            throw new Exception ("User detail not updated");
                        }
                    }
                } else {
                    throw new Exception ("You are not allow to change email");
                }
            } else {
                throw new Exception ("Please do not change url id");
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

if (isset($_POST['password_update'])) {
    $id = validateInput($_POST['user_id']);
    $current_password = validateInput($_POST['current_password']);
    $new_password = validateInput($_POST['new_password']);
    $confirm_password = validateInput($_POST['confirm_password']);
    $password_encrypt = sha1($current_password);

    $current_password_error = fieldRequired($current_password);
    $new_password_error = fieldRequired($new_password);
    $confirm_password_error = fieldRequired($confirm_password);
    $check_password = checkPass($new_password, $confirm_password);
    $new_error = $new_password_error ? $new_password_error : $check_password;
    $confirm_error = $confirm_password_error ? $confirm_password_error : $check_password;
    $error = "current=$current_password_error&new=$new_error&confirm=$confirm_error";
    try {
        if (!($current_password_error
            ||$new_password_error
            ||$confirm_password_error
            ||$check_password)) {
            
            $columns = array('password', 'id');
            $values = array($password_encrypt, $id);
            $password_exist = fetchUser('users', $conn, 'si', $columns, $values);
            
            if ($password_exist) {
                if (updateUser('users', $conn, $id, $new_password, '')) {
                    session_destroy();
                    header('Location: login.php');
                } else {
                    throw new Exception ("User password not updated");
                }
            } else {
                throw new Exception ("Current password is wrong");
            }
        } else {
            header("Location: edit_password.php?id=$id&$error");
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: edit_password.php?id=$id");
    }
}

if (isset($_POST['user_logout'])) {
    session_destroy();
    header('Location: login.php');
}

?>