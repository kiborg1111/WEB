<?php
session_start();
header('Content-Type: application/json');
require_once '../includes/db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Требуется авторизация']);
    exit;
}

$user_id = $_SESSION['user_id'];

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Ошибка: пустой запрос']);
    exit;
}

$address = trim($data['address'] ?? '');
$phone = trim($data['phone'] ?? '');

if (empty($address)) {
    echo json_encode(['success' => false, 'message' => 'Укажите адрес доставки в личном кабинете']);
    exit;
}

if (empty($phone)) {
    echo json_encode(['success' => false, 'message' => 'Укажите номер телефона в личном кабинете']);
    exit;
}

$stmt = $conn->prepare("SELECT c.product_id, c.quantity, p.name, p.price
                        FROM cart c
                        JOIN products p ON c.product_id = p.id
                        WHERE c.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);

if(empty($cart_items)) {
    echo json_encode(['success' => false, 'message' => 'Корзина пуста']);
    exit;
}

$total = 0;
foreach($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}

try {
    $conn->begin_transaction();
    
    $order_number = 'ORD-' . time() . '-' . $user_id;
    
    $stmt = $conn->prepare("INSERT INTO orders (user_id, order_number, total, address, phone, status)
                            VALUES (?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("isdss", $user_id, $order_number, $total, $address, $phone);
    $stmt->execute();
    
    $order_id = $conn->insert_id;
    
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, name, quantity, price)
                            VALUES (?, ?, ?, ?, ?)");
    foreach($cart_items as $item){
        $stmt->bind_param("iisid", $order_id, $item['product_id'], $item['name'], $item['quantity'], $item['price']);
        $stmt->execute();
    }
    
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    
    $conn->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Заказ оформлен',
        'order_id' => $order_id,
        'order_number' => $order_number
    ]);
    
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Ошибка при оформлении заказа: ' . $e->getMessage()]);
}
?>