USE reciclaje;
DELIMITER //
CREATE PROCEDURE VerificarUsuario(
    IN p_usuario_id INT
)
BEGIN
    DECLARE v_existe INT;
    SELECT COUNT(*) INTO v_existe FROM Usuario WHERE usuario_id = p_usuario_id;
    IF v_existe = 0 THEN
        SIGNAL SQLSTATE '4'
        SET MESSAGE_TEXT = 'El usuario no existe en el sistema';
    END IF;
END //
DELIMITER ;
