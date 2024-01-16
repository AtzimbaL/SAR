<?php
include '../components/connect.php';

if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = $_COOKIE['tutor_id'];
} else {
    $tutor_id = '';
    header('location:login.php');
}

if (isset($_POST['submit'])) {
    $name = $_POST['name']; // Cambiamos 'email' a 'name' para buscar por nombre
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    $curso = $_POST['curso'];
    $curso = filter_var($curso, FILTER_SANITIZE_STRING);

    if (!empty($name) && !empty($curso)) {
        // Verificar si el nombre del usuario existe en la tabla "users"
        $select_user = $conn->prepare("SELECT id FROM `users` WHERE name = ? LIMIT 1"); // Cambiamos 'email' a 'name'
        $select_user->execute([$name]);
        $user_row = $select_user->fetch(PDO::FETCH_ASSOC);

        if ($select_user->rowCount() > 0) {
            $user_id_to_enroll = $user_row['id'];

            // Insertar datos en la tabla "detalles_enrolamiento"
            $insert_enrollment = $conn->prepare("INSERT INTO `enrolar` (user_id, content_id) VALUES (?, ?)");
            $insert_enrollment->execute([$user_id_to_enroll, $curso]);

            $message[] = 'El usuario se ha enrolado exitosamente en el curso!';
        } else {
            $message[] = 'El nombre del usuario no existe en la base de datos.';
        }
    } else {
        $message[] = 'Por favor, complete todos los campos.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    
<meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Playlist</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="form-container" style="min-height: calc(100vh - 19rem);">
    <form action="" method="post">
        <h3>Enrolar Alumnos</h3>
        <div class="flex">
            <div class="col">
                <p>Nombre del usuario</p> <!-- Cambiamos el texto del campo -->
                <input type="text" name="name" id="name" placeholder="Nombre del usuario" maxlength="100" class="box" required>

<!-- Agregar un elemento HTML para mostrar los resultados -->
<div class="autocomplete">
    <input type="text" id="user-results" placeholder="">
    <div id="user-results-list"></div>
</div>

                <p>Nombre del curso</p>
                <select name="curso" class="box" required>
                    <option value="" selected disabled>-- Elegir curso --</option>
                    <option value="ISO">ISO</option>
                    <option value="Scrum">Scrum</option>
                </select>
            </div>
        </div>
        <input type="submit" name="submit" value="Enrolar" class="btn">
    </form>
</section>

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const nameInput = document.getElementById("name");
    const userResults = document.getElementById("user-results-list");

    nameInput.addEventListener("input", function () {
        const searchTerm = nameInput.value.trim();

        // Realizar una solicitud AJAX para buscar usuarios
        if (searchTerm.length > 0) {
            fetch(`search_users.php?search=${searchTerm}`)
                .then(response => response.json())
                .then(data => {
                    // Construir la lista de resultados
                    const resultList = data.map(user => `<div class="user-result style="font-size: 16px;">${user.name}</div>`).join('');
                    userResults.innerHTML = resultList;

                    // Agregar un evento de clic a los elementos de la lista
                    const resultItems = document.querySelectorAll(".user-result");
                    resultItems.forEach(item => {
                        item.addEventListener("click", function () {
                            // Llenar el campo de entrada con el nombre seleccionado
                            nameInput.value = item.textContent;
                            userResults.innerHTML = ''; // Ocultar la lista de resultados
                        });
                    });
                })
                .catch(error => {
                    console.error('Error en la solicitud AJAX:', error);
                });
        } else {
            userResults.innerHTML = ''; // Limpiar resultados si el campo está vacío
        }
    });
});
</script>

</body>
</html>
