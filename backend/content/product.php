
<table class = "tableMain" style = "width : 100%; background-color : white" cellspacing = "0" cellpading = "10">
    
</table>
<div class = "pagination"></div>
<script>

    async function deleteProduct(id)
    {
        if(confirm("Xác nhận xóa sản phẩm?"))
        {
            var formData = new FormData()
            formData.append('choice', 'delete_product')
            formData.append('id_delete', id)
            var link = await fetch('crud/product_api.php', {
                method : 'POST',
                body : formData
            })
            DisPlayMain()
        }
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

    async function EditProduct(id)
    {
        var formData = new FormData();
        formData.append('choice', 'display_edit_product')
        formData.append('id_edit', id)
        var link = await fetch('crud/product_api.php', {
            method : 'POST',
            body : formData 
        });
        var json = await link.json();
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
        if(confirm("Xác nhận chỉnh sửa sản phẩm?"))
        {
            var formData = new FormData();
            formData.append('choice', 'handle_edit_product');
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
            
            var link = await fetch('crud/product_api.php', {
                method: 'POST',
                body: formData
            });
            var json = await link.json();
            var success = `Chỉnh sửa sản phẩm ${nameProduct} thành công`
            var fail = `Sản phẩm ${nameProduct} đã tồn tại. Không thể chỉnh sửa sản phẩm này`
            if(json.success === success)
            {
                alert(success)
                DisPlayMain()
            }
            if(json.fail === fail)
            {
                alert(fail)
            }
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
                                            
                            <a href = "" style = "cursor : pointer;" class = "exit_crud-all">x</a>
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

                        HandleEditProduct(idProduct, nameProduct, encodedBase64Image, priceProduct, publisherProduct, quantityProduct, publishYear,
                        detailProduct, categoryProduct, authorProduct);
                    }
                }
            })

            var detailExit = document.querySelector('.exit_crud-all');
            var modal = document.querySelector('.modal');
            detailExit.addEventListener('click', function(e)
            {
                e.preventDefault();
                modal.remove();
            })

        })
    }

   
    // Search ----------------------------------------------------------------
    var currentPage = 1
    var pageSize = 5
    var pagination = document.querySelector('.pagination')
    function DisplayProduct(data, element)
    {
        var informations
        if(data.number != 0)
        {

            document.querySelector('table').innerHTML = ""
            informations =
                `<thead>
                        <tr>
                            <th colspan = "9">
                                <a class = "click_add-product" href="">Add Product</a>
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
                    <tbody>`
                        data.informations.forEach(function(value)
                        {
                            informations += `
                            <tr>
                                <td>${value.id}</td>
                                <td>${value.nameProduct}</td>
                                <td>
                                    <img style = "width : 80px; height : 80px;" src="${value.image}" alt="">
                                </td>
                                <td>${value.price}</td>
                                <td>${value.quantity}</td>
                                <td>${value.publish_year}</td>
                                <td>
                                    <a data-id-product = "${value.id}" class = "elementEdit" href="">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        <h5 style="color: green; display: inline-block; vertical-align: middle; margin-left: 5px;">Chỉnh sửa</h5>
                                    </a>
                                </td>
                                <td>
                                    <a class = "elementDel" data-id-product = "${value.id}" href="">
                                        <i style = "color : red" class="fa-solid fa-trash"></i>
                                        <h5 style="color: green; display: inline-block; vertical-align: middle; margin-left: 5px;">Xóa</h5>
                                    </a>
                                </td>
                            </tr>
                            `
                        })
                    informations += `
                    </tbody>
                `
        }
        else 
        {
            informations = ""
        }
        
        document.querySelector('.tableMain').innerHTML = informations

        var addProduct = document.querySelector('.click_add-product')
        addProduct.addEventListener('click', function(e)
        {
            console.log("123")
            e.preventDefault()
            AddProduct()
        })

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

        var elementDel = document.querySelectorAll(".elementDel")
        elementDel.forEach(function(item)
        {
            item.addEventListener('click', function(event)
        {
            event.preventDefault();
            var idProduct = this.getAttribute('data-id-product')
            deleteProduct(idProduct)
            document.querySelector('.container_right-header-search input[type="text"]').value = ""
            document.querySelector("#select_search-product").value = "0"
            DisPlayMain()

        })
        })
    }
    async function DisPlayMain()
    {
        var formData = new FormData();
        formData.append('choice', 'default_display_product')
        formData.append('page', currentPage);
        formData.append('pageSize', pageSize);
        var response = await fetch(`crud/product_api.php`, {
            method : 'POST',
            body : formData
        });
        var json = await response.json()
        console.log(json)
        console.log("123")
        DisplayProduct(json, "table")
        DisplayPagination(json, 2)
    }
    DisPlayMain()
    async function SearchIdProduct(idProduct)
    {
        var formData = new FormData();
        formData.append('choice', 'search_id_product')
        formData.append('inputSearchName', idProduct)
        formData.append('page', currentPage)
        formData.append('pageSize', pageSize)
        var response = await fetch(`crud/product_api.php`, {
            method : 'POST',
            body : formData
        });
        var json = await response.json()
        DisplayProduct(json, "table")
    }
    async function SearchAdvanced(nameSearch, categorySearch, priceFrom, priceTo)
    {
        var formData = new FormData();
        formData.append('choice', 'search_product')
        formData.append('nameSearchAdvanced', nameSearch)
        formData.append('search_select', categorySearch)
        formData.append('priceFrom', priceFrom)
        formData.append('priceTo', priceTo)
        formData.append('page', currentPage)
        formData.append('pageSize', pageSize)
        var response = await fetch(`crud/product_api.php`, {
            method : 'POST',
            body : formData
        });
        var json = await response.json()
        console.log(json)
        DisplayProduct(json, "table")
        DisplayPagination(json, 0) 
    }
    
    var copySearchName
    var copySearchPriceFrom
    var copySearchPriceTo
    var checkSelect = document.querySelector("#select_search-product")
    checkSelect.addEventListener("change", function(e)
    {
        document.querySelector('.container_right-header-search input[name="inputName"]').value = ""
        document.querySelector('input[name="inputFrom"]').value = ""
        document.querySelector('input[name="inputTo"]').value = ""
        if(checkSelect.value == 1)
        {
            document.querySelector('input[name="inputFrom"]').disabled = true 
            document.querySelector('input[name="inputTo"]').disabled = true
        }
        else 
        {
            document.querySelector('input[name="inputFrom"]').disabled = false 
            document.querySelector('input[name="inputTo"]').disabled = false
        }

    })

    // Xu li an submit tìm kiếm
    document.querySelector('.button_search').addEventListener('click', function(event)
    {
        currentPage = 1
        pagination.innerHTML = ""
        var inputSearch = document.querySelector('.container_right-header-search input[type="text"]').value
        copySearchName = inputSearch
        var categorySearchAdvanced = 0
        var priceFrom = document.querySelector('input[name="inputFrom"]').value
        copySearchPriceFrom = priceFrom
        var priceTo = document.querySelector('input[name="inputTo"]').value
        copySearchPriceTo = priceTo
        event.preventDefault();
      
        var indexSelect = checkSelect.value
        if(indexSelect == 0)
        {
            if(copySearchPriceFrom == "" || copySearchPriceTo == "" || copySearchName == "")
            {       
                if((copySearchPriceFrom == "" || copySearchPriceTo == "") && copySearchName == "")
                {
                    SearchAdvanced("", 0, "", "")
                }
                else if((copySearchPriceFrom == "" || copySearchPriceTo == "") && copySearchName != "")
                {
                    SearchAdvanced(copySearchName, 0, copySearchPriceFrom, copySearchPriceTo);
                }
                else if((copySearchPriceFrom != "" && copySearchPriceTo != "") && copySearchName == "")
                {
                    SearchAdvanced(copySearchName, 0, copySearchPriceFrom, copySearchPriceTo);
                }
            }  
            else 
            {
                SearchAdvanced(inputSearch, categorySearchAdvanced, priceFrom, priceTo);
            }
        }
        else if(indexSelect == 1)
        {
            SearchIdProduct(inputSearch)
        }

    })


    function DisplayPagination(data, check) {
    pagination.innerHTML = "";
    var maxPage = Math.ceil(data.number/ pageSize);
    console.log(maxPage)
    var start = 1;
    var end = maxPage;
    if(currentPage > 2 && maxPage > 3 && currentPage < maxPage)
    {
        start = currentPage - 1;
        end = currentPage + 1;
    }
    else if(currentPage == maxPage && maxPage > 3)
    {
        start = currentPage - 2;
        end = maxPage;
    }
    else if(maxPage > 3 && currentPage <= 2)
    {
        end = 3;
    }
    else if(maxPage == 1)
    {
        pagination.classList.add('hide');
    }
    if(maxPage > 1)
    {
        pagination.classList.remove('hide')
    }
    if(currentPage > 1)
    {
        var prevPage = document.createElement('li');
        prevPage.innerText = "Prev";
        prevPage.setAttribute('onclick', "ChangePage(" + (currentPage - 1) +", " + check + ")");
        pagination.appendChild(prevPage);
    }
    for (var i = start; i <= end; i++) {
        var pageButton = document.createElement('li');
        pageButton.innerText = i;
        if(i == currentPage)
        {
            pageButton.classList.add('headPage')
        }
        pageButton.setAttribute('onclick', "ChangePage(" + (i) + ", " + check + ")")
        pagination.appendChild(pageButton);
    }
    if(currentPage < maxPage)
    {
        var nextPage = document.createElement('li')
        nextPage.innerText = "Next";
        nextPage.setAttribute('onclick', "ChangePage(" + (currentPage + 1) +", " + check + ")");
        pagination.appendChild(nextPage)
    }
    }
    function ChangePage(index, check)
    {
        currentPage = index;
        if(check == 0)
        {
            if(copySearchPriceFrom == "" || copySearchPriceTo == "" || copySearchName == "")
            {       
                if((copySearchPriceFrom == "" || copySearchPriceTo == "") && copySearchName == "")
                {
                    DisPlayMain()

                }
                else if((copySearchPriceFrom == "" || copySearchPriceTo == "") && copySearchName != "")
                {
                    SearchAdvanced(copySearchName, 0, copySearchPriceFrom, copySearchPriceTo);
                }
                else if((copySearchPriceFrom != "" && copySearchPriceTo != "") && copySearchName == "")
                {
                    SearchAdvanced(copySearchName, 0, copySearchPriceFrom, copySearchPriceTo);
                }
            }
            else 
            {
                SearchAdvanced(copySearchName, 0, copySearchPriceFrom, copySearchPriceTo);
            }
        }
        else if(check == 1)
        {
            SearchIdProduct(copySearchName)
        }
        else if(check == 2)
        {
            DisPlayMain()
        }
    }

    async function HandleAddProduct(nameProduct, imageProduct, priceProduct, publisherProduct, quantityProduct, 
    yearProduct, detailProduct, categoryProduct, authorProduct)
    {
        if(confirm("Xác nhận thêm sản phẩm?"))
        {
            var formData = new FormData();
            formData.append('choice', 'add_product')
            formData.append('name_product', nameProduct);
            formData.append('image_product', imageProduct);
            formData.append('price_product', priceProduct);
            formData.append('publisher_product', publisherProduct);
            formData.append('quantity_product', quantityProduct);
            formData.append('publish_year', yearProduct);
            formData.append('detail_product', detailProduct);
            formData.append('category_product', categoryProduct);
            formData.append('author_product', authorProduct);
            
            var link = await fetch('crud/product_api.php', {
                method: 'POST',
                body: formData
            });
            var json = await link.json();
            var success = `Thêm sản phẩm ${nameProduct} vào cửa hàng thành công`;
            var fail = `Sản phẩm ${nameProduct} đã tồn tại trong cửa hàng`;
            if(json.status === success)
            {
                alert(success)
                DisPlayMain()
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
    function AddProduct()
    {

        var addProduct = `
            <div class = "modal">
                <div class = "modal_base">
                    <div class = "productAdd">
                        <form method = "POST" action = "" class = "add_product" enctype="multipart/form-data">
                            <a class = "exit_crud-all" href="">x</a>
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
                                            include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
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
                                            include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
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
                                            <a class = "clickA_add-product" style = "font-size : 20px; color : red" href="">
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
                                        $isActive = 0;
                                        $sql = "SELECT * FROM products WHERE isActive = ?";
                                        $result = DataSQL::querySQlAll($sql, [$isActive]);
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
        `
        document.body.insertAdjacentHTML('beforeend', addProduct);
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
                HandleAddProduct(nameProduct, encodedBase64Image, priceProduct, publisherProduct, quantityProduct, publishYear,
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

        var detailExit = document.querySelector('.exit_crud-all');
        var modal = document.querySelector('.modal');
        detailExit.addEventListener('click', function(e)
        {
            e.preventDefault();
            modal.remove(); 
        })
    }

    
    //
    async function RestoreProduct(id)
    {
        var formData = new FormData();
        formData.append('choice', 'restore_product')
        formData.append('id_restore', id);
        var link = await fetch('crud/product_api.php', {
            method : 'POST',
            body : formData
        });
        LinkLoadProductNoExist()
    }
    
    async function LinkLoadProductNoExist()
    {
        var formData = new FormData();
        formData.append('choice', 'product_noExist')
        var link = await fetch('crud/product_api.php', {
            method : 'POST',
            body : formData
        })
        var json = await link.json();
        console.log(json);
        LoadProductNoExist(json)
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
    function LoadProductNoExist(data)
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

    
    
</script>