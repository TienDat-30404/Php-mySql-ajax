
<table style = "width : 100%; background-color : white" cellspacing = "0" cellpading = "10">
    <thead>
        <tr>
            <th colspan = "9">
                <a href="index.php?title=product&action=add">Add Product</a>
            </th>
        </tr>
        <tr>
            <th style = "width : 60px">Id</th>
            <th style = "width : 500px">Name</th>
            <th>image</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Publish Year</th>
            <th colspan = "2">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php 
              include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
            $sql = "SELECT * FROM products WHERE isActive = 1";
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
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['publish_year']; ?></td>
                    <td>
                        <a data-id-product = <?php echo $row['id']; ?> class = "elementEdit" href="">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </td>
                    <td>
                        <a class = "elementDel" data-id-product = <?php echo $row['id']; ?> href="">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php }
        ?>
    </tbody>
</table>

<script>
    async function deleteProduct(id)
    {
        var link = await fetch(`crud/delete_product.php?id_delete=${id}`)
        LinkLoadProduct()
    }
    var elementDel = document.querySelectorAll(".elementDel")
    elementDel.forEach(function(item)
    {
        item.addEventListener('click', function(event)
    {
        event.preventDefault();
        var idProduct = this.getAttribute('data-id-product')
        deleteProduct(idProduct)
    })
    })
    async function LinkLoadProduct()
    {
        var link = await fetch('crud/get_all_product.php');
        var json =  await link.json();
        LoadProduct(json)
        var elementDel = document.querySelectorAll(".elementDel")
        elementDel.forEach(function(item)
        {
            item.addEventListener('click', function(event)
        {
            event.preventDefault();
            var idProduct = this.getAttribute('data-id-product')
            deleteProduct(idProduct)
        })
        })
    }
    function LoadProduct(data)
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
                    <td>${value.price}</td>
                    <td>${value.publish_year}</td>
                    <td>
                        <a href=index.php?title=product&action=edit&id_edit=${value.id}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </td>
                    <td>
                        <a class = "elementDel" data-id-product = ${value.id} href="">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
            `
            tableBody.appendChild(row)
        })
    }

    async function EditProduct(id)
    {
        var link = await fetch(`crud/edit_product.php?id_edit=${id}`)
        var json = await link.json();
        console.log(json)
        DisplayEditProduct(json)
        
    }
    var checkExit = false
    var elementEdit = document.querySelectorAll('.elementEdit')
    elementEdit.forEach(function(item)
    {
        item.addEventListener('click', function(event)
        {
            checkExit = true
           event.preventDefault();
           var idProduct = this.getAttribute('data-id-product')
           EditProduct(idProduct)
        })
    })
    async function HandleEditProduct(idProduct, nameProduct, imageProduct, priceProduct, publisherProduct, quantityProduct, 
    yearProduct, detailProduct, categoryProduct, authorProduct)
    {
        var formData = new FormData();
        formData.append('id_product', idProduct);
        formData.append('name_product', nameProduct);
        formData.append('image_product', imageProduct);
        formData.append('price_product', priceProduct);
        formData.append('publisher_product', publisherProduct);
        formData.append('quantity_product', quantityProduct);
        formData.append('publish_year', yearProduct);
        formData.append('detail_product', detailProduct);
        formData.append('category_product', categoryProduct);
        formData.append('author_product', authorProduct);
        
        var link = await fetch('crud/handle_editProduct.php', {
            method: 'POST',
            body: formData
        });
        var json = await link.json();
        var success = `Chỉnh sửa sản phẩm ${nameProduct} thành công`
        var fail = `Sản phẩm ${nameProduct} đã tồn tại. Không thể chỉnh sửa sản phẩm này`
        if(json.success === success)
        {
            alert(success)
        }
        if(json.fail === fail)
        {
            alert(fail)
        }
    }

    function DisplayEditProduct(data)
    {
        data.informations.forEach(function(value)
        {
            var nameCategory = value.category_name
            var nameAuthor = value.author_name
            var namePublisher = value.publisher_name
            if(value.detail == null)
            {
                value.detail = ""
            }
            var editProduct = 
            `
            <div class = modal>
                <div class = "modal_base">
                    <div class = "productEdit">
                        <form method = "" action = "" class = "edit_product" enctype="multipart/form-data">
                                            
                            <a href = "index.php?title=product" style = "cursor : pointer;" class = "exit_edit-product">x</a>
                            <h2 class = "edit_product-title">Chỉnh sửa sản phẩm</h2>
                            <ul class = "edit_product-content">
                            
                                <input type="hidden" name = "id_product" value = "${value.id}">
                                <li>
                                    <h4>Tên sản phẩm</h4>
                                    <input style = "width : 400px" value = "${value.product_name}" name = "name_product" type="text">
                                    <span class = "form-message"></span>
                                </li>
                                <li>
                                    <h4>Ảnh sản phẩm</h4>
                                    <input name = "image_product" type="file">
                                    <img class = "image_display" style = "width : 60px; height : 40px" src="${value.image}" alt="">
                                    <span class = "form-message"></span>

                                </li>
                                <li>
                                    <h4>Giá sản phẩm</h4>
                                    <input value = "${value.price}" name = "price_product" type="text">
                                    <span class = "form-message"></span>
                                </li>
                                <li>
                                    <h4>Thể loại sản phẩm</h4>
                                    <select name="category_product">
                                        
                                    </select>
                                </li>
                                <li>
                                    <h4>Tên tác giả</h4>
                                    <select name="author_product" id="">
                                        
                                    </select>
                                </li>
                                <li>
                                    <h4>Nhà xuất bản</h4>
                                    <select name="publisher_product" id="">
                                        
                                    </select>
                                </li>
                                
                            
                                <li>
                                    <h4>Số lượng</h4>
                                    <input value = "${value.quantity}" name = "quantity_product" type="text">
                                    <span class = "form-message"></span>

                                </li>
                                <li>
                                    <h4>Năm xuất bản</h4>
                                    <input value = "${value.publish_year}" name = "publish_year" type="text">
                                    <span class = "form-message"></span>

                                </li>
                                <li style = "width : 50%; margin-top : 40px">
                                    <h4>Chi tiết sản phẩm</h4>
                                    <textarea style = "margin-left : 10px" name="detail_product" id="" cols="30" rows="4">${value.detail}</textarea>
                                </li>
                                <li>
                                    <input name = "button_editProduct" type="submit" value = "Chỉnh sửa">
                                </li>
                            </ul>
                        </form>
                    </div>
                    
                </div>
            </div>  
            `
            document.body.insertAdjacentHTML('beforeend', editProduct);
            

            // Add combobox category
            var categorySelect = document.querySelector('select[name="category_product"]');
            data.categories.forEach(function(value)
            {
                var option = document.createElement('option')
                option.text = value.name
                option.value=  value.id
                if(nameCategory == value.name)
                {
                    option.selected = true
                }
                categorySelect.appendChild(option)
            })

            // add combobox author
            var authorSelect = document.querySelector('select[name="author_product"]');
            data.authors.forEach(function(value)
            {

                var option = document.createElement('option')
                option.text = value.name
                option.value=  value.id
                if(nameAuthor == value.name)
                {
                    option.selected = true
                }
                authorSelect.appendChild(option)
            })

            // add combobox publisher
            var publishersSelect = document.querySelector('select[name="publisher_product"]');
            data.publishers.forEach(function(value)
            {
                var option = document.createElement('option')
                option.text = value.name
                option.value=  value.id
                if(namePublisher == value.name)
                {
                    option.selected = true
                }
                publishersSelect.appendChild(option)
            })
            var fileInput = document.querySelector('input[name="image_product"]');
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

            // handle button edit
            var editButton = document.querySelector('input[name="button_editProduct"]')
            editButton.addEventListener('click', function(event)
            {
                event.preventDefault();
                var idProduct = document.querySelector('input[name="id_product"]').value
                var nameProduct = document.querySelector('input[name="name_product"]').value
                var priceProduct = document.querySelector('input[name="price_product"]').value
                var categoryProduct = document.querySelector('select[name="category_product"]').value
                var authorProduct = document.querySelector('select[name="author_product"]').value
                var publisherProduct = document.querySelector('select[name="publisher_product"]').value
                var quantityProduct = document.querySelector('input[name="quantity_product"]').value
                var publishYear = document.querySelector('input[name="publish_year"]').value
                var detailProduct = document.querySelector('textarea[name="detail_product"]').value
                var fileImage = document.querySelector('input[name="image_product"]').files[0];
                var firstFocus = null;
                if(nameProduct == "" ||  priceProduct == "" || isNaN(priceProduct) || priceProduct < 0 || quantityProduct == "" ||
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
                    if(fileImage)
                    {
                        var reader = new FileReader();
                        reader.onloadend = function () {
                        // reader.result chứa dữ liệu ảnh dưới dạng chuỗi base64
                        var base64Image = reader.result;
                        var encodedBase64Image = base64Image.replace(/\+/g, '%2B'); // tìm tất cả dấu + trong chuỗi và mã hóa chúng, để tranh trường hợp
                        // khi truyền qua URL sẽ bị hiểu nhầm + là khoảng trắng -> dẫn đến sai
                        console.log(encodedBase64Image); // In ra chuỗi base64 của ảnh
                        
                        HandleEditProduct(idProduct, nameProduct, encodedBase64Image, priceProduct, publisherProduct, quantityProduct, publishYear,
                        detailProduct, categoryProduct, authorProduct);
                    }
                    reader.readAsDataURL(fileImage);
                    }
                    else 
                    {
                        var imageDisplay = document.querySelector('.image_display');
                        var base64Image = imageDisplay.src;
                        var encodedBase64Image = base64Image.replace(/\+/g, '%2B');
                        console.log(encodedBase64Image);

                        HandleEditProduct(idProduct, nameProduct, encodedBase64Image, priceProduct, publisherProduct, quantityProduct, publishYear,
                        detailProduct, categoryProduct, authorProduct);
                    }
                }
            })

             // Exit Edit 
            // var exitEdit = document.querySelector('.exit_edit-product')
            // var modalEdit = document.querySelector('.modal')
            // console.log(checkExit)
            // exitEdit.addEventListener('click', function(event)
            // {
            //     event.preventDefault();
            //     if(checkExit == true)
            //     {
            //         document.querySelector('.modal').style.display = "none"
            //         checkExit = true
            //     }
            // })
        })
    }

   
    
    
</script>