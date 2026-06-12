<?php
session_start();
header('Content-Type: application/json');
require_once '../includes/db.php';

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Требуется авторизация']);
    exit;
}

$user_id = $_SESSION['user_id'];

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        $stmt = $conn->prepare("SELECT c.*, p.name, p.price, p.image
                                FROM cart c
                                JOIN products p ON c.product_id = p.id
                                WHERE c.user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $cart = $result->fetch_all(MYSQLI_ASSOC);

        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        echo json_encode(['success' => true, 'cart' => $cart, 'total' => $total]);
        break;

    case 'POST':
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if(!isset($data['product_id'])) {
            echo json_encode(['success' => false, 'message' => 'product_id не указан']);
            break;
        }

        $product_id = $data['product_id'];
        $quantity = isset($data['quantity']) ? $data['quantity'] : 1;

        $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $existing = $result->fetch_assoc();

        if($existing) {
            $new_quantity = $existing['quantity'] + $quantity;
            $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
            $stmt->bind_param("ii", $new_quantity, $existing['id']);
            $stmt->execute();
            echo json_encode(['success' => true, 'message' => 'Количество обновлено']);
        } else {
            $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $user_id, $product_id, $quantity);
            $stmt->execute();
            echo json_encode(['success' => true, 'message' => 'Товар добавлен в корзину']);
        }

        break;

    case 'PUT':
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if(!isset($data['product_id']) || !isset($data['quantity'])) {
            echo json_encode(['success' => false, 'message' => 'product_id и quantity обязательны']);
            break;
        }

        $product_id = $data['product_id'];
        $quantity = $data['quantity'];

        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("iii", $quantity, $user_id, $product_id);
        $stmt->execute();

        if($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Количество обновлено']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Товар не найден в корзине']);
        }

        break;

    case 'DELETE':
        $product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

        if(!$product_id) {
            echo json_encode(['success' => false, 'message' => 'product_id не указан']);
            break;
        }

        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Товар удалён из корзины']);

        break;
    }
?>