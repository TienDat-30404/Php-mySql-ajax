
<table style = "width : 100%; background-color : white" cellspacing = "0" cellpading = "10">
    <thead>
        <tr>
            <th colspan = "4">
                <a class = "addPublisher" href="">Add Publisher</a>
            </th>
        </tr>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th colspan = "2">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php 
              include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
            $sql = "SELECT * FROM publishers ORDER BY id ASC";
            $result = DataSQL::querySQl($sql);
            while($row = mysqli_fetch_array($result))
            {
                ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td>
                        <a data-id-publisher = <?php echo $row['id']; ?> class = "editPublisher" href="">
                            <i class="fa-solid fa-pen-to-square"></i>
                            <h5 style="color: green; display: inline-block; vertical-align: middle; margin-left: 5px;">Chỉnh sửa</h5>
                        </a>
                    </td>
                    <td>
                        <a data-id-publisher = <?php echo $row['id']; ?> class = "deletePublisher" href="">
                            <i style = "color : red" class="fa-solid fa-trash"></i>
                            <h5 style="color: green; display: inline-block; vertical-align: middle; margin-left: 5px;">Xóa</h5>
                        </a>
                    </td>
                </tr>
            <?php }
        ?>
    </tbody>
</table>
<div class = "pagination"></div>
<script>

    // Edit Category -------------------------------------------------------------------------------
    async function EditPublisher(id)
    {
        var formData = new FormData();
        formData.append('choice', 'display_edit_publisher')
        formData.append('id_edit', id)
        var response = await fetch(`crud/publisher_api.php`, {
            method : 'POST',
            body : formData
        });
        var json = await response.json();
        DisplayPublisher(json)
    }
    var elementEdit = document.querySelectorAll('.editPublisher')
    elementEdit.forEach(function(item)
    {
        item.addEventListener('click', function(event)
        {
            checkExit = true
           event.preventDefault();
           var idPublisher = this.getAttribute('data-id-publisher')
            EditPublisher(idPublisher)
        })
    })
    function DisplayPublisher(data)
    {
        data.forEach(function(value)
        {
            var editPublisher = 
            `
            <div class = modal>
                <div class = "modal_base">
                    <div class = "categoryEdit">
                        <form method = "" action = "" class = "edit_category" enctype="multipart/form-data">               
                            <a href = "index.php?title=publisher" style = "cursor : pointer;" class = "exit_crud-all">x</a>
                            <h2 class = "edit_category-title">Chỉnh sửa nhà xuất bản</h2>
                            <ul class = "edit_category-content">
                                <li>
                                    <h4>Id nhà xuất bản</h4>
                                    <input name = "id_publisher" style = "text-align : center; margin-left : 40px; width : 100px; outline : none; border : 1px solid black; background-color : transparent" value = "${value.id}" type="text" readonly>
                                </li>
                                <li>
                                    <h4>Tên tác giả</h4>
                                    <input style = "width : 400px" value = "${value.name}" name = "name_publisher" type="text">
                                    <span class = "form-message"></span>
                                </li>
                                <li>
                                    <input name = "button_editPublisher" type="submit" value = "Chỉnh sửa">
                                </li>
                                <input type="hidden" name = "id_publisher" value = "${value.id}">
                            </ul>
                        </form>
                    </div>
                    
                </div>
            </div>  
            `
            document.body.insertAdjacentHTML('beforeend', editPublisher);
            var editButton = document.querySelector('input[name="button_editPublisher"]')
            editButton.addEventListener('click', function(event)
            {
                event.preventDefault();
                var idPublisher = document.querySelector('input[name="id_publisher"]').value
                var namePublisher = document.querySelector('input[name="name_publisher"]').value.trim()
                var firstFocus = null;
                if(namePublisher == "")
                {
                    var ElementP = document.querySelector('input[name="name_publisher"]')
                    var notification = ElementP.nextElementSibling;
                    notification.innerText = "Tên không được rông";
                    ElementP.classList.add('border-message')
                }

                else 
                {
                    var ElementP = document.querySelector('input[name="name_publisher"]')
                    var notification = ElementP.nextElementSibling;
                    notification.innerText = "";
                    ElementP.classList.remove('border-message')
                    HandleEditPublisher(idPublisher, namePublisher);
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
    async function HandleEditPublisher(idPublisher, namePublisher)
    {
        if(confirm("Xác nhận chỉnh sửa"))
        {
            var formData = new FormData();
            formData.append('choice', 'handle_edit_publisher')
            formData.append('id_publisher', idPublisher);
            formData.append('name_publisher', namePublisher);
            var link = await fetch('crud/publisher_api.php', {
                method: 'POST',
                body: formData
            });
            var json = await link.json();
            var success = `Chỉnh sửa nhà xuất bản ${namePublisher} thành công`
            var fail = `Nhà xuất bản ${namePublisher} đã tồn tại. Không thể chỉnh sửa nhà xuất bản này`
            if(json.success === success)
            {
                alert(success)
                var ElementP = document.querySelector('input[name="name_publisher"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "";
                ElementP.classList.remove('border-message')
            }
            if(json.fail === fail)
            {
                alert(fail)
                var ElementP = document.querySelector('input[name="name_publisher"]')
                ElementP.focus()
                var notification = ElementP.nextElementSibling;
                notification.innerText = "Tên nhà xuất bản đã tồn tại";
                ElementP.classList.add('border-message')
            }
        }
    }




    // Add Publisher
    function AddPublisher()
    {
        var addPublisher = 
        `
        <div class = modal>
            <div class = "modal_base">
                <div style = "width : 42%; height : 35%" class = "categoryEdit">
                    <form method = "" action = "" class = "edit_category" enctype="multipart/form-data">               
                        <a href = "index.php?title=publisher" style = "cursor : pointer;" class = "exit_crud-all">x</a>
                        <h2 class = "edit_category-title">Thêm nhà xuất bản</h2>
                        <ul class = "edit_category-content">z
                            <li >
                                <h4>Tên nhà xuất bản</h4>
                                <input style = "width : 400px" name = "name_publisher" type="text">
                                <span class = "form-message"></span>
                            </li>
                            <li style = "display : flex; margin : 0 auto; margin-top : 50px">
                                <input name = "button_addPublisher" type="submit" class = "button_AddPublisher" value = "Thêm">
                            </li>
                        </ul>
                    </form>
                </div>
                
            </div>
        </div>  
        `
        document.body.insertAdjacentHTML('beforeend', addPublisher);
        var addButton = document.querySelector('input[name="button_addPublisher"]')
        addButton.addEventListener('click', function(event)
        {
            event.preventDefault();
            var namePublisher = document.querySelector('input[name="name_publisher"]').value.trim()
            if(namePublisher == "")
            {

                var ElementP = document.querySelector('input[name="name_publisher"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "Tên không được rông";
                ElementP.classList.add('border-message')
                ElementP.focus()
            }    

            else 
            {
                var ElementP = document.querySelector('input[name="name_publisher"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "";
                ElementP.classList.remove('border-message')
                HandleAddPublisher(namePublisher);
            }
        })

        var detailExit = document.querySelector('.exit_crud-all');
        var modal = document.querySelector('.modal');
        detailExit.addEventListener('click', function(e)
        {
            e.preventDefault();
            modal.remove(); 
        })
    }
    var addPublisher = document.querySelector('.addPublisher')
    addPublisher.addEventListener('click', function(e)
    {
        e.preventDefault()
        AddPublisher()
    })

    async function HandleAddPublisher(namePublisher)
    {
        if(confirm("Xác nhân thêm nhà xuất bản này?"))
        {
            var formData = new FormData();
            formData.append('choice', 'add_publisher')
            formData.append('name_publisher', namePublisher);
            var link = await fetch('crud/publisher_api.php', {
                method: 'POST',
                body: formData
            });
            var json = await link.json();
            var success = `Thêm nhà xuất bản ${namePublisher} vào cửa hàng thành công`;
            var fail = `Nhà xuất bản ${namePublisher} đã tồn tại trong cửa hàng`;
            if(json.status === success)
            {
                alert(success)
                var ElementP = document.querySelector('input[name="name_publisher"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "";
                ElementP.classList.remove('border-message')
            }
            if(json.status === fail)
            {
                alert(fail)
                var ElementP = document.querySelector('input[name="name_publisher"]')
                ElementP.focus()
                var notification = ElementP.nextElementSibling;
                notification.innerText = "Tên nhà xuất bản đã tồn tại";
                ElementP.classList.remove('border-message')
            }
        }
    }


    // Delete Publisher
    async function DeletePublisher(id)
    {
        if(confirm("Xác nhận xóa nhà xuất bản này?"))
        {
            var formData = new FormData();
            formData.append('choice', 'delete_publisher')
            formData.append('id_delete', id)
            var link = await fetch(`crud/publisher_api.php`, {
                method : 'POST',
                body : formData
            })
            LinkLoadPublisher()
            DisplayDefaultPublisher(0, "")
        }
    }
    var elementDel = document.querySelectorAll(".deletePublisher")
    elementDel.forEach(function(item)
    {
        item.addEventListener('click', function(event)
        {
            console.log(item)
            event.preventDefault();
            var idPublisher = this.getAttribute('data-id-publisher')
            DeletePublisher(idPublisher)
        })
    })
    async function LinkLoadPublisher()
    {
        var formData = new FormData();
        formData.append('choice', 'get_all_publisher')
        var link = await fetch('crud/publisher_api.php', {
            method : 'POST',
            body : formDta
        });
        var json =  await link.json();
        LoadPublisher(json)
        var elementDel = document.querySelectorAll(".deletePublisher")
        elementDel.forEach(function(item)
        {
            item.addEventListener('click', function(event)
            {
                console.log(item)
                event.preventDefault();
                var idPublisher = this.getAttribute('data-id-publisher')
                DeletePublisher(idPublisher)
            })
        })
        var elementEdit = document.querySelectorAll('.editPublisher')
        elementEdit.forEach(function(item)
        {
            item.addEventListener('click', function(event)
            {
                checkExit = true
                event.preventDefault();
                var idPublisher = this.getAttribute('data-id-publisher')
                EditPublisher(idPublisher)
            })
        })
    }
    function LoadPublisher(data)
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
                        <a class = "editPublisher" data-id-publisher = ${value.id} href="">
                            <i class="fa-solid fa-pen-to-square"></i>
                            <h5 style="color: green; display: inline-block; vertical-align: middle; margin-left: 5px;">Chỉnh sửa</h5>
                        </a>
                    </td>
                    <td>
                        <a class = "deletePublisher" data-id-publisher = ${value.id} href="">
                            <i style = "color : red" class="fa-solid fa-trash"></i>
                            <h5 style="color: green; display: inline-block; vertical-align: middle; margin-left: 5px;">Xóa</h5>
                        </a>
                    </td>
                </tr>
            `
            tableBody.appendChild(row)
        })
    }



    // Search -------------------------------------------------------------
    var currentPage = 1
    var pageSize = 5
    var pagination = document.querySelector('.pagination')
    function DisplaySearchPublisher(data, element)
    {
        var informations
        if(data.number != 0)
        {
            document.querySelector('table').innerHTML = ""
            informations =
                `  <thead>
                        <tr>
                            <th colspan = "4">
                                <a class = "addPublisher" href="">Add Publisher</a>
                            </th>
                        </tr>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th colspan = "2">Action</th>
                        </tr>
                    </thead>
                <tbody>`
                    data.informations.forEach(function(value)
                    {
                        informations += `
                        <tr>
                            <td>${value.id}</td>
                            <td>${value.name}</td>
                            <td>
                                <a data-id-publisher = "${value.id}" class = "editPublisher" href="">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <h5 style="color: green; display: inline-block; vertical-align: middle; margin-left: 5px;">Chỉnh sửa</h5>
                                </a>
                            </td>
                            <td>
                                <a data-id-publisher = "${value.id}" class = "deletePublisher" href="">
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
        document.querySelector(element).innerHTML = informations

        var addPublisher = document.querySelector('.addPublisher')
        addPublisher.addEventListener('click', function(e)
        {
            e.preventDefault()
            AddPublisher()
        })
        
        var elementEdit = document.querySelectorAll('.editPublisher')
        elementEdit.forEach(function(item)
        {
            item.addEventListener('click', function(event)
            {
                checkExit = true
            event.preventDefault();
            var idPublisher = this.getAttribute('data-id-publisher')
            EditPublisher(idPublisher)
            })
        })

        var elementDel = document.querySelectorAll(".deletePublisher")
        elementDel.forEach(function(item)
        {
            item.addEventListener('click', function(event)
        {
            event.preventDefault();
            var idPublisher = this.getAttribute('data-id-publisher')
            DeletePublisher(idPublisher)
        })
        })
    }
    

    async function DisplayDefaultPublisher(idPublisher, namePublisher)
    {
        var formData = new FormData();
        formData.append('choice', 'search_publisher')
        formData.append('id_search', idPublisher)
        formData.append('name_search', namePublisher)
        formData.append('page', currentPage)
        formData.append('pageSize', pageSize)
        var response = await fetch(`crud/publisher_api.php`, {
            method : 'POST',
            body : formData
        });
        var json = await response.json()
        console.log(json)
        DisplaySearchPublisher(json, "table")
        DisplayPagination(json, 0)
    }
    DisplayDefaultPublisher(0, "")
    async function SearchPublisher(idPublisher, namePublisher)
    {
        var formData = new FormData();
        formData.append('choice', 'search_publisher')
        formData.append('id_search', idPublisher)
        formData.append('name_search', namePublisher)
        formData.append('page', currentPage)
        formData.append('pageSize', pageSize)
        var response = await fetch(`crud/publisher_api.php`, {
            method : 'POST',
            body : formData
        });
        var json = await response.json()
        console.log(json)
        DisplaySearchPublisher(json, "table")
        var indexSelect = checkSelect.value
        if(indexSelect == 0)
        {
            DisplayPagination(json, 0)
        }
        else if(indexSelect == 2)
        {
            DisplayPagination(json, 1)
        }
    }
    var copySearch
    var checkSelect = document.querySelector("#select_search-publisher")
    checkSelect.addEventListener("change", function(e)
    {
        document.querySelector('input[name="name_search-publisher"').value = ""

    })
    document.querySelector('.button_search').addEventListener('click', function(event)
    {
        currentPage = 1
        pagination.innerHTML = ""
        var inputSearch = document.querySelector('input[name="name_search-publisher"').value
        copySearch = inputSearch
        event.preventDefault();
        var indexSelect = checkSelect.value
        if(indexSelect == 0)
        {
           SearchPublisher(0, "")
        }
        else if(indexSelect == 1)
        {
            SearchPublisher(inputSearch, "")
        }
        else if(indexSelect == 2)
        {
            SearchPublisher(0, inputSearch)
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
        if(check == 0)
        {
            SearchPublisher(0, "")
        }
        else if(check == 1)
        {
            SearchPublisher(0, copySearch)
        }
    }
</script>