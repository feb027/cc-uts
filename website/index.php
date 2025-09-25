<?php
// Front Controller
use App\Core\Autoloader;
use App\Controllers\GameController;use App\Controllers\ConfigurationController;use App\Controllers\BenchmarkController;use App\Core\Router;

session_start();
require __DIR__ . '/app/Core/Autoloader.php';
require __DIR__ . '/app/Core/Helpers.php';
Autoloader::register();

// Instantiate controllers (avoid naming collision with $game array in views)
$gameCtrl = new GameController();
$confCtrl = new ConfigurationController();
$benchCtrl = new BenchmarkController();

// Define routes
$router = new Router();
// Games
$router->get('index', fn() => $gameCtrl->index());
$router->get('create', fn() => $gameCtrl->create());
$router->get('show', fn() => $gameCtrl->show());
$router->post('store', fn() => $gameCtrl->store());
$router->get('edit', fn() => $gameCtrl->edit());
$router->post('update', fn() => $gameCtrl->update());
$router->get('delete', fn() => $gameCtrl->delete());
// Configurations
$router->post('config_store', fn() => $confCtrl->store());
$router->get('config_edit', fn() => $confCtrl->edit());
$router->post('config_update', fn() => $confCtrl->update());
$router->get('config_delete', fn() => $confCtrl->delete());
// Benchmarks
$router->post('bench_store', fn() => $benchCtrl->store());
$router->get('bench_edit', fn() => $benchCtrl->edit());
$router->post('bench_update', fn() => $benchCtrl->update());
$router->get('bench_delete', fn() => $benchCtrl->delete());

$router->dispatch($_GET['action'] ?? 'index');
?>
