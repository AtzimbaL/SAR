<?php
include '../components/connect.php';

if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = $_COOKIE['tutor_id'];
} else {
    $tutor_id = '';
    header('location:login.php');
}

// Función para generar IDs únicos
function generateUniqueID($conn) {
    // Generar un ID único basado en la fecha actual y un número aleatorio
    $uniqueID = date('YmdHis') . mt_rand(1000, 9999);

    // Verificar si el ID ya existe en la base de datos
    $checkID = $conn->prepare("SELECT id FROM lecciones WHERE id = ?");
    $checkID->execute([$uniqueID]);

    // Si el ID ya existe, llamar recursivamente a esta función para generar uno nuevo
    if ($checkID->rowCount() > 0) {
        return generateUniqueID($conn);
    }

    // Si el ID es único, devolverlo
    return $uniqueID;
}

// Obtener la lista de cursos registrados desde la base de datos
$select_courses = $conn->prepare("SELECT id, title FROM `content` WHERE tutor_id = ?");
$select_courses->execute([$tutor_id]);
$course_options = '';
if ($select_courses->rowCount() > 0) {
    while ($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)) {
        $course_id = $fetch_course['id'];
        $course_title = $fetch_course['title'];
        $course_options .= "<option value='$course_id'>$course_title</option>";
    }
} else {
    $course_options = '<option value="" disabled>No hay cursos registrados.</option>';
}

if (isset($_POST['submit'])) {

    $content_id = $_POST['content_id'];
    $id = generateUniqueID($conn); // Generar un ID único
    $title = $_POST['title'];
    $title = filter_var($title, FILTER_SANITIZE_STRING);
    $instrucciones = $_POST['instrucciones']; // Agrega este campo

    // Manejo de archivos
    if (isset($_FILES['recursos']) && $_FILES['recursos']['error'] === UPLOAD_ERR_OK) {
        $file_name = $_FILES['recursos']['name'];
        $file_tmp = $_FILES['recursos']['tmp_name'];

        // Obtener la extensión del archivo
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

        // Directorio donde se almacenarán los archivos
        $upload_dir = '../uploaded_files/';

        // Nombre único para el archivo
        $unique_name = $id . '.' . $file_ext;

        // Ruta completa del archivo en el servidor
        $file_path = $upload_dir . $unique_name;

        if (move_uploaded_file($file_tmp, $file_path)) {
            // Archivo subido con éxito, ahora puedes insertar los detalles en la base de datos
            $add_leccion = $conn->prepare("INSERT INTO `lecciones` (id, content_id, title, recursos, instrucciones) VALUES (?, ?, ?, ?, ?)");
            if ($add_leccion->execute([$id, $content_id, $title, $unique_name, $instrucciones])) {
                $message[] = 'Lección agregada con éxito!';
            } else {
                $message[] = 'Error al agregar la lección a la base de datos.';
            }
        } else {
            $message[] = 'Error al cargar el archivo.';
        }
    } else {
        $message[] = 'Debe seleccionar un archivo para subir.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="video-form">
    <h1 class="heading">Agregar una nueva lección</h1>
    <form class="register" action="" method="post" enctype="multipart/form-data">
        <!-- Campo de selección para elegir el curso -->
        <p>Selecciona un curso <span>*</span></p>
        <select name="content_id" id="content_id" class="box" required>
            <option value="" disabled selected>-- Selecciona un curso --</option>
            <?php echo $course_options; ?>
        </select>
        <p>Escribe el título de la lección <span>*</span></p>
        <input type="text" name="title" placeholder="Nombre de la lección" maxlength="100" class="box">
        <p>Subir archivo <span>*</span></p>
        <input type="file" name="recursos" required class="box">
        <p>Instrucciones de la lección <span>*</span></p>
        <textarea name="instrucciones" rows="4" placeholder="Instrucciones de la lección" class="box"></textarea>

        <input type="submit" value="Agregar" name="submit" class="btn">
    </form>
</section>

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>
</body>
</html>
