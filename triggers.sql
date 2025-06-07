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