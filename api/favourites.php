<?php
session_start();
header('Content-Type: application/json');
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Требуется авторизация']);
    exit;
}

$user_id = $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $stmt = $conn->prepare("SELECT p.* FROM favorites f
                            JOIN products p ON f.product_id = p.id
                            WHERE f.user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode(['success' => true, 'favorites' => $products]);
}
elseif ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $product_id = (int)($input['product_id'] ?? 0);

    if (!$product_id) {
        echo json_encode(['success' => false, 'message' => 'Не указан товар']);
        exit;
    }

    $stmt = $conn->prepare("SELECT id FROM favorites WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Товар уже в избранном']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO favorites (user_id, product_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Добавлено в избранное']);
}
elseif ($method === 'DELETE') {
    $product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
    if (!$product_id) {
        echo json_encode(['success' => false, 'message' => 'Не указан товар']);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM favorites WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Удалено из избранного']);
}
else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Метод не поддерживается']);
}
?>