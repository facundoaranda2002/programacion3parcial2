<?php
class Cuenta
{
    public $nombre;
    public $apellido;
    public $tipoDocumento;
    public $numeroDocumento;
    public $email;
    public $tipoDeCuenta;
    public $saldo;
    public $nroDeCuenta;
    public $estaActivo;  

    //Base de datos
    public function crearCuenta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO cuentas (nombre, apellido, tipoDocumento, numeroDocumento, email, tipoDeCuenta, saldo, estaActivo) VALUES (:nombre, :apellido, :tipoDocumento, :numeroDocumento, :email, :tipoDeCuenta, :saldo, :estaActivo)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':tipoDocumento', $this->tipoDocumento, PDO::PARAM_STR);
        $consulta->bindValue(':numeroDocumento', $this->numeroDocumento, PDO::PARAM_INT);
        $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
        $consulta->bindValue(':tipoDeCuenta', $this->tipoDeCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':saldo', $this->saldo, PDO::PARAM_INT);
        $consulta->bindValue(':estaActivo', $this->estaActivo, PDO::PARAM_BOOL);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }
    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM cuentas WHERE estaActivo = true");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Cuenta');
    }
    public static function obtenerCuenta($nroDeCuenta)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM cuentas WHERE nroDeCuenta = :nroDeCuenta AND estaActivo = true");
        $consulta->bindValue(':nroDeCuenta', $nroDeCuenta, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Cuenta');
    }

    public function ModificarMontoCuenta()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE cuentas SET saldo = :saldo WHERE nombre = :nombre AND apellido = :apellido AND tipoDeCuenta = :tipoDeCuenta AND estaActivo = true");
        $consulta->bindValue(':tipoDeCuenta', $this->tipoDeCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':saldo', $this->saldo, PDO::PARAM_INT);
        $consulta->execute();
    }

    public function ModificarCuenta()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE cuentas SET nombre = :nombre, apellido = :apellido, tipoDocumento = :tipoDocumento, numeroDocumento = :numeroDocumento, email = :email WHERE nroDeCuenta = :nroDeCuenta AND tipoDeCuenta = :tipoDeCuenta");
        $consulta->bindValue(':tipoDeCuenta', $this->tipoDeCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':nroDeCuenta', $this->nroDeCuenta, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':tipoDocumento', $this->tipoDocumento, PDO::PARAM_STR);
        $consulta->bindValue(':numeroDocumento', $this->numeroDocumento, PDO::PARAM_INT);
        $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
        $consulta->execute();
    }
    public function EliminarCuenta()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE cuentas SET estaActivo = :estaActivo WHERE nroDeCuenta = :nroDeCuenta AND tipoDeCuenta = :tipoDeCuenta");
        $consulta->bindValue(':estaActivo', $this->estaActivo, PDO::PARAM_BOOL);
        $consulta->bindValue(':tipoDeCuenta', $this->tipoDeCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':nroDeCuenta', $this->nroDeCuenta, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function obtenerCuentaTipoYNumero($nroDeCuenta, $tipoDeCuenta)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM cuentas WHERE nroDeCuenta = :nroDeCuenta AND tipoDeCuenta = :tipoDeCuenta AND estaActivo = true");
        $consulta->bindValue(':nroDeCuenta', $nroDeCuenta, PDO::PARAM_INT);
        $consulta->bindValue(':tipoDeCuenta', $tipoDeCuenta, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Cuenta');
    }

    public static function IsMismoCliente($cliente1 , $cliente2)
    {
        $exito = false;
        if($cliente1->nombre == $cliente2->nombre && $cliente1->apellido == $cliente2->apellido && $cliente1->tipoDeCuenta == $cliente2->tipoDeCuenta)
        {
            $exito = true;
        }
        return $exito;
    }

    
    

}
?>