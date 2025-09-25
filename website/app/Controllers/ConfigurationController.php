<?php
namespace App\Controllers;

use App\Models\Configuration;use App\Models\Game;use function App\Core\redirect;use function App\Core\flash;

class ConfigurationController
{
    public function store(): void
    {
        $gameId = (int)($_POST['game_id'] ?? 0);
        $profile = trim($_POST['profile_name'] ?? '');
        if ($profile === '' || !$gameId) {
            $_SESSION['error'] = 'Nama profil wajib diisi';
            redirect('index.php?action=show&id=' . $gameId);
        }
        Configuration::create([
            'game_id' => $gameId,
            'profile_name' => $profile,
            'mouse_dpi' => $_POST['mouse_dpi'] ?? '',
            'in_game_sensitivity' => $_POST['in_game_sensitivity'] ?? '',
            'crosshair_code' => $_POST['crosshair_code'] ?? '',
            'graphics_notes' => $_POST['graphics_notes'] ?? '',
        ]);
        flash('success','Konfigurasi ditambahkan');
        redirect('index.php?action=show&id=' . $gameId);
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $config = Configuration::find($id);
        if (!$config) { http_response_code(404); echo 'Konfigurasi tidak ditemukan'; return; }
        $game = Game::find((int)$config['game_id']);
        if (!$game) { http_response_code(404); echo 'Game tidak ditemukan'; return; }
        // Reuse show view? better separate small view
        require __DIR__ . '/../Views/games/config_edit.php';
    }

    public function update(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $config = Configuration::find($id);
        if (!$config) { http_response_code(404); echo 'Konfigurasi tidak ditemukan'; return; }
        $profile = trim($_POST['profile_name'] ?? '');
        if ($profile === '') {
            $_SESSION['error'] = 'Nama profil wajib diisi';
            redirect('index.php?action=config_edit&id=' . $id);
        }
        Configuration::update($id, [
            'profile_name' => $profile,
            'mouse_dpi' => $_POST['mouse_dpi'] ?? '',
            'in_game_sensitivity' => $_POST['in_game_sensitivity'] ?? '',
            'crosshair_code' => $_POST['crosshair_code'] ?? '',
            'graphics_notes' => $_POST['graphics_notes'] ?? '',
        ]);
        flash('success','Konfigurasi diperbarui');
        redirect('index.php?action=show&id=' . $config['game_id']);
    }

    public function delete(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $config = Configuration::find($id);
        if ($config) {
            Configuration::delete($id);
            flash('success','Konfigurasi dihapus');
            redirect('index.php?action=show&id=' . $config['game_id']);
        } else {
            http_response_code(404); echo 'Konfigurasi tidak ditemukan';
        }
    }
}
