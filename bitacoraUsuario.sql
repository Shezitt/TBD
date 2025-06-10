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
	SELECT idReciclaje, Usuario.nombre AS nombreAdmin, modificacion, motivo, fechaHora
	FROM Modificacion_Reciclaje
    JOIN Usuario ON idUsuario = idAdmin
    WHERE idUsuario = p_username;
END $$

DELIMITER ;