
<table style = "width : 100%; background-color : white" cellspacing = "0" cellpading = "10">
    <thead>
        <tr>
            <th colspan = "9">
                <a href="index.php?title=product&action=add">Add Product</a>
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
              include_once $_SERVER['DOCUMENT_ROOT'] . "/project_web2-Copy-2/backend/database/connect.php";
            $sql = "SELECT * FROM products";
            $result = DataSQL::querySQl($sql);
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
                        <a href="">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </td>
                    <td>
                        <a href="../backend/crud/delete_product.php?id_delete=<?php echo $row['id']; ?>">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php }
        ?>
    </tbody>
</table>