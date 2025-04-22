-- Triggers de logs

-- PARA MATERIAL

DELIMITER $$

CREATE TRIGGER trg_material_insert
AFTER INSERT ON Material
FOR EACH ROW
BEGIN
    INSERT INTO log_material (accion, id_material, nombre_new, coef_puntos_new, coef_co2_new)
    VALUES ('INSERT', NEW.idMaterial, NEW.nombre, NEW.coeficientePuntos, NEW.coeficienteCO2);
END $$

CREATE TRIGGER trg_material_update
AFTER UPDATE ON Material
FOR EACH ROW
BEGIN
    INSERT INTO log_material (accion, id_material, nombre_old, nombre_new, coef_puntos_old, coef_puntos_new, coef_co2_old, coef_co2_new)
    VALUES ('UPDATE', OLD.idMaterial, OLD.nombre, NEW.nombre, OLD.coeficientePuntos, NEW.coeficientePuntos, OLD.coeficienteCO2, NEW.coeficienteCO2);
END $$

CREATE TRIGGER trg_material_delete
AFTER DELETE ON Material
FOR EACH ROW
BEGIN
    INSERT INTO log_material (accion, id_material, nombre_old, coef_puntos_old, coef_co2_old)
    VALUES ('DELETE', OLD.idMaterial, OLD.nombre, OLD.coeficientePuntos, OLD.coeficienteCO2);
END $$

DELIMITER ;

-- PARA RECOMPENSA

DELIMITER $$

CREATE TRIGGER trg_recompensa_insert
AFTER INSERT ON Recompensa
FOR EACH ROW
BEGIN
    INSERT INTO log_recompensa (accion, id_recompensa, nombre_new, puntos_new, nivel_req_new)
    VALUES ('INSERT', NEW.idRecompensa, NEW.nombre, NEW.puntosNecesarios, NEW.nivelRequerido);
END $$

CREATE TRIGGER trg_recompensa_update
AFTER UPDATE ON Recompensa
FOR EACH ROW
BEGIN
    INSERT INTO log_recompensa (accion, id_recompensa, nombre_old, nombre_new, puntos_old, puntos_new, nivel_req_old, nivel_req_new)
    VALUES ('UPDATE', OLD.idRecompensa, OLD.nombre, NEW.nombre, OLD.puntosNecesarios, NEW.puntosNecesarios, OLD.nivelRequerido, NEW.nivelRequerido);
END $$

CREATE TRIGGER trg_recompensa_delete
AFTER DELETE ON Recompensa
FOR EACH ROW
BEGIN
    INSERT INTO log_recompensa (accion, id_recompensa, nombre_old, puntos_old, nivel_req_old)
    VALUES ('DELETE', OLD.idRecompensa, OLD.nombre, OLD.puntosNecesarios, OLD.nivelRequerido);
END $$

DELIMITER ;


-- PARA NIVEL

DELIMITER $$

CREATE TRIGGER trg_nivel_insert
AFTER INSERT ON Nivel
FOR EACH ROW
BEGIN
    INSERT INTO log_nivel (accion, id_nivel, nivel_new, nombre_new, puntos_new)
    VALUES ('INSERT', NEW.idNivel, NEW.nivel, NEW.nombre, NEW.puntosTotalesNecesarios);
END $$

CREATE TRIGGER trg_nivel_update
AFTER UPDATE ON Nivel
FOR EACH ROW
BEGIN
    INSERT INTO log_nivel (accion, id_nivel, nivel_old, nivel_new, nombre_old, nombre_new, puntos_old, puntos_new)
    VALUES ('UPDATE', OLD.idNivel, OLD.nivel, NEW.nivel, OLD.nombre, NEW.nombre, OLD.puntosTotalesNecesarios, NEW.puntosTotalesNecesarios);
END $$

CREATE TRIGGER trg_nivel_delete
AFTER DELETE ON Nivel
FOR EACH ROW
BEGIN
    INSERT INTO log_nivel (accion, id_nivel, nivel_old, nombre_old, puntos_old)
    VALUES ('DELETE', OLD.idNivel, OLD.nivel, OLD.nombre, OLD.puntosTotalesNecesarios);
END $$

DELIMITER ;


-- PARA CANJE

DELIMITER $$

CREATE TRIGGER trg_canje_insert
AFTER INSERT ON Canje
FOR EACH ROW
BEGIN
    INSERT INTO log_canje (accion, id_canje, id_usuario_new, id_recompensa_new, fecha_new)
    VALUES ('INSERT', NEW.idCanje, NEW.idUsuario, NEW.idRecompensa, NEW.fecha);
END $$

CREATE TRIGGER trg_canje_update
AFTER UPDATE ON Canje
FOR EACH ROW
BEGIN
    INSERT INTO log_canje (accion, id_canje, id_usuario_old, id_usuario_new, id_recompensa_old, id_recompensa_new, fecha_old, fecha_new)
    VALUES ('UPDATE', OLD.idCanje, OLD.idUsuario, NEW.idUsuario, OLD.idRecompensa, NEW.idRecompensa, OLD.fecha, NEW.fecha);
END $$

CREATE TRIGGER trg_canje_delete
AFTER DELETE ON Canje
FOR EACH ROW
BEGIN
    INSERT INTO log_canje (accion, id_canje, id_usuario_old, id_recompensa_old, fecha_old)
    VALUES ('DELETE', OLD.idCanje, OLD.idUsuario, OLD.idRecompensa, OLD.fecha);
END $$

DELIMITER ;


-- PARA PUNTO RECICLAJE

DELIMITER $$

CREATE TRIGGER trg_punto_insert
AFTER INSERT ON Punto_Reciclaje
FOR EACH ROW
BEGIN
    INSERT INTO log_punto_reciclaje (accion, id_punto, nombre_new, coord_new, apertura_new, cierre_new)
    VALUES ('INSERT', NEW.idPunto, NEW.nombre, NEW.coordenadas, NEW.apertura, NEW.cierre);
END $$

CREATE TRIGGER trg_punto_update
AFTER UPDATE ON Punto_Reciclaje
FOR EACH ROW
BEGIN
    INSERT INTO log_punto_reciclaje (accion, id_punto, nombre_old, nombre_new, coord_old, coord_new, apertura_old, apertura_new, cierre_old, cierre_new)
    VALUES ('UPDATE', OLD.idPunto, OLD.nombre, NEW.nombre, OLD.coordenadas, NEW.coordenadas, OLD.apertura, NEW.apertura, OLD.cierre, NEW.cierre);
END $$

CREATE TRIGGER trg_punto_delete
AFTER DELETE ON Punto_Reciclaje
FOR EACH ROW
BEGIN
    INSERT INTO log_punto_reciclaje (accion, id_punto, nombre_old, coord_old, apertura_old, cierre_old)
    VALUES ('DELETE', OLD.idPunto, OLD.nombre, OLD.coordenadas, OLD.apertura, OLD.cierre);
END $$

DELIMITER ;


