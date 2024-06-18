<?php
class DataSQL {
    public static function querySQl($sql){
        include(__DIR__ . '/../../frontend/includes/config.php');
        $result = mysqli_query($connection, $sql);

        if(!$result){
            echo "Không thể truy vấn: " . mysqli_error($connection);
        }
        mysqli_close($connection);

        return $result;
    }

    public static function querySQLAll($sql, $params = []) {
        include(__DIR__ . '/../../frontend/includes/config.php');
        
        // Chuẩn bị statement
        $stmt = $connection->prepare($sql);
        
        if ($stmt === false) {
            echo "Lỗi prepare statement: " . $connection->error;
            return false;
        }
        
        // Nếu có tham số, bind parameters vào statement
        if (!empty($params)) {
            $types = ""; // Chuỗi chứa các định danh kiểu dữ liệu
            
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i'; // Integer
                } elseif (is_double($param)) {
                    $types .= 'd'; // Double
                } else {
                    $types .= 's'; // Mặc định là string
                }
            }
            
            $stmt->bind_param($types, ...$params);
        }
        
        // Thực thi statement
        $stmt->execute();
        
        // Kiểm tra lỗi
        if ($stmt->errno) {
            echo "Lỗi execute query: " . $stmt->error;
            return false;
        }
        
        // Lấy kết quả nếu cần
        $result = $stmt->get_result();
        
        // Đóng statement và kết nối
        $stmt->close();
        $connection->close();
        
        return $result;
    }


    public static function querySQLCount($sql, $params = []) {
        include(__DIR__ . '/../../frontend/includes/config.php');
        
        // Chuẩn bị statement
        $stmt = $connection->prepare($sql);
        
        if ($stmt === false) {
            echo "Lỗi prepare statement: " . $connection->error;
            return false;
        }
        
        // Nếu có tham số, bind parameters vào statement
        if (!empty($params)) {
            $types = ""; // Chuỗi chứa các định danh kiểu dữ liệu
            
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i'; // Integer
                } elseif (is_double($param)) {
                    $types .= 'd'; // Double
                } else {
                    $types .= 's'; // Mặc định là string
                }
            }
            
            $stmt->bind_param($types, ...$params);
        }
        
        // Thực thi statement
        $stmt->execute();
        
        // Kiểm tra lỗi
        if ($stmt->error) {
            echo "Lỗi execute query: " . $stmt->error;
            return false;
        }
        
        // Lấy số lượng bản ghi bị ảnh hưởng
        $result = $stmt->get_result();
        $rowCount = $result->num_rows;
        
        // Đóng statement và kết nối
        $stmt->close();
        $connection->close();
        
        return $rowCount;
    }

    public static function executeSQL($sql, $params = []) {
        include(__DIR__ . '/../../frontend/includes/config.php');
        
        $stmt = $connection->prepare($sql);
        
        if ($stmt === false) {
            echo "Lỗi prepare statement: " . $connection->error;
            return false;
        }
        
        if (!empty($params)) {
            $types = "";
            
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i';
                } elseif (is_double($param)) {
                    $types .= 'd';
                } else {
                    $types .= 's';
                }
            }
            
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        
        if ($stmt->errno) {
            echo "Lỗi execute query: " . $stmt->error;
            return false;
        }
        
        $affectedRows = $stmt->affected_rows;
        
        $stmt->close();
        
        return $affectedRows;
    }

    
    public static function executeSQLToSTMT($sql, $params = []) {
        include(__DIR__ . '/../../frontend/includes/config.php');
        
        $stmt = $connection->prepare($sql);
        
        if ($stmt === false) {
            echo "Lỗi prepare statement: " . $connection->error;
            return false;
        }
        
        if (!empty($params)) {
            $types = "";
            
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i';
                } elseif (is_double($param)) {
                    $types .= 'd';
                } else {
                    $types .= 's';
                }
            }
            
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        
        if ($stmt->errno) {
            echo "Lỗi execute query: " . $stmt->error;
            return false;
        }
        
        return $stmt; // Trả về đối tượng mysqli_stmt
    }
}



