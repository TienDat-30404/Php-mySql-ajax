
<table style = "width : 100%; background-color : white" cellspacing = "0" cellpading = "10">
    <thead>
        <tr>
            <th>Id</th>
            <th>Customer</th>
            <th>Staff</th>
            <th>Date Created</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Chi tiết</th>
            <th>Xóa</th>
        </tr>
    </thead>
    <tbody>
        <?php 
              include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
            $sql = "SELECT users.*, bills.*, bills.id as idBill FROM bills JOIN users ON bills.user_id = users.id";
            $result = DataSQL::querySQl($sql);
            while($row = mysqli_fetch_array($result))
            {

                ?>
                <tr>
                    <td><?php echo $row['idBill']; ?></td>
                    <td><?php echo $row['fullname']; ?></td>
                    <td><?php echo $row['staff_id']; ?></td>
                    <td><?php echo $row['date_create']; ?></td>
                    <td><?php echo $row['total_price']; ?></td>
                    <td>
                        <a data-id-order = <?php echo $row['idBill']; ?> class = "confirmOrder" href="">
                            <?php 
                                if($row['bill_status_id'] == 1)
                                {
                                    ?>
                                        <i style = "color : red" class="fa-solid fa-spinner"></i>
                                        <h5 style="color : red; display: inline-block; vertical-align: middle; margin-left: 5px;">Chờ xử lí</h5>
                                <?php }
                                else 
                                {
                                    ?> 
                                        <i style="color: green" class="fa-solid fa-check-circle"></i>
                                        <h5 style="color: green; display: inline-block; vertical-align: middle; margin-left: 5px;">Đã xử lí</h5>
                                <?php }
                            ?>
                        </a>
                    </td>
                    <td>
                        <a data-id-order = <?php echo $row['idBill']; ?> class = "detailOrder" href="">
                            <i style = "color : blue" class="fa-solid fa-circle-info"></i>
                            <h5 style=" display: inline-block; vertical-align: middle; margin-left: 5px;">Chi tiết</h5>
                        </a>
                    </td>
                    <td>
                        <a data-id-order = <?php echo $row['idBill']; ?> class = "deleteOrder" href="">
                            <i style = "color : red" class="fa-solid fa-trash"></i>
                            <h5 style=" display: inline-block; vertical-align: middle; margin-left: 5px;">Xóa</h5>
                        </a>
                    </td>
                </tr>
            <?php }
        ?>
    </tbody>
</table>
<script>

    // Detail Order -------------------------------------------------------------------------------
    async function DetailOrder(id)
    {
        var link = await fetch(`crud/detail_order.php?id_order=${id}`)
        var json = await link.json();
        console.log(json)
        DisplayDetailOrder(json)
    }
    var elementEdit = document.querySelectorAll('.detailOrder')
    elementEdit.forEach(function(item)
    {
        item.addEventListener('click', function(event)
        {
            checkExit = true
           event.preventDefault();
           var idOrder = this.getAttribute('data-id-order')
           DetailOrder(idOrder)
        })
    })
    function DisplayDetailOrder(data)
    {
        var modal = document.createElement('div');
        modal.className = 'modal';

        var modalBase = document.createElement('div');
        modalBase.className = 'modal_base';
        data.forEach(function(value)
        {
            var detailOrder = 
            `
                    <div class = "detail_order">
                        <form  class = "edit_category" >               
                            <a href = "index.php?title=order" style = "cursor : pointer;" class = "exit_edit-category">x</a>
                            <h2 class = "edit_category-title">Chi tiết đơn hàng</h2>
                            <div class = "information_order">
                                <h3>Nhân viên bán hàng : </h3>
                                <h4>${value.nameStaff}</h4>
                                <span>|</span>
                                <h3>Tên khách hàng : </h3>
                                <h4>${value.nameUser}</h4>
                                <span>|</span>
                                <h3>Ngày tạo : </h3>
                                <h4>${value.dateCreate}</h4>
                                <span>|</span>
                                <h3>Địa chỉ : </h3>
                                <h4>${value.address}</h4>
                                <span>|</span>
                                <h3>Phương thức thanh toán : </h3>
                                <h4>${value.paymentMethod}</h4>
                                <span>|</span>
                                <h3>Số tài khoản : </h3> 
                                <h4>${value.numberAccountBank}</h4> 
                            </div>`
                            for (var i = 0; i < data.length; i++) {
                                detailOrder += `
                                <ul class = "information_detail-order">
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
                            detailOrder += `</form></div>`
                                                    
            var tempDiv = document.createElement('div');
            tempDiv.innerHTML = detailOrder;
            modalBase.appendChild(tempDiv);

        })
        modal.appendChild(modalBase);
        document.body.appendChild(modal)
    }



   

    // Delete Order
    async function DeleteOrder(id)
    {
        var link = await fetch(`crud/delete_order.php?id_delete=${id}`)
        LinkLoadOrder()
    }
    var elementDel = document.querySelectorAll(".deleteOrder")
    elementDel.forEach(function(item)
    {
        item.addEventListener('click', function(event)
        {
            console.log(item)
            event.preventDefault();
            var idOrder = this.getAttribute('data-id-order')
            DeleteOrder(idOrder)
        })
    })
   


    // Confirm Order
    async function ConfirmOrder(id)
    {
        var formData = new FormData();
        formData.append('id_bill', id)
        if (confirm("Xác nhận đơn hàng?")) {
            var link = await fetch(`crud/handle_confirmOrder.php`, {
            method : 'POST',
            body : formData
        })
        } 
        else {
            console.log("Hủy bỏ xóa!");
        }
        LinkLoadOrder()
    }
    var buttonConfirm = document.querySelectorAll('.confirmOrder')
    buttonConfirm.forEach(function(item)
    {
        item.addEventListener('click', function(event)
        {
            event.preventDefault()
            var idBill = this.getAttribute('data-id-order')
            ConfirmOrder(idBill)
        })
    })




    async function LinkLoadOrder()
    {
        var link = await fetch('crud/get_all_bill.php');
        var json =  await link.json();
        LoadOrder(json)
        var buttonConfirm = document.querySelectorAll('.confirmOrder')
        buttonConfirm.forEach(function(item)
        {
            item.addEventListener('click', function(event)
            {
                event.preventDefault()
                var idBill = this.getAttribute('data-id-order')
                ConfirmOrder(idBill)
            })
        })
        var buttonConfirm = document.querySelectorAll('.deleteOrder')
        buttonConfirm.forEach(function(item)
        {
            item.addEventListener('click', function(event)
            {
                event.preventDefault()
                var idBill = this.getAttribute('data-id-order')
                DeleteOrder(idBill)
            })
        })
    }
    function LoadOrder(data)
    {
        var tableBody = document.querySelector('table tbody')
        tableBody.innerHTML = ""
        data.forEach(function(value)
        {
            if(value.staff_id == null)
            {
                value.staff_id = ""
            }
            var htmlStatus;
            if(value.bill_status_id == 2)
            {
                htmlStatus = 
                `
                    <i style="color: green" class="fa-solid fa-check-circle"></i>
                    <h5 style="color: green; display: inline-block; vertical-align: middle; margin-left: 5px;">Đã xử lí</h5>
                `;
            }
            else 
            {
                htmlStatus = 
                `
                    <i style = "color : red" class="fa-solid fa-spinner"></i>
                    <h5 style="color : red; display: inline-block; vertical-align: middle; margin-left: 5px;">Chờ xử lí</h5>
                `
            }
            var row = document.createElement('tr')
            row.innerHTML = 
            `
                <tr>
                    <td>${value.idBill}</td>
                    <td>${value.fullname}</td>
                    <td>${value.staff_id}</td>
                    <td>${value.date_create}</td>
                    <td>${value.total_price}</td>
                    <td>
                        <a data-id-order = ${value.idBill} class = "confirmOrder" href="">
                            ${htmlStatus}
                        </a>
                    </td>
                    <td>
                        <a data-id-author = ${value.idBill} class = "deleteAuthor" href="">
                            <i style = "color : blue" class="fa-solid fa-circle-info"></i>
                            <h5 style=" display: inline-block; vertical-align: middle; margin-left: 5px;">Chi tiết</h5>
                        </a>
                    </td>
                    <td>
                        <a data-id-order = ${value.idBill} class = "deleteOrder" href="">
                            <i style = "color : red" class="fa-solid fa-trash"></i>
                            <h5 style=" display: inline-block; vertical-align: middle; margin-left: 5px;">Xóa</h5>
                        </a>
                    </td>
                </tr>
            `
            tableBody.appendChild(row)
        })
    }

    // 
</script>