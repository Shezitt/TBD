-- Nivel

INSERT INTO Nivel (nivel, nombre, puntosTotalesNecesarios) 
VALUES (1, 'Principiante', 0);

-- Rol

INSERT INTO Rol (nombreRol) 
VALUES ('usuario');

INSERT INTO Rol (nombreRol)
VALUES ('administrador');


-- Usuario

INSERT INTO Usuario (username, nombre, correo, password, puntos, puntosTotal, fechaRegistro, idRol, idNivel)
VALUES ('shezitt', 'Shamir Terán', 'shamirteranmustafa@gmail.com', 'contra', 0.00, 0.00, '2025-04-20', '2','1');


-- Material

INSERT INTO Material (nombre, coeficientePuntos, coeficienteCO2)
VALUES ('Papel y cartón', 1.0, 0.82);

INSERT INTO Material (nombre, coeficientePuntos, coeficienteCO2)
VALUES ('Plásticos', 1.0, 1.5);

INSERT INTO Material (nombre, coeficientePuntos, coeficienteCO2)
VALUES ('Vidrio', 1.0, 0.32);

INSERT INTO Material (nombre, coeficientePuntos, coeficienteCO2)
VALUES ('Metales', 1.0, 2.0);

INSERT INTO Material (nombre, coeficientePuntos, coeficienteCO2)
VALUES ('Textiles', 1.0, 6.0);



-- Punto reciclaje

INSERT INTO Punto_Reciclaje (nombre, coordenadas, apertura, cierre) 
VALUES ('Punto 1', POINT(10.1234, 20.5678), '08:00:00', '22:00:00');

INSERT INTO Punto_Reciclaje (nombre, coordenadas, apertura, cierre) 
VALUES ('Punto 2', POINT(30.1234, 40.5678), '08:00:00', '22:00:00');


-- Punto_Reciclaje_Materiales

INSERT INTO Punto_Reciclaje_Materiales (idPunto, idMaterial)
VALUES (1, 1);
INSERT INTO Punto_Reciclaje_Materiales (idPunto, idMaterial)
VALUES (1, 2);
INSERT INTO Punto_Reciclaje_Materiales (idPunto, idMaterial)
VALUES (1, 3);

INSERT INTO Punto_Reciclaje_Materiales (idPunto, idMaterial)
VALUES (2, 4);
INSERT INTO Punto_Reciclaje_Materiales (idPunto, idMaterial)
VALUES (2, 5);


-- Catalogo

INSERT INTO Catalogo (nombreCatalogo)
VALUES ('Tarjetas de regalo');

INSERT INTO Catalogo (nombreCatalogo)
VALUES ('Gadgets');

INSERT INTO Catalogo (nombreCatalogo)
VALUES ('Cupones de descuento');

-- Recompensas

INSERT INTO Recompensa (nombre, puntosNecesarios, nivelRequerido, idCatalogo)
VALUES ('Tarjeta Steam', 100.0, 1, 1);

INSERT INTO Recompensa (nombre, puntosNecesarios, nivelRequerido, idCatalogo)
VALUES ('Tarjeta Amazon', 100.0, 1, 1);

INSERT INTO Recompensa (nombre, puntosNecesarios, nivelRequerido, idCatalogo)
VALUES ('Tarjeta Spotify', 200.0, 2, 1);

INSERT INTO Recompensa (nombre, puntosNecesarios, nivelRequerido, idCatalogo)
VALUES ('Power Bank', 100.0, 1, 2);

INSERT INTO Recompensa (nombre, puntosNecesarios, nivelRequerido, idCatalogo)
VALUES ('Auriculares Bluetooth', 100.0, 2, 2);

INSERT INTO Recompensa (nombre, puntosNecesarios, nivelRequerido, idCatalogo)
VALUES ('Mouse gamer', 500.0, 3, 2);

INSERT INTO Recompensa (nombre, puntosNecesarios, nivelRequerido, idCatalogo)
VALUES ('2x1 bebidas', 50.0, 1, 3);