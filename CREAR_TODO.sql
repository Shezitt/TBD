-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema reciclaje
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema reciclaje
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `reciclaje` DEFAULT CHARACTER SET utf8 ;
USE `reciclaje` ;

-- -----------------------------------------------------
-- Table `reciclaje`.`Rol`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reciclaje`.`Rol` (
  `idRol` INT NOT NULL AUTO_INCREMENT,
  `nombreRol` VARCHAR(45) NOT NULL,
  `activo` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`idRol`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reciclaje`.`Nivel`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reciclaje`.`Nivel` (
  `idNivel` INT NOT NULL AUTO_INCREMENT,
  `nivel` INT NOT NULL,
  `nombre` VARCHAR(45) NOT NULL,
  `puntosTotalesNecesarios` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`idNivel`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reciclaje`.`Usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reciclaje`.`Usuario` (
  `idUsuario` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NOT NULL,
  `nombre` VARCHAR(45) NOT NULL,
  `correo` VARCHAR(45) NOT NULL,
  `password` VARCHAR(60) NOT NULL,
  `puntos` DECIMAL(10,2) NOT NULL,
  `puntosTotal` DECIMAL(10,2) NOT NULL,
  `fechaRegistro` DATETIME NOT NULL,
  `idRol` INT NOT NULL,
  `idNivel` INT NOT NULL,
  PRIMARY KEY (`idUsuario`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  INDEX `fk_Usuario_Rol1_idx` (`idRol` ASC),
  INDEX `fk_Usuario_Nivel1_idx` (`idNivel` ASC),
  UNIQUE INDEX `correo_UNIQUE` (`correo` ASC),
  CONSTRAINT `fk_Usuario_Rol1`
    FOREIGN KEY (`idRol`)
    REFERENCES `reciclaje`.`Rol` (`idRol`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Usuario_Nivel1`
    FOREIGN KEY (`idNivel`)
    REFERENCES `reciclaje`.`Nivel` (`idNivel`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reciclaje`.`Log_Acceso`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reciclaje`.`Log_Acceso` (
  `idLogAcceso` INT(11) NOT NULL AUTO_INCREMENT,
  `idUsuario` INT(11) NOT NULL,
  `fechaHora` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idLogAcceso`),
  INDEX `fk_Log_Acceso_Usuario_idx` (`idUsuario` ASC),
  INDEX `idx_fecha_hora` (`fechaHora` ASC),
  INDEX `idx_usuario_fecha` (`idUsuario` ASC, `fechaHora` ASC),
  CONSTRAINT `fk_Log_Acceso_Usuario`
    FOREIGN KEY (`idUsuario`)
    REFERENCES `reciclaje`.`Usuario` (`idUsuario`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;    

-- -----------------------------------------------------
-- Table `reciclaje`.`Punto_Reciclaje`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reciclaje`.`Punto_Reciclaje` (
  `idPunto` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `latitud` DECIMAL(9,6) NOT NULL,
  `longitud` DECIMAL(9,6) NOT NULL,
  `apertura` TIME NOT NULL,
  `cierre` TIME NOT NULL,
  `activo` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`idPunto`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reciclaje`.`Material`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reciclaje`.`Material` (
  `idMaterial` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `coeficientePuntos` DECIMAL(10,2) NOT NULL,
  `coeficienteCO2` DECIMAL(10,2) NOT NULL,
  `activo` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`idMaterial`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reciclaje`.`Registro_Reciclaje`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reciclaje`.`Registro_Reciclaje` (
  `idRegistro` INT NOT NULL AUTO_INCREMENT,
  `idUsuario` INT NOT NULL,
  `idMaterial` INT NOT NULL,
  `idPunto` INT NOT NULL,
  `cantidad` DECIMAL(10,2) NOT NULL,
  `fecha` DATETIME NOT NULL,
  `puntosGanados` DECIMAL(10,2) NOT NULL,
  `impactoCO2` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`idRegistro`),
  INDEX `fk_Registro_Reciclaje_Usuario_idx` (`idUsuario` ASC),
  INDEX `fk_Registro_Reciclaje_Material1_idx` (`idMaterial` ASC),
  INDEX `fk_Registro_Reciclaje_Punto_Reciclaje1_idx` (`idPunto` ASC),
  CONSTRAINT `fk_Registro_Reciclaje_Usuario`
    FOREIGN KEY (`idUsuario`)
    REFERENCES `reciclaje`.`Usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Registro_Reciclaje_Material1`
    FOREIGN KEY (`idMaterial`)
    REFERENCES `reciclaje`.`Material` (`idMaterial`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Registro_Reciclaje_Punto_Reciclaje1`
    FOREIGN KEY (`idPunto`)
    REFERENCES `reciclaje`.`Punto_Reciclaje` (`idPunto`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reciclaje`.`Catalogo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reciclaje`.`Catalogo` (
  `idCatalogo` INT NOT NULL AUTO_INCREMENT,
  `nombreCatalogo` VARCHAR(45) NOT NULL,
  `activo` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`idCatalogo`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reciclaje`.`Recompensa`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reciclaje`.`Recompensa` (
  `idRecompensa` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `puntosNecesarios` DECIMAL(10,2) NOT NULL,
  `nivelRequerido` INT NOT NULL,
  `activo` TINYINT NOT NULL DEFAULT 1,
  `idCatalogo` INT NOT NULL,
  PRIMARY KEY (`idRecompensa`),
  INDEX `fk_Recompensa_Catalogo1_idx` (`idCatalogo` ASC),
  CONSTRAINT `fk_Recompensa_Catalogo1`
    FOREIGN KEY (`idCatalogo`)
    REFERENCES `reciclaje`.`Catalogo` (`idCatalogo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reciclaje`.`Canje`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reciclaje`.`Canje` (
  `idCanje` INT NOT NULL AUTO_INCREMENT,
  `idUsuario` INT NOT NULL,
  `idRecompensa` INT NOT NULL,
  `fecha` DATETIME NOT NULL,
  `completado` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`idCanje`),
  INDEX `fk_Canje_Usuario1_idx` (`idUsuario` ASC),
  INDEX `fk_Canje_Recompensa1_idx` (`idRecompensa` ASC),
  CONSTRAINT `fk_Canje_Usuario1`
    FOREIGN KEY (`idUsuario`)
    REFERENCES `reciclaje`.`Usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Canje_Recompensa1`
    FOREIGN KEY (`idRecompensa`)
    REFERENCES `reciclaje`.`Recompensa` (`idRecompensa`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reciclaje`.`Permiso`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reciclaje`.`Permiso` (
  `idPermiso` INT NOT NULL AUTO_INCREMENT,
  `nombrePermiso` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idPermiso`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reciclaje`.`rol_has_permiso`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reciclaje`.`rol_has_permiso` (
  `Rol_idRol` INT NOT NULL,
  `Permiso_idPermiso` INT NOT NULL,
  PRIMARY KEY (`Rol_idRol`, `Permiso_idPermiso`),
  INDEX `fk_Rol_has_Permiso_Permiso1_idx` (`Permiso_idPermiso` ASC),
  INDEX `fk_Rol_has_Permiso_Rol1_idx` (`Rol_idRol` ASC),
  CONSTRAINT `fk_Rol_has_Permiso_Rol1`
    FOREIGN KEY (`Rol_idRol`)
    REFERENCES `reciclaje`.`Rol` (`idRol`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Rol_has_Permiso_Permiso1`
    FOREIGN KEY (`Permiso_idPermiso`)
    REFERENCES `reciclaje`.`Permiso` (`idPermiso`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reciclaje`.`Punto_Reciclaje_Materiales`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reciclaje`.`Punto_Reciclaje_Materiales` (
  `idPunto` INT NOT NULL,
  `idMaterial` INT NOT NULL,
  PRIMARY KEY (`idPunto`, `idMaterial`),
  INDEX `fk_Punto_Reciclaje_has_Material_Material1_idx` (`idMaterial` ASC),
  INDEX `fk_Punto_Reciclaje_has_Material_Punto_Reciclaje1_idx` (`idPunto` ASC),
  CONSTRAINT `fk_Punto_Reciclaje_has_Material_Punto_Reciclaje1`
    FOREIGN KEY (`idPunto`)
    REFERENCES `reciclaje`.`Punto_Reciclaje` (`idPunto`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Punto_Reciclaje_has_Material_Material1`
    FOREIGN KEY (`idMaterial`)
    REFERENCES `reciclaje`.`Material` (`idMaterial`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reciclaje`.`Promocion`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reciclaje`.`Promocion` (
  `idPromocion` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `multiplicador` DECIMAL(10,2) NOT NULL,
  `fechaInicio` DATE NOT NULL,
  `fechaFin` DATE NOT NULL,
  `activo` TINYINT NOT NULL DEFAULT 1,
  `nivelRequerido` INT NOT NULL DEFAULT 1,
  PRIMARY KEY (`idPromocion`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- MATERIAL
SELECT * FROM Material;

ALTER TABLE Material ADD COLUMN coeficienteAgua DECIMAL(10,2) AFTER coeficienteCO2;
ALTER TABLE Material ADD COLUMN coeficienteEnergia DECIMAL(10,2) AFTER coeficienteAgua;

UPDATE Material SET coeficienteAgua = 2500 WHERE idMaterial=1;
UPDATE Material SET coeficienteAgua = 25 WHERE idMaterial=2;
UPDATE Material SET coeficienteAgua = 5 WHERE idMaterial=3;
UPDATE Material SET coeficienteAgua = 5 WHERE idMaterial=4;
UPDATE Material SET coeficienteAgua = 50 WHERE idMaterial=5;

UPDATE Material SET coeficienteEnergia = 2.0 WHERE idMaterial=1;
UPDATE Material SET coeficienteEnergia = 6.0 WHERE idMaterial=2;
UPDATE Material SET coeficienteEnergia = 0.4 WHERE idMaterial=3;
UPDATE Material SET coeficienteEnergia = 1.3 WHERE idMaterial=4;
UPDATE Material SET coeficienteEnergia = 0.2 WHERE idMaterial=5;


-- REGISTRO RECICLAJE
SELECT * FROM Registro_Reciclaje;

ALTER TABLE Registro_Reciclaje ADD COLUMN impactoAgua DECIMAL(10,2) AFTER impactoCO2;
ALTER TABLE Registro_Reciclaje ADD COLUMN impactoEnergia DECIMAL(10,2) AFTER impactoAgua;

-- Registrar reciclaje (trigger)

-- Trigger para entregar automaticamente sus puntos al usuario, luego de haber registrado un reciclaje.

DELIMITER $$
CREATE TRIGGER IF NOT EXISTS after_insert_registro_reciclaje
BEFORE INSERT ON Registro_Reciclaje
FOR EACH ROW
BEGIN
	UPDATE Usuario
    SET puntos = puntos + NEW.puntosGanados,
    puntosTotal = puntosTotal + NEW.puntosGanados
	WHERE idUsuario = NEW.idUsuario;
END;
$$
DELIMITER ;

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


-- Registro_Reciclaje

CREATE INDEX idx_fecha ON Registro_Reciclaje (fecha);
CREATE INDEX idx_usuario_material ON Registro_Reciclaje (idUsuario, idMaterial);


-- Tabla Modificacion_Reciclaje

CREATE TABLE IF NOT EXISTS `reciclaje`.`Modificacion_Reciclaje` (
  `idModificacion` INT NOT NULL AUTO_INCREMENT,
  `idReciclaje` INT NOT NULL,
  `idAdmin` INT NOT NULL,
  `modificacion` TEXT NOT NULL,
  `motivo` VARCHAR(255) NOT NULL,
  `fechaHora` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idModificacion`),
  INDEX `fk_Modificacion_Reciclaje_Registro_idx` (`idReciclaje` ASC),
  INDEX `fk_Modificacion_Reciclaje_Admin_idx` (`idAdmin` ASC),
  CONSTRAINT `fk_Modificacion_Reciclaje_Registro`
    FOREIGN KEY (`idReciclaje`)
    REFERENCES `reciclaje`.`Registro_Reciclaje` (`idRegistro`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Modificacion_Reciclaje_Admin`
    FOREIGN KEY (`idAdmin`)
    REFERENCES `reciclaje`.`Usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;


-- Procedimiento almacenado para ActualizarPuntosGanados

DELIMITER $$

CREATE PROCEDURE `reciclaje`.`ActualizarPuntosGanados`(
    IN p_idReciclaje INT,
    IN p_idAdmin INT,
    IN p_nuevoPuntosGanados DECIMAL(10,2),
    IN p_motivo VARCHAR(255)
)
BEGIN
    DECLARE v_puntosActual DECIMAL(10,2);
    DECLARE v_textoModificacion TEXT;

    -- Obtener valor actual de puntosGanados
    SELECT puntosGanados INTO v_puntosActual
    FROM Registro_Reciclaje
    WHERE idRegistro = p_idReciclaje;

    -- Actualizar el registro con nuevo valor
    UPDATE Registro_Reciclaje
    SET puntosGanados = p_nuevoPuntosGanados
    WHERE idRegistro = p_idReciclaje;

    -- Construir texto descriptivo de la modificación
    SET v_textoModificacion = CONCAT(
        'Cambio puntosGanados de ', FORMAT(v_puntosActual, 2),
        ' a ', FORMAT(p_nuevoPuntosGanados, 2)
    );

    -- Insertar registro en Modificacion_Reciclaje
    INSERT INTO Modificacion_Reciclaje(
        idReciclaje,
        idAdmin,
        modificacion,
        motivo,
        fechaHora
    ) VALUES (
        p_idReciclaje,
        p_idAdmin,
        v_textoModificacion,
        p_motivo,
        NOW()
    );

END $$

DELIMITER ;


-- Procedimiento almacenado para ver historial de modificacion reciclaje

DELIMITER $$

CREATE PROCEDURE ObtenerModificacionReciclaje (
	IN p_username VARCHAR(255)
)
BEGIN
	SELECT mr.idReciclaje, uadmin.nombre AS nombreAdmin, mr.modificacion, mr.motivo, mr.fechaHora
	FROM Modificacion_Reciclaje mr
    JOIN Registro_Reciclaje rr ON rr.idRegistro = mr.idReciclaje
    JOIN Usuario uadmin ON uadmin.idUsuario = idAdmin
    JOIN Usuario u ON u.idUsuario = rr.idUsuario
    WHERE u.username = p_username
    ORDER BY mr.fechaHora;
END $$

DELIMITER ;


-- Para cambiar los puntos del usuario luego de la modificacion

DELIMITER $$

CREATE TRIGGER IF NOT EXISTS after_update_registro_reciclaje
AFTER UPDATE ON Registro_Reciclaje
FOR EACH ROW
BEGIN
    DECLARE diferencia FLOAT;
    SET diferencia = NEW.puntosGanados - OLD.puntosGanados;

    UPDATE Usuario SET puntos = puntos + diferencia,
    puntosTotal = puntosTotal + diferencia WHERE idUsuario = OLD.idUsuario;

END$$

DELIMITER ;


-- Procedimientos almacenados

USE reciclaje

-- getDatosUsuario

DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS sp_getDatosUsuario (
	IN p_idUsuario INT
)
BEGIN
	SELECT *
	FROM Usuario 
	WHERE idUsuario = p_idUsuario;
END;
$$
DELIMITER ;

-- Verificar que nombre de usuario y email son unicos (registrar usuario)

DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS sp_verificarUsernameEmailUnico (
    IN p_username VARCHAR(45),
    IN p_email VARCHAR(45)
)
BEGIN
    SELECT COUNT(*) = 0 AS respuesta
    FROM Usuario
    WHERE username = p_username OR correo = p_email;
END;
$$
DELIMITER ;

-- Registrar nuevo usuario 

DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS sp_registrarUsuario (
    IN p_nombre VARCHAR(45),
    IN p_username VARCHAR(45),
    IN p_email VARCHAR(45), 
    IN p_password VARCHAR(45)
)
BEGIN 
    INSERT INTO Usuario (username, nombre, correo, password, puntos, puntosTotal, fechaRegistro, idRol, idNivel)
    VALUES (p_username, p_nombre, p_email, p_password, 0.0, 0.0, now(), 1, 1);
END;
$$
DELIMITER ;

-- Autenticar usuario (iniciar sesión)

DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS sp_verificarUsuario (
	IN p_username VARCHAR(45),
    IN p_password VARCHAR(45)
)
BEGIN 
	SELECT *
    FROM Usuario
    WHERE username = p_username AND password = p_password;
END;
$$
DELIMITER ;

-- Impacto ambiental de usuario

DELIMITER $$

CREATE PROCEDURE IF NOT EXISTS sp_getImpactoUsuario (
	IN p_idUsuario INT
)
BEGIN
	SELECT
		Material.nombre AS tipo_material,
        SUM(Registro_Reciclaje.cantidad) AS total_reciclado_kg,
        SUM(Registro_Reciclaje.impactoCO2) AS total_co2_reducido,
        SUM(Registro_Reciclaje.impactoAgua) AS total_agua_reducido,
        SUM(Registro_Reciclaje.impactoEnergia) AS total_energia_reducido,
        ROUND(
            (SUM(Registro_Reciclaje.cantidad) /
             (SELECT SUM(RR2.cantidad)
              FROM Registro_Reciclaje RR2
              WHERE RR2.idMaterial = Material.idMaterial)
            ) * 100, 2
        ) AS porcentaje_usuario
	FROM Registro_Reciclaje
	JOIN Material ON Registro_Reciclaje.idMaterial = Material.idMaterial
	JOIN Usuario ON Registro_Reciclaje.idUsuario = Usuario.idUsuario
	WHERE Usuario.idUsuario = p_idUsuario AND Material.activo = 1 
	GROUP BY Material.nombre
	ORDER BY total_reciclado_kg DESC;
END;
$$

DELIMITER ;

-- Promociones disponibles para usuario

DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS sp_getPromocionesUsuario (
	IN p_idUsuario INT
)
BEGIN
	DECLARE v_nivelUsuario INT;
    
	SELECT nivel INTO v_nivelUsuario
    FROM Usuario JOIN Nivel ON Usuario.idNivel = Nivel.idNivel
    WHERE idUsuario = p_idUsuario;

	SELECT *    
    FROM Promocion
    WHERE v_nivelUsuario >= Promocion.nivelRequerido AND Promocion.activo = 1;
    
END;
$$
DELIMITER ;

-- Listar todos los puntos de reciclaje

DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS sp_getPuntosReciclaje ()
BEGIN
	SELECT * 
    FROM Punto_Reciclaje
    WHERE activo = 1;
END;
$$
DELIMITER ;

-- Ver puntos de reciclaje cercanos 

DELIMITER $$

CREATE PROCEDURE IF NOT EXISTS sp_getPuntosReciclajeCercanos (
    IN p_latitud DOUBLE,
    IN p_longitud DOUBLE
)
BEGIN
    SELECT *,
        6371 * 2 * ASIN(
            SQRT(
                POW(SIN(RADIANS(pr.latitud - p_latitud) / 2), 2) +
                COS(RADIANS(p_latitud)) * 
                COS(RADIANS(pr.latitud)) * 
                POW(SIN(RADIANS(pr.longitud - p_longitud) / 2), 2)
            )
        ) AS distancia_km
    FROM Punto_Reciclaje pr
    WHERE activo = 1
    HAVING distancia_km < 5
    ORDER BY distancia_km;
END $$

DELIMITER ;

-- Ver materiales aceptados punto de reciclaje

DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS sp_getMaterialesPuntoReciclaje (
	IN p_idPunto INT
)
BEGIN
	SELECT *
    FROM Punto_Reciclaje_Materiales
    JOIN Material ON Material.idMaterial = Punto_Reciclaje_Materiales.idMaterial
    WHERE Punto_Reciclaje_Materiales.idPunto = p_idPunto AND Material.activo = 1;
END;
$$
DELIMITER ;

-- Registrar reciclaje 
-- tambien revisa si es que hay alguna promocion vigente para multiplicar sus puntos

DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS sp_registrarReciclaje (
	IN p_usuario_id INT,
    IN p_material_id INT,
    IN p_punto_reciclaje_id INT,
    IN p_cantidad_kg DECIMAL(10,2),
    OUT p_puntos_ganados DECIMAL(10,2),
    OUT p_impacto_co2 DECIMAL (10,2),
    OUT p_impacto_agua DECIMAL (10,2),
    OUT p_impacto_energia DECIMAL (10,2)
)
BEGIN
	DECLARE v_coef_puntos DECIMAL(10,2);
    DECLARE v_coef_co2 DECIMAL(10,2);
    DECLARE v_coef_agua DECIMAL(10,2);
    DECLARE v_coef_energia DECIMAL(10,2);
    DECLARE v_impacto_co2 DECIMAL(10,2);
    DECLARE v_impacto_agua DECIMAL(10,2);
    DECLARE v_impacto_energia DECIMAL(10,2);
    DECLARE v_fechaActual DATE;
    DECLARE v_nivelUsuario INT;
    DECLARE v_multiplicadorPromo DECIMAL(10,2) DEFAULT 0;
    
    SELECT coeficientePuntos, coeficienteCO2, coeficienteAgua, coeficienteEnergia
    INTO v_coef_puntos, v_coef_co2, v_coef_agua, v_coef_energia
    FROM Material 
    WHERE idMaterial = p_material_id;
    
    SET v_impacto_co2 = v_coef_co2 * p_cantidad_kg;
    SET v_impacto_agua = v_coef_agua * p_cantidad_kg;
    SET v_impacto_energia = v_coef_energia * p_cantidad_kg;

    SELECT nivel INTO v_nivelUsuario
    FROM Usuario JOIN Nivel ON Usuario.idNivel = Nivel.idNivel
    WHERE idUsuario = p_usuario_id;
    
    SET v_fechaActual = CURDATE();
    
    SELECT IFNULL(SUM(Promocion.multiplicador), 1) INTO v_multiplicadorPromo
    FROM Promocion
    WHERE Promocion.activo = 1 
    AND v_fechaActual BETWEEN Promocion.fechaInicio AND Promocion.fechaFin 
    AND v_nivelUsuario >= Promocion.nivelRequerido;

    SET p_puntos_ganados = v_multiplicadorPromo * v_coef_puntos *  p_cantidad_kg;
    SET p_impacto_co2 = v_impacto_co2;
    SET p_impacto_agua = v_impacto_agua;
    SET p_impacto_energia = v_impacto_energia;
	
	INSERT INTO Registro_Reciclaje(idUsuario, idMaterial, idPunto, cantidad, fecha, puntosGanados, impactoCO2, impactoAgua, impactoEnergia)
    VALUES (p_usuario_id, p_material_id, p_punto_reciclaje_id, p_cantidad_kg, NOW(), p_puntos_ganados, v_impacto_co2, v_impacto_agua, v_impacto_energia); 
    
END;
$$
DELIMITER ;

-- Recompensas

-- Listar catalogos

DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS sp_getCatalogos ()
BEGIN
	SELECT *
    FROM Catalogo
    WHERE activo = 1;
END;
$$
DELIMITER ;

-- Listar recompensas de un catalogo

DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS sp_getRecompensasCatalogo (
	IN p_idCatalogo INT
)
BEGIN
	SELECT *
    FROM Recompensa
    WHERE activo = 1 AND idCatalogo = p_idCatalogo;
END;
$$
DELIMITER ;

-- Verificar si usuario tiene nivel para cierta recompensa

DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS sp_verificarNivelUsuarioRecompensa (
	IN p_idRecompensa INT,
    IN p_idUsuario INT
)
BEGIN
	DECLARE v_nivelUsuario INT;
    
    SELECT nivel INTO v_nivelUsuario
    FROM Usuario JOIN Nivel ON Usuario.idNivel = Nivel.idNivel
    WHERE idUsuario = p_idUsuario;
    
	SELECT COUNT(*) > 0 AS respuesta
    FROM Recompensa
    WHERE activo = 1 AND idRecompensa = p_idRecompensa
    AND nivelRequerido <= v_nivelUsuario;
END;
$$
DELIMITER ;

-- Verificar si usuario tiene puntos necesarios para cierta recompensa

DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS sp_verificarPuntosUsuarioRecompensa (
	IN p_idRecompensa INT,
    IN p_idUsuario INT
)
BEGIN
	DECLARE v_puntosUsuario DECIMAL(10,2);
    
    SELECT puntos INTO v_puntosUsuario
    FROM Usuario 
    WHERE idUsuario = p_idUsuario;
    
	SELECT COUNT(*) > 0 AS respuesta
    FROM Recompensa
    WHERE activo = 1 AND idRecompensa = p_idRecompensa
    AND puntosNecesarios <= v_puntosUsuario;
END;
$$
DELIMITER ;


-- Canje de recompensas del usuario

DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS sp_canjearRecompensa (
    IN p_usuario_id INT,
    IN p_recompensa_id INT
)
BEGIN
    DECLARE v_puntos_usuario DECIMAL(10,2);
    DECLARE v_puntos_requeridos DECIMAL(10,2);
    DECLARE v_nivel_usuario INT;
    DECLARE v_nivel_requerido INT;

    SELECT puntosTotal INTO v_puntos_usuario FROM Usuario WHERE idUsuario = p_usuario_id;
    SELECT puntosNecesarios INTO v_puntos_requeridos FROM Recompensa WHERE idRecompensa = p_recompensa_id;
    SELECT nivel INTO v_nivel_usuario FROM Usuario JOIN Nivel ON Usuario.idNivel = Nivel.idNivel WHERE idUsuario = p_usuario_id;
    SELECT nivelRequerido INTO v_nivel_requerido FROM Recompensa WHERE idRecompensa = p_recompensa_id;

    -- Verificamos si hay puntitos sufis y que tenga nivel suficiente
    IF v_puntos_usuario >= v_puntos_requeridos AND v_nivel_usuario >= v_nivel_requerido THEN
        UPDATE Usuario SET puntos = puntos - v_puntos_requeridos WHERE idUsuario = p_usuario_id;
        -- Registramos el canje 
        INSERT INTO Canje(idUsuario, idRecompensa, fecha)
        VALUES (p_usuario_id, p_recompensa_id, NOW());
    END IF;
END $$
DELIMITER ;

-- Listar canjes pendientes

DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS sp_getCanjesPendientes()
BEGIN
    SELECT 
        Canje.idCanje AS idCanje,
        Usuario.nombre AS nombreUsuario,
        Nivel.nivel AS nivelUsuario,
        Recompensa.nombre AS nombreRecompensa, 
        Canje.fecha AS fecha
    FROM 
        Canje JOIN Usuario ON Canje.idUsuario = Usuario.idUsuario
        JOIN Nivel ON Nivel.idNivel = Usuario.idNivel
        JOIN Recompensa ON Recompensa.idRecompensa = Canje.idRecompensa
    WHERE Canje.completado = 0;

END $$
DELIMITER ;

-- Reportes de impacto ambiental

DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS GenerarReporteImpactoAmbientalHistorico()
BEGIN
    SELECT 
        Material.nombre AS tipo_material,
        SUM(Registro_Reciclaje.cantidad) AS total_reciclado_kg,
        SUM(Registro_Reciclaje.impactoCO2) AS total_co2_reducido,
        SUM(Registro_Reciclaje.impactoAgua) AS total_agua_reducido,
        SUM(Registro_Reciclaje.impactoEnergia) AS total_energia_reducido
    FROM Registro_Reciclaje
    JOIN Material ON Registro_Reciclaje.idMaterial = Material.idMaterial
    GROUP BY Material.nombre ORDER BY total_reciclado_kg DESC;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS GenerarReporteImpactoAmbientalRango (
	IN fechaInicio DATE,
    IN fechaFin DATE
)
BEGIN
    SELECT 
        Material.nombre AS tipo_material,
        SUM(Registro_Reciclaje.cantidad) AS total_reciclado_kg,
        SUM(Registro_Reciclaje.impactoCO2) AS total_co2_reducido
    FROM Registro_Reciclaje
    JOIN Material ON Registro_Reciclaje.idMaterial = Material.idMaterial
    WHERE Registro_Reciclaje.fecha BETWEEN fechaInicio AND fechaFin
    GROUP BY Material.nombre ORDER BY total_reciclado_kg DESC;
END $$
DELIMITER ;

-- Ranking top 10 usuarios con mas puntos

DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS getRankingPuntos()
BEGIN
    SELECT
        username, Usuario.nombre, puntosTotal, nivel
    FROM Usuario 
    JOIN Nivel ON Usuario.idNivel = Nivel.idNivel
    ORDER BY puntosTotal DESC LIMIT 10;
END $$
DELIMITER ;



-- PROCEDIMIENTOS ALMACENADOS PARA ADMINISTRADOR

-- Agregar nuevo material de reciclaje

DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS sp_nuevoMaterial (
    IN p_nombre VARCHAR(45),
    IN p_coefpuntos DECIMAL(10,2),
    IN p_coefco2 DECIMAL(10,2),
    IN p_coefagua DECIMAL(10,2),
    IN p_coefenergia DECIMAL(10,2)
)
BEGIN
    INSERT INTO Material (nombre, coeficientePuntos, coeficienteCO2, coeficienteAgua, coeficienteEnergia)
    VALUES (p_nombre, p_coefpuntos, p_coefco2, p_coefagua, p_coefenergia);
END $$

DELIMITER ;

-- Agregar nueva promocion

DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS sp_nuevaPromocion (
    IN p_nombre VARCHAR(45),
    IN p_multiplicador DECIMAL(10,2),
    IN p_nivel_requerido INT,
    IN p_fecha_inicio DATE,
    IN p_fecha_fin DATE
)
BEGIN
    INSERT INTO Promocion (nombre, multiplicador, nivelRequerido,  fechaInicio, fechaFin)
    VALUES (p_nombre, p_multiplicador, p_nivel_requerido, p_fecha_inicio, p_fecha_fin);
END $$

DELIMITER ;

-- Agregar nuevo catalogo

DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS sp_nuevoCatalogo (
    IN p_nombre VARCHAR(45)
)
BEGIN
    INSERT INTO Catalogo (nombreCatalogo)
    VALUES (p_nombre);
END $$
DELIMITER ;

-- Agregar nueva recompensa

DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS sp_nuevaRecompensa (
    IN p_nombre VARCHAR(45),
    IN p_puntos_necesarios DECIMAL(10,2),
    IN p_nivel_requerido INT,
    IN p_idCatalogo INT
)
BEGIN
    INSERT INTO Recompensa (nombre, puntosNecesarios, nivelRequerido, idCatalogo)
    VALUES (p_nombre, p_puntos_necesarios, p_nivel_requerido, p_idCatalogo);
END $$
DELIMITER ;

-- Completar canje

DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS sp_completarCanje (
    IN p_idCanje INT
)
BEGIN
    UPDATE Canje
    SET completado = 1
    WHERE idCanje = p_idCanje;
END $$
DELIMITER ;

-- Agregar punto de reciclaje

DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS sp_nuevoPuntoReciclaje (
    IN p_nombre VARCHAR(45),
    IN p_latitud DOUBLE,
    IN p_longitud DOUBLE,
    IN p_apertura TIME,
    IN p_cierre TIME
)
BEGIN
    INSERT INTO Punto_Reciclaje (nombre, latitud, longitud, apertura, cierre)
    VALUES (p_nombre, p_latitud, p_longitud, p_apertura, p_cierre);
END $$
DELIMITER ;

-- Agregar nuevo nivel

DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS sp_nuevoNivel (
    IN p_nivel INT,
    IN p_nombre VARCHAR(45),
    IN p_puntos DECIMAL(10,2)
)
BEGIN
    INSERT INTO Nivel (nivel, nombre, puntosTotalesNecesarios)
    VALUES (p_nivel, p_nombre, p_puntos);
END $$

DELIMITER ;

-- ADMINISTRADOR - SECCION REPORTES E IMPACTO

DELIMITER $$ 
CREATE PROCEDURE IF NOT EXISTS sp_reporteUsuariosImpacto (
    IN p_fecha_inicio DATE,
    IN p_fecha_fin DATE
)
BEGIN
    SELECT Usuario.nombre,
			nivel,
			puntosTotal, 
            SUM(cantidad) AS total_reciclado_kg,
            SUM(impactoCO2) AS total_co2_reducido,
            SUM(impactoAgua) AS total_agua_reducido,
            SUM(impactoEnergia) AS total_energia_reducido
    FROM Usuario JOIN Registro_Reciclaje 
    ON Usuario.idUsuario = Registro_Reciclaje.idUsuario
    JOIN Nivel ON Nivel.idNivel = Usuario.idNivel
    WHERE fecha BETWEEN p_fecha_inicio AND p_fecha_fin
    GROUP BY Usuario.idUsuario 
    ORDER BY puntosTotal DESC;
    END $$
DELIMITER ;

-- IMPACTO AMBIENTAL MATERIALES IMPACTO

DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS sp_reporteMaterialesImpacto(
    IN p_fecha_inicio DATE,
    IN p_fecha_fin DATE
)
BEGIN
    SELECT 
        Material.nombre AS material,
        SUM(Registro_Reciclaje.cantidad) AS total_reciclado_kg,
        SUM(Registro_Reciclaje.puntosGanados) AS total_puntos,
        SUM(Registro_Reciclaje.impactoCO2) AS total_co2_reducido,
        SUM(Registro_Reciclaje.impactoAgua) AS total_agua_reducido,
        SUM(Registro_Reciclaje.impactoEnergia) AS total_energia_reducido
    FROM 
        Registro_Reciclaje
    JOIN 
        Material ON Registro_Reciclaje.idMaterial = Material.idMaterial
    WHERE 
        Registro_Reciclaje.fecha BETWEEN p_fecha_inicio AND p_fecha_fin
    GROUP BY 
        Material.idMaterial
    ORDER BY 
        total_reciclado_kg DESC;
END $$

DELIMITER ;

-- REPORTE CANJES

DELIMITER $$
CREATE PROCEDURE sp_reporteCanjes(
    IN p_fecha_inicio DATE,
    IN p_fecha_fin DATE
)
BEGIN
    SELECT 
        Recompensa.nombre AS recompensa,
        COUNT(Canje.idCanje) AS total_canjes,
        SUM(CASE WHEN Canje.completado = 1 THEN 1 ELSE 0 END) AS canjes_completados
    FROM 
        Canje
    JOIN 
        Recompensa ON Canje.idRecompensa = Recompensa.idRecompensa
    WHERE
        Canje.fecha BETWEEN p_fecha_inicio AND p_fecha_fin
    GROUP BY 
        Recompensa.idRecompensa
    ORDER BY 
        total_canjes DESC;
END $$
DELIMITER ;

-- ADMINISTRADOR SECCION AUDITORIA


-- historial reciclaje

DELIMITER $$
CREATE PROCEDURE sp_historialReciclaje(
    IN fecha_inicio DATE,
    IN fecha_fin DATE
)
BEGIN
    SELECT 
        Usuario.username, 
        Usuario.nombre AS nombre_usuario,
        Punto_Reciclaje.nombre AS nombre_punto,
        Material.nombre AS nombre_material,
        Registro_Reciclaje.cantidad,
        Registro_Reciclaje.puntosGanados,
        Registro_Reciclaje.impactoCO2,
        Registro_Reciclaje.impactoAgua,
        Registro_Reciclaje.impactoEnergia,
        Registro_Reciclaje.fecha
    FROM Registro_Reciclaje
    JOIN Usuario ON Usuario.idUsuario = Registro_Reciclaje.idUsuario
    JOIN Material ON Material.idMaterial = Registro_Reciclaje.idMaterial
    JOIN Punto_Reciclaje ON Punto_Reciclaje.idPunto = Registro_Reciclaje.idPunto
    WHERE Registro_Reciclaje.fecha BETWEEN fecha_inicio AND fecha_fin
    ORDER BY Registro_Reciclaje.fecha DESC;
END $$

DELIMITER ;


-- Historial reciclaje usuario

DELIMITER $$

CREATE PROCEDURE listar_reciclaje_usuario (
	IN p_idUsuario INT
)
BEGIN 
	SELECT idRegistro, fecha, p.nombre AS nombrePunto, m.nombre AS material, cantidad, puntosGanados, impactoCO2, impactoAgua, impactoEnergia
	FROM Registro_Reciclaje 
	JOIN Punto_Reciclaje p ON p.idPunto = Registro_Reciclaje.idPunto
	JOIN Material m ON m.idMaterial = Registro_Reciclaje.idMaterial
	WHERE idUsuario = p_idUsuario
    ORDER BY fecha DESC;
END $$

DELIMITER ;

-- Historial canjes usuario

DELIMITER $$

CREATE PROCEDURE listar_canje_usuario (
	IN p_idUsuario INT
)
BEGIN 
	SELECT idCanje, fecha, nombreCatalogo, r.nombre, completado
    FROM Canje
    JOIN Recompensa r ON r.idRecompensa = Canje.idRecompensa
    JOIN Catalogo ON Catalogo.idCatalogo = r.idCatalogo
    ORDER BY fecha DESC;
END $$

DELIMITER ;

-- POBLACION

INSERT INTO Nivel (nivel, nombre, puntosTotalesNecesarios) 
VALUES (1, 'Principiante', 0);

-- Rol

INSERT INTO Rol (nombreRol) 
VALUES ('usuario');

INSERT INTO Rol (nombreRol)
VALUES ('administrador');

-- Permisos

INSERT INTO Permiso (nombrePermiso) VALUES ("dashboard");
INSERT INTO Permiso (nombrePermiso) VALUES ("dashboard_gestion");
INSERT INTO Permiso (nombrePermiso) VALUES ("dashboard_reportes");
INSERT INTO Permiso (nombrePermiso) VALUES ("dashboard_historial");

INSERT INTO rol_has_permiso VALUES (2, 1);
INSERT INTO rol_has_permiso VALUES (2, 2);
INSERT INTO rol_has_permiso VALUES (2, 3);
INSERT INTO rol_has_permiso VALUES (2, 4);


-- Usuario

INSERT INTO Usuario (username, nombre, correo, password, puntos, puntosTotal, fechaRegistro, idRol, idNivel)
VALUES ('shezitt', 'Shamir Terán', 'shamirteranmustafa@gmail.com', 'contra', 0.00, 0.00, '2025-04-20', '1','1');

INSERT INTO Usuario (username, nombre, correo, password, puntos, puntosTotal, fechaRegistro, idRol, idNivel)
VALUES ("carlos", "Carlos La Fuente", "carloslafuente12@gmail.com", "contra", 0.0, 0.0, "2025-04-20", 2, 1);


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

INSERT INTO Punto_Reciclaje (nombre, latitud, longitud, apertura, cierre) 
VALUES ('Tupuraya', -17.373699, -66.142277, '08:00:00', '22:00:00');

INSERT INTO Punto_Reciclaje (nombre, latitud, longitud, apertura, cierre) 
VALUES ('Cala Cala', -17.373699, -66.168236, '08:00:00', '22:00:00');


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
VALUES ('Tarjeta Epic', 100.0, 2, 1);

INSERT INTO Recompensa (nombre, puntosNecesarios, nivelRequerido, idCatalogo)
VALUES ('Power Bank', 100.0, 1, 2);

INSERT INTO Recompensa (nombre, puntosNecesarios, nivelRequerido, idCatalogo)
VALUES ('Auriculares Bluetooth', 100.0, 2, 2);

INSERT INTO Recompensa (nombre, puntosNecesarios, nivelRequerido, idCatalogo)
VALUES ('Mouse gamer', 500.0, 3, 2);

INSERT INTO Recompensa (nombre, puntosNecesarios, nivelRequerido, idCatalogo)
VALUES ('2x1 bebidas', 50.0, 1, 3);

-- Promociones

INSERT INTO Promocion (nombre, multiplicador, fechaInicio, fechaFin, nivelRequerido)
VALUES ("Promo Corpus Christi", 1.5, "2025-06-01", "2026-06-30", 1);