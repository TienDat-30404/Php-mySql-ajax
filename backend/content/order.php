
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
    
</table>
<div class = "pagination"></div>
<script>

    // Detail Order -------------------------------------------------------------------------------
    async function DetailOrder(id)
    {
        var formData = new FormData()
        formData.append('choice', 'detail_order')
        formData.append('id_order', id)
        var link = await fetch(`crud/order_api.php`, {
            method : 'POST',
            body : formData
        })
        var json = await link.json();
        DisplayDetailOrder(json)
    }
    var elementEdit = document.querySelectorAll('.detailOrder')
    elementEdit.forEach(function(item)
    {
        item.addEventListener('click', function(event)
        {
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
        if(confirm("Xác nhận xóa đơn hàng này?"))
        {
            var formData = new FormData();
            formData.append('choice', 'delete_order')
            formData.append('id_delete', id)
            var link = await fetch(`crud/order_api.php`, {
                method : 'POST',
                body : formData
            })
            DisplayDefaultOrder()
        }
    }
    var elementDel = document.querySelectorAll(".deleteOrder")
    elementDel.forEach(function(item)
    {
        item.addEventListener('click', function(event)
        {
            event.preventDefault();
            var idOrder = this.getAttribute('data-id-order')
            DeleteOrder(idOrder)
        })
    })
   


    // Confirm Order
    async function ConfirmOrder(id)
    {
        if (confirm("Xác nhận đơn hàng?")) {
            var formData = new FormData();
            formData.append('choice', 'confirm_order')
            formData.append('id_bill', id)
            var link = await fetch(`crud/order_api.php`, {
            method : 'POST',
            body : formData
        })
        } 
        else {
            console.log("Hủy bỏ xóa!");
        }
        DisplayDefaultOrder()
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
                        <a data-id-order = ${value.idBill} class = "detailOrder" href="">
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



     // Search -------------------------------------------------------------
     var currentPage = 1
    var pageSize = 7
    var pagination = document.querySelector('.pagination')
    function DisplaySearchOrder(data, element)
    {
        var informations
        if(data.number != 0)
        {
            document.querySelector('table').innerHTML = ""
            informations =
                `  <thead>
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
                <tbody>`
                    data.informations.forEach(function(value)
                    {
                        if(value.staff_id == null)
                        {
                            value.staff_id = ""
                        }
                        informations += `
                        <tr>
                            <td>${value.idBill}</td>
                            <td>${value.fullname}</td>
                            <td>${value.staff_id}</td>
                            <td>${value.date_create}</td>
                            <td>${value.total_price}</td>
                            <td>
                                <a data-id-order = "${value.id}" class = "confirmOrder" href="">`
                                  if(value.bill_status_id == 1)
                                  {
                                    informations += 
                                    `
                                        <i style = "color : red" class="fa-solid fa-spinner"></i>
                                        <h5 style="color : red; display: inline-block; vertical-align: middle; margin-left: 5px;">Chờ xử lí</h5>
                                    `
                                  } 
                                  else 
                                  {
                                        informations += `
                                            <i style="color: green" class="fa-solid fa-check-circle"></i>
                                            <h5 style="color: green; display: inline-block; vertical-align: middle; margin-left: 5px;">Đã xử lí</h5>
                                       `
                                  }
          
                                informations += `</a>
                            </td>
                            <td>
                                <a data-id-order = "${value.idBill}" class = "detailOrder" href="">
                                    <i style = "color : blue" class="fa-solid fa-circle-info"></i>
                                    <h5 style=" display: inline-block; vertical-align: middle; margin-left: 5px;">Chi tiết</h5>
                                </a>
                            </td>
                            <td>
                                <a data-id-order = "${value.idBill}" class = "deleteOrder" href="">
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
        
        var elementDetail = document.querySelectorAll('.detailOrder')
        elementDetail.forEach(function(item)
        {
            item.addEventListener('click', function(event)
            {
                event.preventDefault();
                var idOrder = this.getAttribute('data-id-order')
                DetailOrder(idOrder)
            })
        })

        var elementDel = document.querySelectorAll(".deleteOrder")
        elementDel.forEach(function(item)
        {
            item.addEventListener('click', function(event)
        {
            event.preventDefault();
            var idOrder = this.getAttribute('data-id-order')
            DeleteOrder(idOrder)
            // SearchOrder(0, "")

        })
        })
    }
    
    async function SearchOrder(idOrder, nameCustomer, dateFrom, dateTo, status)
    {
        var formData = new FormData();
        formData.append('choice', 'search_order')
        formData.append('id_order', idOrder)
        formData.append('name_customer', nameCustomer)
        formData.append('date_from', dateFrom)
        formData.append('date_to', dateTo)
        formData.append('status', status)
        formData.append('page', currentPage)
        formData.append('pageSize', pageSize)
        var response = await fetch(`crud/order_api.php`, {
            method : 'POST',
            body : formData
        });
        var json = await response.json()
        console.log(json)
        DisplaySearchOrder(json, "table")
        // DisplayPagination(json, 0)

        var indexSelect = checkSelect.value
        if(indexSelect == 0)
        {
            if(copySearch == "" && copyDateFrom != "" && copyDateTo != "")
            {
                DisplayPagination(json, 6)
            }
            else if(copySearch == "" && copyDateFrom == "" && copyDateTo == "")
            {
                DisplayPagination(json, 5)
            }
            else if(copySearch != "" && copyDateFrom != "" && copyDateTo != "")
            {
                DisplayPagination(json, 6)
            }
        }
        else if(indexSelect == 1)
        {
            DisplayPagination(json, 1)
        }
        else if(indexSelect == 2)
        {
            DisplayPagination(json, 2)
        }
        else if(indexSelect == 3)
        {
            DisplayPagination(json, 3)
        }
        else if(indexSelect == 4)
        {
            DisplayPagination(json, 4)
        }

    }

    async function DisplayDefaultOrder()
    {
        var formData = new FormData();
        formData.append('choice', 'display_default_order')
        formData.append('page', currentPage)
        formData.append('pageSize', pageSize)
        var response = await fetch(`crud/order_api.php`, 
            {
                method : 'POST',
                body : formData
            }
        );
        var json = await response.json()
        console.log(json)
        DisplaySearchOrder(json, "table")
        DisplayPagination(json, 5)
    }
    DisplayDefaultOrder();

    var copySearch
    var copyDateFrom
    var copyDateTo
    var checkSelect = document.querySelector("#select_search-order")
    checkSelect.addEventListener("change", function(e)
    {
        document.querySelector('input[name="name_search-order"').value = ""
    })
    document.querySelector('.button_search').addEventListener('click', function(event)
    {
        currentPage = 1
        pagination.innerHTML = ""
        var inputSearch = document.querySelector('input[name="name_search-order"').value
        var dateFrom = document.querySelector('input[name="date_from"').value
        var dateTo = document.querySelector('input[name="date_to"').value
        copySearch = inputSearch
        copyDateFrom = dateFrom
        copyDateTo = dateTo
        event.preventDefault();
        var indexSelect = checkSelect.value
        if(indexSelect == 0)
        {
            
            if(copySearch == "" && copyDateFrom != "" && copyDateTo != "")
            {
                SearchOrder(0, "", copyDateFrom, copyDateTo, 0)
            }
            else if(copySearch != "" && copyDateFrom != "" && copyDateTo != "")
            {
                SearchOrder(0, copySearch, copyDateFrom, copyDateTo, 0)
            }
            else if(copySearch == "" && copyDateFrom == "" && copyDateTo == "")
            {
                SearchOrder(0, "", "", "", 0)
            }
        }
        else if(indexSelect == 1)
        {
            SearchOrder(inputSearch, "", "", "", 0)
        }
        else if(indexSelect == 2)
        {
            SearchOrder(0, inputSearch, "", "", 0);
        }
        else if(indexSelect == 3)
        {
            SearchOrder(0, "", "", "", 1)
        }
        else if(indexSelect == 4)
        {
            SearchOrder(0, "", "", "", 2);
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
        if(check == 0)
        {
           SearchOrder(0, copySearch, copyDateFrom, copyDateTo, 0)
        }
        else if(check == 1)
        {
            SearchOrder(copySearch, "", "", "", 0)
        }
        else if(check == 2)
        {
            SearchOrder(0, copySearch, "", "", 0);
        }
        else if(check == 3)
        {
            SearchOrder(0, "", "", "", 1)
        }
        else if(check == 4)
        {
            SearchOrder(0, "", "", "", 2);
        }
        else if(check == 5)
        {
            DisplayDefaultOrder();
        }
        else if(check == 6)
        {
            SearchOrder(0, "", copyDateFrom, copyDateTo, 0)
        }
    }
</script>