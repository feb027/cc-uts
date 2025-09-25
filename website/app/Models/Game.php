<?php
namespace App\Models;

use App\Core\Database;use PDO;

class Game
{

    public static function stats(): array
    {
        $db = Database::getInstance();
        $res = $db->query('SELECT 
            (SELECT COUNT(*) FROM games) AS total_games,
            (SELECT COUNT(*) FROM configurations) AS total_configs,
            (SELECT COUNT(*) FROM benchmarks) AS total_benchmarks');
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return $row ?: ['total_games'=>0,'total_configs'=>0,'total_benchmarks'=>0];
    }

    public static function all(): array
    {
        $stmt = Database::getInstance()->query('SELECT g.*, 
            (SELECT COUNT(*) FROM configurations c WHERE c.game_id = g.id) AS config_count,
            (SELECT COUNT(*) FROM benchmarks b WHERE b.game_id = g.id) AS bench_count
            FROM games g ORDER BY g.created_at DESC');
        return $stmt->fetchAll();
    }

    public static function search(string $q): array
    {
        $like = '%' . $q . '%';
        $stmt = Database::getInstance()->prepare('SELECT g.*, 
            (SELECT COUNT(*) FROM configurations c WHERE c.game_id = g.id) AS config_count,
            (SELECT COUNT(*) FROM benchmarks b WHERE b.game_id = g.id) AS bench_count
            FROM games g WHERE g.name LIKE ? ORDER BY g.created_at DESC');
        $stmt->execute([$like]);
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::getInstance()->prepare('SELECT * FROM games WHERE id = ?');
        $stmt->execute([$id]);
        $game = $stmt->fetch();
        return $game ?: null;
    }

    public static function create(array $data): int
    {
        $stmt = Database::getInstance()->prepare('INSERT INTO games (name, cover_image_url, created_at, updated_at) VALUES (?, ?, NOW(), NOW())');
        $stmt->execute([$data['name'], $data['cover_image_url'] ?? null]);
        return (int)Database::getInstance()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = Database::getInstance()->prepare('UPDATE games SET name = ?, cover_image_url = ?, updated_at = NOW() WHERE id = ?');
        return $stmt->execute([$data['name'], $data['cover_image_url'] ?? null, $id]);
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::getInstance()->prepare('DELETE FROM games WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
