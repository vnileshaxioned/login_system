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
    $password_encrypt = md5($pass);

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
            $email_exist = $email_exist->fetch_assoc();
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
            $login_query = $conn->prepare(selectQuery('users')." WHERE email = ? AND password = ?");
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
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;
                header('Location: dashboard.php');
            } else {
                throw new Exception ("User detail not updated");
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
    }
    $conn->close();
}

if (isset($_POST['profile_image_update'])) {
    $id = validateInput($_POST['user_id']);
    $file_name = validateInput($_FILES['file']['name']);
    $file_size = validateInput($_FILES['file']['size']);
    $type = validateInput(strtolower(pathinfo($file_name,PATHINFO_EXTENSION)));
    $temp_name = validateInput($_FILES['file']['tmp_name']);
    $path = "upload/".$file_name;
    $check_file = checkFile($file_size, $type);
    try {
        if ($file_name != '') {
            if (!$check_file) {
                if (updateUser('users', $conn, $file_name, $id)) {
                    $moved = move_uploaded_file($temp_name, $path);
                    $_SESSION['user_updated'] = "Profile image updated successful";
                    $_SESSION['profile_image'] = $file_name;
                    header('Location: dashboard.php');
                } else {
                    throw new Exception ("User profile image not updated");
                }
            }
        } else {
            throw new Exception ("Please select a image");
        }
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}

if (isset($_POST['password_update'])) {
    $id = validateInput($_POST['user_id']);
    $current_password = validateInput($_POST['current_password']);
    $new_password = validateInput($_POST['new_password']);
    $confirm_password = validateInput($_POST['confirm_password']);
    $current_password_error = fieldRequired($current_password);
    $new_password_error = fieldRequired($new_password);
    $confirm_password_error = fieldRequired($confirm_password);
    $check_password = checkPass($new_password, $confirm_password);
    if (!($current_password_error
        ||$new_password_error
        ||$confirm_password_error
        ||$check_password)) {
        try {
            $password_exist = $conn->prepare(selectQuery('users').' WHERE password = ? AND id = ?');
            $password_exist->bind_param('si', md5($current_password), $id);
            $password_exist->execute();
            $password_exist = $password_exist->get_result()->fetch_assoc();
            if ($password_exist) {
                if (updateUser('users', $conn, '', $id,$new_password)) {
                    session_destroy();
                    header('Location: login.php');
                } else {
                    throw new Exception ("User password not updated");
                }
            } else {
                throw new Exception ("Current password is wrong");
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
    }
}

if (isset($_POST['user_logout'])) {
    session_destroy();
    header('Location: login.php');
}

?>