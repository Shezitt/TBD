USE reciclaje;
DELIMITER //
CREATE PROCEDURE GenerarReporteImpactoAmbiental()
BEGIN
    SELECT 
        Material.nombre AS tipo_material,
        SUM(Registro_Reciclaje.cantidad) AS total_reciclado_kg,
        SUM(Registro_Reciclaje.impactoCO2) AS total_co2_reducido
    FROM Registro_Reciclaje
    JOIN Material ON Registro_Reciclaje.idMaterial = Material.idMaterial
    GROUP BY Material.nombre;
END //
DELIMITER ;
