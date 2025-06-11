-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema reciclaje
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema reciclaje
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `reciclaje` DEFAULT CHARACTER SET utf8 ;
USE `reciclaje` ;

-- -----------------------------------------------------
-- Table `reciclaje`.`Rol`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reciclaje`.`Rol` (
  `idRol` INT NOT NULL AUTO_INCREMENT,
  `nombreRol` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idRol`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reciclaje`.`Nivel`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reciclaje`.`Nivel` (
  `idNivel` INT NOT NULL AUTO_INCREMENT,
  `nivel` INT NOT NULL,
  `nombre` VARCHAR(45) NOT NULL,
  `puntosTotalesNecesarios` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`idNivel`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reciclaje`.`Usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reciclaje`.`Usuario` (
  `idUsuario` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NOT NULL,
  `nombre` VARCHAR(45) NOT NULL,
  `correo` VARCHAR(45) NOT NULL,
  `password` VARCHAR(60) NOT NULL,
  `puntos` DECIMAL(10,2) NOT NULL,
  `puntosTotal` DECIMAL(10,2) NOT NULL,
  `fechaRegistro` DATETIME NOT NULL,
  `idRol` INT NOT NULL,
  `idNivel` INT NOT NULL,
  PRIMARY KEY (`idUsuario`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  INDEX `fk_Usuario_Rol1_idx` (`idRol` ASC),
  INDEX `fk_Usuario_Nivel1_idx` (`idNivel` ASC),
  UNIQUE INDEX `correo_UNIQUE` (`correo` ASC),
  CONSTRAINT `fk_Usuario_Rol1`
    FOREIGN KEY (`idRol`)
    REFERENCES `reciclaje`.`Rol` (`idRol`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Usuario_Nivel1`
    FOREIGN KEY (`idNivel`)
    REFERENCES `reciclaje`.`Nivel` (`idNivel`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reciclaje`.`Punto_Reciclaje`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reciclaje`.`Punto_Reciclaje` (
  `idPunto` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `latitud` DECIMAL(9,6) NOT NULL,
  `longitud` DECIMAL(9,6) NOT NULL,
  `apertura` TIME NOT NULL,
  `cierre` TIME NOT NULL,
  `activo` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`idPunto`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reciclaje`.`Material`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reciclaje`.`Material` (
  `idMaterial` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `coeficientePuntos` DECIMAL(10,2) NOT NULL,
  `coeficienteCO2` DECIMAL(10,2) NOT NULL,
  `activo` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`idMaterial`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reciclaje`.`Registro_Reciclaje`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reciclaje`.`Registro_Reciclaje` (
  `idRegistro` INT NOT NULL AUTO_INCREMENT,
  `idUsuario` INT NOT NULL,
  `idMaterial` INT NOT NULL,
  `idPunto` INT NOT NULL,
  `cantidad` DECIMAL(10,2) NOT NULL,
  `fecha` DATETIME NOT NULL,
  `puntosGanados` DECIMAL(10,2) NOT NULL,
  `impactoCO2` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`idRegistro`),
  INDEX `fk_Registro_Reciclaje_Usuario_idx` (`idUsuario` ASC),
  INDEX `fk_Registro_Reciclaje_Material1_idx` (`idMaterial` ASC),
  INDEX `fk_Registro_Reciclaje_Punto_Reciclaje1_idx` (`idPunto` ASC),
  CONSTRAINT `fk_Registro_Reciclaje_Usuario`
    FOREIGN KEY (`idUsuario`)
    REFERENCES `reciclaje`.`Usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Registro_Reciclaje_Material1`
    FOREIGN KEY (`idMaterial`)
    REFERENCES `reciclaje`.`Material` (`idMaterial`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Registro_Reciclaje_Punto_Reciclaje1`
    FOREIGN KEY (`idPunto`)
    REFERENCES `reciclaje`.`Punto_Reciclaje` (`idPunto`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reciclaje`.`Catalogo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reciclaje`.`Catalogo` (
  `idCatalogo` INT NOT NULL AUTO_INCREMENT,
  `nombreCatalogo` VARCHAR(45) NOT NULL,
  `activo` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`idCatalogo`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reciclaje`.`Recompensa`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reciclaje`.`Recompensa` (
  `idRecompensa` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `puntosNecesarios` DECIMAL(10,2) NOT NULL,
  `nivelRequerido` INT NOT NULL,
  `activo` TINYINT NOT NULL DEFAULT 1,
  `idCatalogo` INT NOT NULL,
  PRIMARY KEY (`idRecompensa`),
  INDEX `fk_Recompensa_Catalogo1_idx` (`idCatalogo` ASC),
  CONSTRAINT `fk_Recompensa_Catalogo1`
    FOREIGN KEY (`idCatalogo`)
    REFERENCES `reciclaje`.`Catalogo` (`idCatalogo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reciclaje`.`Canje`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reciclaje`.`Canje` (
  `idCanje` INT NOT NULL AUTO_INCREMENT,
  `idUsuario` INT NOT NULL,
  `idRecompensa` INT NOT NULL,
  `fecha` DATETIME NOT NULL,
  `completado` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`idCanje`),
  INDEX `fk_Canje_Usuario1_idx` (`idUsuario` ASC),
  INDEX `fk_Canje_Recompensa1_idx` (`idRecompensa` ASC),
  CONSTRAINT `fk_Canje_Usuario1`
    FOREIGN KEY (`idUsuario`)
    REFERENCES `reciclaje`.`Usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Canje_Recompensa1`
    FOREIGN KEY (`idRecompensa`)
    REFERENCES `reciclaje`.`Recompensa` (`idRecompensa`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reciclaje`.`Permiso`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reciclaje`.`Permiso` (
  `idPermiso` INT NOT NULL AUTO_INCREMENT,
  `nombrePermiso` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idPermiso`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reciclaje`.`rol_has_permiso`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reciclaje`.`rol_has_permiso` (
  `Rol_idRol` INT NOT NULL,
  `Permiso_idPermiso` INT NOT NULL,
  PRIMARY KEY (`Rol_idRol`, `Permiso_idPermiso`),
  INDEX `fk_Rol_has_Permiso_Permiso1_idx` (`Permiso_idPermiso` ASC),
  INDEX `fk_Rol_has_Permiso_Rol1_idx` (`Rol_idRol` ASC),
  CONSTRAINT `fk_Rol_has_Permiso_Rol1`
    FOREIGN KEY (`Rol_idRol`)
    REFERENCES `reciclaje`.`Rol` (`idRol`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Rol_has_Permiso_Permiso1`
    FOREIGN KEY (`Permiso_idPermiso`)
    REFERENCES `reciclaje`.`Permiso` (`idPermiso`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reciclaje`.`Punto_Reciclaje_Materiales`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reciclaje`.`Punto_Reciclaje_Materiales` (
  `idPunto` INT NOT NULL,
  `idMaterial` INT NOT NULL,
  PRIMARY KEY (`idPunto`, `idMaterial`),
  INDEX `fk_Punto_Reciclaje_has_Material_Material1_idx` (`idMaterial` ASC),
  INDEX `fk_Punto_Reciclaje_has_Material_Punto_Reciclaje1_idx` (`idPunto` ASC),
  CONSTRAINT `fk_Punto_Reciclaje_has_Material_Punto_Reciclaje1`
    FOREIGN KEY (`idPunto`)
    REFERENCES `reciclaje`.`Punto_Reciclaje` (`idPunto`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Punto_Reciclaje_has_Material_Material1`
    FOREIGN KEY (`idMaterial`)
    REFERENCES `reciclaje`.`Material` (`idMaterial`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `reciclaje`.`Promocion`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `reciclaje`.`Promocion` (
  `idPromocion` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `multiplicador` DECIMAL(10,2) NOT NULL,
  `fechaInicio` DATE NOT NULL,
  `fechaFin` DATE NOT NULL,
  `activo` TINYINT NOT NULL DEFAULT 1,
  `nivelRequerido` INT NOT NULL DEFAULT 1,
  PRIMARY KEY (`idPromocion`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
