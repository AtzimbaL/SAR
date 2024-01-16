<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['submit'])){

   $id = unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_files/'.$rename;

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_user->execute([$email]);
   
   if($select_user->rowCount() > 0){
      $message[] = 'El correo ya existe!';
   }else{
      if($pass != $cpass){
         $message[] = 'La contraseña no coincide!';
      }else{
         $insert_user = $conn->prepare("INSERT INTO `users`(id, name, email, password, image) VALUES(?,?,?,?,?)");
         $insert_user->execute([$id, $name, $email, $cpass, $rename]);
         move_uploaded_file($image_tmp_name, $image_folder);
         
         $verify_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
         $verify_user->execute([$email, $pass]);
         $row = $verify_user->fetch(PDO::FETCH_ASSOC);
         
         if($verify_user->rowCount() > 0){
            setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
            header('location:home.php');
         }
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
   <title>Registro</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <!-- Estilos para centrar y aumentar el formulario -->
   <style>
      body {
         display: flex;
         justify-content: center;
         align-items: center;
         height: 100vh;
         margin: 0;
      }
      html, body {
      height: 100%;
      margin: 0;
      padding: 0;
}

      .form-container {
         text-align: center;
      }

      /* Estilos adicionales para el formulario más grande */
      .register {
         max-width: 600px; /* Ancho máximo del formulario */
         padding: 40px; /* Mayor espacio interno */
         border: 1px solid #ccc;
         border-radius: 8px;
         background-color: #fff;
      }

      /* Aumentar el tamaño de fuente y el espacio entre elementos */
      .register h3,
      .register p {
         font-size: 24px;
         margin-bottom: 15px;
      }

      .register input[type="text"],
      .register input[type="email"],
      .register input[type="password"],
      .register input[type="file"] {
         width: 100%;
         padding: 10px;
         font-size: 18px;
         margin-bottom: 15px;
      }

      .register .flex {
         display: flex;
         justify-content: space-between;
      }

      .register .col {
         flex: 1;
         margin-right: 10px;
      }

      .register .link {
         font-size: 18px;
         margin-top: 15px;
      }
   </style>
</head>
<body>

<section class="form-container">

   <form class="register" action="" method="post" enctype="multipart/form-data">
      <h3>Crear cuenta</h3>
      <div class="flex">
         <div class="col">
            <p>Tu nombre <span>*</span></p>
            <input type="text" name="name" placeholder="Ingresa tu nombre" maxlength="50" required class="box">
            <p>Tu correo electrónico <span>*</span></p>
            <input type="email" name="email" placeholder="Ingresa tu correo electrónico" maxlength="50" required class="box">
         </div>
         <div class="col">
            <p>Tu contraseña <span>*</span></p>
            <input type="password" name="pass" placeholder="Ingresa tu contraseña" maxlength="20" required class="box">
            <p>Confirma tu contraseña <span>*</span></p>
            <input type="password" name="cpass" placeholder="Confirma tu contraseña" maxlength="20" required class="box">
         </div>
      </div>
      <p>Selecciona una imagen <span>*</span></p>
      <input type="file" name="image" accept="image/*" required class="box">
      <p class="link">¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a></p>
      <input type="submit" name="submit" value="Registrar ahora" class="btn">
   </form>

</section>

</body>
</html>