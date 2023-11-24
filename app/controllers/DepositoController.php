<?php
require_once './models/Deposito.php';
require_once './models/Cuenta.php';

class DepositoController extends Deposito 

{
  public function CargarUno($request, $response, $args) // POST : nroDeCuenta fecha monto archivo
  {
    $parametros = $request->getParsedBody();

    $tipoDeCuenta = $parametros['tipoDeCuenta'];
    $nroDeCuenta = $parametros['nroDeCuenta'];
    $fecha = new DateTime();
    $fecha->format('Y-m-d');
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
        $cuenta->modificarMontoCuenta();
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
      $fecha = New Datetime();
      $fecha->sub(new DateInterval('P1D'));
      $fecha->format('d-m-Y');
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

    $lista = Deposito::consultaMovimientosE($moneda);

    $payload = json_encode(array("listaDeposito" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }


  public function TraerUnoTipoYCuenta($request, $response, $args) // POST :  nroDeCuenta tipoDeCuenta
  {
    $parametros = $request->getParsedBody();

    $nroDeCuenta = $parametros['nroDeCuenta'];
    $tipoDeCuenta = $parametros['tipoDeCuenta'];

    $cuenta = Cuenta::obtenerCuentaTipoYNumero($nroDeCuenta,$tipoDeCuenta);
    if($cuenta)
    {
      $payload = json_encode($cuenta);
      $payload = json_encode(array("TipoDeCuenta" => $cuenta->tipoDeCuenta,"Saldo" => $cuenta->saldo));
    }
    else if(Cuenta::obtenerCuenta($nroDeCuenta))
    {
      $payload = json_encode(array("mensaje" => "Tipo de cuenta incorrecto"));
    }
    else
    {
      $payload = json_encode(array("mensaje" => "No existe cuenta"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  // public function TraerTodosEstado($request, $response, $args) // GET 
  // {
  //   $parametrosParam = $request->getQueryParams();
  //   $estado = $parametrosParam['estado'];

  //   $lista = Mesa::obtenerTodosEstado($estado);
  //   $payload = json_encode(array("listaMesa" => $lista));

  //   $response->getBody()->write($payload);
  //   return $response
  //     ->withHeader('Content-Type', 'application/json');
  // }
  // public function ModificarUno($request, $response, $args) // POST 
  // {
  //   $parametros = $request->getParsedBody();

  //   $idMesa = $parametros['idMesa'];
  //   $estado = $parametros['estado'];

  //   Mesa::modificarMesa($idMesa, $estado);

  //   $payload = json_encode(array("mensaje" => "Mesa modificada con exito"));

  //   $response->getBody()->write($payload);
  //   return $response
  //     ->withHeader('Content-Type', 'application/json');
  // }
  // public function BorrarUno($request, $response, $args) // POST 
  // {

  //   $idMesa = $args['idMesa'];
  //   Mesa::borrarMesa($idMesa);

  //   $payload = json_encode(array("mensaje" => "Mesa borrado con exito"));

  //   $response->getBody()->write($payload);
  //   return $response
  //     ->withHeader('Content-Type', 'application/json');
  // }
  // public function GuardarCSV($request, $response, $args) // GET
  // {

  //   if($archivo = fopen("csv/mesas.csv", "w"))
  //   {
  //     $lista = Mesa::obtenerTodos();
  //     foreach( $lista as $mesa )
  //     {
  //         fputcsv($archivo, [$mesa->idMesa, $mesa->estado]);
  //     }
  //     fclose($archivo);
  //     $payload =  json_encode(array("mensaje" => "La lista de mesas se guardo correctamente"));
  //   }
  //   else
  //   {
  //     $payload =  json_encode(array("mensaje" => "No se pudo abrir el archivo de mesas"));
  //   }

  //   $response->getBody()->write($payload);
  //   return $response
  //     ->withHeader('Content-Type', 'application/json');
  // }
  // public function CargarCSV($request, $response, $args) // GET
  // {
  //   if(($archivo = fopen("csv/mesas.csv", "r")) !== false)
  //   {
  //     Mesa::borrarMesas();
  //     while (($filaMesa = fgetcsv($archivo, 0, ',')) !== false)
  //     {
  //       $nuevaMesa = new Mesa();
  //       $nuevaMesa->idMesa = $filaMesa[0];
  //       $nuevaMesa->estado = $filaMesa[1];
  //       $nuevaMesa->crearMesaCSV();
  //     }
  //     fclose($archivo);
  //     $payload =  json_encode(array("mensaje" => "Las mesas se cargaron correctamente"));
  //   }
  //   else
  //   {
  //     $payload =  json_encode(array("mensaje" => "No se pudo leer el archivo de mesas"));
  //   }

  //   $response->getBody()->write($payload);
  //   return $response
  //     ->withHeader('Content-Type', 'application/json');
  // }

}