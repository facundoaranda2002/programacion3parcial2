<?php

class Usuario
{
    public $rol;
    public $nombreUsuario;
    public $password;
    public $idUsuario;

    public function crearUsuario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (rol, nombreUsuario, password) VALUES ( :rol, :nombreUsuario, :password)");
        

        $consulta->bindValue(':rol', $this->rol, PDO::PARAM_STR);

        $consulta->bindValue(':nombreUsuario', $this->nombreUsuario, PDO::PARAM_STR);

        $consulta->bindValue(':password', $this->password, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    /*
    public function crearUsuarioCSV()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (sueldo, rol, fechaIngreso, nombreUsuario, password, idUsuario) VALUES (:sueldo, :rol, :fechaIngreso, :nombreUsuario, :password, :idUsuario)");
        
        $consulta->bindValue(':sueldo', $this->sueldo, PDO::PARAM_INT);
        $consulta->bindValue(':rol', $this->rol, PDO::PARAM_STR);
        $consulta->bindValue(':fechaIngreso', $this->fechaIngreso, PDO::PARAM_STR); 
        $consulta->bindValue(':nombreUsuario', $this->nombreUsuario, PDO::PARAM_STR);
        $consulta->bindValue(':password', $this->password, PDO::PARAM_STR);
        $consulta->bindValue(':idUsuario', $this->idUsuario, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }
    */

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    /*
    public static function obtenerUsuario($idUsuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT sueldo, rol, fechaIngreso, nombreUsuario, idUsuario, password FROM usuarios WHERE idUsuario = :idUsuario");
        $consulta->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }
    */

    /*
    public static function modificarUsuario($sueldo, $rol, $fechaIngreso, $nombreUsuario, $password, $idUsuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET sueldo = :sueldo, rol = :rol, fechaIngreso = :fechaIngreso, nombreUsuario = :nombreUsuario, password = :password WHERE idUsuario = :idUsuario");
        $consulta->bindValue(':sueldo', $sueldo, PDO::PARAM_STR);
        $consulta->bindValue(':rol', $rol, PDO::PARAM_INT);
        $consulta->bindValue(':fechaIngreso', $fechaIngreso, PDO::PARAM_STR);
        $consulta->bindValue(':nombreUsuario', $nombreUsuario, PDO::PARAM_STR);
        $consulta->bindValue(':password', $password, PDO::PARAM_STR);
        $consulta->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        //$consulta->bindValue(':idProducto', $idProducto, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarUsuario($idUsuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM usuarios WHERE idUsuario = :idUsuario");
        $consulta->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarUsuarios()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("TRUNCATE usuarios");
        $consulta->execute();
    }
    */


}