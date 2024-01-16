<?php
// Configura la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sarsolu1_clientes";

$conn = new mysqli($servername, $username, $password,$dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Llamada a la API de Moodle para obtener la información de los cursos
$url = 'https://capestudiantil.com/cursos/webservice/rest/server.php';
$params = array(
    'wstoken' => 'cced63b078f5e2e5fab3401710055bde',
    'wsfunction' => 'core_course_get_courses',
    'moodlewsrestformat' => 'json'
);

$response = file_get_contents($url . '?' . http_build_query($params));
$courses = json_decode($response, true);
if (!empty($courses)) {
    foreach ($courses as $course) {
        $fullname = $conn->real_escape_string($course['fullname']);
        $summary = $conn->real_escape_string($course['summary']);

        // Preparar la consulta SQL para insertar
        $sql = "INSERT INTO cursos (fullname, summary) VALUES ('$fullname', '$summary')";

        if ($conn->query($sql) === true) {
            echo "Curso almacenado correctamente: " . $fullname . "<br>";
        } else {
            echo "Error al almacenar el curso: " . $conn->error . "<br>";
        }
    }
} else {
    echo "No se encontraron cursos.";
}

// Cerrar la conexión
$conn->close();
?>
