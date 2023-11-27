<?php


class Deposito
{
    public $nroDeDeposito;
    public $fecha;
    public $monto;
    public $nroDeCuenta;
    public $tipoDeCuenta; 
    public $nombre;
    public $apellido;
    public $tipoDocumento;
    public $numeroDocumento;
    public $email;
    public $saldo;
    public $estaActivo;

    public function crearDeposito()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO depositos (nroDeCuenta, tipoDeCuenta, fecha, monto, estaActivo, nombre, apellido, tipoDocumento, numeroDocumento, email, saldo) VALUES (:nroDeCuenta, :tipoDeCuenta, :fecha, :monto, :estaActivo, :nombre, :apellido, :tipoDocumento, :numeroDocumento, :email, :saldo)");


        $consulta->bindValue(':nroDeCuenta', $this->nroDeCuenta, PDO::PARAM_INT);
        $consulta->bindValue(':tipoDeCuenta', $this->tipoDeCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->bindValue(':monto', $this->monto, PDO::PARAM_INT);
        $consulta->bindValue(':estaActivo', $this->estaActivo, PDO::PARAM_BOOL);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':tipoDocumento', $this->tipoDocumento, PDO::PARAM_STR);
        $consulta->bindValue(':numeroDocumento', $this->numeroDocumento, PDO::PARAM_INT);
        $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
        $consulta->bindValue(':saldo', $this->saldo, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }
    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM depositos WHERE estaActivo = true");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Deposito');
    }
    public static function obtenerDeposito($nroDeDeposito)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM depositos WHERE nroDeDeposito = :nroDeDeposito AND estaActivo = true");
        $consulta->bindValue(':nroDeDeposito', $nroDeDeposito, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Deposito');
    }

    public static function consultaMovimientosA($tipoDeCuenta, $fecha)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM depositos WHERE tipoDeCuenta = :tipoDeCuenta AND fecha = :fecha AND estaActivo = true");
        $consulta->bindValue(':tipoDeCuenta', $tipoDeCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Deposito');
    }

    public static function consultaMovimientosB($tipoDeCuenta, $nroDeCuenta)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM depositos WHERE tipoDeCuenta = :tipoDeCuenta AND nroDeCuenta = :nroDeCuenta AND estaActivo = true");
        $consulta->bindValue(':tipoDeCuenta', $tipoDeCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':nroDeCuenta', $nroDeCuenta, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Deposito');
    }

    public static function consultaMovimientosC($fechaInicio, $fechaFin)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM depositos WHERE fecha BETWEEN :fechaInicio AND :fechaFin AND estaActivo = true ORDER BY nombre");
        $consulta->bindValue(':fechaInicio', $fechaInicio, PDO::PARAM_STR);
        $consulta->bindValue(':fechaFin', $fechaFin, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Deposito');
    }

    public static function consultaMovimientosD($tipoDeCuenta)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM depositos WHERE tipoDeCuenta = :tipoDeCuenta AND estaActivo = true");
        $consulta->bindValue(':tipoDeCuenta', $tipoDeCuenta, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Deposito');
    }

    public static function consultaMovimientosE($moneda)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM depositos WHERE SUBSTRING(tipoDeCuenta, CHAR_LENGTH(tipoDeCuenta), 1) = :letra AND estaActivo = true");
        $consulta->bindValue(':letra', $moneda, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Deposito');
    }

    public function ModificarDeposito()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE depositos SET nombre = :nombre, apellido = :apellido, tipoDocumento = :tipoDocumento, numeroDocumento = :numeroDocumento, email = :email WHERE nroDeCuenta = :nroDeCuenta AND tipoDeCuenta = :tipoDeCuenta");
        $consulta->bindValue(':tipoDeCuenta', $this->tipoDeCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':nroDeCuenta', $this->nroDeCuenta, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':tipoDocumento', $this->tipoDocumento, PDO::PARAM_STR);
        $consulta->bindValue(':numeroDocumento', $this->numeroDocumento, PDO::PARAM_INT);
        $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
        $consulta->execute();
    }

    public function EliminarDeposito()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE depositos SET estaActivo = :estaActivo WHERE nroDeCuenta = :nroDeCuenta AND tipoDeCuenta = :tipoDeCuenta");
        $consulta->bindValue(':estaActivo', $this->estaActivo, PDO::PARAM_BOOL);
        $consulta->bindValue(':tipoDeCuenta', $this->tipoDeCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':nroDeCuenta', $this->nroDeCuenta, PDO::PARAM_INT);
        $consulta->execute();
    }

    
}