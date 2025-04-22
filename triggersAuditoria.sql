-- Material

CREATE TABLE IF NOT EXISTS Auditoria_Material (
  `idAuditoria` INT NOT NULL AUTO_INCREMENT,
  `fecha` DATETIME NOT NULL,
  `executedSQL` TEXT NOT NULL,
  `reverseSQL` TEXT NOT NULL,
  PRIMARY KEY (`idAuditoria`)
) ENGINE = InnoDB;

DELIMITER $$

CREATE TRIGGER IF NOT EXISTS auditoria_insert_material
AFTER INSERT ON Material
FOR EACH ROW
BEGIN
  INSERT INTO Auditoria_Material
  (`fecha`, `executedSQL`, `reverseSQL`)
  VALUES
  (NOW(),
   CONCAT('INSERT INTO Material (idMaterial, nombre, coeficientePuntos, coeficienteCO2, activo) VALUES (', 
          NEW.idMaterial, ', "', NEW.nombre, '", ', NEW.coeficientePuntos, ', ', NEW.coeficienteCO2, ', ', NEW.activo, ')'),
   CONCAT('DELETE FROM Material WHERE idMaterial = ', NEW.idMaterial));
END $$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER IF NOT EXISTS auditoria_update_material
AFTER UPDATE ON Material
FOR EACH ROW
BEGIN
  INSERT INTO Auditoria_Material
  (`fecha`, `executedSQL`, `reverseSQL`)
  VALUES
  (NOW(),
   CONCAT('UPDATE Material SET nombre = "', NEW.nombre, '", coeficientePuntos = ', NEW.coeficientePuntos, 
          ', coeficienteCO2 = ', NEW.coeficienteCO2, ', activo = ', NEW.activo, ' WHERE idMaterial = ', NEW.idMaterial),
   CONCAT('UPDATE Material SET nombre = "', OLD.nombre, '", coeficientePuntos = ', OLD.coeficientePuntos, 
          ', coeficienteCO2 = ', OLD.coeficienteCO2, ', activo = ', OLD.activo, ' WHERE idMaterial = ', OLD.idMaterial));
END $$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER IF NOT EXISTS auditoria_material_delete
AFTER DELETE ON Material
FOR EACH ROW
BEGIN
  INSERT INTO Auditoria_Material
  (`fecha`, `executedSQL`, `reverseSQL`)
  VALUES
  (NOW(),
   CONCAT('DELETE FROM Material WHERE idMaterial = ', OLD.idMaterial),
   CONCAT('INSERT INTO Material (idMaterial, nombre, coeficientePuntos, coeficienteCO2, activo) VALUES (', 
          OLD.idMaterial, ', "', OLD.nombre, '", ', OLD.coeficientePuntos, ', ', OLD.coeficienteCO2, ', ', OLD.activo, ')'));
END $$

DELIMITER ;

-- Punto_Reciclaje

CREATE TABLE IF NOT EXISTS Auditoria_Punto_Reciclaje (
  `idAuditoria` INT NOT NULL AUTO_INCREMENT,
  `fecha` DATETIME NOT NULL,
  `executedSQL` TEXT NOT NULL,
  `reverseSQL` TEXT NOT NULL,
  PRIMARY KEY (`idAuditoria`)
) ENGINE = InnoDB;

DELIMITER $$

DELIMITER $$

CREATE TRIGGER IF NOT EXISTS auditoria_punto_reciclaje_insert
AFTER INSERT ON Punto_Reciclaje
FOR EACH ROW
BEGIN
  INSERT INTO Auditoria_Punto_Reciclaje (fecha, executedSQL, reverseSQL)
  VALUES (
    NOW(),
    CONCAT('INSERT INTO Punto_Reciclaje (nombre, latitud, longitud, apertura, cierre, activo) VALUES (''', NEW.nombre, ''', ', NEW.latitud, ', ', NEW.longitud, ', ''', NEW.apertura, ''', ''', NEW.cierre, ''', ', NEW.activo, ')'),
    CONCAT('DELETE FROM Punto_Reciclaje WHERE idPunto = ', NEW.idPunto)
  );
END$$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER IF NOT EXISTS auditoria_punto_reciclaje_update
AFTER UPDATE ON Punto_Reciclaje
FOR EACH ROW
BEGIN
  INSERT INTO Auditoria_Punto_Reciclaje (fecha, executedSQL, reverseSQL)
  VALUES (
    NOW(),
    CONCAT('UPDATE Punto_Reciclaje SET nombre = ''', NEW.nombre, ''', latitud = ', NEW.latitud, ', longitud = ', NEW.longitud, ', apertura = ''', NEW.apertura, ''', cierre = ''', NEW.cierre, ''', activo = ', NEW.activo, ' WHERE idPunto = ', NEW.idPunto),
    CONCAT('UPDATE Punto_Reciclaje SET nombre = ''', OLD.nombre, ''', latitud = ', OLD.latitud, ', longitud = ', OLD.longitud, ', apertura = ''', OLD.apertura, ''', cierre = ''', OLD.cierre, ''', activo = ', OLD.activo, ' WHERE idPunto = ', OLD.idPunto)
  );
END$$

DELIMITER ;


DELIMITER $$

CREATE TRIGGER IF NOT EXISTS auditoria_punto_reciclaje_delete
AFTER DELETE ON Punto_Reciclaje
FOR EACH ROW
BEGIN
  INSERT INTO Auditoria_Punto_Reciclaje (fecha, executedSQL, reverseSQL)
  VALUES (
    NOW(),
    CONCAT('DELETE FROM Punto_Reciclaje WHERE idPunto = ', OLD.idPunto),
    CONCAT('INSERT INTO Punto_Reciclaje (nombre, latitud, longitud, apertura, cierre, activo) VALUES (''', OLD.nombre, ''', ', OLD.latitud, ', ', OLD.longitud, ', ''', OLD.apertura, ''', ''', OLD.cierre, ''', ', OLD.activo, ')')
  );
END$$

DELIMITER ;

-- Punto_Reciclaje_Materiales

CREATE TABLE IF NOT EXISTS Auditoria_Punto_Reciclaje_Materiales (
  `idAuditoria` INT NOT NULL AUTO_INCREMENT,
  `fecha` DATETIME NOT NULL,
  `executedSQL` TEXT NOT NULL,
  `reverseSQL` TEXT NOT NULL,
  PRIMARY KEY (`idAuditoria`)
) ENGINE = InnoDB;

DELIMITER ;

DELIMITER $$

CREATE TRIGGER IF NOT EXISTS auditoria_punto_reciclaje_materiales_insert
AFTER INSERT ON Punto_Reciclaje_Materiales
FOR EACH ROW
BEGIN
    INSERT INTO Auditoria_Punto_Reciclaje_Materiales (fecha, executedSQL, reverseSQL)
    VALUES (NOW(),
            CONCAT('INSERT INTO Punto_Reciclaje_Materiales (idPunto, idMaterial) VALUES (', NEW.idPunto, ', ', NEW.idMaterial, ')'),
            CONCAT('DELETE FROM Punto_Reciclaje_Materiales WHERE idPunto = ', NEW.idPunto, ' AND idMaterial = ', NEW.idMaterial)
           );
END $$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER IF NOT EXISTS auditoria_punto_reciclaje_materiales_update
AFTER UPDATE ON Punto_Reciclaje_Materiales
FOR EACH ROW
BEGIN
    INSERT INTO Auditoria_Punto_Reciclaje_Materiales (fecha, executedSQL, reverseSQL)
    VALUES (NOW(),
            CONCAT('UPDATE Punto_Reciclaje_Materiales SET idPunto = ', NEW.idPunto, ', idMaterial = ', NEW.idMaterial, 
                   ' WHERE idPunto = ', OLD.idPunto, ' AND idMaterial = ', OLD.idMaterial),
            CONCAT('UPDATE Punto_Reciclaje_Materiales SET idPunto = ', OLD.idPunto, ', idMaterial = ', OLD.idMaterial, 
                   ' WHERE idPunto = ', NEW.idPunto, ' AND idMaterial = ', NEW.idMaterial)
           );
END $$

DELIMITER ;


DELIMITER $$

CREATE TRIGGER IF NOT EXISTS auditoria_punto_reciclaje_materiales_delete
AFTER DELETE ON Punto_Reciclaje_Materiales
FOR EACH ROW
BEGIN
    INSERT INTO Auditoria_Punto_Reciclaje_Materiales (fecha, executedSQL, reverseSQL)
    VALUES (NOW(),
            CONCAT('DELETE FROM Punto_Reciclaje_Materiales WHERE idPunto = ', OLD.idPunto, ' AND idMaterial = ', OLD.idMaterial),
            CONCAT('INSERT INTO Punto_Reciclaje_Materiales (idPunto, idMaterial) VALUES (', OLD.idPunto, ', ', OLD.idMaterial, ')')
           );
END $$

DELIMITER ;



-- Catalogo


CREATE TABLE IF NOT EXISTS Auditoria_Catalogo (
  `idAuditoria` INT NOT NULL AUTO_INCREMENT,
  `fecha` DATETIME NOT NULL,
  `executedSQL` TEXT NOT NULL,
  `reverseSQL` TEXT NOT NULL,
  PRIMARY KEY (`idAuditoria`)
) ENGINE = InnoDB;

DELIMITER $$

DELIMITER $$

CREATE TRIGGER IF NOT EXISTS auditoria_catalogo_insert
AFTER INSERT ON Catalogo
FOR EACH ROW
BEGIN
  INSERT INTO Auditoria_Catalogo (fecha, executedSQL, reverseSQL)
  VALUES (NOW(), 
          CONCAT('INSERT INTO Catalogo (nombreCatalogo, activo) VALUES (''', NEW.nombreCatalogo, ''', ', NEW.activo, ');'), 
          CONCAT('DELETE FROM Catalogo WHERE idCatalogo = ', NEW.idCatalogo, ';'));
END;

$$
DELIMITER ;


DELIMITER $$

CREATE TRIGGER IF NOT EXISTS auditoria_catalogo_update
AFTER UPDATE ON Catalogo
FOR EACH ROW
BEGIN
  INSERT INTO Auditoria_Catalogo (fecha, executedSQL, reverseSQL)
  VALUES (NOW(), 
          CONCAT('UPDATE Catalogo SET nombreCatalogo = ''', NEW.nombreCatalogo, ''', activo = ', NEW.activo, ' WHERE idCatalogo = ', NEW.idCatalogo, ';'), 
          CONCAT('UPDATE Catalogo SET nombreCatalogo = ''', OLD.nombreCatalogo, ''', activo = ', OLD.activo, ' WHERE idCatalogo = ', OLD.idCatalogo, ';'));
END;

$$
DELIMITER ;


DELIMITER $$

CREATE TRIGGER IF NOT EXISTS auditoria_catalogo_delete
AFTER DELETE ON Catalogo
FOR EACH ROW
BEGIN
  INSERT INTO Auditoria_Catalogo (fecha, executedSQL, reverseSQL)
  VALUES (NOW(), 
          CONCAT('DELETE FROM Catalogo WHERE idCatalogo = ', OLD.idCatalogo, ';'), 
          CONCAT('INSERT INTO Catalogo (nombreCatalogo, activo) VALUES (''', OLD.nombreCatalogo, ''', ', OLD.activo, ');'));
END;

$$
DELIMITER ;



-- Recompensa

CREATE TABLE IF NOT EXISTS Auditoria_Recompensa (
  `idAuditoria` INT NOT NULL AUTO_INCREMENT,
  `fecha` DATETIME NOT NULL,
  `executedSQL` TEXT NOT NULL,
  `reverseSQL` TEXT NOT NULL,
  PRIMARY KEY (`idAuditoria`)
) ENGINE = InnoDB;

DELIMITER $$
CREATE TRIGGER IF NOT EXISTS auditoria_recompensa_insert
AFTER INSERT ON Recompensa
FOR EACH ROW
BEGIN
    INSERT INTO Auditoria_Recompensa (fecha, executedSQL, reverseSQL)
    VALUES (
        NOW(),
        CONCAT('INSERT INTO Recompensa (nombre, puntosNecesarios, nivelRequerido, activo, idCatalogo) VALUES (',
               NEW.nombre, ', ', NEW.puntosNecesarios, ', ', NEW.nivelRequerido, ', ', NEW.activo, ', ', NEW.idCatalogo, ')'),
        CONCAT('DELETE FROM Recompensa WHERE idRecompensa = ', NEW.idRecompensa)
    );
END;
$$
DELIMITER ;


DELIMITER $$
CREATE TRIGGER IF NOT EXISTS auditoria_recompensa_update
AFTER UPDATE ON Recompensa
FOR EACH ROW
BEGIN
    INSERT INTO Auditoria_Recompensa (fecha, executedSQL, reverseSQL)
    VALUES (
        NOW(),
        CONCAT('UPDATE Recompensa SET nombre = "', NEW.nombre, '", puntosNecesarios = ', NEW.puntosNecesarios, 
               ', nivelRequerido = ', NEW.nivelRequerido, ', activo = ', NEW.activo, ', idCatalogo = ', NEW.idCatalogo, 
               ' WHERE idRecompensa = ', OLD.idRecompensa),
        CONCAT('UPDATE Recompensa SET nombre = "', OLD.nombre, '", puntosNecesarios = ', OLD.puntosNecesarios, 
               ', nivelRequerido = ', OLD.nivelRequerido, ', activo = ', OLD.activo, ', idCatalogo = ', OLD.idCatalogo, 
               ' WHERE idRecompensa = ', OLD.idRecompensa)
    );
END;
$$
DELIMITER ;


DELIMITER $$
CREATE TRIGGER IF NOT EXISTS auditoria_recompensa_delete
AFTER DELETE ON Recompensa
FOR EACH ROW
BEGIN
    INSERT INTO Auditoria_Recompensa (fecha, executedSQL, reverseSQL)
    VALUES (
        NOW(),
        CONCAT('DELETE FROM Recompensa WHERE idRecompensa = ', OLD.idRecompensa),
        CONCAT('INSERT INTO Recompensa (nombre, puntosNecesarios, nivelRequerido, activo, idCatalogo) VALUES (',
               OLD.nombre, ', ', OLD.puntosNecesarios, ', ', OLD.nivelRequerido, ', ', OLD.activo, ', ', OLD.idCatalogo, ')')
    );
END;
$$
DELIMITER ;


-- Nivel

CREATE TABLE IF NOT EXISTS Auditoria_Nivel (
  `idAuditoria` INT NOT NULL AUTO_INCREMENT,
  `fecha` DATETIME NOT NULL,
  `executedSQL` TEXT NOT NULL,
  `reverseSQL` TEXT NOT NULL,
  PRIMARY KEY (`idAuditoria`)
) ENGINE = InnoDB;


-- Trigger AFTER INSERT para la tabla Nivel
DELIMITER $$
CREATE TRIGGER IF NOT EXISTS auditoria_nivel_insert
AFTER INSERT ON Nivel
FOR EACH ROW
BEGIN
    INSERT INTO Auditoria_Nivel (fecha, executedSQL, reverseSQL)
    VALUES (NOW(), 
            CONCAT('INSERT INTO Nivel (nivel, nombre, puntosTotalesNecesarios) VALUES (', NEW.nivel, ', "', NEW.nombre, '", ', NEW.puntosTotalesNecesarios, ')'),
            CONCAT('DELETE FROM Nivel WHERE idNivel = ', NEW.idNivel));
END;
$$
DELIMITER ;

-- Trigger AFTER UPDATE para la tabla Nivel
DELIMITER $$
CREATE TRIGGER IF NOT EXISTS auditoria_nivel_update
AFTER UPDATE ON Nivel
FOR EACH ROW
BEGIN
    INSERT INTO Auditoria_Nivel (fecha, executedSQL, reverseSQL)
    VALUES (NOW(),
            CONCAT('UPDATE Nivel SET nivel = ', NEW.nivel, ', nombre = "', NEW.nombre, '", puntosTotalesNecesarios = ', NEW.puntosTotalesNecesarios, ' WHERE idNivel = ', OLD.idNivel),
            CONCAT('UPDATE Nivel SET nivel = ', OLD.nivel, ', nombre = "', OLD.nombre, '", puntosTotalesNecesarios = ', OLD.puntosTotalesNecesarios, ' WHERE idNivel = ', OLD.idNivel));
END;
$$
DELIMITER ;

-- Trigger AFTER DELETE para la tabla Nivel
DELIMITER $$
CREATE TRIGGER IF NOT EXISTS auditoria_nivel_delete
AFTER DELETE ON Nivel
FOR EACH ROW
BEGIN
    INSERT INTO Auditoria_Nivel (fecha, executedSQL, reverseSQL)
    VALUES (NOW(),
            CONCAT('DELETE FROM Nivel WHERE idNivel = ', OLD.idNivel),
            CONCAT('INSERT INTO Nivel (nivel, nombre, puntosTotalesNecesarios) VALUES (', OLD.nivel, ', "', OLD.nombre, '", ', OLD.puntosTotalesNecesarios, ')'));
END;
$$
DELIMITER ;



-- Canje (este no incluye insert)

CREATE TABLE IF NOT EXISTS Auditoria_Canje (
  `idAuditoria` INT NOT NULL AUTO_INCREMENT,
  `fecha` DATETIME NOT NULL,
  `executedSQL` TEXT NOT NULL,
  `reverseSQL` TEXT NOT NULL,
  PRIMARY KEY (`idAuditoria`)
) ENGINE = InnoDB;

DELIMITER $$

CREATE TRIGGER IF NOT EXISTS auditoria_canje_delete
AFTER DELETE ON Canje
FOR EACH ROW
BEGIN
    INSERT INTO Auditoria_Canje (fecha, executedSQL, reverseSQL)
    VALUES (NOW(),
    CONCAT('DELETE FROM Canje WHERE idCanje = ', OLD.idCanje, ';'),
    CONCAT('INSERT INTO Canje (idCanje, idUsuario, idRecompensa, fecha, completado) 
            VALUES (', OLD.idCanje, ', ', OLD.idUsuario, ', ', OLD.idRecompensa, ', "', OLD.fecha, '", ', OLD.completado, ');'));
END;
$$

DELIMITER ;


DELIMITER $$

CREATE TRIGGER IF NOT EXISTS auditoria_canje_update
AFTER UPDATE ON Canje
FOR EACH ROW
BEGIN
    INSERT INTO Auditoria_Canje (fecha, executedSQL, reverseSQL)
    VALUES (NOW(),
    CONCAT('UPDATE Canje SET idUsuario = ', NEW.idUsuario, 
           ', idRecompensa = ', NEW.idRecompensa, 
           ', fecha = "', NEW.fecha, 
           '", completado = ', NEW.completado, 
           ' WHERE idCanje = ', OLD.idCanje, ';'),
    CONCAT('UPDATE Canje SET idUsuario = ', OLD.idUsuario, 
           ', idRecompensa = ', OLD.idRecompensa, 
           ', fecha = "', OLD.fecha, 
           '", completado = ', OLD.completado, 
           ' WHERE idCanje = ', OLD.idCanje, ';'));
END;
$$

DELIMITER ;



-- Promocion

CREATE TABLE IF NOT EXISTS Auditoria_Promocion (
  `idAuditoria` INT NOT NULL AUTO_INCREMENT,
  `fecha` DATETIME NOT NULL,
  `executedSQL` TEXT NOT NULL,
  `reverseSQL` TEXT NOT NULL,
  PRIMARY KEY (`idAuditoria`)
) ENGINE = InnoDB;

DELIMITER $$

CREATE TRIGGER IF NOT EXISTS auditoria_promocion_insert
AFTER INSERT ON Promocion
FOR EACH ROW
BEGIN
    INSERT INTO Auditoria_Promocion (fecha, executedSQL, reverseSQL)
    VALUES (NOW(), 
            CONCAT('INSERT INTO Promocion (nombre, multiplicador, fechaInicio, fechaFin, activo, nivelRequerido) VALUES (', 
                    QUOTE(NEW.nombre), ', ', 
                    NEW.multiplicador, ', ', 
                    QUOTE(NEW.fechaInicio), ', ', 
                    QUOTE(NEW.fechaFin), ', ', 
                    NEW.activo, ', ', 
                    NEW.nivelRequerido, ')'), 
            CONCAT('DELETE FROM Promocion WHERE idPromocion = ', NEW.idPromocion));
END$$

DELIMITER ;


DELIMITER $$

CREATE TRIGGER IF NOT EXISTS auditoria_promocion_update
AFTER UPDATE ON Promocion
FOR EACH ROW
BEGIN
    INSERT INTO Auditoria_Promocion (fecha, executedSQL, reverseSQL)
    VALUES (NOW(), 
            CONCAT('UPDATE Promocion SET nombre = ', QUOTE(NEW.nombre), 
                   ', multiplicador = ', NEW.multiplicador, 
                   ', fechaInicio = ', QUOTE(NEW.fechaInicio), 
                   ', fechaFin = ', QUOTE(NEW.fechaFin), 
                   ', activo = ', NEW.activo, 
                   ', nivelRequerido = ', NEW.nivelRequerido, 
                   ' WHERE idPromocion = ', OLD.idPromocion), 
            CONCAT('UPDATE Promocion SET nombre = ', QUOTE(OLD.nombre), 
                   ', multiplicador = ', OLD.multiplicador, 
                   ', fechaInicio = ', QUOTE(OLD.fechaInicio), 
                   ', fechaFin = ', QUOTE(OLD.fechaFin), 
                   ', activo = ', OLD.activo, 
                   ', nivelRequerido = ', OLD.nivelRequerido, 
                   ' WHERE idPromocion = ', OLD.idPromocion));
END$$

DELIMITER ;


DELIMITER $$

CREATE TRIGGER IF NOT EXISTS auditoria_promocion_delete
AFTER DELETE ON Promocion
FOR EACH ROW
BEGIN
    INSERT INTO Auditoria_Promocion (fecha, executedSQL, reverseSQL)
    VALUES (NOW(), 
            CONCAT('DELETE FROM Promocion WHERE idPromocion = ', OLD.idPromocion), 
            CONCAT('INSERT INTO Promocion (nombre, multiplicador, fechaInicio, fechaFin, activo, nivelRequerido) VALUES (', 
                    QUOTE(OLD.nombre), ', ', 
                    OLD.multiplicador, ', ', 
                    QUOTE(OLD.fechaInicio), ', ', 
                    QUOTE(OLD.fechaFin), ', ', 
                    OLD.activo, ', ', 
                    OLD.nivelRequerido, ')'));
END$$

DELIMITER ;







-- Usuario

CREATE TABLE IF NOT EXISTS Auditoria_Usuario (
  `idAuditoria` INT NOT NULL AUTO_INCREMENT,
  `fecha` DATETIME NOT NULL,
  `executedSQL` TEXT NOT NULL,
  `reverseSQL` TEXT NOT NULL,
  PRIMARY KEY (`idAuditoria`)
) ENGINE = InnoDB;


DELIMITER $$

CREATE TRIGGER IF NOT EXISTS auditoria_usuario_insert
AFTER INSERT ON Usuario
FOR EACH ROW
BEGIN
    INSERT INTO Auditoria_Usuario (fecha, executedSQL, reverseSQL)
    VALUES (NOW(), 
            CONCAT('INSERT INTO Usuario (username, nombre, correo, password, puntos, puntosTotal, fechaRegistro, idRol, idNivel) VALUES (', 
                    QUOTE(NEW.username), ', ', 
                    QUOTE(NEW.nombre), ', ', 
                    QUOTE(NEW.correo), ', ', 
                    QUOTE(NEW.password), ', ', 
                    NEW.puntos, ', ', 
                    NEW.puntosTotal, ', ', 
                    QUOTE(NEW.fechaRegistro), ', ', 
                    NEW.idRol, ', ', 
                    NEW.idNivel, ')'), 
            CONCAT('DELETE FROM Usuario WHERE idUsuario = ', NEW.idUsuario));
END$$

DELIMITER ;


DELIMITER $$

CREATE TRIGGER IF NOT EXISTS auditoria_usuario_update
AFTER UPDATE ON Usuario
FOR EACH ROW
BEGIN
    INSERT INTO Auditoria_Usuario (fecha, executedSQL, reverseSQL)
    VALUES (NOW(), 
            CONCAT('UPDATE Usuario SET username = ', QUOTE(NEW.username), 
                   ', nombre = ', QUOTE(NEW.nombre), 
                   ', correo = ', QUOTE(NEW.correo), 
                   ', password = ', QUOTE(NEW.password), 
                   ', puntos = ', NEW.puntos, 
                   ', puntosTotal = ', NEW.puntosTotal, 
                   ', fechaRegistro = ', QUOTE(NEW.fechaRegistro), 
                   ', idRol = ', NEW.idRol, 
                   ', idNivel = ', NEW.idNivel, 
                   ' WHERE idUsuario = ', OLD.idUsuario), 
            CONCAT('UPDATE Usuario SET username = ', QUOTE(OLD.username), 
                   ', nombre = ', QUOTE(OLD.nombre), 
                   ', correo = ', QUOTE(OLD.correo), 
                   ', password = ', QUOTE(OLD.password), 
                   ', puntos = ', OLD.puntos, 
                   ', puntosTotal = ', OLD.puntosTotal, 
                   ', fechaRegistro = ', QUOTE(OLD.fechaRegistro), 
                   ', idRol = ', OLD.idRol, 
                   ', idNivel = ', OLD.idNivel, 
                   ' WHERE idUsuario = ', OLD.idUsuario));
END$$

DELIMITER ;


DELIMITER $$

CREATE TRIGGER IF NOT EXISTS auditoria_usuario_delete
AFTER DELETE ON Usuario
FOR EACH ROW
BEGIN
    INSERT INTO Auditoria_Usuario (fecha, executedSQL, reverseSQL)
    VALUES (NOW(), 
            CONCAT('DELETE FROM Usuario WHERE idUsuario = ', OLD.idUsuario), 
            CONCAT('INSERT INTO Usuario (username, nombre, correo, password, puntos, puntosTotal, fechaRegistro, idRol, idNivel) VALUES (', 
                    QUOTE(OLD.username), ', ', 
                    QUOTE(OLD.nombre), ', ', 
                    QUOTE(OLD.correo), ', ', 
                    QUOTE(OLD.password), ', ', 
                    OLD.puntos, ', ', 
                    OLD.puntosTotal, ', ', 
                    QUOTE(OLD.fechaRegistro), ', ', 
                    OLD.idRol, ', ', 
                    OLD.idNivel, ')'));
END$$

DELIMITER ;



-- Registro_Reciclaje (no incluye insert)

CREATE TABLE IF NOT EXISTS Auditoria_Registro_Reciclaje (
  `idAuditoria` INT NOT NULL AUTO_INCREMENT,
  `fecha` DATETIME NOT NULL,
  `executedSQL` TEXT NOT NULL,
  `reverseSQL` TEXT NOT NULL,
  PRIMARY KEY (`idAuditoria`)
) ENGINE = InnoDB;

DELIMITER $$

CREATE TRIGGER IF NOT EXISTS auditoria_registro_reciclaje_update
AFTER UPDATE ON Registro_Reciclaje
FOR EACH ROW
BEGIN
    INSERT INTO Auditoria_Registro_Reciclaje (fecha, executedSQL, reverseSQL)
    VALUES (NOW(), 
            CONCAT('UPDATE Registro_Reciclaje SET idUsuario = ', NEW.idUsuario, 
                   ', idMaterial = ', NEW.idMaterial, 
                   ', idPunto = ', NEW.idPunto, 
                   ', cantidad = ', NEW.cantidad, 
                   ', fecha = ', QUOTE(NEW.fecha), 
                   ', puntosGanados = ', NEW.puntosGanados, 
                   ', impactoCO2 = ', NEW.impactoCO2, 
                   ' WHERE idRegistro = ', OLD.idRegistro), 
            CONCAT('UPDATE Registro_Reciclaje SET idUsuario = ', OLD.idUsuario, 
                   ', idMaterial = ', OLD.idMaterial, 
                   ', idPunto = ', OLD.idPunto, 
                   ', cantidad = ', OLD.cantidad, 
                   ', fecha = ', QUOTE(OLD.fecha), 
                   ', puntosGanados = ', OLD.puntosGanados, 
                   ', impactoCO2 = ', OLD.impactoCO2, 
                   ' WHERE idRegistro = ', OLD.idRegistro));
END$$

DELIMITER ;


DELIMITER $$

CREATE TRIGGER IF NOT EXISTS auditoria_registro_reciclaje_delete
AFTER DELETE ON Registro_Reciclaje
FOR EACH ROW
BEGIN
    INSERT INTO Auditoria_Registro_Reciclaje (fecha, executedSQL, reverseSQL)
    VALUES (NOW(), 
            CONCAT('DELETE FROM Registro_Reciclaje WHERE idRegistro = ', OLD.idRegistro), 
            CONCAT('INSERT INTO Registro_Reciclaje (idUsuario, idMaterial, idPunto, cantidad, fecha, puntosGanados, impactoCO2) VALUES (', 
                    OLD.idUsuario, ', ', 
                    OLD.idMaterial, ', ', 
                    OLD.idPunto, ', ', 
                    OLD.cantidad, ', ', 
                    QUOTE(OLD.fecha), ', ', 
                    OLD.puntosGanados, ', ', 
                    OLD.impactoCO2, ')'));
END$$

DELIMITER ;
