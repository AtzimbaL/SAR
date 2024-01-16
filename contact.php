<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['submit'])){

   $name = $_POST['name']; 
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email']; 
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number']; 
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $msg = $_POST['msg']; 
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);

   $select_contact = $conn->prepare("SELECT * FROM `contact` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $select_contact->execute([$name, $email, $number, $msg]);

   if($select_contact->rowCount() > 0){
      $message[] = 'Mensaje enviado!';
   }else{
      $insert_message = $conn->prepare("INSERT INTO `contact`(name, email, number, message) VALUES(?,?,?,?)");
      $insert_message->execute([$name, $email, $number, $msg]);
      $message[] = 'Mensaje enviado con éxito!';

  // Envía el correo electrónico
  $to = 'monserratvera236@gmail.com'; // Cambia esto a tu dirección de correo electrónico de Gmail
  $subject = 'Nuevo mensaje de contacto';
  $message_body = "Nombre: $name\nCorreo Electrónico: $email\nTeléfono: $number\nMensaje:\n$msg";
  $headers = "From: monserratvera236@gmail.com"; // Cambia esto a tu dirección de correo de Gmail
  
  // Configura la función mail() para utilizar SMTP
  ini_set("SMTP", "smtp.gmail.com");
  ini_set("smtp_port", "587");
  ini_set("sendmail_from", "monserratvera236@gmail.com"); // Cambia esto a tu dirección de correo de Gmail
  

}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>contact</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- contact section starts  -->

<section class="contact">

   <div class="row">

      <div class="image">
         <img src="images/contact-img.svg" alt="">
      </div>

      <form action="" method="post">
         <h3>Mensaje</h3>
         <input type="text" placeholder="Nombre" required maxlength="100" name="name" class="box">
         <input type="email" placeholder="Correo electrónico" required maxlength="100" name="email" class="box">
         <input type="number" min="0" max="9999999999" placeholder="Telefono" required maxlength="10" name="number" class="box">
         <textarea name="msg" class="box" placeholder="Mensaje" required cols="30" rows="10" maxlength="1000"></textarea>
         <input type="submit" value="Enviar" class="inline-btn" name="submit">
      </form>

   </div>

   <div class="box-container">

      <div class="box">
         <i class="fas fa-phone"></i>
         <h3>phone number</h3>
         <a href="tel:5563386068">+52 (55) 6338-6068</a>
      </div>

      <div class="box">
         <i class="fas fa-envelope"></i>
         <h3>Correo electronico</h3>
         <a href="capacitacion@sar-solution.com">capacitacion@sar-solution.com</a>
      </div>

      <div class="box">
         <i class="fas fa-map-marker-alt"></i>
         <h3>Direccion</h3>
         <a href="#">Tecámac de Felipe Villanueva, México, México</a>
      </div>


   </div>

</section>



<?php include 'components/footer.php'; ?>  

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>