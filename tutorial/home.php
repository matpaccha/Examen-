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
    <title>Home</title>
</head>

<body>

    <style>
        body {
            background: url('consultorio1.jpg') no-repeat center center fixed;
            background-size: cover;
        }
    </style>

    <div class="nav">
        <div class="logo">
            <p><a href="home.php">Cormedent / Consulta</a></p>
        </div>

    
        <div class="right-links">
            <?php
            $id = $_SESSION['id'];
            $query = mysqli_query($con, "SELECT * FROM users WHERE Id=$id");

            while ($result = mysqli_fetch_assoc($query)) {
                $res_Uname = $result['Username'];
                $res_Email = $result['Email'];
                $res_Age = $result['Age'];
                $res_id = $result['Id'];
            }

            echo "<a href='edit.php?Id=$res_id'>Cambiar Perfil</a>";
            ?>
            <a href="php/logout.php"> <button class="btn">Cerrar Sesión</button> </a>
        </div>
    </div>

    <main>
        <div class="main-box top">
            <div class="top">
                <div class="box">
                    <p>Hola <b>
                            <?php echo $res_Uname ?>
                        </b>, Bienvenido</p>
                        <button onclick="window.location.href='disponibilidad_doctores.php'">Ver Disponibilidad de Doctores</button>
                </div>
            </div>
        </div>

        

        
        
    </main>

</body>

<header>
<div class="side-content">
            <div class="box">
                <!-- Formulario para ingresar la fecha de consulta -->
                <form action="" method="post">
                    <label for="ciudad">Ciudad:</label>
                    <select name="ciudad" required>
                        <!-- Opciones para seleccionar una ciudad -->
                        <option value="Quito">Quito</option>
                        <option value="Guayaquil">Guayaquil</option>
                        <!-- Agrega más opciones según sea necesario -->
                    </select>

                    <label for="fecha_consulta">Fecha de Consulta:</label>
                    <input type="date" name="fecha_consulta" required>

                    <label for="hora_consulta">Hora de Consulta:</label>
                    <input type="time" name="hora_consulta" required>
                    <!-- Aquí se agrega un mensaje de error si existe -->
                <?php if (isset($error_message)) { ?>
                    <p class="error"><?php echo $error_message; ?></p>
                <?php } ?>
                <!-- Aquí se agrega un mensaje de éxito si existe -->
                <?php if (isset($success_message)) { ?>
                    <p class="success"><?php echo $success_message; ?></p>
                <?php } ?>

                   
            <?php
            // Mostrar mensajes de éxito o error
            if (isset($success_message)) {
                echo "<div class='box'>
            <p class='success'>$success_message</p>
            </div>";
            } elseif (isset($error_message)) {
                echo "<div class='box'>
             <p class='error'>$error_message</p>
            </div>";
            }
            ?>
        </div>


</header>

<header>
<div class="main-box top">
            <div class="box">


         <!-- Agregamos los campos del formulario existente -->
         <label for="doctor_id">Doctor:</label>
                    <select name="doctor_id" required>
                        <!-- Opciones para seleccionar un doctor -->
                        <?php
                        $doctores_query = mysqli_query($con, "SELECT id, nombre FROM doctores");
                        while ($doctor = mysqli_fetch_assoc($doctores_query)) {
                            echo "<option value='" . $doctor['id'] . "'>" . $doctor['nombre'] . "</option>";
                        }
                        ?>
                    </select>

                    <label for="nombre_completo">Nombre del Paciente:</label>
                    <input type="text" name="nombre_completo" required>

                    <label for="correo">Correo Electrónico Del Paciente:</label>
                    <input type="email" name="correo" required>

                    <label for="telefono">Teléfono de Contacto:</label>
                    <input type="tel" name="telefono" required>

                    <label for="motivo_consulta">Motivo de Consulta:</label>
                    <textarea name="motivo_consulta" rows="4" required></textarea>

                    <!-- Botón para enviar el formulario -->
                    <input type="submit" class="btn" name="submit_consulta" value="Guardar Consulta">
                </form>
            </div>
        </div>

    
    
</header>

</html>