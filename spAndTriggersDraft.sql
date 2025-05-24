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
        SUM(Registro_Reciclaje.impactoCO2) AS total_co2_reducido
    FROM Registro_Reciclaje
    JOIN Material ON Registro_Reciclaje.idMaterial = Material.idMaterial
    JOIN Usuario ON Registro_Reciclaje.idUsuario = Usuario.idUsuario
    WHERE Usuario.idUsuario = p_idUsuario AND Material.activo = 1 
    GROUP BY Material.nombre ORDER BY total_reciclado_kg DESC;
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
       *,
        6371 * 2 * ASIN(
            SQRT(
                POW(SIN(RADIANS(pr.latitud - p_latitud) / 2), 2) +
                COS(RADIANS(p_latitud)) * 
                COS(RADIANS(pr.latitud)) * 
                POW(SIN(RADIANS(pr.longitud - p_longitud) / 2), 2)
            )
        ) AS distancia_km
    FROM Punto_Reciclaje
    WHERE activo = 1
    HAVING distancia_km < 5
    ORDER BY distancia_km;
END;
$$

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
    OUT p_puntos_ganados DECIMAL(10,2)
)
BEGIN
	DECLARE v_coef_puntos DECIMAL(10,2);
    DECLARE v_coef_co2 DECIMAL(10,2);
    DECLARE v_impacto_co2 DECIMAL(10,2);
    DECLARE v_fechaActual DATE;
    DECLARE v_nivelUsuario INT;
    DECLARE v_multiplicadorPromo DECIMAL(10,2) DEFAULT 0;
    
    SELECT coeficientePuntos, coeficienteCO2 
    INTO v_coef_puntos, v_coef_co2 
    FROM Material 
    WHERE idMaterial = p_material_id;
    
    SET v_impacto_co2 = v_coef_co2 * p_cantidad_kg;

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
	
	INSERT INTO Registro_Reciclaje(idUsuario, idMaterial, idPunto, cantidad, fecha, puntosGanados, impactoCO2)
    VALUES (p_usuario_id, p_material_id, p_punto_reciclaje_id, p_cantidad_kg, NOW(), p_puntos_ganados, v_impacto_co2); 
    
END;
$$
DELIMITER ;

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
        SUM(Registro_Reciclaje.impactoCO2) AS total_co2_reducido
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
    ORDER BY puntosTotal DESC;
END $$
DELIMITER ;



-- PROCEDIMIENTOS ALMACENADOS PARA ADMINISTRADOR

-- Agregar nuevo material de reciclaje

DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS sp_nuevoMaterial (
    IN p_nombre VARCHAR(45),
    IN p_coefpuntos DECIMAL(10,2),
    IN p_coefco2 DECIMAL(10,2)
)
BEGIN
    INSERT INTO Material (nombre, coeficientePuntos, coeficienteCO2)
    VALUES (p_nombre, p_coefpuntos, p_coefco2);
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