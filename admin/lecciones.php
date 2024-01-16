<?php
include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

if(isset($_GET['content_id'])){
   $content_id = $_GET['content_id'];
   // Realiza una consulta para obtener las lecciones asociadas a $content_id
   $select_lecciones = $conn->prepare("SELECT * FROM `lecciones` WHERE content_id = ?");
   $select_lecciones->execute([$content_id]);
}else{
   // Manejar el caso en el que no se proporciona content_id
   echo "No se han encontrado lecciones de este curso";
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
<section class="contents">
   <h1 class="heading">Lecciones</h1>
   <div class="box-container">
      <?php
         if(isset($content_id) && $select_lecciones->rowCount() > 0){
            while ($fetch_leccion = $select_lecciones->fetch(PDO::FETCH_ASSOC)) {
               // Aquí puedes mostrar información de cada lección
               $leccion_title = $fetch_leccion['title'];
      ?>
      
       
         
        <div class="box" style="text-align: center;">
      <h3 class="title" style="margin-bottom: .5rem;"><?= $leccion_title; ?></h3>
<!-- Agrega este enlace en tu página existente para cada lección -->
<a href="view_lecciones.php?id=<?= $fetch_leccion['id']; ?>" class="btn btn-green">Ver</a>

      
   </div>
  
      
      <?php
            }
         } else {
            echo '<p class="empty">No se encontraron lecciones de este curso.</p>';
         }
      ?>
 
   
 </div>
</section>


<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>
</body>
</html>
