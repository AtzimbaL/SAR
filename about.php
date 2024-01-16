<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Acerca de....</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- about section starts  -->

<section class="about">

   <div class="row">

      <div class="image">
         <img src="images/about-img.svg" alt="">
      </div>

      <div class="content">
         <h3>Sar Solutions</h3>
         <p>Una empresa 100% Mexicana que se dedica a la capacitación en temas de calidad, administración y mejora continua.</p>
      </div>

   </div>

   <div class="box-container">

      <div class="box">
         <i class="fas fa-graduation-cap"></i>
         <div>
            <h3>100+</h3>
            <span>Cursos</span>
         </div>
      </div>

      <div class="box">
         <i class="fas fa-user-graduate"></i>
         <div>
            <h3>7k+</h3>
            <span>Estudiantes</span>
         </div>
      </div>

      <div class="box">
         <i class="fas fa-chalkboard-user"></i>
         <div>
            <h3>3+</h3>
            <span>Paises</span>
         </div>
      </div>

      <div class="box">
         <i class="fas fa-briefcase"></i>
         <div>
            <h3>12+</h3>
            <span>Socios comerciales</span>
         </div>
      </div>

   </div>

</section>

<!-- about section ends -->

<!-- reviews section starts  -->

<section class="reviews">

   <h1 class="heading">Recomendaciones</h1>

   <div class="box-container">

   <div class="box" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
   <img src="images/Prueba 1.jpg" alt="Microsoft Word">
   <div class="user">
      <h3 style="margin-top: 10px;">Microsoft Word</h3>
   </div>
   <p style="color: #007bff; margin-top: 5px;">$0.00</p>
   <div style="text-align: center;">
   <button class="inscribirse-button" style="background-color: #007bff; color: #fff; border: none; border-radius: 20px; padding: 15px 30px; cursor: pointer; font-size: 20px; font-weight: bold; transition: transform 0.2s;">
         Inscribirse
      </button>
   </div>
</div>
<div class="box" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
   <img src="images/Prueba 2.jpg" alt="Microsoft Powerpoint">
   <div class="user">
      <h3 style="margin-top: 10px;">Microsoft Powerpoint</h3>
   </div>
   <p style="color: #007bff; margin-top: 5px;">$0.00</p>
   <div style="text-align: center;">
   <button class="inscribirse-button" style="background-color: #007bff; color: #fff; border: none; border-radius: 20px; padding: 15px 30px; cursor: pointer; font-size: 20px; font-weight: bold; transition: transform 0.2s;">
         Inscribirse
      </button>
   </div>
</div>
<div class="box" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
   <img src="images/Prueba 3.jpg" alt="Microsoft Office">
   <div class="user">
      <h3 style="margin-top: 10px;">Microsoft Office</h3>
   </div>
   <p style="color: #007bff; margin-top: 5px;">$49.00</p>
   <div style="text-align: center;">
   <button class="inscribirse-button" style="background-color: #007bff; color: #fff; border: none; border-radius: 20px; padding: 15px 30px; cursor: pointer; font-size: 20px; font-weight: bold; transition: transform 0.2s;">
         Inscribirse
      </button>
   </div>
</div>
<div class="box" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
   <img src="images/Prueba 4.jpg" alt="Microsoft Excel">
   <div class="user">
      <h3 style="margin-top: 10px;">Microsoft Excel</h3>
   </div>
   <p style="color: #007bff; margin-top: 5px;">$90.00</p>
   <div style="text-align: center;">
   <button class="inscribirse-button" style="background-color: #007bff; color: #fff; border: none; border-radius: 20px; padding: 15px 30px; cursor: pointer; font-size: 20px; font-weight: bold; transition: transform 0.2s;">
         Inscribirse
      </button>
   </div>
</div>
<div class="box" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
   <img src="images/Prueba 5.jpg" alt="Mapeo de Procesos">
   <div class="user">
      <h3 style="margin-top: 10px;">Mapeo de Procesos</h3>
   </div>
   <p style="color: #007bff; margin-top: 5px;">$290.00</p>
   <div style="text-align: center;">
   <button class="inscribirse-button" style="background-color: #007bff; color: #fff; border: none; border-radius: 20px; padding: 15px 30px; cursor: pointer; font-size: 20px; font-weight: bold; transition: transform 0.2s;">
         Inscribirse
      </button>
   </div>
</div>
<div class="box" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
   <img src="images/Prueba 6.jpg" alt="SAP FI Finanzas">
   <div class="user">
      <h3 style="margin-top: 10px;">SAP FI Finanzas</h3>
   </div>
   <p style="color: #007bff; margin-top: 5px;">$290.00</p>
   <div style="text-align: center;">
   <button class="inscribirse-button" style="background-color: #007bff; color: #fff; border: none; border-radius: 20px; padding: 15px 30px; cursor: pointer; font-size: 20px; font-weight: bold; transition: transform 0.2s;">
         Inscribirse
      </button>
   </div>
</div>

   </div>

</section>

<!-- reviews section ends -->










<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>