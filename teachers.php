<?php
include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
   $user_id = $_COOKIE['user_id'];
} else {
   $user_id = '';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Categoria</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      /* Estilos generales de la tarjeta */
      .box-container {
         display: flex;
         flex-wrap: wrap;
         justify-content: space-between;
      }

      .box.custom-card {
         border: 1px solid #ccc;
         border-radius: 10px; /* Esquinas redondeadas */
         width: calc(33.33% - 20px); /* Ancho de la tarjeta personalizable */
         margin-bottom: 20px;
         display: flex;
         flex-direction: column;
         background-color: #f7f7f7;
         box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); /* Sombras más pronunciadas */
         transition: transform 0.2s;
      }

      /* Título de la tarjeta */
      .box.custom-card h3 {
         font-size: 1.6rem;
         text-align: center;
         text-transform: uppercase;
         color: #333;
         padding: 10px 0;
      }

      /* Descripción de la tarjeta */
      .box.custom-card p {
         font-size: 1rem;
         text-align: center;
         color: #555;
         padding: 0 20px;
         margin: 0;
      }

      /* Botón "Ver Cursos" */
      .box.custom-card a.ver-cursos-button {
         font-size: 1rem;
         background-color: #007bff;
         color: #fff;
         border: none;
         border-radius: 5px;
         padding: 10px 20px;
         text-decoration: none;
         text-align: center;
         transition: background-color 0.3s;
         cursor: pointer;
         align-self: center;
         margin-top: auto;
         display: inline-block;
      }

      .box.custom-card a.ver-cursos-button:hover {
         background-color: #0056b3;
      }

      /* Estilo para las imágenes de la categoría */
      .category-image img {
         width: 100%;
         height: auto;
         border-radius: 10px 10px 0 0;
      }
   </style>
</head>

<body>
   <?php include 'components/user_header.php'; ?>

   <section class="playlists">
      <h1 class="heading">Categoría</h1>
      <div class="box-container">
         <?php
         $select_playlists = $conn->prepare("SELECT id, title, description, thumb FROM playlist");
         $select_playlists->execute();
         if ($select_playlists->rowCount() > 0) {
            while ($fetch_playlist = $select_playlists->fetch(PDO::FETCH_ASSOC)) {
               $playlist_id = $fetch_playlist['id'];
               $playlist_title = $fetch_playlist['title'];
               $playlist_description = $fetch_playlist['description'];
               $playlist_thumb = $fetch_playlist['thumb'];
         ?>
            <div class="box custom-card">
               <!-- Imagen de la categoría -->
               <div class="category-image">
                  <img src="uploaded_files/<?= $playlist_thumb; ?>" alt="Imagen de la categoría">
               </div>
               <!-- Título y Descripción de la categoría -->
               <h3><?= $playlist_title; ?></h3>
               <p><?= $playlist_description; ?></p>
               <!-- Enlace "Ver Cursos" con el ID de la playlist -->
               <a href="cursos.php?playlist_id=<?= $playlist_id; ?>" class="ver-cursos-button">Ver Cursos</a>
            </div>
         <?php
            }
         } else {
            echo '<p class="empty">No se encontraron categorías.</p>';
         }
         ?>
      </div>
   </section>

   <?php include 'components/footer.php'; ?>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>
