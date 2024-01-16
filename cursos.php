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
      .box.custom-card {
         border: 1px solid #ccc;
         border-radius: 5px; /* Esquinas redondeadas */
         width: 300px; /* Ancho de la tarjeta personalizable */
         margin: 10px;
         display: flex;
         flex-direction: column;
         background-color: #f7f7f7;
         box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
         transition: transform 0.2s;
      }

      /* Título de la tarjeta */
      .box.custom-card h3 {
         font-size: 1.6rem; /* Tamaño de fuente para el título */
         text-align: center;
         text-transform: uppercase;
         color: #333;
         padding: 10px 0;
      }

      /* Descripción de la tarjeta */
      .box.custom-card p {
         font-size: 1.5rem; /* Tamaño de fuente para la descripción */
         text-align: center;
         color: #0000FF;
         padding: 5px 10px; /* Espaciado interno ajustado */
         max-height: 30rem; /* Altura máxima para la descripción (3 líneas de texto) */
         overflow: hidden;
         text-overflow: ellipsis;
         white-space: normal; /* Permitir múltiples líneas de texto */
      }

      /* Enlace "Ver Curso" */
      .box.custom-card a.ver-curso-link {
         font-size: 2rem; /* Tamaño de fuente para el enlace */
         background-color: #007bff;
         color: #fff;
         border: none;
         border-radius: 5px; /* Esquinas redondeadas */
         padding: 5px 10px; /* Tamaño del enlace más pequeño */
         text-decoration: none;
         text-align: center;
         transition: background-color 0.3s;
         cursor: pointer;
         margin-top: auto; /* Mover el enlace hacia abajo */
         align-self: center; /* Centrar el enlace en la tarjeta */
      }

      .box {
         width: 500px; /* Cambia el ancho de la tarjeta según tus necesidades */
         height: 450px; /* Cambia la altura de la tarjeta según tus necesidades */
         border: 2px solid #ddd; /* Agrega un borde si lo deseas */
         margin: 20px; /* Añade margen entre las tarjetas */
         padding: 10px; /* Añade espacio interno dentro de la tarjeta */
         border-radius: 5px; /* Agrega bordes redondeados si lo deseas */
         box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2); /* Agrega una sombra a la tarjeta */
      }

      /* Estilo al pasar el ratón sobre el enlace */
      .box.custom-card a.ver-curso-link:hover {
         background-color: #0056b3;
      }
   </style>
</head>

<body>
   <?php include 'components/user_header.php'; ?>

   <section class="cursos">
      <h1 class="heading">Cursos</h1>
      <div class="box-container">
         <?php
         // Obtener el playlist_id de la URL
         if (isset($_GET['playlist_id'])) {
            $playlist_id = $_GET['playlist_id'];

            // Consulta SQL para seleccionar los cursos de la categoría específica
            $select_courses = $conn->prepare("SELECT id, title, description, thumb FROM content WHERE playlist_id = ?");
            $select_courses->execute([$playlist_id]);

            if ($select_courses->rowCount() > 0) {
               while ($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)) {
                  // Accede a los valores de la tabla "content" según las columnas.
                  $course_id = $fetch_course['id'];
                  $course_title = $fetch_course['title'];
                  $course_description = $fetch_course['description'];
                  $thumbnail = $fetch_course['thumb'];
         ?>

                  <!-- Tarjeta de curso -->
                  <div class="box custom-card">
                     <!-- Imagen en miniatura -->
                     <img src="<?php
                        if (file_exists('uploaded_files/' . $thumbnail)) {
                           echo 'uploaded_files/' . $thumbnail;
                        } else {
                           echo 'default_thumbnail.jpg'; // Ruta a la miniatura predeterminada en caso de que no exista
                        }
                        ?>" class="thumb" alt="">
    
                     <!-- Aquí puedes mostrar los datos del curso en tu HTML -->
                     <h3><?= $course_title; ?></h3>
                     <p><?= $course_description; ?></p>
                     <!-- Enlace "Ver Curso" con el ID de curso -->
                     <a href="ver_curso.php?id=<?= $course_id; ?>" class="ver-curso-link">Ver Curso</a>
                  </div>

         <?php
               }
            } else {
               echo '<p class="empty">No se encontraron cursos para esta categoría.</p>';
            }
         } else {
            echo '<p class="empty">No se especificó una categoría.</p>';
         }
         ?>
      </div>
   </section>

   <?php include 'components/footer.php'; ?>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>