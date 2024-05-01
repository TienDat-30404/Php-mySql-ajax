<!-- <div class = "category">
    <div class = "category_title">
        <i class="fa-solid fa-list category_title-icon"></i>
        <h3 class = "category_title-name">Danh mục sản phẩm</h3>
    </div>
    <div class = "category_content">

        <ul class = "category_content-list">
            <li>
                <img src="https://cdn0.fahasa.com/media/wysiwyg/Huyen-KD/6921738026268_5_-removebg-preview.png" alt="">
                <h4>Bút vẽ trang trí</h4>
            </li>
            <li>
                <img src="https://cdn0.fahasa.com/media/wysiwyg/Huyen-KD/2000201847327.jpg" alt="">
                <h4>Thú bông</h4>
            </li>
            <li>
                <img src="https://cdn0.fahasa.com/media/wysiwyg/Huyen-KD/6971010806637.jpg" alt="">
                <h4>Bút teen</h4>
            </li>
            <li>
                <img src="https://cdn0.fahasa.com/media/wysiwyg/hieu_kd/2023-08-frame/bia-mem_ban-trai-3_1.jpg" alt="">
                <h4>Đam mỹ</h4>
            </li>
            <li>
                <img src="https://cdn0.fahasa.com/media/wysiwyg/icon-menu/category/van-hoc.png" alt="">
                <h4>Văn học</h4>
            </li>
            <li>
                <img src="https://cdn0.fahasa.com/media/wysiwyg/hieu_kd/2023-08-frame/TLKN.png" alt="">
                <h4>Tâm lí kĩ năng</h4>
            </li>
            <li>
                <img src="https://cdn0.fahasa.com/media/wysiwyg/Duy-VHDT/Danh-muc-san-pham/8934986006454-100.jpg" alt="">
                <h4>Thiếu nhi</h4>
            </li>
            <li>
                <img src="https://cdn0.fahasa.com/media/wysiwyg/hieu_kd/2023-08-frame/output-onlinepngtools.png" alt="">
                <h4>Kinh tế</h4>
            </li>
            <li>
                <img src="https://cdn0.fahasa.com/media/wysiwyg/hieu_kd/2023-08-frame/b1_-HNN.png" alt="">
                <h4>Sách học </h4>
            </li>
            <li>
                <img src="https://cdn0.fahasa.com/media/wysiwyg/Duy-VHDT/ngoai-van-t1-24(1).jpg" alt="">
                <h4>Ngoại Văn</h4>
            </li>
        </ul>
    </div>
</div> -->

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