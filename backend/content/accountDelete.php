
<table style = "width : 100%; background-color : white" cellspacing = "0" cellpading = "10">
    <thead>
        
        <tr>
            <th style = "width : 40px">Id</th>
            <th>Role Id</th>
            <th>Email</th>
            <th>Password</th>
            <th>FullName</th>
            <th>Address</th>
            <th>Number Phone</th>
            <th>Restore</th>
        </tr>
    </thead>
    <tbody>
        <?php 
              include_once $_SERVER['DOCUMENT_ROOT'] . "/Php-thuan/backend/database/connect.php";
            $sql = "SELECT * FROM users WHERE active = 0";
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
                        <a class = "restore_user" data-id-user = <?php echo $row['id']; ?> href="">
                            <i class="fa-solid fa-rotate-left"></i>
                            <h5 style="color: green; display: inline-block; vertical-align: middle; margin-left: 5px;">Khôi phục</h5>
                        </a>
                    </td>
                </tr>
            <?php }
        ?>
    </tbody>
</table>

<script>
    async function RestoreUser(id)
    {
        var formData = new FormData();
        formData.append('choice', 'restore_user')
        formData.append('id_user', id);
        var link = await fetch('crud/user_api.php', {
            method : 'POST',
            body : formData
        });
        LinkLoadUser()
    }
    var buttonRestore = document.querySelectorAll('.restore_user')
    buttonRestore.forEach(function(item)
    {
        item.addEventListener('click', function(event)
        {
            event.preventDefault();
            var idUser = this.getAttribute('data-id-user')
            console.log(idUser)
            RestoreUser(idUser);
        })
    })
    async function LinkLoadUser()
    {
        var formData = new FormData();
        formData.append('choice', 'get_all_user_no_exist')
        var link = await fetch('crud/user_api.php', {
            method : 'POSt',
            body : formData
        });
        var json =  await link.json();
        LoadUser(json)
        var elementDel = document.querySelectorAll(".restore_user")
        elementDel.forEach(function(item)
        {
            item.addEventListener('click', function(event)
        {
            console.log(item)
            event.preventDefault();
            var idUser = this.getAttribute('data-id-user')
            RestoreUser(idUser)
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
                    <a class = "restore_user" data-id-user = "${value.id} href="">
                        <i class="fa-solid fa-rotate-left"></i>
                        <h5 style="color: green; display: inline-block; vertical-align: middle; margin-left: 5px;">Khôi phục</h5>
                    </a>
                </td>
            </tr>
            `
            tableBody.appendChild(row)
        })
    }
</script>