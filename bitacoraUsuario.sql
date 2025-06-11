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
