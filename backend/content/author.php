
<table style = "width : 100%; background-color : white" cellspacing = "0" cellpading = "10">
    <thead>
        <tr>
            <th colspan = "4">
                <a class = "addAuthor" href="">Add Author</a>
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
            $sql = "SELECT * FROM authors";
            $result = DataSQL::querySQl($sql);
            while($row = mysqli_fetch_array($result))
            {
                ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td>
                        <a data-id-author = <?php echo $row['id']; ?> class = "editAuthor" href="">
                            <i class="fa-solid fa-pen-to-square"></i>
                            <h5 style="color: green; display: inline-block; vertical-align: middle; margin-left: 5px;">Chỉnh sửa</h5>
                        </a>
                    </td>
                    <td>
                        <a data-id-author = <?php echo $row['id']; ?> class = "deleteAuthor" href="">
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
    async function EditAuthor(id)
    {
        var link = await fetch(`crud/edit_author.php?id_edit=${id}`)
        var json = await link.json();
        console.log(json)
        DisplayAuthor(json)
    }
    var elementEdit = document.querySelectorAll('.editAuthor')
    elementEdit.forEach(function(item)
    {
        item.addEventListener('click', function(event)
        {
            checkExit = true
           event.preventDefault();
           var idAuthor = this.getAttribute('data-id-author')
           EditAuthor(idAuthor)
        })
    })
    function DisplayAuthor(data)
    {
        data.forEach(function(value)
        {
            var editAuthor = 
            `
            <div class = modal>
                <div class = "modal_base">
                    <div class = "categoryEdit">
                        <form method = "" action = "" class = "edit_category" enctype="multipart/form-data">               
                            <a href = "index.php?title=author" style = "cursor : pointer;" class = "exit_edit-category">x</a>
                            <h2 class = "edit_category-title">Chỉnh sửa tác giả</h2>
                            <ul class = "edit_category-content">
                                <li>
                                    <h4>Id tác giả</h4>
                                    <input name = "id_author" style = "text-align : center; margin-left : 40px; width : 100px; outline : none; border : 1px solid black; background-color : transparent" value = "${value.id}"  type="text" readonly>
                                </li>
                                <li>
                                    <h4>Tên tác giả</h4>
                                    <input style = "width : 400px" value = "${value.name}" name = "name_author" type="text">
                                    <span class = "form-message"></span>
                                </li>
                                <li>
                                    <input name = "button_editAuthor" type="submit" value = "Chỉnh sửa">
                                </li>
                                <input type="hidden" name = "id_author" value = "${value.id}">
                            </ul>
                        </form>
                    </div>
                    
                </div>
            </div>  
            `
            document.body.insertAdjacentHTML('beforeend', editAuthor);
            var editButton = document.querySelector('input[name="button_editAuthor"]')
            editButton.addEventListener('click', function(event)
            {
                event.preventDefault();
                var idAuthor = document.querySelector('input[name="id_author"]').value
                var nameAuthor = document.querySelector('input[name="name_author"]').value.trim()
                var firstFocus = null;
                if(nameAuthor == "")
                {
                    var ElementP = document.querySelector('input[name="name_author"]')
                    var notification = ElementP.nextElementSibling;
                    notification.innerText = "Tên không được rông";
                    ElementP.classList.add('border-message')
                }

                else 
                {
                    var ElementP = document.querySelector('input[name="name_author"]')
                    var notification = ElementP.nextElementSibling;
                    notification.innerText = "";
                    ElementP.classList.remove('border-message')
                    HandleEditAuthor(idAuthor, nameAuthor);
                }
            })

        })
    }
    async function HandleEditAuthor(idAuthor, nameAuthor)
    {
        if(confirm("Xác nhân chỉnh sửa?"))
        {
            var formData = new FormData();
            formData.append('id_author', idAuthor);
            formData.append('name_author', nameAuthor);
            var link = await fetch('crud/handle_editAuthor.php', {
                method: 'POST',
                body: formData
            });
            var json = await link.json();
            var success = `Chỉnh sửa tác giả ${nameAuthor} thành công`
            var fail = `Tác giả ${nameAuthor} đã tồn tại. Không thể chỉnh sửa tác giả này`
            if(json.success === success)
            {
                alert(success)
                var ElementP = document.querySelector('input[name="name_author"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "";
                ElementP.classList.remove('border-message')
            }
            if(json.fail === fail)
            {
                alert(fail)
                var ElementP = document.querySelector('input[name="name_author"]')
                ElementP.focus()
                var notification = ElementP.nextElementSibling;
                notification.innerText = "Tên tác giả đã tồn tại";
                ElementP.classList.add('border-message')
            }
        }
    }




    // Add Author
    function AddAuthor()
    {
        var addAuthor = 
        `
        <div class = modal>
            <div class = "modal_base">
                <div style = "width : 42%; height : 35%" class = "categoryEdit">
                    <form method = "" action = "" class = "edit_category" enctype="multipart/form-data">               
                        <a href = "index.php?title=author" style = "cursor : pointer;" class = "exit_edit-category">x</a>
                        <h2 class = "edit_category-title">Thêm tác giả</h2>
                        <ul class = "edit_category-content">
                            <li >
                                <h4>Tên tác giả</h4>
                                <input style = "width : 400px" name = "name_author" type="text">
                                <span class = "form-message"></span>
                            </li>
                            <li style = "display : flex; margin : 0 auto; margin-top : 50px">
                                <input name = "button_addAuthor" type="submit" class = "button_addAuthor" value = "Thêm">
                            </li>
                        </ul>
                    </form>
                </div>
                
            </div>
        </div>  
        `
        document.body.insertAdjacentHTML('beforeend', addAuthor);
        var addButton = document.querySelector('input[name="button_addAuthor"]')
        addButton.addEventListener('click', function(event)
        {
            event.preventDefault();
            var nameAuthor = document.querySelector('input[name="name_author"]').value.trim()
            if(nameAuthor == "")
            {

                var ElementP = document.querySelector('input[name="name_author"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "Tên không được rông";
                ElementP.classList.add('border-message')
                ElementP.focus()
            }    

            else 
            {
                var ElementP = document.querySelector('input[name="name_author"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "";
                ElementP.classList.remove('border-message')
                HandleAddAuthor(nameAuthor);
            }
        })
    }
    var addAuthor = document.querySelector('.addAuthor')
    addAuthor.addEventListener('click', function(e)
    {
        e.preventDefault()
        AddAuthor()
    })

    async function HandleAddAuthor(nameAuthor)
    {
        if(confirm("Xác nhận thêm?"))
        {
            var formData = new FormData();
            formData.append('name_author', nameAuthor);
            var link = await fetch('crud/handle_addAuthor.php', {
                method: 'POST',
                body: formData
            });
            var json = await link.json();
            var success = `Thêm tác giả ${nameAuthor} vào cửa hàng thành công`;
            var fail = `Tác giả ${nameAuthor} đã tồn tại trong cửa hàng`;
            if(json.status === success)
            {
                alert(success)
                var ElementP = document.querySelector('input[name="name_author"]')
                var notification = ElementP.nextElementSibling;
                notification.innerText = "";
                ElementP.classList.remove('border-message')
            }
            if(json.status === fail)
            {
                alert(fail)
                var ElementP = document.querySelector('input[name="name_author"]')
                ElementP.focus()
                var notification = ElementP.nextElementSibling;
                notification.innerText = "Tên tác giả đã tồn tại";
                ElementP.classList.remove('border-message')
            }
        }
    }


    // Delete Author
    async function DeleteAuthor(id)
    {
        if(confirm("Xác nhận xóa tác giả?"))
        {     
            var link = await fetch(`crud/delete_author.php?id_delete=${id}`)
            LinkLoadAuthor()
            DisplayDefaultAuthor(0, "")
        }
    }
    var elementDel = document.querySelectorAll(".deleteAuthor")
    elementDel.forEach(function(item)
    {
        item.addEventListener('click', function(event)
        {
            console.log(item)
            event.preventDefault();
            var idAuthor = this.getAttribute('data-id-author')
            DeleteAuthor(idAuthor)
        })
    })
    async function LinkLoadAuthor()
    {
        var link = await fetch('crud/get_all_authors.php');
        var json =  await link.json();
        LoadAuthor(json)
        var elementDel = document.querySelectorAll(".deleteAuthor")
        elementDel.forEach(function(item)
        {
            item.addEventListener('click', function(event)
            {
            console.log(item)
            event.preventDefault();
            var idAuthor = this.getAttribute('data-id-author')
            DeleteAuthor(idAuthor)
            })
        })
    }
    function LoadAuthor(data)
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
                        <a class = "editAuthor" data-id-author = ${value.id} href="">
                            <i class="fa-solid fa-pen-to-square"></i>
                            <h5 style="color: green; display: inline-block; vertical-align: middle; margin-left: 5px;">Chỉnh sửa</h5>
                        </a>
                    </td>
                    <td>
                        <a class = "deleteAuthor" data-id-author = ${value.id} href="">
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
    var pageSize = 7
    var pagination = document.querySelector('.pagination')
    function DisplaySearchAuthor(data, element)
    {
        var informations
        if(data.number != 0)
        {
            document.querySelector('table').innerHTML = ""
            informations =
                `  <thead>
                        <tr>
                            <th colspan = "4">
                                <a class = "addAuthor" href="">Add Author</a>
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
                                <a data-id-author = "${value.id}" class = "editAuthor" href="">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <h5 style="color: green; display: inline-block; vertical-align: middle; margin-left: 5px;">Chỉnh sửa</h5>
                                </a>
                            </td>
                            <td>
                                <a data-id-author = "${value.id}" class = "deleteAuthor" href="">
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

        var addAuthor = document.querySelector('.addAuthor')
        addAuthor.addEventListener('click', function(e)
        {
            e.preventDefault()
            AddAuthor()
        })
        
        var elementEdit = document.querySelectorAll('.editAuthor')
        elementEdit.forEach(function(item)
        {
            item.addEventListener('click', function(event)
            {
                checkExit = true
            event.preventDefault();
            var idAuthor = this.getAttribute('data-id-author')
            EditAuthor(idAuthor)
            })
        })

        var elementDel = document.querySelectorAll(".deleteAuthor")
        elementDel.forEach(function(item)
        {
            item.addEventListener('click', function(event)
        {
            event.preventDefault();
            var idAuthor = this.getAttribute('data-id-author')
            DeleteAuthor(idAuthor)
            // SearchIdAndName(0, "")

        })
        })
    }
    
    async function SearchIdAndName(idAuthor, nameAuthor)
    {
        var response = await fetch(`crud/search_author.php?page=${currentPage}&pageSize=${pageSize}&id_search=${idAuthor}&
        name_search=${nameAuthor}`);
        var json = await response.json()
        console.log(json)
        DisplaySearchAuthor(json, "table")
        // DisplayPagination(json, 0)
        checkSelect.addEventListener("change", function(e)
        {
            if(checkSelect.value == 0)
            {
                DisplayPagination(json, 1)
            }
            else if(checkSelect.value == 2)
            {
                DisplayPagination(json, 1)
            }

        })
    }

    async function DisplayDefaultAuthor(idAuthor, nameAuthor)
    {
        var response = await fetch(`crud/search_author.php?page=${currentPage}&pageSize=${pageSize}&id_search=${idAuthor}&
        name_search=${nameAuthor}`);
        var json = await response.json()
        console.log(json)
        DisplaySearchAuthor(json, "table")
        DisplayPagination(json, 0)
    }
    DisplayDefaultAuthor(0, "")
    async function SearchIdAndName(idAuthor, nameAuthor)
    {
        var response = await fetch(`crud/search_author.php?page=${currentPage}&pageSize=${pageSize}&id_search=${idAuthor}&
        name_search=${nameAuthor}`);
        var json = await response.json()
        console.log(json)
        DisplaySearchAuthor(json, "table")
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
    var checkSelect = document.querySelector("#select_search-author")
    checkSelect.addEventListener("change", function(e)
    {
        document.querySelector('input[name="name_search-author"').value = ""

    })
    document.querySelector('.button_search').addEventListener('click', function(event)
    {
        currentPage = 1
        pagination.innerHTML = ""
        var inputSearch = document.querySelector('input[name="name_search-author"').value
        copySearch = inputSearch
        event.preventDefault();
        var indexSelect = checkSelect.value
        if(indexSelect == 0)
        {
           SearchIdAndName(0, "")
        }
        else if(indexSelect == 1)
        {
            SearchIdAndName(inputSearch, "")
        }
        else if(indexSelect == 2)
        {
            SearchIdAndName(0, inputSearch)
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
            SearchIdAndName(0, "")
        }
        else if(check == 1)
        {
            SearchIdAndName(0, copySearch)
        }
    }
</script>