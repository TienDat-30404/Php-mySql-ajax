
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
</script>