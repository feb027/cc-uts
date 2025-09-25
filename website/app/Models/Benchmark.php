<?php
namespace App\Models;

use App\Core\Database;

class Benchmark
{
    public static function forGame(int $gameId, ?string $from = null, ?string $to = null): array
    {
        $sql = 'SELECT * FROM benchmarks WHERE game_id = ?';
        $params = [$gameId];
        if ($from) { $sql .= ' AND (benchmark_date IS NOT NULL AND benchmark_date >= ?)'; $params[] = $from; }
        if ($to) { $sql .= ' AND (benchmark_date IS NOT NULL AND benchmark_date <= ?)'; $params[] = $to; }
        $sql .= ' ORDER BY benchmark_date DESC, created_at DESC';
        $stmt = Database::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::getInstance()->prepare('SELECT * FROM benchmarks WHERE id = ?');
        $stmt->execute([$id]);
        /** @var array|null $row */
        $row = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $stmt = Database::getInstance()->prepare('INSERT INTO benchmarks (game_id, benchmark_date, driver_version, avg_fps, low_1_percent_fps, cpu_temp, gpu_temp, notes, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?, NOW(), NOW())');
        $stmt->execute([
            $data['game_id'],
            $data['benchmark_date'] ?: null,
            $data['driver_version'] ?: null,
            $data['avg_fps'] !== '' ? $data['avg_fps'] : null,
            $data['low_1_percent_fps'] !== '' ? $data['low_1_percent_fps'] : null,
            $data['cpu_temp'] !== '' ? $data['cpu_temp'] : null,
            $data['gpu_temp'] !== '' ? $data['gpu_temp'] : null,
            $data['notes'] ?: null,
        ]);
        return (int)Database::getInstance()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = Database::getInstance()->prepare('UPDATE benchmarks SET benchmark_date = ?, driver_version = ?, avg_fps = ?, low_1_percent_fps = ?, cpu_temp = ?, gpu_temp = ?, notes = ?, updated_at = NOW() WHERE id = ?');
        return $stmt->execute([
            $data['benchmark_date'] ?: null,
            $data['driver_version'] ?: null,
            $data['avg_fps'] !== '' ? $data['avg_fps'] : null,
            $data['low_1_percent_fps'] !== '' ? $data['low_1_percent_fps'] : null,
            $data['cpu_temp'] !== '' ? $data['cpu_temp'] : null,
            $data['gpu_temp'] !== '' ? $data['gpu_temp'] : null,
            $data['notes'] ?: null,
            $id
        ]);
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::getInstance()->prepare('DELETE FROM benchmarks WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
