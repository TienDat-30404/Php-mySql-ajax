

<div class = "category">
    <div class = "category_all">
        <a href = "index.php" class = "category_all-text">Mẫu mới về</a>
    </div>
    
    <ul class = "category_list">
        <?php include "frontend/includes/config.php"; 
            $sql = "SELECT * FROM categories";
            $result = mysqli_query($connection, $sql);
            while($row = mysqli_fetch_array($result))
            {
                ?>
                <h4 style = "margin-top : 0; margin-bottom : 0" data-id-category = <?php echo $row['id']; ?> class = "clickCategory">
                    <li>
                        <img width="35" height="35" src="<?php echo $row['image']; ?>" data-was-processed="true">
                        <h4 class = "category_list-name"><?php echo $row['name']; ?></h4>    
                    </li>
                </h4>
            <?php }
         ?>

    </ul>
</div>