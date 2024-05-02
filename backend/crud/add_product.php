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
                    </li>
                    <li>
                        <h4>Ảnh sản phẩm</h4>
                        <input name = "image_product" type="file">
                    </li>
                    <li>
                        <h4>Giá sản phẩm</h4>
                        <input name = "price_product" type="text">
                    </li>
                    <li>
                        <h4>Thể loại sản phẩm</h4>
                        <select name="category_product" id="">
                            <?php 
                                include_once $_SERVER['DOCUMENT_ROOT'] . "/project_web2-Copy-2/backend/database/connect.php";
                                $sql = "SELECT * FROM categories";
                                $result = DataSQL::querySQL($sql);
                                while($row = mysqli_fetch_array($result))
                                {
                                    ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                <?php }
                            ?>
                        </select>
                    </li>
                    <li>
                        <h4>Tên tác giả</h4>
                        <select name="author_product" id="">
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
                    </li>
                    <li>
                        <h4>Nhà xuất bản</h4>
                        <!-- <input name = "publisher_product" type="text"> -->
                        <select name="publisher_product" id="">
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
                    </li>
                    
                   
                    <li>
                        <h4>Số lượng</h4>
                        <input name = "quantity_product" type="text">
                    </li>
                    <li>
                        <h4>Năm xuất bản</h4>
                        <input name = "publish_year" type="text">
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
                            include_once $_SERVER['DOCUMENT_ROOT'] . "/project_web2-Copy-2/backend/database/connect.php";
                            $sql = "SELECT * FROM products";
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
                                        <a href="">Khôi phục</a>
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
    var addButton = document.querySelector('input[name="button_addProduct"]')
    async function addProduct(nameProduct, imageProduct, priceProduct, publisherProduct, quantityProduct, 
    yearProduct, detailProduct, categoryProduct, authorProduct)
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
        }
        if(json.status === fail)
        {
            alert(fail)
        }
    }
    var addButton = document.querySelector('input[name="button_addProduct"]')
    addButton.addEventListener('click', function(event)
    {
        event.preventDefault();
        var nameProduct = document.querySelector('input[name="name_product"]').value
        if(nameProduct === "")
        {
            alert("Tên không được rỗng")
        }
        var fileImage = document.querySelector('input[name="image_product"]').files[0];
        var reader = new FileReader();
        reader.onloadend = function () {
        // reader.result chứa dữ liệu ảnh dưới dạng chuỗi base64
        var base64Image = reader.result;
        var encodedBase64Image = base64Image.replace(/\+/g, '%2B'); // tìm tất cả dấu + trong chuỗi và mã hóa chúng, để tranh trường hợp
        // khi truyền qua URL sẽ bị hiểu nhầm + là khoảng trắng -> dẫn đến sai
        console.log(encodedBase64Image); // In ra chuỗi base64 của ảnh
        var priceProduct = document.querySelector('input[name="price_product"]').value
        var categoryProduct = document.querySelector('select[name="category_product"]').value
        var authorProduct = document.querySelector('select[name="author_product"]').value
        var publisherProduct = document.querySelector('select[name="publisher_product"]').value
        var quantityProduct = document.querySelector('input[name="quantity_product"]').value
        var publishYear = document.querySelector('input[name="publish_year"]').value
        var detailProduct = document.querySelector('textarea[name="detail_product"]').value
        addProduct(nameProduct, encodedBase64Image, priceProduct, publisherProduct, quantityProduct, publishYear,
        detailProduct, categoryProduct, authorProduct);
        };

        reader.readAsDataURL(fileImage);
    })
</script>


