<?php
session_start();

include("php/config.php");
if (!isset($_SESSION['valid'])) {
    header("Location: index.php");
}

// Verificar si el formulario de consulta ha sido enviado
if (isset($_POST['submit_consulta'])) {
    // Obtener el ID del usuario actual
    $user_id = $_SESSION['id'];

    // Obtener los datos del formulario
    $ciudad = isset($_POST['ciudad']) ? mysqli_real_escape_string($con, $_POST['ciudad']) : '';
    $fecha_consulta = isset($_POST['fecha_consulta']) ? mysqli_real_escape_string($con, $_POST['fecha_consulta']) : '';
    $hora_consulta = isset($_POST['hora_consulta']) ? mysqli_real_escape_string($con, $_POST['hora_consulta']) : '';
    $doctor_id = isset($_POST['doctor_id']) ? mysqli_real_escape_string($con, $_POST['doctor_id']) : '';
    $nombre_completo = isset($_POST['nombre_completo']) ? mysqli_real_escape_string($con, $_POST['nombre_completo']) : '';
    $correo = isset($_POST['correo']) ? mysqli_real_escape_string($con, $_POST['correo']) : '';
    $telefono = isset($_POST['telefono']) ? mysqli_real_escape_string($con, $_POST['telefono']) : '';
    $motivo_consulta = isset($_POST['motivo_consulta']) ? mysqli_real_escape_string($con, $_POST['motivo_consulta']) : '';

    // Verificar disponibilidad del doctor en la fecha y hora seleccionadas
    $disponibilidad_query = "SELECT * FROM disponibilidad_doctores WHERE doctor_id='$doctor_id' AND fecha='$fecha_consulta' AND ('$hora_consulta' BETWEEN hora_inicio AND hora_fin)";
    $disponibilidad_result = mysqli_query($con, $disponibilidad_query);

    if (mysqli_num_rows($disponibilidad_result) > 0) {
        // El doctor está disponible, guardar la consulta
        $insert_query = "INSERT INTO consultas_medicas (user_id, ciudad, fecha, hora, doctor_id, nombre_completo, correo, telefono, motivo_consulta) 
                         VALUES ('$user_id', '$ciudad', '$fecha_consulta', '$hora_consulta', '$doctor_id', '$nombre_completo', '$correo', '$telefono', '$motivo_consulta')";
        mysqli_query($con, $insert_query);

        // Mensaje de éxito
        $success_message = "Consulta médica enviada exitosamente. El doctor te contactará pronto.";
    } else {
        // El doctor no está disponible en la fecha y hora seleccionadas
        $error_message = "El doctor no está disponible en la fecha y hora seleccionadas. Por favor, elige otro horario.";
    }
}
// Consulta SQL para obtener la disponibilidad de los doctores
$disponibilidad_query = mysqli_query($con, "SELECT * FROM disponibilidad_doctores");
$disponibilidad_doctores = array();

while ($row = mysqli_fetch_assoc($disponibilidad_query)) {
    $disponibilidad_doctores[] = $row;
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <style>
        body {
            background: url('consultorio1.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }

        .full-page {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        .box {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            max-width: 800px;
            width: 100%;
            box-sizing: border-box;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        .formulario {
            margin-top: 20px;
        }

        .contact-section {
            text-align: center;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .social-links {
            list-style: none;
            padding: 0;
            margin: 10px 0;
        }

        .social-links li {
            display: inline-block;
            margin: 0 10px;
        }

        .social-links a {
            text-decoration: none;
            color: #333;
            font-size: 18px;
            transition: color 0.3s;
        }

        .social-links a:hover {
            color: #007bff;
        }
    </style>
</head>

<body>

    <!-- Formulario al principio -->
    <div class="full-page formulario">
        <div class="box">
            <!-- Coloca aquí el código de tu formulario de disponibilidad_doctores -->
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="full-page">
        <div class="box">
            <h2>Disponibilidad de Doctores</h2>
            <button onclick="window.location.href='home.php'">Regresar a Home</button>

            <?php
            // Verifica si hay disponibilidad de doctores
            if (!empty($disponibilidad_doctores)) {
                echo "<table border='1'>
                        <tr>
                            <th>Doctor</th>
                            <th>Fecha</th>
                            <th>Hora de Inicio</th>
                            <th>Hora Fin</th>
                        </tr>";

                // Imprime cada fila de disponibilidad
                foreach ($disponibilidad_doctores as $disponibilidad) {
                    echo "<tr>
                            <td>{$disponibilidad['doctor_id']}</td>
                            <td>{$disponibilidad['fecha']}</td>
                            <td>{$disponibilidad['hora_inicio']}</td>
                            <td>{$disponibilidad['hora_fin']}</td>
                        </tr>";
                }

                echo "</table>";
            } else {
                echo "<p>No hay horarios de disponibilidad de doctores disponibles actualmente.</p>";
            }
            ?>
        </div>
    </div>
    <div class="full-page contact-section">
        <div class="box">
            <h2>Contactános</h2>
            <p>¡Estamos aquí para ayudarte! Si tienes alguna pregunta o comentario, no dudes en ponerte en contacto con nosotros.</p>

            <!-- Redes Sociales -->
            <ul class="social-links">
                <li><a href="https://www.instagram.com/tu_instagram" target="_blank">Instagram</a></li>
                <li><a href="https://www.facebook.com/tu_facebook" target="_blank">Facebook</a></li>
                <!-- Puedes agregar más enlaces según tus necesidades -->
            </ul>
        </div>
    </div>


    <!-- Formulario al final -->
    <div class="full-page formulario">
        <div class="box">
            <!-- Coloca aquí el código de tu formulario de disponibilidad_doctores -->
        </div>
    </div>

</body>

</html>
