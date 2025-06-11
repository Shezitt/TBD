-- MATERIAL
SELECT * FROM Material;

ALTER TABLE Material ADD COLUMN coeficienteAgua DECIMAL(10,2) AFTER coeficienteCO2;
ALTER TABLE Material ADD COLUMN coeficienteEnergia DECIMAL(10,2) AFTER coeficienteAgua;

UPDATE Material SET coeficienteAgua = 2500 WHERE idMaterial=1;
UPDATE Material SET coeficienteAgua = 25 WHERE idMaterial=2;
UPDATE Material SET coeficienteAgua = 5 WHERE idMaterial=3;
UPDATE Material SET coeficienteAgua = 5 WHERE idMaterial=4;
UPDATE Material SET coeficienteAgua = 50 WHERE idMaterial=5;

UPDATE Material SET coeficienteEnergia = 2.0 WHERE idMaterial=1;
UPDATE Material SET coeficienteEnergia = 6.0 WHERE idMaterial=2;
UPDATE Material SET coeficienteEnergia = 0.4 WHERE idMaterial=3;
UPDATE Material SET coeficienteEnergia = 1.3 WHERE idMaterial=4;
UPDATE Material SET coeficienteEnergia = 0.2 WHERE idMaterial=5;


-- REGISTRO RECICLAJE
SELECT * FROM Registro_Reciclaje;

ALTER TABLE Registro_Reciclaje ADD COLUMN impactoAgua DECIMAL(10,2) AFTER impactoCO2;
ALTER TABLE Registro_Reciclaje ADD COLUMN impactoEnergia DECIMAL(10,2) AFTER impactoAgua;