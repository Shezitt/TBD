USE reciclaje; 
-- 	DROP PROCEDURE IF EXISTS GenerarReporteImpactoAmbiental;

DELIMITER //
CREATE PROCEDURE CanjearRecompensa(
    IN p_usuario_id INT,
    IN p_recompensa_id INT
)
BEGIN
    DECLARE v_puntos_usuario DECIMAL(10,2);
    DECLARE v_puntos_requeridos DECIMAL(10,2);
    SELECT puntos INTO v_puntos_usuario FROM Usuario WHERE idUsuario = p_usuario_id;
    SELECT puntosNecesarios INTO v_puntos_requeridos FROM Recompensa WHERE idRecompensa = p_recompensa_id;
    -- Verificcamos si hay puntitos sufis
    IF v_puntos_usuario >= v_puntos_requeridos THEN
        UPDATE Usuario SET puntos = puntos - v_puntos_requeridos WHERE idUsuario = p_usuario_id;
        -- Registramos el canje 
        INSERT INTO Canje(idUsuario, idRecompensa, fecha)
        VALUES (p_usuario_id, p_recompensa_id, NOW());
    ELSE
        SIGNAL SQLSTATE '4'
        SET MESSAGE_TEXT = 'Puntos insuficientes para el canje';
    END IF;
END //
DELIMITER ;

