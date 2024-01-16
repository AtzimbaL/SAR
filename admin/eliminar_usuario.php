<?php
include '../components/connect.php';

if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = $_COOKIE['tutor_id'];
} else {
    // Puedes redirigir aquí si no se establece $tutor_id
    header('location: login.php');
    exit; // Agregamos un exit para evitar que el código continúe si no se establece $tutor_id
}

// Verificar si se proporciona un ID de usuario válido a través de la URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    // Realizar una consulta para obtener los datos del usuario específico
    $select_user = $conn->prepare("SELECT id, name, email FROM `users` WHERE id = ?");
    $select_user->execute([$user_id]);
    $user_data = $select_user->fetch(PDO::FETCH_ASSOC);

    if (!$user_data) {
        // No se encontró al usuario, puedes mostrar un mensaje de error o redirigir
        echo "No se encontró al usuario.";
        exit;
    }
} else {
    // Si no se proporciona un ID de usuario válido, puedes mostrar un mensaje de error o redirigir
    echo "ID de usuario no proporcionado.";
    exit;
}

$message = array();

if (isset($_POST['delete'])) {
    // Realiza la eliminación del usuario utilizando una consulta DELETE
    $delete_user = $conn->prepare("DELETE FROM `users` WHERE id = ?");
    $delete_user->execute([$user_id]);

    // Redirige a la página de alumnos.php después de eliminar
    header('location: alumnos.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Usuario</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>

<body>

    <?php include '../components/admin_header.php'; ?>

    <section class="form-container" style="min-height: calc(100vh - 19rem);">

        <form action="" method="post" enctype="multipart/form-data">
            <h3>Eliminar Usuario</h3>
            <div class="flex">
                <div class="col">
                    <p>Nombre: <?= $user_data['name']; ?></p>
                    <p>Correo electrónico: <?= $user_data['email']; ?></p>
                </div>
            </div>
            <p>¿Estás seguro de que deseas eliminar a este usuario?</p>
            <input type="submit" name="delete" value="Eliminar Usuario" class="btn">
        </form>

        <?php
        // Mostrar mensajes después de la eliminación
        if (!empty($message)) {
            foreach ($message as $msg) {
                echo "<p>$msg</p>";
            }
        }
        ?>

    </section>

    <!-- update profile section ends -->

    <?php include '../components/footer.php'; ?>

    <script src="../js/admin_script.js"></script>

</body>

</html>

