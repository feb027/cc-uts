<?php
namespace App\Controllers;

use App\Models\Benchmark;use App\Models\Game;use function App\Core\redirect;use function App\Core\flash;

class BenchmarkController
{
    public function store(): void
    {
        $gameId = (int)($_POST['game_id'] ?? 0);
        if (!$gameId) { flash('error','Game tidak valid'); redirect('index.php'); return; }
        // Simple validation: at least avg_fps or low_1_percent_fps must be provided
        $avg = trim((string)($_POST['avg_fps'] ?? ''));
        $low1 = trim((string)($_POST['low_1_percent_fps'] ?? ''));
        if ($avg === '' && $low1 === '') {
            flash('error','Isi minimal salah satu nilai FPS');
            redirect('index.php?action=show&id=' . $gameId);
            return;
        }
        Benchmark::create([
            'game_id' => $gameId,
            'benchmark_date' => $_POST['benchmark_date'] ?? '',
            'driver_version' => trim($_POST['driver_version'] ?? ''),
            'avg_fps' => $_POST['avg_fps'] ?? '',
            'low_1_percent_fps' => $_POST['low_1_percent_fps'] ?? '',
            'cpu_temp' => $_POST['cpu_temp'] ?? '',
            'gpu_temp' => $_POST['gpu_temp'] ?? '',
            'notes' => $_POST['notes'] ?? '',
        ]);
        flash('success','Benchmark ditambahkan');
        redirect('index.php?action=show&id=' . $gameId);
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $bench = Benchmark::find($id); /** @var array|null $bench */
        if (!$bench) { http_response_code(404); echo 'Benchmark tidak ditemukan'; return; }
        $game = Game::find((int)$bench['game_id']);
        if (!$game) { http_response_code(404); echo 'Game tidak ditemukan'; return; }
        require __DIR__ . '/../Views/games/bench_edit.php';
    }

    public function update(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $bench = Benchmark::find($id); /** @var array|null $bench */
        if (!$bench) { http_response_code(404); echo 'Benchmark tidak ditemukan'; return; }
        $avg = trim((string)($_POST['avg_fps'] ?? ''));
        $low1 = trim((string)($_POST['low_1_percent_fps'] ?? ''));
        if ($avg === '' && $low1 === '') {
            flash('error','Isi minimal salah satu nilai FPS');
            redirect('index.php?action=edit_benchmark&id=' . $id);
            return;
        }
        Benchmark::update($id, [
            'benchmark_date' => $_POST['benchmark_date'] ?? '',
            'driver_version' => trim($_POST['driver_version'] ?? ''),
            'avg_fps' => $_POST['avg_fps'] ?? '',
            'low_1_percent_fps' => $_POST['low_1_percent_fps'] ?? '',
            'cpu_temp' => $_POST['cpu_temp'] ?? '',
            'gpu_temp' => $_POST['gpu_temp'] ?? '',
            'notes' => $_POST['notes'] ?? '',
        ]);
        flash('success','Benchmark diperbarui');
        redirect('index.php?action=show&id=' . $bench['game_id']);
    }

    public function delete(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $bench = Benchmark::find($id); /** @var array|null $bench */
        if ($bench) {
            Benchmark::delete($id);
            flash('success','Benchmark dihapus');
            redirect('index.php?action=show&id=' . $bench['game_id']);
        } else {
            http_response_code(404); echo 'Benchmark tidak ditemukan';
        }
    }
}
