
<table style = "width : 100%; background-color : white" cellspacing = "0" cellpading = "10">
    <thead>
        <tr>
            <th colspan = "9">
                <a href="index.php?title=accountExist&action=add">Add User</a>
            </th>
        </tr>
        <tr>
            <th style = "width : 40px">Id</th>
            <th>Role Id</th>
            <th>Email</th>
            <th>Password</th>
            <th>FullName</th>
            <th>Address</th>
            <th>Number Phone</th>
            <th colspan = "2">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php 
              include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
            $sql = "SELECT * FROM users WHERE active = 1";
            $result = DataSQL::querySQl($sql);
            while($row = mysqli_fetch_array($result))
            {
                ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['role_id']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['password']; ?></td>
                    <td><?php echo $row['fullname']; ?></td>
                    <td><?php echo $row['address']; ?></td>
                    <td><?php echo $row['phone_number']; ?></td>
                    
                    <td>
                        <a href="index.php?title=accountExist&action=edit&id_edit=<?php echo $row['id']; ?>">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </td>
                    <td>
                        <a data-id-user = <?php echo $row['id']; ?> class = "delete_user" href="">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php }
        ?>
    </tbody>
</table>
<script>
    // Delete User
    async function DeleteUser(id)
    {
        if(confirm("Xác nhận xóa?"))
        {
            var link = await fetch(`crud/delete_user.php?id_delete=${id}`)
            LinkLoadUser()
        }
    }
    var elementDel = document.querySelectorAll(".delete_user")
    elementDel.forEach(function(item)
    {
        item.addEventListener('click', function(event)
        {
            console.log(item)
            event.preventDefault();
            var idUser = this.getAttribute('data-id-user')
            DeleteUser(idUser)
        })
    })
    async function LinkLoadUser()
    {
        var link = await fetch('crud/get_all_user.php');
        var json =  await link.json();
        LoadUser(json)
        var elementDel = document.querySelectorAll(".delete_user")
        elementDel.forEach(function(item)
        {
            item.addEventListener('click', function(event)
        {
            console.log(item)
            event.preventDefault();
            var idUser = this.getAttribute('data-id-user')
            DeleteUser(idUser)
        })
        })
    }
    function LoadUser(data)
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
                <td>${value.role_id}</td>
                <td>${value.email}</td>
                <td>${value.password}</td>
                <td>${value.fullname}</td>
                <td>${value.address}</td>
                <td>${value.phone_number}</td>
                
                <td>
                    <a href="index.php?title=accountExist&action=edit&id_edit=${value.id}">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                </td>
                <td>
                    <a data-id-user = "${value.id}" class = "delete_user" href="">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
            `
            tableBody.appendChild(row)
        })
    }
</script>