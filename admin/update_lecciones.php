<?php
include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

if(isset($_GET['id'])){
   $leccion_id = $_GET['id'];

   // Consultar la base de datos para obtener la información de la lección
   $select_leccion = $conn->prepare("SELECT * FROM `lecciones` WHERE id = ?");
   $select_leccion->execute([$leccion_id]);

   // Comprobar si se encontró la lección
   if($select_leccion->rowCount() > 0){
      $fetch_leccion = $select_leccion->fetch(PDO::FETCH_ASSOC);
      $titulo = $fetch_leccion['title'];
      $recursos = $fetch_leccion['recursos'];
      $instrucciones = $fetch_leccion['instrucciones']; // Agregamos las instrucciones
      
      // Determinar el tipo de contenido en función de la extensión del archivo
      $extension = pathinfo($recursos, PATHINFO_EXTENSION);
      $esVideo = in_array($extension, ['mp4', 'avi', 'mov']);
      $esPDF = in_array($extension, ['pdf']);


      // Procesar la actualización de la lección si se envía el formulario
      if(isset($_POST['update_leccion'])){
         // Aquí debes manejar la actualización de la lección con los datos enviados desde el formulario
         // Por ejemplo, puedes realizar una consulta SQL para actualizar los campos en la base de datos.

         // Ejemplo de consulta para actualizar el título, instrucciones y recursos:
         $nuevoTitulo = $_POST['nuevo_titulo'];
         $nuevasInstrucciones = $_POST['nuevas_instrucciones'];
         $nuevosRecursos = $_POST['nuevos_recursos'];

         $update_query = $conn->prepare("UPDATE `lecciones` SET title = ?, instrucciones = ?, recursos = ? WHERE id = ?");
         $result = $update_query->execute([$nuevoTitulo, $nuevasInstrucciones, $nuevosRecursos, $leccion_id]);

         if($result){
            // La lección se actualizó con éxito, puedes mostrar un mensaje de confirmación.
            $message[] = 'Leccion Actualizada';

            // También puedes recargar la página para ver los cambios actualizados.
            header("Refresh: 2; view_lecciones.php?id=$leccion_id"); // Cambia 'nombre_de_esta_pagina.php' al nombre de esta página.
         } else {
            // Manejar el caso en el que no se pudo actualizar la lección
            $message[] = 'No se puedo actualizar :(';

         }
      }
   } else {
      // Manejar el caso en el que no se encontró la lección
      echo "La lección no se encontró.";
   }
} else {
   // Manejar el caso en el que no se proporcionó el ID de la lección en la URL
   echo "Se requiere un ID de lección para ver su contenido.";
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
   <!-- SEMANTIC -->
   <link rel="stylesheet" type="text/css" href="semantic/dist/semantic.min.css">
   <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
   <script src="semantic/dist/semantic.min.js"></script>

   <?php if (!$esVideo): ?>
      <!-- Incluir archivos CSS y JS de Viewer.js si no es un video -->
      <link rel="stylesheet" href="ruta/a/viewer.min.css">
      <script src="ruta/a/viewer.min.js"></script>
      
   <?php endif; ?>
   <script>
   document.addEventListener("DOMContentLoaded", function () {
      var viewer = new Viewer(document.getElementById("document-viewer"), {
         // Opciones de configuración de Viewer.js:
         inline: false, // Muestra el visor en una ventana emergente en lugar de en línea
         toolbar: {
            zoomIn: 2,    // Nivel de zoom al hacer clic en el botón de zoom in
            zoomOut: 2,   // Nivel de zoom al hacer clic en el botón de zoom out
            oneToOne: true, // Mostrar botón "1:1" para ajustar la escala al tamaño original
            reset: true,   // Mostrar botón "Restablecer" para volver al tamaño original
            prev: true,    // Mostrar botón "Anterior" para navegar a la página anterior (si se muestra un PDF)
            next: true,    // Mostrar botón "Siguiente" para navegar a la página siguiente (si se muestra un PDF)
            rotateLeft: true,  // Mostrar botón de rotación a la izquierda
            rotateRight: true, // Mostrar botón de rotación a la derecha
         },
      });

      // Manejar el clic en el botón para abrir el visor
      document.getElementById("open-document-button").addEventListener("click", function () {
         viewer.show("../uploaded_files/<?= $recursos; ?>"); // Ruta del documento a mostrar
      });
   });
</script>

</head>
<body>
   <?php include '../components/admin_header.php'; ?>
   <section class="view-content">
   <div class="container">
   <h1 class="heading" style="text-align: center;"><?= $titulo; ?></h1>

      <div class="leccion">
         
         
         <!-- Agregar el formulario y el botón para actualizar la lección -->
         <section class="form-container" style="min-height: calc(100vh - 19rem);">
         <form action="" method="post">
            <h3 class="heading">Actualizar Lección:</h3>
            <p>Nuevo titulo: <span>*</span></p>
            <input type="text" name="nuevo_titulo" placeholder="" maxlength="100" class="box" required>
            <p>Nuevas instrucciones: <span>*</span></p>
            <input type="text" id="nuevas_instrucciones" name="nuevas_instrucciones" placeholder="" maxlength="100" class="box" required>
            <p>Subir archivo <span>*</span></p>
        <input type="file" name="nuevos_recursos" id="nuevos_recuros" required class="box">
            <input type="submit" value="Actualizar Lección" class="btn btn-green" name="update_leccion">
         </form>
         
         <!-- Agregar el botón para eliminar la lección -->
        
      </section>
                </div>
      </div>
   </section>
   <?php include '../components/footer.php'; ?>
   <!-- Agregar cualquier script necesario -->
   <script src="../js/admin_script.js"></script>
</body>
</html>
