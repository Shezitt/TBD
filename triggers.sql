-- Registrar reciclaje (trigger)

-- Trigger para entregar automaticamente sus puntos al usuario, luego de haber registrado un reciclaje.
-- tambien actualiza el nivel del usuario

DELIMITER $$
CREATE TRIGGER IF NOT EXISTS after_insert_registro_reciclaje
BEFORE INSERT ON Registro_Reciclaje
FOR EACH ROW
BEGIN
	DECLARE v_puntos_usuario DECIMAL(10,2);
    DECLARE v_nivel_actualizado INT;

	UPDATE Usuario
    SET puntos = puntos + NEW.puntosGanados,
    puntosTotal = puntosTotal + NEW.puntosGanados
	WHERE idUsuario = NEW.idUsuario;

    SELECT puntosTotal INTO v_puntos_usuario
    FROM Usuario u
    WHERE u.idUsuario = NEW.idUsuario;

    -- Dar al usuario el nivel que corresponde

    SELECT idNivel INTO v_nivel_actualizado
    FROM Nivel n
    WHERE n.puntosTotalesNecesarios <= v_puntos_usuario 
    ORDER BY n.nivel DESC LIMIT 1;

	UPDATE Usuario SET idNivel = v_nivel_actualizado
    WHERE idUsuario = NEW.idUsuario;

END;
$$
DELIMITER ;