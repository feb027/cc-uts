<?php
namespace App\Models;

use App\Core\Database;use PDO;

class Configuration
{
    public static function forGame(int $gameId): array
    {
        $stmt = Database::getInstance()->prepare('SELECT * FROM configurations WHERE game_id = ? ORDER BY created_at DESC');
        $stmt->execute([$gameId]);
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::getInstance()->prepare('SELECT * FROM configurations WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $stmt = Database::getInstance()->prepare('INSERT INTO configurations (game_id, profile_name, mouse_dpi, in_game_sensitivity, crosshair_code, graphics_notes, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())');
        $stmt->execute([
            $data['game_id'],
            $data['profile_name'],
            $data['mouse_dpi'] !== '' ? $data['mouse_dpi'] : null,
            $data['in_game_sensitivity'] !== '' ? $data['in_game_sensitivity'] : null,
            $data['crosshair_code'] !== '' ? $data['crosshair_code'] : null,
            $data['graphics_notes'] !== '' ? $data['graphics_notes'] : null,
        ]);
        return (int)Database::getInstance()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = Database::getInstance()->prepare('UPDATE configurations SET profile_name = ?, mouse_dpi = ?, in_game_sensitivity = ?, crosshair_code = ?, graphics_notes = ?, updated_at = NOW() WHERE id = ?');
        return $stmt->execute([
            $data['profile_name'],
            $data['mouse_dpi'] !== '' ? $data['mouse_dpi'] : null,
            $data['in_game_sensitivity'] !== '' ? $data['in_game_sensitivity'] : null,
            $data['crosshair_code'] !== '' ? $data['crosshair_code'] : null,
            $data['graphics_notes'] !== '' ? $data['graphics_notes'] : null,
            $id
        ]);
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::getInstance()->prepare('DELETE FROM configurations WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
