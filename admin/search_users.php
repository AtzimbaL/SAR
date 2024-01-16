<?php
include '../components/connect.php';

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $search = filter_var($search, FILTER_SANITIZE_STRING);

    // Realizar una consulta para buscar usuarios por nombre
    $select_users = $conn->prepare("SELECT name FROM `users` WHERE name LIKE ? LIMIT 10");
    $select_users->execute(["%$search%"]);
    $results = $select_users->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los resultados como JSON
    echo json_encode($results);
}
?>
