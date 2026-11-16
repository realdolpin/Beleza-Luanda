<?php
// Recebe o carrinho em JSON, confirma os preços na base de dados e grava o pedido.
// Os preços enviados pelo navegador são ignorados de propósito.
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../includes/helpers.php';

function fail(string $msg, int $code = 422): never {
    http_response_code($code);
    echo json_encode(['ok' => false, 'error' => $msg]);
    exit;
}

$in    = json_decode(file_get_contents('php://input'), true) ?: [];
$name  = trim($in['name'] ?? '');
$phone = trim($in['phone'] ?? '');
$zone  = trim($in['zone'] ?? '');
$cart  = $in['cart'] ?? [];
if ($name === '' || $phone === '' || $zone === '' || !is_array($cart) || !$cart) fail('Preenche o nome, telefone e zona.');

try {
    $catalogo = array_column(products(), null, 'id');
    $linhas = []; $total = 0;
    foreach ($cart as $id => $qty) {
        $id = (int)$id; $qty = max(1, min(99, (int)$qty));
        if (!isset($catalogo[$id])) continue;
        $p = $catalogo[$id];
        $linhas[] = [$id, $p['name'], $qty, (int)$p['price']];
        $total += $qty * (int)$p['price'];
    }
    if (!$linhas) fail('Carrinho vazio.');

    $pdo = db();
    $pdo->beginTransaction();
    $codigo = 'BL-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
    $pdo->prepare('INSERT INTO orders (code, customer_name, phone, zone, total) VALUES (?,?,?,?,?)')
        ->execute([$codigo, mb_substr($name, 0, 120), mb_substr($phone, 0, 40), mb_substr($zone, 0, 160), $total]);
    $orderId = $pdo->lastInsertId();
    $ins = $pdo->prepare('INSERT INTO order_items (order_id, product_id, product_name, qty, unit_price) VALUES (?,?,?,?,?)');
    foreach ($linhas as $l) $ins->execute([$orderId, ...$l]);
    $pdo->commit();

    echo json_encode(['ok' => true, 'code' => $codigo, 'total' => $total]);
} catch (Throwable $ex) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    fail('Erro no servidor. Confirma a ligação à base de dados.', 500);
}
