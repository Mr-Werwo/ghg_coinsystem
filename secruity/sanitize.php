<?php
function sanitize_input($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function secure_query($pdo, $query, $params) {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt;
}
?>
