<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:contents.php');
}

if(isset($_POST['delete_video'])){
   // Código de eliminación del video
   // ...
   header('location:contents.php'); // Redirige después de eliminar el video
}

if(isset($_POST['delete_comment'])){
   // Código de eliminación de comentarios
   // ...
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Ver contenido</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="view-content">
   <?php
      $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ? AND tutor_id = ?");
      $select_content->execute([$get_id, $tutor_id]);
      if($select_content->rowCount() > 0){
         while($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)){
            $video_id = $fetch_content['id'];

            $count_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ? AND content_id = ?");
            $count_likes->execute([$tutor_id, $video_id]);
            $total_likes = $count_likes->rowCount();

            $count_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ? AND content_id = ?");
            $count_comments->execute([$tutor_id, $video_id]);
            $total_comments = $count_comments->rowCount();
   ?>
   <div class="container">
   <h1 class="heading">Ver contenido</h1>
      <video src="../uploaded_files/<?= $fetch_content['video']; ?>" autoplay controls poster="../uploaded_files/<?= $fetch_content['thumb']; ?>" class="video"></video>
      <h3 class="title"><?= $fetch_content['title']; ?></h3>
      <div class="flex">
         <div><i class="fas fa-heart"></i><span><?= $total_likes; ?></span></div>
         <div><i class="fas fa-comment"></i><span><?= $total_comments; ?></span></div>
      </div>
      <div class="description"><?= $fetch_content['description']; ?></div>
      <form action="" method="post">
         <div class="flex-btn">
            <input type="hidden" name="video_id" value="<?= $video_id; ?>">
            <a href="update_content.php?get_id=<?= $video_id; ?>" class="option-btn">Actualizar</a>
            <input type="submit" value="Eliminar" class="delete-btn" onclick="return confirm('delete this video?');" name="delete_video">
         </div>
      </form>
   </div>
   <?php
    }
   }else{
      echo '<p class="empty">No hay contenido! <a href="add_content.php" class="btn" style="margin-top: 1.5rem;">add videos</a></p>';
   }
      
   ?>
</section>

<section class="comments">
   <h1 class="heading">Comentarios del usuario</h1>
   <div class="show-comments">
      <?php
         $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE content_id = ?");
         $select_comments->execute([$get_id]);
         if($select_comments->rowCount() > 0){
            while($fetch_comment = $select_comments->fetch(PDO::FETCH_ASSOC)){   
               $select_commentor = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
               $select_commentor->execute([$fetch_comment['user_id']]);
               $fetch_commentor = $select_commentor->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="box">
         <div class="user">
            <?php if (is_array($fetch_commentor) && isset($fetch_commentor['image'])): ?>
               <img src="../uploaded_files/<?= $fetch_commentor['image']; ?>" alt="">
               <div>
                  <h3><?= $fetch_commentor['name']; ?></h3>
               </div>
            <?php else: ?>
               <!-- Manejar el caso en el que $fetch_commentor no sea un array válido o no tenga 'image' -->
               <style>
                  p {
                     font-size: 16px; /* Puedes ajustar el tamaño según tus necesidades */
                  }
               </style>
               <p>Usuario no disponible</p>
            <?php endif; ?>
         </div>
         <?php if (is_array($fetch_comment) && isset($fetch_comment['comment'])): ?>
            <p class="text"><?= $fetch_comment['comment']; ?></p>
            <form action="" method="post" class="flex-btn">
               <input type="hidden" name="comment_id" value="<?= $fetch_comment['id']; ?>">
               <button type="submit" name="delete_comment" class="inline-delete-btn" onclick="return confirm('delete this comment?');">Eliminar comentario</button>
            </form>
         <?php else: ?>
            <!-- Manejar el caso en el que $fetch_comment no sea un array válido o no tenga 'comment' -->
            <h1>Comentario no disponible</h1>
         <?php endif; ?>
      </div>
      <?php
       }
      }else{
         echo '<p class="empty">No se encontraron comentarios!</p>';
      }
      ?>
   </div>
</section>











<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>