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


<div class = modal>
    <div class = "modal_base">
        <div class = "categoryEdit">
            <form method = "POST" action = "crud/handle_editProduct.php" class = "edit_category" enctype="multipart/form-data">               
                <a style = "cursor : pointer;" class = "exit_edit-category">x</a>
                <h2 class = "edit_category-title">Chỉnh sửa sản phẩm</h2>
                <ul class = "edit_category-content">
                    <input type="hidden" name = "id_cateogry" value = "${value.id}">
                    <li>
                        <h4>Tên sản phẩm</h4>
                        <input style = "width : 400px" value = "${value.product_name}" name = "name_category" type="text">
                        <span class = "form-message"></span>
                    </li>
                    <li>
                        <h4>Ảnh sản phẩm</h4>
                        <input name = "image_category" type="file">
                        <img class = "image_display" style = "width : 60px; height : 40px" src="${value.image}" alt="">
                        <span class = "form-message"></span>

                    </li>
                    <li>
                        <input name = "button_editCategory" type="submit" value = "Chỉnh sửa">
                    </li>
                </ul>
            </form>
        </div>
        
    </div>
</div>  