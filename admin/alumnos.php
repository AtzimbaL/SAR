<?php
include '../components/connect.php';

if (isset($_COOKIE['tutor_id'])) {
   $tutor_id = $_COOKIE['tutor_id'];
} else {
   $tutor_id = '';
   // Puedes redirigir aquí si no se establece $tutor_id
   header('location:login.php');
}

// Realiza la consulta para obtener los nombres y correos de todos los usuarios registrados en la tabla "users" en la base de datos "course_db"
$select_users = $conn->prepare("SELECT id, name, email FROM `users`");
$select_users->execute();
$users_data = $select_users->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="../css/admin_style.css">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <style>
      /* Custom styles for table and search */
      .user-table {
         width: 100%;
         border-collapse: collapse;
         font-size: 16px;
         margin-top: 20px;
      }

      .user-table th, .user-table td {
         padding: 15px;
         border: 1px solid #ddd;
         text-align: left;
      }

      .user-table th {
         background-color: #007BFF;
         color: #fff;
      }

      .user-table tbody tr:nth-child(even) {
         background-color: #f2f2f2;
      }

      .search-container {
         margin-top: 20px;
         display: flex;
         align-items: center;
      }

      #searchInput {
         padding: 10px;
         border: 1px solid #ccc;
         border-radius: 4px;
         flex: 1;
         margin-right: 10px;
         font-size: 14px;
      }

      #searchButton {
         padding: 10px 20px;
         background-color: #007BFF;
         color: #fff;
         border: none;
         border-radius: 4px;
         cursor: pointer;
         font-size: 16px;
      }
   </style>
</head>

<body>
   <?php include '../components/admin_header.php'; ?> 
   <section class="contents">
      <!-- Campo de búsqueda -->
      <div class="search-container">
         <input type="text" id="searchInput" placeholder="Buscar por nombre o correo electrónico">
         <button id="searchButton">Buscar</button>
      </div>

      <!-- Mostrar los datos de todos los usuarios en una tabla -->
      <table class="user-table" id="userTable">
         <thead>
            <tr>
               <th>Nombre</th>
               <th>Correo electrónico</th>
               <th>Acciones</th>
            </tr>
         </thead>
         <tbody>
            <?php foreach ($users_data as $user) : ?>
               <tr>
                  <td><?= $user['name']; ?></td>
                  <td><?= $user['email']; ?></td>
                  <td>
   <a href="eliminar_usuario.php?id=<?= $user['id']; ?>" class="delete-button"><i class="fas fa-trash-alt"></i></a>
   <a href="editar_usuario.php?id=<?= $user['id']; ?>" class="edit-button"><i class="fas fa-edit"></i></a>
</td>
               </tr>
            <?php endforeach; ?>
         </tbody>
      </table>

      <script>
         document.getElementById("searchButton").addEventListener("click", function() {
            searchTable();
         });

         function searchTable() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("userTable");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
               td = tr[i].getElementsByTagName("td");
               for (j = 0; j < td.length; j++) {
                  txtValue = td[j].textContent || td[j].innerText;
                  if (txtValue.toUpperCase().indexOf(filter) > -1) {
                     tr[i].style.display = "";
                     break;
                  } else {
                     tr[i].style.display = "none";
                  }
               }
            }
         }

         function deleteUser(userId) {
            if (confirm("¿Estás seguro de que deseas eliminar a este usuario?")) {
               // Realiza la eliminación del usuario utilizando AJAX u otro método de tu elección
               // Luego, puedes recargar la página o actualizar la tabla para reflejar los cambios
               alert("Usuario eliminado con éxito");
            }
         }

         function editUser(userId) {
            // Aquí puedes implementar la lógica para editar al usuario
            // Redirigir a una página de edición o abrir un modal de edición, por ejemplo
            alert("Editar usuario con ID: " + userId);
         }
      </script>
   </section> <!-- Cierre de la etiqueta <section> de contents -->
   
   <?php include '../components/footer.php'; ?>

   <script src="../js/admin_script.js"></script>
</body>
</html>

