<div class = modal>
    <div class = "modal_base">
        <div class = "productAdd">
            <form method = "POST" action = "crud/handle_addProduct.php" class = "add_product" enctype="multipart/form-data">
                <a class = "exit_add-product" href="index.php?title=product">x</a>
                <h2 class = "add_product-title">Tạo mới sản phẩm</h2>
                <ul class = "add_product-content">
                    <li>
                        <h4>Tên sản phẩm</h4>
                        <input name = "name_product" type="text">
                        <span class = "form-message"></span>
                    </li>
                    <li>
                        <h4>Ảnh sản phẩm</h4>
                        <input name = "image_product" type="file">
                        <span class = "form-message"></span>

                    </li>
                    <li>
                        <h4>Giá sản phẩm</h4>
                        <input name = "price_product" type="text">
                        <span class = "form-message"></span>
                    </li>
                    <li>
                        <h4>Thể loại sản phẩm</h4>
                        <select name="category_product" id="">
                            <option value = "0">Chọn thể loại</option>
                            <?php 
                                include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
                                $sql = "SELECT * FROM categories";
                                $result = DataSQL::querySQL($sql);
                                while($row = mysqli_fetch_array($result))
                                {
                                    ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                <?php }
                            ?>
                        </select>
                        <span class = "form-message"></span>
                    </li>
                    <li>
                        <h4>Tên tác giả</h4>
                        <select name="author_product" id="">
                            <option value = "0">Chọn tác giả</option>
                            <?php
                                include_once $_SERVER['DOCUMENT_ROOT'] . "/project_web2-Copy-2/backend/database/connect.php";
                                $sql = "SELECT * FROM authors";
                                $result = DataSQL::querySQL($sql);
                                while($row = mysqli_fetch_array($result))
                                {
                                    ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                <?php }
                            ?>
                        </select>
                        <span class = "form-message"></span>
                    </li>
                    <li>
                        <h4>Nhà xuất bản</h4>
                        <select name="publisher_product" id="">
                            <option value = "-1">Chọn nhà xuất bản</option>
                            <?php 
                                include_once $_SERVER['DOCUMENT_ROOT'] . "/project_web2-Copy-2/backend/database/connect.php";
                                $sql = "SELECT * FROM publishers";
                                $result = DataSQL::querySQL($sql);
                                while($row = mysqli_fetch_array($result))
                                {
                                    ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                <?php }
                            ?>
                        </select>
                        <span class = "form-message"></span>
                    </li>
                    
                   
                    <li>
                        <h4>Số lượng</h4>
                        <input name = "quantity_product" type="text">
                        <span class = "form-message"></span>

                    </li>
                    <li>
                        <h4>Năm xuất bản</h4>
                        <input name = "publish_year" type="text">
                        <span class = "form-message"></span>

                    </li>
                    <li style = "width : 50%; margin-top : 40px">
                        <h4>Chi tiết sản phẩm</h4>
                        <textarea style = "margin-left : 10px" name="detail_product" id="" cols="30" rows="4"></textarea>
                    </li>
                    <li style = "margin-top : 40px">
                        <input name = "button_addProduct" type="submit" value = "Thêm">
                    </li>
                </ul>
            </form>
            <div class = "product_noExist">
                <table style = "width : 100%; background-color : white" cellspacing = "0" cellpading = "10">
                    <thead>
                        <tr>
                            <th colspan = "9">
                                <a style = "font-size : 20px; color : red" href="index.php?title=product&action=add">
                                    Danh sách sản phẩm hiện không tồn tại trong hệ thống
                                </a>
                            </th>
                        </tr>
                        <tr>
                            <th style = "width : 60px">Id</th>
                            <th style = "width : 500px">Name</th>
                            <th>image</th>
                            <th>Price</th>
                            <th>Publish Year</th>
                            <th colspan = "2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
                            $sql = "SELECT * FROM products WHERE isActive = 0";
                            $result = DataSQL::querySQl($sql);
                            while($row = mysqli_fetch_array($result))
                            {
                                ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td>
                                        <img style = "width : 80px; height : 80px;" src="<?php echo $row['image']; ?>" alt="">
                                    </td>
                                    <td><?php echo $row['price']; ?></td>
                                    <td><?php echo $row['publish_year']; ?></td>
                                    <td>
                                        <a href = "" class = "restore_product" data-id-restore = <?php echo $row['id']; ?>>Khôi phục</a>
                                    </td>
                                </tr>
                            <?php }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>

<script>
    async function RestoreProduct(id)
    {
        var formData = new FormData();
        formData.append('id_restore', id);
        var link = await fetch('crud/restore_product.php', {
            method : 'POST',
            body : formData
        });
        LinkLoadProduct()
    }
    var buttonRestore = document.querySelectorAll('.restore_product')
    buttonRestore.forEach(function(item)
    {
        item.addEventListener('click', function(event)
        {
            event.preventDefault();
            var idProduct = this.getAttribute('data-id-restore')
            console.log(idProduct)
            RestoreProduct(idProduct);
        })
    })
    async function LinkLoadProduct()
    {
        var link = await fetch('crud/productNoExist.php');
        var json = await link.json();
        console.log(json);
        LoadProduct(json)
        buttonRestore = document.querySelectorAll('.restore_product');
        buttonRestore.forEach(function(item) {
            item.addEventListener('click', function(event) {
                event.preventDefault();
                var idProduct = this.getAttribute('data-id-restore');
                console.log(idProduct);
                RestoreProduct(idProduct);
            });
    });
    }
    function LoadProduct(data)
    {
        var tableBody = document.querySelector('.product_noExist tbody')
        tableBody.innerHTML = ''
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
                <td>${value.price}</td>
                <td>${value.publish_year}</td>
                <td>
                    <a href = "" class = "restore_product" data-id-restore = ${value.id}>Khôi phục</a>
                </td>
            </tr>
            `;
            tableBody.appendChild(row);
        })
    }

    var addButton = document.querySelector('input[name="button_addProduct"]')
    async function addProduct(nameProduct, imageProduct, priceProduct, publisherProduct, quantityProduct, 
    yearProduct, detailProduct, categoryProduct, authorProduct)
    {
        if(confirm("Xác nhận thêm sản phẩm?"))
        {
            var formData = new FormData();
            formData.append('name_product', nameProduct);
            formData.append('image_product', imageProduct);
            formData.append('price_product', priceProduct);
            formData.append('publisher_product', publisherProduct);
            formData.append('quantity_product', quantityProduct);
            formData.append('publish_year', yearProduct);
            formData.append('detail_product', detailProduct);
            formData.append('category_product', categoryProduct);
            formData.append('author_product', authorProduct);
    
            var link = await fetch('crud/handle_addProduct.php', {
                method: 'POST',
                body: formData
            });
            var json = await link.json();
            var success = `Thêm sản phẩm ${nameProduct} vào cửa hàng thành công`;
            var fail = `Sản phẩm ${nameProduct} đã tồn tại trong cửa hàng`;
            if(json.status === success)
            {
                alert(success)
                document.querySelector('input[name="name_product"]').value = ""
                document.querySelector('input[name="price_product"]').value = ""
                document.querySelector('select[name="category_product"]').value = "0"
                document.querySelector('select[name="author_product"]').value = "0"
                document.querySelector('select[name="category_product"]').value = "0"
                document.querySelector('select[name="publisher_product"]').value = "0"
                document.querySelector('input[name="quantity_product"]').value = ""
                document.querySelector('input[name="publish_year"]').value = ""
                document.querySelector('textarea[name="detail_product"]').value = ""
                document.querySelector('input[name="image_product"]').files[0] = ""
            }
            if(json.status === fail)
            {
                alert(fail)
            }
        }
    }
    var addButton = document.querySelector('input[name="button_addProduct"]')
    addButton.addEventListener('click', function(event)
    {
        event.preventDefault();
        var nameProduct = document.querySelector('input[name="name_product"]').value
        var priceProduct = document.querySelector('input[name="price_product"]').value
        var categoryProduct = document.querySelector('select[name="category_product"]').value
        var authorProduct = document.querySelector('select[name="author_product"]').value
        var categoryProduct = document.querySelector('select[name="category_product"]').value
        var publisherProduct = document.querySelector('select[name="publisher_product"]').value
        var quantityProduct = document.querySelector('input[name="quantity_product"]').value
        var publishYear = document.querySelector('input[name="publish_year"]').value
        var detailProduct = document.querySelector('textarea[name="detail_product"]').value
        var fileImage = document.querySelector('input[name="image_product"]').files[0];
        var firstFocus = null;
        if(nameProduct == "" || fileImage == undefined || priceProduct == "" || categoryProduct == 0 || authorProduct == 0 || publisherProduct == -1 ||
        isNaN(priceProduct) || priceProduct < 0 || quantityProduct == "" ||
        isNaN(quantityProduct) || publishYear == "" || isNaN(publishYear))
        {
            if(nameProduct == "")
            {
                var ElementP = document.querySelector('input[name="name_product"]')
                console.log(ElementP)
                var notification = ElementP.nextElementSibling;
                notification.innerText = "Tên không được rông";
                ElementP.classList.add('border-message')
                if(firstFocus == null)
                {
                    firstFocus = ElementP;
                    console.log(firstFocus)
                }

            }
            else 
            {
                var ElementP = document.querySelector('input[name="name_product"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "";
                ElementP.classList.remove('border-message')
            }
            if(fileImage == undefined)
            {
                var ElementP = document.querySelector('input[name="image_product"]')
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
                var ElementP = document.querySelector('input[name="image_product"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "";
                ElementP.classList.remove('border-message')
            }
            if(priceProduct == "")
            {
                var ElementP = document.querySelector('input[name="price_product"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "Giá không được rông";
                ElementP.classList.add('border-message')
                if(firstFocus == null)
                {
                    firstFocus = ElementP;
                }
                
            }
            else 
            {
                var ElementP = document.querySelector('input[name="price_product"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "";
                ElementP.classList.remove('border-message')
            }
            if(isNaN(priceProduct) && priceProduct != "")
            {
                var ElementP = document.querySelector('input[name="price_product"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "Giá phải là số";
                ElementP.classList.add('border-message')
                if(firstFocus == null)
                {
                    firstFocus = ElementP;
                }
                
            }
            else if(!isNaN(priceProduct) && priceProduct != "")
            {
                var ElementP = document.querySelector('input[name="price_product"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "";
                ElementP.classList.remove('border-message')
            }
            if(priceProduct <= 0 && priceProduct != "")
            {
                var ElementP = document.querySelector('input[name="price_product"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "Giá phải lớn hơn 0";
                ElementP.classList.add('border-message')
                if(firstFocus == null)
                {
                    firstFocus = ElementP;
                }
                
            }
            else if(priceProduct > 0 && priceProduct != "") 
            {
                var ElementP = document.querySelector('input[name="price_product"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "";
                ElementP.classList.remove('border-message')
            }
            if(categoryProduct == 0)
            {
                var ElementP = document.querySelector('select[name="category_product"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "Vui lòng chọn thể loại sản phẩm";
                ElementP.classList.add('border-message')
                if(firstFocus == null)
                {
                    firstFocus = ElementP;
                }
            }
            else 
            {
                var ElementP = document.querySelector('select[name="category_product"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "";
                ElementP.classList.remove('border-message')
            }
            if(authorProduct == 0)
            {
                var ElementP = document.querySelector('select[name="author_product"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "Vui lòng chọn tác giả";
                ElementP.classList.add('border-message')
                if(firstFocus == null)
                {
                    firstFocus = ElementP;
                }
            }
            else 
            {
                var ElementP = document.querySelector('select[name="author_product"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "";
                ElementP.classList.remove('border-message')
            }
            if(publisherProduct == -1)
            {
                var ElementP = document.querySelector('select[name="publisher_product"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "Vui lòng chọn nhà xuất bản";
                ElementP.classList.add('border-message')
                if(firstFocus == null)
                {
                    firstFocus = ElementP;
                }
            }
            else 
            {
                var ElementP = document.querySelector('select[name="publisher_product"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "";
                ElementP.classList.remove('border-message')
            }
            if(quantityProduct == "")
            {
                var ElementP = document.querySelector('input[name="quantity_product"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "Số lượng không được rỗng";
                ElementP.classList.add('border-message')
                if(firstFocus == null)
                {
                    firstFocus = ElementP;
                }
                
            }
            else 
            {
                var ElementP = document.querySelector('input[name="quantity_product"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "";
                ElementP.classList.remove('border-message')
            }
            if(isNaN(quantityProduct) && quantityProduct != "")
            {
                var ElementP = document.querySelector('input[name="quantity_product"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "Số lượng phải là số";
                ElementP.classList.add('border-message')
                if(firstFocus == null)
                {
                    firstFocus = ElementP;
                }
                
            }
            else if(!isNaN(quantityProduct) && quantityProduct != "")
            {
                var ElementP = document.querySelector('input[name="quantity_product"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "";
                ElementP.classList.remove('border-message')
            }
            if(quantityProduct <= 0 && quantityProduct != "")
            {
                var ElementP = document.querySelector('input[name="quantity_product"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "Số lượng phải lớn hơn 0";
                ElementP.classList.add('border-message')
                if(firstFocus == null)
                {
                    firstFocus = ElementP;
                }
                
            }
            else if(quantityProduct > 0 && quantityProduct != "")
            {
                var ElementP = document.querySelector('input[name="quantity_product"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "";
                ElementP.classList.remove('border-message')
            }
            if(publishYear == "")
            {
                var ElementP = document.querySelector('input[name="publish_year"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "Năm xuất bản ko được rỗng";
                ElementP.classList.add('border-message')
                if(firstFocus == null)
                {
                    firstFocus = ElementP;
                }
                
            }
            else 
            {
                var ElementP = document.querySelector('input[name="publish_year"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "";
                ElementP.classList.remove('border-message')
            }
            if(isNaN(publishYear) && publishYear != "")
            {
                var ElementP = document.querySelector('input[name="publish_year"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "Năm xuất bản phải là số";
                ElementP.classList.add('border-message')
                if(firstFocus == null)
                {
                    firstFocus = ElementP;
                }
                
            }
            else if(!isNaN(publishYear) && publishYear != "")
            {
                var ElementP = document.querySelector('input[name="publish_year"]')
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
            console.log(encodedBase64Image); // In ra chuỗi base64 của ảnh
            addProduct(nameProduct, encodedBase64Image, priceProduct, publisherProduct, quantityProduct, publishYear,
            detailProduct, categoryProduct, authorProduct);
            document.querySelectorAll('.form-message').forEach(element => {
                    element.innerText = "";
                });
                document.querySelectorAll('.border-message').forEach(element => {
                    element.classList.remove('border-message');
                });
            }
            reader.readAsDataURL(fileImage);
        }
    })
</script>


