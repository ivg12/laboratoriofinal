<?php

    $nombre = $_POST["NOMBRE"]; 
    $apellido1 = $_POST["PRIMER_APELLIDO"];
    $apellido2 = $_POST["SEGUNDO_APELLIDO"];
    $email = $_POST["EMAIL"];
    $login = $_POST["LOGIN"];
    $userpassword = $_POST["PASSWORD"]; //para evitar repetir $password, usado con otros fines

    // Validación adicional en PHP
    if (empty($nombre) || empty($apellido1) || empty($apellido2) || empty($email) || empty($login) || empty($userpassword)) {
        die("Por favor, completa todos los campos.");
    }

    // Validación de formato de correo electrónico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("El formato del correo electrónico no es válido.");        
    }

    // Validación de longitud de contraseña
    if (strlen($userpassword) < 4 || strlen($userpassword) > 8) {
        die("La contraseña debe tener entre 4 y 8 caracteres.");
    }
    
    // Conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "practicafinalfullstack";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

   // Verificar si el correo electrónico ya existe en la base de datos
   $sql = "SELECT ID FROM registros_formulario WHERE EMAIL = '$email'";
   $result = $conn->query($sql);
   if ($result->num_rows > 0) {
       die("El correo electrónico ya está registrado. Por favor, utiliza otro correo electrónico.");
   }

    // Insertar los datos en la base de datos
    $sql = "INSERT INTO registros_formulario (NOMBRE, PRIMER_APELLIDO, SEGUNDO_APELLIDO, EMAIL, LOGIN, PASSWORD) VALUES ('$nombre', '$apellido1', '$apellido2', '$email', '$login', '$userpassword')";
    if ($conn->query($sql) === TRUE) {
        echo "Registro completado con éxito";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    

    $sql = "SELECT * FROM registros_formulario";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Usuarios registrados</h2>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . $row["NOMBRE"] . " " . $row["PRIMER_APELLIDO"] . " " . $row["SEGUNDO_APELLIDO"] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "No hay usuarios registrados.";
    }

    $conn->close();
?>