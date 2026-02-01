<?php
// อนุญาตให้ Vue (Port 8081 หรืออื่นๆ) เชื่อมต่อได้ - แก้ปัญหา Failed to fetch
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

// กรณี Browser ส่ง OPTIONS มาเช็คสิทธิ์ (Preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include 'condb.php';

try {
    $method = $_SERVER['REQUEST_METHOD'];
    $input = json_decode(file_get_contents("php://input"), true);

    // --- 1. ดึงข้อมูล (GET) ---
    if ($method === "GET") {
        $stmt = $conn->prepare("SELECT * FROM type ORDER BY type_id DESC");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["success" => true, "data" => $result]);
    }

    // --- 2. เพิ่มข้อมูล (POST) ---
    elseif ($method === "POST") {
        $data = $input ?? $_POST;
        if (empty($data["type_name"])) {
            echo json_encode(["success" => false, "message" => "กรุณากรอกชื่อประเภท"]);
            exit;
        }
        $stmt = $conn->prepare("INSERT INTO type (type_name) VALUES (:type_name)");
        $stmt->bindParam(":type_name", $data["type_name"]);
        $stmt->execute();
        echo json_encode(["success" => true, "message" => "เพิ่มข้อมูลสำเร็จ"]);
    }

    // --- 3. แก้ไขข้อมูล (PUT) ---
    elseif ($method === "PUT") {
        if (!isset($input["type_id"])) {
            echo json_encode(["success" => false, "message" => "ไม่พบรหัสประเภท (type_id)"]);
            exit;
        }
        // แก้ไข: ลบคอมม่าเกินหน้า WHERE และใช้ตัวแปรให้ตรงกัน
        $sql = "UPDATE type SET type_name = :type_name WHERE type_id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":type_name", $input["type_name"]);
        $stmt->bindParam(":id", $input["type_id"], PDO::PARAM_INT);
        $stmt->execute();
        echo json_encode(["success" => true, "message" => "แก้ไขข้อมูลเรียบร้อย"]);
    }

    // --- 4. ลบข้อมูล (DELETE) ---
    elseif ($method === "DELETE") {
        if (!isset($input["type_id"])) {
            echo json_encode(["success" => false, "message" => "ไม่พบรหัสที่ต้องการลบ"]);
            exit;
        }
        // แก้ไข: เปลี่ยนจาก emp_id เป็น type_id
        $stmt = $conn->prepare("DELETE FROM type WHERE type_id = :id");
        $stmt->bindParam(":id", $input["type_id"], PDO::PARAM_INT);
        $stmt->execute();
        echo json_encode(["success" => true, "message" => "ลบข้อมูลเรียบร้อย"]);
    }

} catch (PDOException $e) {
    // ถ้า DB Error จะส่งข้อความบอก แทนที่จะหน้าขาว
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database Error: " . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>