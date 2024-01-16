<?php
include '../components/connect.php';

if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = $_COOKIE['tutor_id'];
} else {
    $tutor_id = '';
    header('location:login.php');
}

if (isset($_GET['id'])) {
    $leccion_id = $_GET['id'];

    // Consultar la base de datos para obtener la información de la lección
    $select_leccion = $conn->prepare("SELECT * FROM lecciones WHERE id = ?");
    $select_leccion->execute([$leccion_id]);

    // Comprobar si se encontró la lección
    if ($select_leccion->rowCount() > 0) {
        $fetch_leccion = $select_leccion->fetch(PDO::FETCH_ASSOC);
        $titulo = $fetch_leccion['title'];
        $recursos = $fetch_leccion['recursos'];
        $instrucciones = $fetch_leccion['instrucciones']; // Agregamos las instrucciones

        // Determinar el tipo de contenido en función de la extensión del archivo
        $extension = pathinfo($recursos, PATHINFO_EXTENSION);
        $esVideo = in_array($extension, ['mp4', 'avi', 'mov']);
        $esPDF = in_array($extension, ['pdf']);
    } else {
        // Manejar el caso en el que no se encuentre la lección
        echo "La lección no se encontró.";
    }
} else {
    // Manejar el caso en el que no se proporcionó el ID de la lección en la URL
    echo "Se requiere un ID de lección para ver su contenido.";
}
if(isset($_POST['delete_leccion'])){

   $eliminar_leccion = $conn->prepare("DELETE FROM lecciones WHERE id = ?");
   $resultado = $eliminar_leccion->execute([$leccion_id]);
   $message[] = 'Lección Elimada!';
   header('location:contents.php'); // Redirige después de eliminar el video
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver contenido</title>

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- custom css file link -->
    <link rel="stylesheet" href="../css/admin_style.css">
    <!-- SEMANTIC -->
    <link rel="stylesheet" type="text/css" href="semantic/dist/semantic.min.css">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    <script src="semantic/dist/semantic.min.js"></script>

</head>

<body>
    <?php include '../components/admin_header.php'; ?>
    <section class="view-content">
        <div class="container">
            <h1 class="heading" style="text-align: center;"><?= $titulo; ?></h1>

            <div class="leccion">
                <?php if ($esVideo): ?>
                <!-- Mostrar el reproductor de video si es un video -->
                <video autoplay controls class="video">
                    <source src="../uploaded_files/<?= $recursos; ?>" type="video/mp4" class="video">
                </video>
                <?php elseif ($esPDF): ?>
                <!-- Mostrar el documento PDF si es un PDF -->
                <iframe src="../uploaded_files/<?= $recursos; ?>" style="width: 100%; height: 500px;"></iframe>
                <?php else: ?>
                <!-- Mostrar un mensaje si no es video ni PDF -->
                <p>El tipo de contenido no es compatible. No se puede mostrar.</p>
                <?php endif; ?>

                <!-- Agrega aquí el contenido adicional de la lección, como instrucciones -->
                <?php if (!empty($instrucciones)): ?>
                <div class="instructions">
                    <h2 class="heading" style="text-align: center;">Instrucciones:</h2>
                    <h1><?= $instrucciones; ?></h1>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <form action="" method="post">
            <div class="flex-btn">
                <a href="update_lecciones.php?id=<?= $fetch_leccion['id']; ?>" class="btn btn-green">Actualizar</a>
                <input type="submit" value="Eliminar" class="delete-btn" onclick="return confirm('Estas seguro de eliminar la lección?');" name="delete_leccion">

            </div>
        </form>
    </section>
    <?php include '../components/footer.php'; ?>
    <!-- Agrega aquí cualquier script necesario -->
    <script src="../js/admin_script.js"></script>
</body>

</html>