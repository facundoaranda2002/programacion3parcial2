<?php
require_once './models/Deposito.php';
require_once './models/Cuenta.php';
require_once './models/logtransacciones.php';

class DepositoController extends Deposito 

{
  public function CargarUno($request, $response, $args) // POST : nroDeCuenta fecha monto archivo
  {
    $parametros = $request->getParsedBody();

    $tipoDeCuenta = $parametros['tipoDeCuenta'];
    $nroDeCuenta = $parametros['nroDeCuenta'];
    $fecha= date('Y-m-d');
    $monto = $parametros['monto'];
    $estaActivo = true;

    $cuenta = Cuenta::obtenerCuentaTipoYNumero($nroDeCuenta, $tipoDeCuenta);
    if($cuenta)
    {
      $deposito = new Deposito();
      $deposito->nroDeCuenta = $nroDeCuenta;
      $deposito->tipoDeCuenta = $tipoDeCuenta;
      $deposito->fecha = $fecha;
      $deposito->monto = $monto;
      $deposito->estaActivo = $estaActivo;
      $deposito->nombre = $cuenta->nombre;
      $deposito->apellido = $cuenta->apellido;
      $deposito->tipoDocumento = $cuenta->tipoDocumento;
      $deposito->numeroDocumento = $cuenta->numeroDocumento;
      $deposito->email = $cuenta->email;
      $deposito->saldo = $cuenta->saldo;
      $id = $deposito->crearDeposito();

      $carpeta_archivos = 'img/ImagenesDeDepositos/2023/';
      $nombre_archivo = $deposito->tipoDeCuenta . $deposito->nroDeCuenta . $id;
      $ruta_destino = $carpeta_archivos . $nombre_archivo . ".jpg";
      if(move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_destino)) 
      {  
        $payload = json_encode(array("mensaje" => "Deposito creado con exito"));
        $cuenta->saldo += $deposito->monto;
        $cuenta->ModificarMontoCuenta();
        /*
        $logtransacciones=new logtransacciones();
        $logtransacciones->fecha = $deposito->fecha;
        $logtransacciones->idUsuario = $deposito->nroDeCuenta;
        $logtransacciones->nroTransaccion = $deposito->nroDeDeposito;
        $logtransacciones->crearTransaccion();
        */
      } 
      else 
      {
        $payload = json_encode(array("mensaje" => "Error Foto"));
      }
    }
    else
    {
      $payload = json_encode(array("mensaje" => "No existe la cuenta a depositar"));
    }
    
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args) // GET :  nroDeDeposito
  {
    $nroDeDeposito = $args['nroDeDeposito'];

    $deposito = Deposito::obtenerDeposito($nroDeDeposito);
    $payload = json_encode($deposito);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args) // GET 
  {
    $lista = Deposito::obtenerTodos();
    $payload = json_encode(array("listaDeposito" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ConsultarMovimientoA($request, $response, $args) // GET 
  {
    $parametrosParam = $request->getQueryParams();
    $tipoDeCuenta = $parametrosParam['tipoDeCuenta'];
    $fecha = $parametrosParam['fecha'];

    if (!isset($fecha)) {
      $fecha = date('Y-m-d', strtotime('-1 day'));
    } 

    $montoTotal = 0;

    $lista = Deposito::consultaMovimientosA($tipoDeCuenta, $fecha);
    foreach($lista as $deposito)
    {
      $montoTotal += $deposito->monto;
    }
    $payload = json_encode(array("Total depositado" => $montoTotal));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ConsultarMovimientoB($request, $response, $args) // GET 
  {
    $parametrosParam = $request->getQueryParams();
    $tipoDeCuenta = $parametrosParam['tipoDeCuenta'];
    $nroDeCuenta = $parametrosParam['nroDeCuenta'];

    $lista = Deposito::consultaMovimientosB($tipoDeCuenta, $nroDeCuenta);

    $payload = json_encode(array("listaDeposito" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ConsultarMovimientoC($request, $response, $args) // GET 
  {
    $parametrosParam = $request->getQueryParams();
    $fechaInicio = $parametrosParam['fechaInicio'];
    $fechaFin = $parametrosParam['fechaFin'];

    $lista = Deposito::consultaMovimientosC($fechaInicio, $fechaFin);

    $payload = json_encode(array("listaDeposito" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ConsultarMovimientoD($request, $response, $args) // GET 
  {
    $parametrosParam = $request->getQueryParams();
    $tipoDeCuenta = $parametrosParam['tipoDeCuenta'];

    $lista = Deposito::consultaMovimientosD($tipoDeCuenta);

    $payload = json_encode(array("listaDeposito" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ConsultarMovimientoE($request, $response, $args) // GET 
  {
    $parametrosParam = $request->getQueryParams();
    $moneda = $parametrosParam['moneda'];
    $ultima_letra = $moneda[strlen($moneda) - 1];

    $lista = Deposito::consultaMovimientosE($ultima_letra);

    $payload = json_encode(array("listaDeposito" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }


}