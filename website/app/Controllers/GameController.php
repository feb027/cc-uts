<?php
namespace App\Controllers;

use App\Models\Game;use App\Models\Configuration;use App\Models\Benchmark;use function App\Core\view;use function App\Core\redirect;use function App\Core\flash;

class GameController
{
    public function index(): void
    {
        $q = trim($_GET['q'] ?? '');
        $games = $q !== '' ? Game::search($q) : Game::all();
        $stats = Game::stats();
        view('games/index', [
            'games' => $games,
            'query' => $q,
            'stats' => $stats,
            'title' => 'GameSpec Tracker - Games'
        ]);
    }

    public function show(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $game = Game::find($id);
        if (!$game) { http_response_code(404); echo 'Game tidak ditemukan'; return; }
        $from = $_GET['from'] ?? null; $from = $from !== '' ? $from : null;
        $to = $_GET['to'] ?? null; $to = $to !== '' ? $to : null;
        $configurations = Configuration::forGame($id);
        $benchmarks = Benchmark::forGame($id, $from, $to);
        $pageTitle = 'Detail Game: ' . ($game['name'] ?? '');
        view('games/show', [
            'game' => $game,
            'configurations' => $configurations,
            'benchmarks' => $benchmarks,
            'from' => $from,
            'to' => $to,
            'title' => $pageTitle
        ]);
    }

    public function create(): void
    {
        view('games/form', ['title' => 'Tambah Game']);
    }

    public function store(): void
    {
        $name = trim($_POST['name'] ?? '');
        $cover = trim($_POST['cover_image_url'] ?? '');
        if ($name === '') {
            $_SESSION['error'] = 'Nama game wajib diisi';
            redirect('index.php?action=create');
        }
    Game::create(['name' => $name, 'cover_image_url' => $cover ?: null]);
    flash('success','Game berhasil ditambahkan');
    redirect('index.php');
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $game = Game::find($id);
        if (!$game) { http_response_code(404); echo 'Game tidak ditemukan'; return; }
        view('games/form', ['game' => $game, 'title' => 'Edit Game']);
    }

    public function update(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $cover = trim($_POST['cover_image_url'] ?? '');
        if ($name === '') {
            $_SESSION['error'] = 'Nama game wajib diisi';
            redirect('index.php?action=edit&id=' . $id);
        }
    Game::update($id, ['name' => $name, 'cover_image_url' => $cover ?: null]);
    flash('success','Game diperbarui');
    redirect('index.php');
    }

    public function delete(): void
    {
        $id = (int)($_GET['id'] ?? 0);
    Game::delete($id);
    flash('success','Game dihapus');
    redirect('index.php');
    }
}
