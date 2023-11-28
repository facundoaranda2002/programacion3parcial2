<?php
require_once './models/Usuario.php';

require_once './utils/AutentificadorJWT.php';

class UsuarioController extends Usuario 
{
    public function CargarUno($request, $response, $args) // POST : sueldo rol fechaIngreso nombreUsuario
    {
        $parametros = $request->getParsedBody();

        $rol = $parametros['rol'];

        $nombreUsuario = $parametros['nombreUsuario'];

        $password = $parametros['password']; 

        $user = new Usuario();

        $user->rol = $rol;

        $user->nombreUsuario = $nombreUsuario;

        $user->password = $password;

        $id = $user->crearUsuario();

        $payload = json_encode(array("mensaje" => "Usuario creado con exito", "id" => $id));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    /*
    public function TraerUno($request, $response, $args) // GET :  idUsuario
    {
        $idUsuario = $args['idUsuario']; 

        $user = Usuario::obtenerUsuario($idUsuario);
        $payload = json_encode($user);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    */

    public function TraerTodos($request, $response, $args) // GET 
    {
        $lista = Usuario::obtenerTodos();
        $payload = json_encode(array("listaUsuarios" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    /*
    public function ModificarUno($request, $response, $args) // PUT  sueldo nombreUsuario rol fechaIngreso idProducto
    {
        $parametros = $request->getParsedBody();

        $sueldo = $parametros['sueldo'];
        $nombreUsuario = $parametros['nombreUsuario'];
        $rol = $parametros['rol'];
        $fechaIngreso = $parametros['fechaIngreso'];
        //$idProducto = $parametros['idProducto'];
        $password = $parametros['password'];
        $idUsuario = $args['idUsuario'];

        Usuario::modificarUsuario($sueldo, $rol, $fechaIngreso, $nombreUsuario, $password, $idUsuario);

        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }


    public function BorrarUno($request, $response, $args) // DELETE idUsuario
    {

        $idUsuario = $args['idUsuario'];
        Usuario::borrarUsuario($idUsuario);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    */

    public function LoginUsuario($request, $response, $args) // POST nombreUsuario password
    {
      $parametros = $request->getParsedBody();

      $nombreUsuario = $parametros['nombreUsuario'];
      $password = $parametros['password'];

      $existe = false;
      $lista = Usuario::obtenerTodos();

      foreach ($lista as $usuario) 
      {
        if($usuario->nombreUsuario == $nombreUsuario && $usuario->password == $password)
        {
          $existe = true;
          $idUsuario = $usuario->idUsuario;
          $rol = $usuario->rol;
          $nombreUsuario = $usuario->nombreUsuario;
          $password = $usuario->password;
          break;
        }
      }
      if($existe)
      {
        $datos=array('idUsuario' => $idUsuario,'rol' => $rol, 'nombreUsuario' => $nombreUsuario,'password' => $password);
        $token = AutentificadorJWT::CrearToken($datos);
        $payload = json_encode(array('jwt' => $token));
      }
      else
      {
        $payload = json_encode(array('error' => 'Nombre de usuario o contraseña incorrectos'));
      }

      $response->getBody()->write($payload);

      return $response->withHeader('Content-Type', 'application/json');
    }

    /*
    public function LoggerAdmin($request, $response, $args) // POST nombreUsuario password
    {
      $parametros = $request->getParsedBody();

      $nombreUsuario = $parametros['nombreUsuario'];
      $password = $parametros['password'];

      $user = new Usuario();
      $user->sueldo = null;
      $user->rol = 'socio';
      // $user->fechaIngreso = $fechaIngreso;
      $user->nombreUsuario = $nombreUsuario;
      //$user->idProducto = $idProducto;
      $user->password = $password;

      $id = $user->crearUsuario();

      $datos=array('idUsuario' => $id,'rol' => 'socio');
      $token = AutentificadorJWT::CrearToken($datos);
      $payload = json_encode(array('jwt' => $token));

      $response->getBody()->write($payload);

      return $response->withHeader('Content-Type', 'application/json');
    }

    public function GuardarCSV($request, $response, $args)
    {
            
      if($archivo = fopen("csv/usuarios.csv", "w"))
      {
        $lista = Usuario::obtenerTodos();
        foreach( $lista as $usuario )
        {
            fputcsv($archivo, [$usuario->sueldo, $usuario->rol, $usuario->fechaIngreso, $usuario->nombreUsuario, $usuario->password, $usuario->idUsuario]);
        }
        fclose($archivo);
        $payload =  json_encode(array("mensaje" => "La lista de usuarios se guardo correctamente"));
      }
      else
      {
        $payload =  json_encode(array("mensaje" => "No se pudo abrir el archivo de usuarios"));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function CargarCSV($request, $response, $args)
    {
      if(($archivo = fopen("csv/usuarios.csv", "r")) !== false)
      {
        Usuario::borrarUsuarios();
        while (($filaUsuario = fgetcsv($archivo, 0, ',')) !== false) //esto seria como un while !feof
        {
          $nuevoUsuario = new Usuario();
          $nuevoUsuario->sueldo = $filaUsuario[0];
          $nuevoUsuario->rol = $filaUsuario[1];
          $nuevoUsuario->fechaIngreso = $filaUsuario[2];
          $nuevoUsuario->nombreUsuario = $filaUsuario[3];
          $nuevoUsuario->password = $filaUsuario[4];
          $nuevoUsuario->idUsuario = $filaUsuario[5];
          $nuevoUsuario->crearUsuarioCSV();
        }
        fclose($archivo);
        $payload  =  json_encode(array("mensaje" => "Los usuarios se cargaron correctamente"));
      }
      else
      {
        $payload =  json_encode(array("mensaje" => "No se pudo leer el archivo de usuarios"));
      }
                
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
*/
}