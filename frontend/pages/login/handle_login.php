<?php 
    include "../../includes/config.php";
    session_start();
    $sql = "SELECT * FROM users";
    $result = mysqli_query($connection, $sql);
    while($row = mysqli_fetch_array($result))
    {
        if(isset($_POST['button_login']))
        {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $nameLogin = $row['fullname'];
            if($email == $row['email'] && $password == $row['password'])
            {
                if($row['role_id'] == 1)
                {    
                    unset($_SESSION['fail_login']);
                    $_SESSION['account'] = array(
                        'id_user' => $row['id'],
                        'id_role' => $row['role_id'],
                        'email' => $email,
                        'fullname' => $nameLogin
                    );
                    header('location: ../../../backend/index.php');
                    break;
                }
                else if($row['role_id'] == 3)
                {
                    unset($_SESSION['fail_login']);
                    $_SESSION['account'] = array(
                        'id_user' => $row['id'],
                        'id_role' => $row['role_id'],
                        'email' => $email,
                        'fullname' => $nameLogin
                    );
                    header('location: ../../../index.php');
                    break;
                }
            }
            else 
            {
                $_SESSION['fail_login'] = array(
                    'email' => $email,
                    'password' => $password
                );
                header('location: ../../../index.php?title=login');
            }
        }
    }

?>
