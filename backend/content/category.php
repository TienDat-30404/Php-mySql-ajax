
<table style = "width : 100%; background-color : white" cellspacing = "0" cellpading = "10">
    <thead>
        <tr>
            <th colspan = "5">
                <a class = "addCategory" href="">Add Category</a>
            </th>
        </tr>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Image</th>
            <th colspan = "2">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php 
              include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
            $sql = "SELECT * FROM categories";
            $result = DataSQL::querySQl($sql);
            while($row = mysqli_fetch_array($result))
            {
                ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><img style = "width : 50px; height : 50px" src="<?php echo $row['image']; ?>" alt=""></td>
                    <td>
                        <a data-id-category = <?php echo $row['id']; ?> class = "editCategory" href="">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </td>
                    <td>
                        <a data-id-category = <?php echo $row['id']; ?> class = "deleteCategory" href="">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php }
        ?>
    </tbody>
</table>
<script>

    // Edit Category -------------------------------------------------------------------------------
    async function EditCategory(id)
    {
        var link = await fetch(`crud/edit_category.php?id_edit=${id}`)
        var json = await link.json();
        console.log(json)
        DisplayCategory(json)
    }
    var elementEdit = document.querySelectorAll('.editCategory')
    elementEdit.forEach(function(item)
    {
        item.addEventListener('click', function(event)
        {
            checkExit = true
           event.preventDefault();
           var idCategory = this.getAttribute('data-id-category')
           EditCategory(idCategory)
        })
    })
    function DisplayCategory(data)
    {
        data.forEach(function(value)
        {
            var editCategory = 
            `
            <div class = modal>
                <div class = "modal_base">
                    <div class = "categoryEdit">
                        <form method = "" action = "" class = "edit_category" enctype="multipart/form-data">               
                            <a href = "index.php?title=category" style = "cursor : pointer;" class = "exit_edit-category">x</a>
                            <h2 class = "edit_category-title">Chỉnh sửa thể loại sản phẩm</h2>
                            <ul class = "edit_category-content">
                                <li>
                                    <h4>Tên sản phẩm</h4>
                                    <input style = "width : 400px" value = "${value.name}" name = "name_category" type="text">
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
                                <input type="hidden" name = "id_category" value = "${value.id}">
                            </ul>
                        </form>
                    </div>
                    
                </div>
            </div>  
            `
            document.body.insertAdjacentHTML('beforeend', editCategory);
            var editButton = document.querySelector('input[name="button_editCategory"]')
            editButton.addEventListener('click', function(event)
            {
                event.preventDefault();
                var idCategory = document.querySelector('input[name="id_category"]').value
                var nameCategory = document.querySelector('input[name="name_category"]').value.trim()
                var fileImage = document.querySelector('input[name="image_category"]').files[0];
                var firstFocus = null;
                if(nameCategory == "")
                {
                    if(nameCategory == "")
                    {
                        var ElementP = document.querySelector('input[name="name_category"]')
                        var notification = ElementP.nextElementSibling;
                        notification.innerText = "Tên không được rông";
                        ElementP.classList.add('border-message')
                        if(firstFocus == null)
                        {   
                            firstFocus = ElementP;
                        }

                    }
                    else 
                    {
                        var ElementP = document.querySelector('input[name="name_category"]')
                        var notification = ElementP.nextElementSibling;
                        notification.innerText = "";
                        ElementP.classList.remove('border-message')
                    }
                }

                else 
                {
                    if(fileImage)
                    {
                        var reader = new FileReader();
                        reader.onloadend = function () {
                        // reader.result chứa dữ liệu ảnh dưới dạng chuỗi base64
                        var base64Image = reader.result;
                        var encodedBase64Image = base64Image.replace(/\+/g, '%2B'); // tìm tất cả dấu + trong chuỗi và mã hóa chúng, để tranh trường hợp
                        // khi truyền qua URL sẽ bị hiểu nhầm + là khoảng trắng -> dẫn đến sai
                        console.log(encodedBase64Image); // In ra chuỗi base64 của ảnh
                        
                        HandleEditCategory(idCategory, nameCategory, encodedBase64Image);
                    }
                    reader.readAsDataURL(fileImage);
                    }
                    else 
                    {
                        var imageDisplay = document.querySelector('.image_display');
                        var base64Image = imageDisplay.src;
                        var encodedBase64Image = base64Image.replace(/\+/g, '%2B');
                        console.log(encodedBase64Image);

                        HandleEditCategory(idCategory, nameCategory, encodedBase64Image);
                    }
                }
            })
            var fileInput = document.querySelector('input[name="image_category"]');
            fileInput.addEventListener("change", function(e) {
                var file = e.target.files[0];
                var reader = new FileReader();
                reader.onload = function(e) {
                    var encodedBase64Image = e.target.result;
                    var imageDisplay = document.querySelector('.image_display');
                    imageDisplay.src = encodedBase64Image;
                };
                reader.readAsDataURL(file);
            });

        })
    }
    async function HandleEditCategory(idCategory, nameCategory, imageCategory)
    {
        var formData = new FormData();
        formData.append('id_category', idCategory);
        formData.append('name_category', nameCategory);
        formData.append('image_category', imageCategory);
        var link = await fetch('crud/handle_editCategory.php', {
            method: 'POST',
            body: formData
        });
        var json = await link.json();
        var success = `Chỉnh sửa thể loại ${nameCategory} thành công`
        var fail = `Thể loại ${nameCategory} đã tồn tại. Không thể chỉnh sửa thể loại này`
        if(json.success === success)
        {
            alert(success)
            var ElementP = document.querySelector('input[name="name_category"]')
            var notification = ElementP.nextElementSibling;
            notification.innerText = "";
            ElementP.classList.remove('border-message')
        }
        if(json.fail === fail)
        {
            alert(fail)
            var ElementP = document.querySelector('input[name="name_category"]')
            var notification = ElementP.nextElementSibling;
            notification.innerText = "Tên thể loại đã tồn tại";
            ElementP.classList.add('border-message')
        }
    }




    // Add Category
    function AddCategory()
    {
        var addCategory = 
        `
        <div class = modal>
            <div class = "modal_base">
                <div class = "categoryEdit">
                    <form method = "" action = "" class = "edit_category" enctype="multipart/form-data">               
                        <a href = "index.php?title=category" style = "cursor : pointer;" class = "exit_edit-category">x</a>
                        <h2 class = "edit_category-title">Thêm thể loại sản phẩm</h2>
                        <ul class = "edit_category-content">
                            <li>
                                <h4>Tên sản phẩm</h4>
                                <input style = "width : 400px" name = "name_category" type="text">
                                <span class = "form-message"></span>
                            </li>
                            <li>
                                <h4>Ảnh sản phẩm</h4>
                                <input name = "image_category" type="file">
                                <img class = "image_display" style = "width : 60px; height : 40px" src="" alt="">
                                <span class = "form-message"></span>

                            </li>
                            <li>
                                <input name = "button_addCategory" type="submit" value = "Thêm">
                            </li>
                        </ul>
                    </form>
                </div>
                
            </div>
        </div>  
        `
        document.body.insertAdjacentHTML('beforeend', addCategory);
        var addButton = document.querySelector('input[name="button_addCategory"]')
        addButton.addEventListener('click', function(event)
        {
            event.preventDefault();
            var nameCategory = document.querySelector('input[name="name_category"]').value.trim()
            var fileImage = document.querySelector('input[name="image_category"]').files[0];
            var firstFocus = null;
            if(nameCategory == "" || fileImage == undefined)
            {
                if(nameCategory == "")
                {
                    var ElementP = document.querySelector('input[name="name_category"]')
                    var notification = ElementP.nextElementSibling;
                    notification.innerText = "Tên không được rông";
                    ElementP.classList.add('border-message')
                    if(firstFocus == null)
                    {
                        firstFocus = ElementP;
                    }

                }
                else 
                {
                    var ElementP = document.querySelector('input[name="name_category"]')
                    var notification = ElementP.nextElementSibling;
                    notification.innerText = "";
                    ElementP.classList.remove('border-message')
                }
                if(fileImage == undefined)
                {
                    var ElementP = document.querySelector('input[name="image_category"]')
                    var notification = ElementP.nextElementSibling;
                    notification.innerText = "Vui lòng tải ảnh lên";
                    ElementP.classList.add('border-message')
                    if(firstFocus == null)
                    {
                        firstFocus = ElementP;
                    }
                
                }
                else 
                {
                    var ElementP = document.querySelector('input[name="image_category"]')
                    var notification = ElementP.nextElementSibling;
                    notification.innerText = "";
                    ElementP.classList.remove('border-message')
                }
                
                firstFocus.focus();
            }

            else 
            {

                var reader = new FileReader();
                reader.onloadend = function () {
                // reader.result chứa dữ liệu ảnh dưới dạng chuỗi base64
                var base64Image = reader.result;
                var encodedBase64Image = base64Image.replace(/\+/g, '%2B'); // tìm tất cả dấu + trong chuỗi và mã hóa chúng, để tranh trường hợp
                // khi truyền qua URL sẽ bị hiểu nhầm + là khoảng trắng -> dẫn đến sai
                HandleAddCategory(nameCategory, encodedBase64Image);
                nameCategory.innerText = "";
                }
                reader.readAsDataURL(fileImage);
            }
        })
    }
    var addCategory = document.querySelector('.addCategory')
    addCategory.addEventListener('click', function(e)
    {
        e.preventDefault()
        AddCategory()
    })

    async function HandleAddCategory(nameCategory, imageCategory)
    {
        var formData = new FormData();
        formData.append('name_category', nameCategory);
        formData.append('image_category', imageCategory);
       
        var link = await fetch('crud/handle_addCategory.php', {
            method: 'POST',
            body: formData
        });
        var json = await link.json();
        var success = `Thêm thể loại ${nameCategory} vào cửa hàng thành công`;
        var fail = `Thể loại ${nameCategory} đã tồn tại trong cửa hàng`;
        if(json.status === success)
        {
            alert(success)
        }
        if(json.status === fail)
        {
            alert(fail)
        }
    }


    // Delete Category
    async function DeleteCategory(id)
    {
        var link = await fetch(`crud/delete_category.php?id_delete=${id}`)
        LinkLoadProduct()
    }
    var elementDel = document.querySelectorAll(".deleteCategory")
    elementDel.forEach(function(item)
    {
        item.addEventListener('click', function(event)
        {
            console.log(item)
            event.preventDefault();
            var idCategory = this.getAttribute('data-id-category')
            DeleteCategory(idCategory)
        })
    })
    async function LinkLoadProduct()
    {
        var link = await fetch('crud/get_all_category.php');
        var json =  await link.json();
        LoadCategory(json)
        var elementDel = document.querySelectorAll(".deleteCategory")
        elementDel.forEach(function(item)
        {
            item.addEventListener('click', function(event)
        {
            console.log(item)
            event.preventDefault();
            var idCategory = this.getAttribute('data-id-category')
            DeleteCategory(idCategory)
        })
        })
    }
    function LoadCategory(data)
    {
        var tableBody = document.querySelector('table tbody')
        tableBody.innerHTML = ""
        data.forEach(function(value)
        {
            var row = document.createElement('tr')
            row.innerHTML = 
            `
                <tr>
                    <td>${value.id}</td>
                    <td>${value.name}</td>
                    <td>
                        <img style = "width : 80px; height : 80px;" src="${value.image}" alt="">
                    </td>
                    <td>
                        <a class = "editCategory" data-id-category = ${value.id} href="">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </td>
                    <td>
                        <a class = "deleteCategory" data-id-category = ${value.id} href="">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
            `
            tableBody.appendChild(row)
        })
    }
</script>