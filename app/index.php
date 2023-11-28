<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';

require_once './controllers/CuentaController.php';
require_once './controllers/DepositoController.php';
require_once './controllers/RetiroController.php';
require_once './controllers/AjusteController.php';
require_once './controllers/UsuarioController.php';

require_once './middlewares/AuthMiddleware.php';

require_once './middlewares/OperadorMiddle.php';
require_once './middlewares/CajeroMiddle.php';
require_once './middlewares/SupervisorMiddle.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Set base path
//$app->setBasePath('/app');

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

$app->group('/Cuenta', function (RouteCollectorProxy $group) {
    $group->get('/', \CuentaController::class . ':TraerTodos');
    $group->get('/ConsultarMovimientoF', \CuentaController::class . ':ConsultarMovimientoF')->add(new OperadorMiddle());
    $group->get('/{nroDeCuenta}', \CuentaController::class . ':TraerUno');
    $group->post('/', \CuentaController::class . ':CargarUno');
    $group->post('/TraerUnoTipoYCuenta', \CuentaController::class . ':TraerUnoTipoYCuenta');
    $group->put('/Modificar', \CuentaController::class . ':ModificarUno');
    $group->delete('/', \CuentaController::class . ':EliminarUno');
})->add(new AuthMiddleware());

$app->group('/Deposito', function (RouteCollectorProxy $group) {
    $group->get('/', \DepositoController::class . ':TraerTodos')->add(new CajeroMiddle());
    $group->get('/ConsultarMovimientoA', \DepositoController::class . ':ConsultarMovimientoA')->add(new OperadorMiddle());
    $group->get('/ConsultarMovimientoB', \DepositoController::class . ':ConsultarMovimientoB')->add(new OperadorMiddle());
    $group->get('/ConsultarMovimientoC', \DepositoController::class . ':ConsultarMovimientoC')->add(new OperadorMiddle());
    $group->get('/ConsultarMovimientoD', \DepositoController::class . ':ConsultarMovimientoD')->add(new OperadorMiddle());
    $group->get('/ConsultarMovimientoE', \DepositoController::class . ':ConsultarMovimientoE')->add(new OperadorMiddle());
    $group->get('/{nroDeDeposito}', \DepositoController::class . ':TraerUno')->add(new CajeroMiddle());
    $group->post('/', \DepositoController::class . ':CargarUno')->add(new CajeroMiddle());
})->add(new AuthMiddleware());

$app->group('/Retiro', function (RouteCollectorProxy $group) {
    $group->get('/', \RetiroController::class . ':TraerTodos')->add(new CajeroMiddle());
    $group->get('/ConsultarMovimientoA', \RetiroController::class . ':ConsultarMovimientoA')->add(new OperadorMiddle());
    $group->get('/ConsultarMovimientoB', \RetiroController::class . ':ConsultarMovimientoB')->add(new OperadorMiddle());
    $group->get('/ConsultarMovimientoC', \RetiroController::class . ':ConsultarMovimientoC')->add(new OperadorMiddle());
    $group->get('/ConsultarMovimientoD', \RetiroController::class . ':ConsultarMovimientoD')->add(new OperadorMiddle());
    $group->get('/ConsultarMovimientoE', \RetiroController::class . ':ConsultarMovimientoE')->add(new OperadorMiddle());
    $group->get('/{nroDeRetiro}', \RetiroController::class . ':TraerUno')->add(new CajeroMiddle());
    $group->post('/', \RetiroController::class . ':CargarUno')->add(new CajeroMiddle());
})->add(new AuthMiddleware());

$app->group('/Ajuste', function (RouteCollectorProxy $group) {
    $group->get('/', \AjusteController::class . ':TraerTodos');
    $group->post('/', \AjusteController::class . ':CargarUno');
})->add(new SupervisorMiddle())->add(new AuthMiddleware());

$app->group('/Usuario', function (RouteCollectorProxy $group) {
    $group->post('[/]', \UsuarioController::class . ':CargarUno');
    $group->post('/Login', \UsuarioController::class . ':LoginUsuario');
});

$app->run();