use reciclaje;

-- Trigger para entregar automaticamente sus puntos al usuario, luego de haber registrado un reciclaje.
DELIMITER //
CREATE TRIGGER after_insert_registro_reciclaje
AFTER INSERT ON Registro_Reciclaje
FOR EACH ROW
BEGIN
	UPDATE Usuario
    SET puntos = puntos + NEW.puntosGanados,
    puntosTotal = puntosTotal + NEW.puntosGanados
	WHERE idUsuario = NEW.idUsuario;
END;
//
DELIMITER ;

-- Triggers para logs


DELIMITER //
-- Procedimiento almacenado para registrar un reciclaje
CREATE PROCEDURE RegistrarReciclaje (
	IN usuario_id INT,
    IN material_id INT,
    IN punto_reciclaje_id INT,
    IN cantidad_kg DECIMAL(10,2)
)
BEGIN
	DECLARE coef_puntos DECIMAL(10,2);
    DECLARE coef_co2 DECIMAL(10,2);
    DECLARE puntos_ganados DECIMAL(10,2);
    DECLARE impacto_co2 DECIMAL(10,2);
    
    SELECT coeficientePuntos INTO coef_puntos 
    FROM Material WHERE idMaterial = material_id;
    
    SELECT coeficienteCO2 INTO coef_co2
    FROM Material WHERE idMaterial = material_id;

	SET puntos_ganados = coef_puntos * cantidad;
    SET impacto_co2 = coef_co2 * cantidad;
	
	INSERT INTO Registro_Reciclaje(idUsuario, idMaterial, idPunto, cantidad, fecha, puntosGanados, impactoCO2)
    VALUES (usuario_id, material_id, punto_id, cantidad_kg, NOW(), puntos_ganados, impacto_co2); 
    
END;
// 
DELIMITER ;