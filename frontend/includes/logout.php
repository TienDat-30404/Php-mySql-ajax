<?php

// Bat dau session (quan trong)
session_start();

//Neu nguoi dung da dang nhap thanh cong, thi huy bien session
if (isset($_SESSION['logged_in'])) 
{
	unset($_SESSION['logged_in']);
    unset($_SESSION['user_id']);
}

//Da dang xuat, quay tro lai trang login.php
header('Location: index.php');
?>