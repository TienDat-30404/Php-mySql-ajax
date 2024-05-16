
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
                        <a href="../backend/crud/delete_user.php?id_delete=<?php echo $row['id']; ?>">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php }
        ?>
    </tbody>
</table>