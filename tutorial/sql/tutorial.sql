CREATE TABLE users (
    Id INT PRIMARY KEY AUTO_INCREMENT,
    Username VARCHAR(200),
    Email VARCHAR(200),
    Age INT,
    Password VARCHAR(32),
    fecha_consulta DATE,
    fecha_cita DATE
    ciudad VARCHAR(100)
);


CREATE TABLE doctores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100),
    especialidad VARCHAR(100)
);

CREATE TABLE consultas_medicas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    ciudad VARCHAR(100),
    fecha DATE,
    hora TIME,
    doctor_id INT,
    FOREIGN KEY (user_id) REFERENCES users(Id),
    FOREIGN KEY (doctor_id) REFERENCES doctores(id)
    nombre_completo VARCHAR(200),
     correo VARCHAR(200),
    telefono VARCHAR(20),
    motivo_consulta TEXT,

);