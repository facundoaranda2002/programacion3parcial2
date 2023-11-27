<?php
require_once './models/Retiro.php';
require_once './models/Cuenta.php';

class RetiroController extends Retiro
{
  public function CargarUno($request, $response, $args) // POST : nroDeCuenta tipoDeCuenta monto
  {
    $parametros = $request->getParsedBody();

    $nroDeCuenta = $parametros['nroDeCuenta'];
    $fecha = date("Y-m-d");
    $monto = $parametros['monto'];
    $tipoDeCuenta = $parametros['tipoDeCuenta'];
    $estaActivo = true;

    
    $cuenta = Cuenta::obtenerCuentaTipoYNumero($nroDeCuenta, $tipoDeCuenta);

    if($cuenta)
    {
      if($cuenta->saldo >= $monto)
      {
        $retiro = new Retiro();
        $retiro->nroDeCuenta = $nroDeCuenta;
        $retiro->tipoDeCuenta = $tipoDeCuenta;
        $retiro->fecha = $fecha;
        $retiro->monto = $monto;
        $retiro->estaActivo = $estaActivo;
        $retiro->nombre = $cuenta->nombre;
        $retiro->apellido = $cuenta->apellido;
        $retiro->tipoDocumento = $cuenta->tipoDocumento;
        $retiro->numeroDocumento = $cuenta->numeroDocumento;
        $retiro->email = $cuenta->email;
        $retiro->saldo = $cuenta->saldo;

        $retiro->crearRetiro();
        $cuenta->saldo -= $retiro->monto;
        $cuenta->modificarMontoCuenta();
        $payload = json_encode(array("mensaje" => "Retiro creado con exito"));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "Saldo insuficiente"));
      }
    }
    else
    {
      $payload = json_encode(array("mensaje" => "No existe esa cuenta"));
    }
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
  public function TraerUno($request, $response, $args) // GET :  nroDeRetiro
  {
    $nroDeRetiro = $args['nroDeRetiro'];

    $retiro = Retiro::obtenerRetiro($nroDeRetiro);
    $payload = json_encode($retiro);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
  public function TraerTodos($request, $response, $args) // GET 
  {
    
    $lista = Retiro::obtenerTodos();
    $payload = json_encode(array("listaRetiro" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ConsultarMovimientoA($request, $response, $args) // GET tipoDeCuenta fecha
  {
    $parametrosParam = $request->getQueryParams();
    $tipoDeCuenta = $parametrosParam['tipoDeCuenta'];
    $fecha = $parametrosParam['fecha'];

    if (!isset($fecha)) 
    {
        $fecha = date('Y-m-d', strtotime('-1 day'));
    } 

    $montoTotal = 0;

    $lista = Retiro::consultaMovimientosA($tipoDeCuenta, $fecha);
    foreach($lista as $retiro)
    {
      $montoTotal += $retiro->monto;
    }
    $payload = json_encode(array("Total retirado" => $montoTotal));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
  public function ConsultarMovimientoB($request, $response, $args) // GET tipoDeCuenta nroDeCuenta
  {
    $parametrosParam = $request->getQueryParams();
    $tipoDeCuenta = $parametrosParam['tipoDeCuenta'];
    $nroDeCuenta = $parametrosParam['nroDeCuenta'];

    $lista = Retiro::consultaMovimientosB($tipoDeCuenta, $nroDeCuenta);

    $payload = json_encode(array("listaRetiros" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
  public function ConsultarMovimientoC($request, $response, $args) // GET fechaInicio fechaFin
  {
    $parametrosParam = $request->getQueryParams();
    $fechaInicio = $parametrosParam['fechaInicio'];
    $fechaFin = $parametrosParam['fechaFin'];

    $lista = Retiro::consultaMovimientosC($fechaInicio, $fechaFin);

    $payload = json_encode(array("listaRetiros" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
  public function ConsultarMovimientoD($request, $response, $args) // GET tipoDeCuenta
  {
    $parametrosParam = $request->getQueryParams();
    $tipoDeCuenta = $parametrosParam['tipoDeCuenta'];

    $lista = Retiro::consultaMovimientosD($tipoDeCuenta);

    $payload = json_encode(array("listaRetiros" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
  public function ConsultarMovimientoE($request, $response, $args) // GET moneda
  {
    $parametrosParam = $request->getQueryParams();
    $moneda = $parametrosParam['moneda'];
    $ultima_letra = $moneda[strlen($moneda) - 1];

    $lista = Retiro::consultaMovimientosE($ultima_letra);

    $payload = json_encode(array("listaDeposito" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

}