-- Registro_Reciclaje

CREATE INDEX idx_fecha ON Registro_Reciclaje (fecha);
CREATE INDEX idx_usuario_material ON Registro_Reciclaje (idUsuario, idMaterial);


