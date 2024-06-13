
<table style = "width : 100%; background-color : white" cellspacing = "0" cellpading = "10">
    <thead>
        <tr>
            <th colspan = "7">
                <a class = "addReceipt" href="">Create Receipt</a>
            </th>
        </tr>
        <tr>
            <th>Id</th>
            <th>Staff</th>
            <th>Date Created</th>
            <th>Total Price</th>
            <th>Supplier</th>
            <th>Chi tiết</th>
            <th>Xóa</th>
        </tr>
    </thead>
    <tbody>
        <?php 
              include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
            $sql = "SELECT * FROM entry_slips";
            $result = DataSQL::querySQl($sql);
            while($row = mysqli_fetch_array($result))
            {
                ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['staff_id']; ?></td>
                    <td><?php echo $row['date_entry']; ?></td>
                    <td><?php echo $row['total_price']; ?></td>
                    <td><?php echo $row['supplier_id']; ?></td>
                    <td>
                        <a data-id-receipt = <?php echo $row['id']; ?> class = "detailReceipt" href="">
                            <i style = "color : blue" class="fa-solid fa-circle-info"></i>
                            <h5 style=" display: inline-block; vertical-align: middle; margin-left: 5px;">Chi tiết</h5>
                        </a>
                    </td>
                    <td>
                        <a data-id-receipt = <?php echo $row['id']; ?> class = "deleteReceipt" href="">
                            <i style = "color : red" class="fa-solid fa-trash"></i>
                            <h5 style=" display: inline-block; vertical-align: middle; margin-left: 5px;">Xóa</h5>
                        </a>
                    </td>
                </tr>
            <?php }
        ?>
    </tbody>
</table>
<div class = "pagination"></div>
<script>

    // Detail Order -------------------------------------------------------------------------------
    async function DetailReceipt(id)
    {
        var formData = new FormData()
        formData.append('choice', 'detail_receipt')
        formData.append('id_receipt', id)
        var link = await fetch(`crud/receipt_api.php`, {
            method : 'POST',
            body : formData
        })
        var json = await link.json();
        DisplayDetailReceipt(json)
    }
    var elementEdit = document.querySelectorAll('.detailReceipt')
    elementEdit.forEach(function(item)
    {
        item.addEventListener('click', function(event)
        {
            checkExit = true
           event.preventDefault();
           var idReceipt = this.getAttribute('data-id-receipt')
           DetailReceipt(idReceipt)
        })
    })
    function DisplayDetailReceipt(data)
    {
        var modal = document.createElement('div');
        modal.className = 'modal';

        var modalBase = document.createElement('div');
        modalBase.className = 'modal_base';
        data.forEach(function(value)
        {
            var detailReceipt = 
            `
                    <div class = "detail_order">
                        <form  class = "edit_category" >               
                            <a href = "index.php?title=receipt" style = "cursor : pointer;" class = "exit_edit-category">x</a>
                            <h2 class = "edit_category-title">Chi tiết phiếu nhập</h2>
                            <div class = "information_order">
                                <h3>Nhân viên nhập hàng : </h3>
                                <h4>${value.nameStaff}</h4>
                                <span>|</span>
                                <h3>Ngày nhập : </h3>
                                <h4>${value.dateCreate}</h4>
                                <span>|</span>
                                <h3>Nhà cung cấp : </h3>
                                <h4>${value.nameSupplier}</h4>
                            </div>
                            <div style = "display : flex; align-items : center; margin : auto 0; transform : translateX(45%)">
                                <h3>Tổng tiền : </h3>
                                <h1 style = "color : red">${value.totalPrice}</h1>
                            </div>
                            `
                            
                            for (var i = 0; i < data.length; i++) {
                                detailReceipt += `
                                <ul style = "margin-top : 0" class = "information_detail-order">
                                        <li>
                                            <h4 style = "width : 100px">Sản phẩm</h4>
                                            <input name="id_publisher" style="outline: none; border: 1px solid black; background-color: transparent" value="${data[i].nameProduct}" type="text" readonly>
                                        </li>
                                    <li>
                                        <h4 style = "margin-right : 10px;">Ảnh</h4>
                                        <img style = "width : 50px; height : 50px;" src="${data[i].imageProduct}" alt="">
                                    </li>
                                    <li>
                                        <h4>Giá</h4>
                                        <input name="id_publisher" style="outline: none; width : 100px; text-align : center; border: 1px solid black; background-color: transparent" value="${data[i].priceProduct}" type="text" readonly>
                                    </li>
                                    <li style = "padding-top : 0">
                                        <h4>Số lượng : </h4>
                                        <h4 style = "color : red">${data[i].quantityProduct}</h4>
                                    </li>
                                </ul>
                                    `
                                    
                            }
                            detailReceipt += `</form></div>`
                                                    
            var tempDiv = document.createElement('div');
            tempDiv.innerHTML = detailReceipt;
            modalBase.appendChild(tempDiv);

        })
        modal.appendChild(modalBase);
        document.body.appendChild(modal)
    }


    var informations = []
    var stt = 0
   // Create Receipt --------------------------------------------------------------------------------------------------------
   function CreateReceipt()
    {
        var createReceipt = 
        `
        <div class = modal>
            <div class = "modal_base">
                <div style = "height : 70%" class = "productAdd">
                    <form class = "add_product">
                        <a class = "exit_add-product" href="index.php?title=receipt">x</a>
                        <h2 class = "add_product-title">Nhập hàng sản phẩm</h2>
                        <ul class = "add_product-content">
                            <li>
                                <h4>Tên sản phẩm</h4>
                                <select name="name_product" id="">
                                    <option value = "0">Chọn sản phẩm</option>
                                    <?php 
                                        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
                                        $sql = "SELECT * FROM products";
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
                                <h4>Giá sản phẩm</h4>
                                <input name = "price_product" type="text">
                                <span class = "form-message"></span>
                            </li>
                            <li>
                                <h4>Số lượng</h4>
                                <input name = "quantity_product" type="text">
                                <span class = "form-message"></span>

                            </li>
                            <li>
                                <h4>Nhà cung cấp</h4>
                                <select name="name_supplier" id="">
                                    <option value = "0">Chọn nhà cung cấp</option>
                                    <?php 
                                        include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
                                        $sql = "SELECT * FROM suppliers";
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
                                <h4>Tên nhân viên</h4>
                                <?php 
                                    include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
                                    if(isset($_SESSION['account']))
                                    {
                                        $idStaff = $_SESSION['account']['id_user'];
                                        $sql = "SELECT * FROM users WHERE users.id = '$idStaff'";
                                        $result = DataSQL::querySQL($sql);
                                        $row = mysqli_fetch_array($result);
                                    }
                                ?>
                                <input style="outline: none; border: 1px solid black; background-color: transparent" id-data-staff = <?php echo $row['id']; ?>
                                value = "<?php echo $row['fullname']; ?>" name = "name_staff" type="text" readonly>
                                <span class = "form-message"></span>
                            </li>
                            <li style = "margin-top : 40px">
                                <input name = "button_addReceipt" type="submit" value = "Thêm">
                            </li>
                        </ul>
                    </form>
                    <div style = "height : 200px" class = "product_noExist">
                        <table style = "width : 100%; background-color : white" cellspacing = "0" cellpading = "10">
                            <thead>
                                <tr>
                                    <th colspan = "9">
                                        <a style = "font-size : 20px; color : red" href="index.php?title=product&action=add">
                                            Sản phẩm hiện đang thêm
                                        </a>
                                    </th>
                                </tr>
                                <tr>
                                    <th style = "width : 500px">Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody style = "overflow-y : auto;" class = "tbodyReceipt">`;
                            if(informations.length > 0)
                                {
                                    for(var i = 0; i < informations.length; i++)
                                    {
                                        var dataReceipt = informations[i];
                                        createReceipt += `
                                            <tr>
                                                <td>${dataReceipt.nameProduct}</td>
                                                <td>${dataReceipt.priceProduct}</td>
                                                <td>${dataReceipt.quantityProduct}</td>
                                                <td>
                                                    <a id-data-create-receipt = "${value.stt}" class = "delete_create-receipt" href="">
                                                        <i style = "color : red" class="fa-solid fa-trash"></i>
                                                        <h5 style=" display: inline-block; vertical-align: middle; margin-left: 5px;">Xóa</h5>
                                                    </a>
                                                </td>
                                            </tr>
                                        `
                                    }
                                }
                            createReceipt += `
                            </tbody>
                        </table>
                        <button class = "acceptReceipt">Xác nhận</button>
                    </div>
                </div> 
            </div>
        </div>
        `;
        document.body.insertAdjacentHTML('beforeend', createReceipt);
        // button add 
        var addButton = document.querySelector('input[name="button_addReceipt"]')
        var copyNameSupplier;
        addButton.addEventListener('click', function(event)
        {
            event.preventDefault();
            var nameStaff = document.querySelector('input[name="name_staff"]').value
            var idProduct = document.querySelector('select[name="name_product"]').value.trim()
            var nameProductSelect = document.querySelector('select[name="name_product"]');
            var nameProduct = nameProductSelect.options[nameProductSelect.selectedIndex].text;
            var priceProduct = document.querySelector('input[name="price_product"]').value
            var quantityProduct = document.querySelector('input[name="quantity_product"]').value
            var nameSupplier = document.querySelector('select[name="name_supplier"]').value
            copyNameSupplier = nameSupplier
            var firstFocus = null;
            if( nameProductSelect.value == 0 || priceProduct == "" ||  quantityProduct == "" || isNaN(priceProduct) || priceProduct < 0 || 
            quantityProduct <= 0 || isNaN(quantityProduct) || nameSupplier == 0)
            {   
                if(nameProductSelect.value == 0)
                {
                    var ElementP = document.querySelector('select[name="name_product"]')
                    var notification = ElementP.nextElementSibling;
                    notification.innerText = "Vui lòng chọn sản phẩm";
                    ElementP.classList.add('border-message')
                    if(firstFocus == null)
                    {
                        firstFocus = ElementP;
                    }
                }
                else 
                {
                    var ElementP = document.querySelector('select[name="name_product"]')
                    var notification = ElementP.nextElementSibling;
                    notification.innerText = "";
                    ElementP.classList.remove('border-message')
                }
                if(priceProduct == "")
                {
                    var ElementP = document.querySelector('input[name="price_product"]')
                    var notification = ElementP.nextElementSibling;
                    notification.innerText = "Giá không được rỗng";
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
                if(nameSupplier == 0)
                {
                    var ElementP = document.querySelector('select[name="name_supplier"]')
                    var notification = ElementP.nextElementSibling;
                    notification.innerText = "Vui lòng chọn nhà cung cấp";
                    ElementP.classList.add('border-message')
                    if(firstFocus == null)
                    {
                        firstFocus = ElementP;
                    }
                }
                else 
                {
                    var ElementP = document.querySelector('select[name="name_supplier"]')
                    var notification = ElementP.nextElementSibling;
                    notification.innerText = "";
                    ElementP.classList.remove('border-message')
                }
                firstFocus.focus();
            }

            else 
            {
                var dataReceipt = {
                    stt : stt,
                    idProduct : idProduct,
                    nameProduct: nameProduct,
                    priceProduct: priceProduct,
                    quantityProduct: quantityProduct,
                };
                informations.push(dataReceipt);
                stt++
                console.log(informations)
                UpdateDisplayReceipt(informations)
                document.querySelectorAll('.form-message').forEach(element => {
                    element.innerText = "";
                });
                document.querySelectorAll('.border-message').forEach(element => {
                    element.classList.remove('border-message');
                });
                nameProductSelect.value = "0";
                document.querySelector('input[name="price_product"]').value = "";
                document.querySelector('input[name="quantity_product"]').value = "";
            }
        })
        // button accept
        var acceptReceipt = document.querySelector('.acceptReceipt')
        acceptReceipt.addEventListener('click', function(event)
        {
            var idStaffReceipt = document.querySelector('input[name="name_staff"]').getAttribute('id-data-staff')
            if(informations.length == 0)
            {
                alert("Vui lòng nhập hàng")
                return;
            }
            HandleCreateReceipt(informations, idStaffReceipt, copyNameSupplier);
        })


    }


    var addReceipt = document.querySelector('.addReceipt')
    addReceipt.addEventListener('click', function(e)
    {
        e.preventDefault()
        CreateReceipt()
    })


    // Load Table --------------------------------------------------------------
    function UpdateDisplayReceipt(data)
    {
        var tableBody = document.querySelector('.tbodyReceipt')
        tableBody.innerHTML = ""
        data.forEach(function(value, index)
        {
            var row = document.createElement('tr')
            row.innerHTML= 
            `
            <tr>
                <td>${value.nameProduct}</td>
                <td>${value.priceProduct}</td>
                <td>${value.quantityProduct}</td>
                <td>
                    <a id-data-create-receipt = "${value.stt}" class = "delete_create-receipt" href="">
                        <i style = "color : red" class="fa-solid fa-trash"></i>
                        <h5 style=" display: inline-block; vertical-align: middle; margin-left: 5px;">Xóa</h5>
                    </a>
                </td>
            </tr>
            `
            tableBody.appendChild(row)
            var deleteCreateReceipt = document.querySelectorAll('.delete_create-receipt');
            deleteCreateReceipt.forEach(function(item) {
                item.addEventListener('click', function(event) {
                    event.preventDefault();
                    var indexCreateReceipt = parseInt(this.getAttribute('id-data-create-receipt'));
                    console.log(indexCreateReceipt)
                    informations = informations.filter(info => info.stt != indexCreateReceipt );
                    console.log(informations)
                    UpdateDisplayReceipt(informations); 
                });
            });

        })
    }
    


    // Insert Data -------------------------------------------------------------------
    async function HandleCreateReceipt(data, idStaff, idSupplier)
    {
        if(confirm("Bạn có chắc muốn nhập hàng?"))
        {
            var formData = new FormData();
            formData.append('choice', 'create_receipt')
            formData.append('id_staff', idStaff);
            formData.append('id_supplier', idSupplier);
            for(var i = 0; i < informations.length; i++)
            {
                formData.append('id_product[]', informations[i].idProduct);
                formData.append('price_product[]', informations[i].priceProduct);
                formData.append('quantity_product[]', informations[i].quantityProduct);
            }
    
           
            var response = await fetch('crud/receipt_api.php', {
                method: 'POST',
                body: formData
            });
            alert("Tạo phiếu nhập thành công")
            informations = []
            UpdateDisplayReceipt(informations)
            // nameProductSelect.value = "0";
            document.querySelector('select[name="name_product"]').value = "0";
            document.querySelector('select[name="name_supplier"]').value = "0";
            document.querySelector('input[name="price_product"]').value = "";
            document.querySelector('input[name="quantity_product"]').value = "";
        }
    }


    // Load Receipt -----------------------------------------------------------------------------
    async function LinkLoadReceipt()
    {
        var formData = new FormData();
        formData.append('choice', 'get_all_receipt');
        var link = await fetch('crud/receipt_api.php', {
            method : 'POST',
            body : formData
        });
        var json =  await link.json();
        LoadReceipt(json)
        var elementDel = document.querySelectorAll(".deleteReceipt")
        elementDel.forEach(function(item)
        {
            item.addEventListener('click', function(event)
            {
                event.preventDefault();
                var idReceipt = this.getAttribute('data-id-receipt')
                DeleteReceipt(idReceipt)
            })
        })
        var elementEdit = document.querySelectorAll('.detailReceipt')
        elementEdit.forEach(function(item)
        {
            item.addEventListener('click', function(event)
            {
                checkExit = true
            event.preventDefault();
            var idReceipt = this.getAttribute('data-id-receipt')
            DetailReceipt(idReceipt)
            })
        })
    }
    function LoadReceipt(data)
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
                <td>${value.staff_id}</td>
                <td>${value.date_entry}</td>
                <td>${value.total_price}</td>
                <td>${value.supplier_id}</td>
                <td>
                    <a data-id-receipt = "${value.id}" class = "detailReceipt" href="">
                        <i style = "color : blue" class="fa-solid fa-circle-info"></i>
                        <h5 style=" display: inline-block; vertical-align: middle; margin-left: 5px;">Chi tiết</h5>
                    </a>
                </td>
                <td>
                    <a data-id-receipt = "${value.id}" class = "deleteReceipt" href="">
                        <i style = "color : red" class="fa-solid fa-trash"></i>
                        <h5 style=" display: inline-block; vertical-align: middle; margin-left: 5px;">Xóa</h5>
                    </a>
                </td>
            </tr>
            `
            tableBody.appendChild(row)
        })
    }



    // DELETE RECEIPT ---------------------------------------------------------------------------------
    async function DeleteReceipt(id)
    {
        if(confirm("Bạn có chắc muốn xóa phiếu nhập này?"))
        {
            var formData = new FormData();
            formData.append('choice', 'delete_receipt')
            formData.append('id_delete', id)
            var link = await fetch(`crud/receipt_api.php`, {
                method : 'POST',
                body : formData
            })
            DisplayDefaultReceipt()
        }
    }
    var elementDel = document.querySelectorAll(".deleteReceipt")
    elementDel.forEach(function(item)
    {
        item.addEventListener('click', function(event)
        {
            console.log(item)
            event.preventDefault();
            var idReceipt = this.getAttribute('data-id-receipt')
            DeleteReceipt(idReceipt)
        })
    })
    

    // Search Receipt

    var currentPage = 1
    var pageSize = 7
    var pagination = document.querySelector('.pagination')
    function DisplaySearchReceipt(data, element)
    {
        var informations
        if(data.number != 0)
        {
            document.querySelector('table').innerHTML = ""
            informations =
                `  <thead>
                        <tr>
                            <th colspan = "7">
                                <a class = "addReceipt" href="">Create Receipt</a>
                            </th>
                        </tr>
                        <tr>
                            <th>Id</th>
                            <th>Staff</th>
                            <th>Date Created</th>
                            <th>Total Price</th>
                            <th>Supplier</th>
                            <th>Chi tiết</th>
                            <th>Xóa</th>
                        </tr>
                    </thead>
                <tbody>`
                    data.informations.forEach(function(value)
                    {
                        informations += `
                        <tr>
                            <td>${value.id}</td>
                            <td>${value.staff_id}</td>
                            <td>${value.date_entry}</td>
                            <td>${value.total_price}</td>
                            <td>${value.supplier_id}</td>
                            
                            <td>
                                <a data-id-receipt = "${value.id}" class = "detailReceipt" href="">
                                    <i style = "color : blue" class="fa-solid fa-circle-info"></i>
                                    <h5 style=" display: inline-block; vertical-align: middle; margin-left: 5px;">Chi tiết</h5>
                                </a>
                            </td>
                            <td>
                                <a data-id-receipt = "${value.id}" class = "deleteReceipt" href="">
                                    <i style = "color : red" class="fa-solid fa-trash"></i>
                                    <h5 style=" display: inline-block; vertical-align: middle; margin-left: 5px;">Xóa</h5>
                                </a>
                            </td>
                        </tr>
                    </tbody> `
                    })
                
        }
        else 
        {
            informations = ""
        }
        document.querySelector(element).innerHTML = informations

        var addReceipt = document.querySelector('.addReceipt')
        addReceipt.addEventListener('click', function(e)
        {
            e.preventDefault()
            CreateReceipt()
        })

        var elementDetail = document.querySelectorAll('.detailReceipt')
        elementDetail.forEach(function(item)
        {
            item.addEventListener('click', function(event)
            {
                event.preventDefault();
                var idReceipt = this.getAttribute('data-id-receipt')
                DetailReceipt(idReceipt)
            })
        })

        var elementDel = document.querySelectorAll(".deleteReceipt")
        elementDel.forEach(function(item)
        {
            item.addEventListener('click', function(event)
        {
            event.preventDefault();
            var idReceipt = this.getAttribute('data-id-receipt')
            DeleteReceipt(idReceipt)

        })
        })
    }
    
    async function SearchReceipt(idReceipt, dateFrom, dateTo, priceFrom, priceTo)
    {
        var formData = new FormData();
        formData.append('choice', 'search_receipt')
        formData.append('id_receipt', idReceipt)
        formData.append('date_from', dateFrom)
        formData.append('date_to', dateTo)
        formData.append('price_from', priceFrom)
        formData.append('price_to', priceTo)
        formData.append('page', currentPage)
        formData.append('pageSize', pageSize)
        var response = await fetch(`crud/receipt_api.php`, {
            method : 'POST',
            body : formData
        });
        var json = await response.json()
        console.log(json)
        DisplaySearchReceipt(json, "table")
        // DisplayPagination(json, 0)

        var indexSelect = checkSelect.value
        if(indexSelect == 0)
        {
            if(copySearch == "" && copyDateFrom == "" && copyDateTo == "" && copyPriceFrom == "" && copyPriceTo == "")
            {
                DisplayPagination(json, 1)
            }
            else if(copySearch == "" && copyDateFrom != "" && copyDateTo != "" && copyPriceFrom == "" && copyPriceTo == "")
            {
                DisplayPagination(json, 2)
            }
            else if(copySearch == "" && copyDateFrom == "" && copyDateTo == "" && copyPriceFrom != "" && copyPriceTo != "")
            {
                DisplayPagination(json, 3)
            }
            else if(copySearch == "" && copyDateFrom != "" && copyDateTo != "" && copyPriceFrom != "" && copyPriceTo != "")
            {
                DisplayPagination(json, 4)
            }
        }
        else if(indexSelect == 1)
        {
            DisplayPagination(json, 5)
        }

    }

    async function DisplayDefaultReceipt()
    {
        var formData = new FormData();
        formData.append('choice', 'display_default_receipt')
        formData.append('page', currentPage)
        formData.append('pageSize', pageSize)
        var response = await fetch(`crud/receipt_api.php`, 
            {
                method : 'POST',
                body : formData
            }
        );
        var json = await response.json()
        console.log(json)
        DisplaySearchReceipt(json, "table")
        DisplayPagination(json, 1)
    }
    DisplayDefaultReceipt();
    var copySearch
    var copyDateFrom
    var copyDateTo
    var copyPriceFrom
    var copyPriceTo
    var checkSelect = document.querySelector("#select_search-receipt")
    checkSelect.addEventListener("change", function(e)
    {
        document.querySelector('input[name="name_search-receipt"').value = ""
    })
    document.querySelector('.button_search').addEventListener('click', function(event)
    {
        currentPage = 1
        pagination.innerHTML = ""
        var inputSearch = document.querySelector('input[name="name_search-receipt"').value
        var dateFrom = document.querySelector('input[name="date_from"').value
        var dateTo = document.querySelector('input[name="date_to"').value
        var priceFrom = document.querySelector('input[name="price_from"').value
        var priceTo = document.querySelector('input[name="price_to"').value

        copySearch = inputSearch
        copyDateFrom = dateFrom
        copyDateTo = dateTo
        copyPriceFrom = priceFrom
        copyPriceTo = priceTo
        event.preventDefault();
        var indexSelect = checkSelect.value
        if(indexSelect == 0)
        {
            if(copySearch == "" && copyDateFrom == "" && copyDateTo == "" && copyPriceFrom == "" && copyPriceTo == "")
            {
                SearchReceipt(0, "", "", "", "", "")
            }
            else if(copySearch == "" && copyDateFrom != "" && copyDateTo != "" && copyPriceFrom == "" && copyPriceTo == "")
            {
                SearchReceipt(0, copyDateFrom, copyDateTo, "", "")
            }
            else if(copySearch == "" && copyDateFrom == "" && copyDateTo == "" && copyPriceFrom != "" && copyPriceTo != "")
            {
                SearchReceipt(0, "", "", copyPriceFrom, copyPriceTo)
            }
            else if(copySearch == "" && copyDateFrom != "" && copyDateTo != "" && copyPriceFrom != "" && copyPriceTo != "")
            {
                SearchReceipt(0, copyDateFrom, copyDateTo, copyPriceFrom, copyPriceTo)
            }
        }
        else if(indexSelect == 1)
        {
            SearchReceipt(copySearch, "", "", "", "")
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
        currentPage = index
        console.log(currentPage)
        if(check == 1)
        {
           DisplayDefaultReceipt()
        }
        else if(check == 2)
        {
            SearchReceipt(0, copyDateFrom, copyDateTo, "", "")
        }
        else if(check == 3)
        {
            SearchReceipt(0, "", "", copyPriceFrom, copyPriceTo);
        }
        else if(check == 4)
        {
            SearchReceipt(0, copyDateFrom, copyDateTo, copyPriceFrom, copyPriceTo)
        }
    }
</script>