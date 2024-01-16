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

if (isset($_POST['submit'])) {
    $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ? LIMIT 1");
    $select_user->execute([$user_id]);
    $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);
 
    $prev_pass = $fetch_user['password'];
    $prev_image = $fetch_user['image'];
 
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
 
   if(!empty($name)){
    $update_name = $conn->prepare("UPDATE `users` SET name = ? WHERE id = ?");
    $update_name->execute([$name, $user_id]);
    $message[] = 'El usuario se a actualizado exitosamente!';
   }
 
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
 
    if(!empty($email)){
       $select_email = $conn->prepare("SELECT email FROM `users` WHERE email = ?");
       $select_email->execute([$email]);
       if($select_email->rowCount() > 0){
          $message[] = 'El correo electronico ya existe!';
       }else{
          $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE id = ?");
          $update_email->execute([$email, $user_id]);
          $message[] = 'Correo actualizado exitosamente!';
       }
    }
 
    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = unique_id().'.'.$ext;
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_files/'.$rename;
 
    if(!empty($image)){
       if($image_size > 2000000){
          $message[] = 'image size too large!';
       }else{
          $update_image = $conn->prepare("UPDATE `users` SET `image` = ? WHERE id = ?");
          $update_image->execute([$rename, $user_id]);
          move_uploaded_file($image_tmp_name, $image_folder);
          if($prev_image != '' AND $prev_image != $rename){
             unlink('uploaded_files/'.$prev_image);
          }
          $message[] = 'Imagen actualizada exitosamente!';
       }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Usuario</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>

<body>

    <?php include '../components/admin_header.php'; ?>

    <section class="form-container" style="min-height: calc(100vh - 19rem);">

        <form action="" method="post" enctype="multipart/form-data">
            <h3>Actualizar Usuario</h3>
            <div class="flex">
                <div class="col">
                    <p>Nombre</p>
                    <input type="text" name="name" placeholder="Nombre" maxlength="100" class="box"
                        value="<?= $user_data['name']; ?>">
                    <p>Correo electrónico</p>
                    <input type="email" name="email" placeholder="Correo electrónico" maxlength="100" class="box"
                        value="<?= $user_data['email']; ?>">
                    <p>Actualizar foto</p>
                    <input type="file" name="image" accept="image/*" class="box">
                </div>
            </div>
            <input type="submit" name="submit" value="Guardar Cambios" class="btn">
        </form>

    </section>

    <!-- update profile section ends -->

    <?php include '../components/footer.php'; ?>

    <script src="../js/admin_script.js"></script>

</body>

</html>
