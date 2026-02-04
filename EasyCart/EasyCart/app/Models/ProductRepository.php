<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class ProductRepository
{
    public static function all(): array
    {
        $sql = self::baseSelectSql();
        $stmt = Database::connection()->prepare($sql . ' ORDER BY p.id');
        $stmt->execute();
        $rows = $stmt->fetchAll();

        return array_map([self::class, 'normalizeProductRow'], $rows);
    }

    public static function findById(int $id): ?array
    {
        $sql = self::baseSelectSql() . ' WHERE p.id = :id';
        $stmt = Database::connection()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();

        return $row ? self::normalizeProductRow($row) : null;
    }

    public static function findByIds(array $ids): array
    {
        $ids = array_values(array_unique(array_map('intval', $ids)));
        if (empty($ids)) {
            return [];
        }

        $placeholders = [];
        $params = [];
        foreach ($ids as $index => $id) {
            $key = ':id_' . $index;
            $placeholders[] = $key;
            $params[$key] = $id;
        }

        $sql = self::baseSelectSql() . ' WHERE p.id IN (' . implode(',', $placeholders) . ')';
        $stmt = Database::connection()->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_INT);
        }
        $stmt->execute();
        $rows = $stmt->fetchAll();

        return array_map([self::class, 'normalizeProductRow'], $rows);
    }

    public static function getDistinctUnits(): array
    {
        $sql = 'SELECT DISTINCT pa.unit FROM catalog_product_attribute pa WHERE pa.unit IS NOT NULL ORDER BY pa.unit';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute();
        return array_values(array_filter(array_column($stmt->fetchAll(), 'unit')));
    }

    public static function getFilteredProducts(array $filters, int $page, int $perPage): array
    {
        $params = [];
        $whereSql = self::buildWhereSql($filters, $params);

        $countSql = 'SELECT COUNT(DISTINCT p.id) AS total FROM catalog_product_entity p'
            . self::joinSql()
            . $whereSql;
        $countStmt = Database::connection()->prepare($countSql);
        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value);
        }
        $countStmt->execute();
        $total = (int)$countStmt->fetchColumn();

        $offset = ($page - 1) * $perPage;
        $dataSql = self::baseSelectSql()
            . $whereSql
            . ' ORDER BY p.id'
            . ' LIMIT :limit OFFSET :offset';

        $dataStmt = Database::connection()->prepare($dataSql);
        foreach ($params as $key => $value) {
            $dataStmt->bindValue($key, $value);
        }
        $dataStmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $dataStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $dataStmt->execute();

        $rows = $dataStmt->fetchAll();

        return [
            'items' => array_map([self::class, 'normalizeProductRow'], $rows),
            'total' => $total,
        ];
    }

    private static function baseSelectSql(): string
    {
        return 'SELECT DISTINCT ON (p.id)'
            . ' p.id AS id,'
            . ' p.name AS name,'
            . ' p.price,'
            . ' COALESCE(ccp.quantity, 0) AS quantity,'
            . ' pa.image,'
            . ' p.shipping_type,'
            . ' COALESCE(c.name, pa.category) AS category,'
            . ' pa.brand AS brand,'
            . ' pa.unit,'
            . ' pa.weight_value,'
            . ' p.description'
            . ' FROM catalog_product_entity p'
            . self::joinSql();
    }

    private static function joinSql(): string
    {
        return ' LEFT JOIN catalog_product_attribute pa ON pa.product_id = p.id'
            . ' LEFT JOIN catalog_category_products ccp ON ccp.product_id = p.id'
            . ' LEFT JOIN catalog_category_entity c ON c.id = ccp.category_id';
    }

    private static function buildWhereSql(array $filters, array &$params): string
    {
        $conditions = [];

        if (!empty($filters['search'])) {
            $conditions[] = '(p.name ILIKE :search OR p.description ILIKE :search)';
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['categories'])) {
            $categoryPlaceholders = [];
            foreach ($filters['categories'] as $index => $category) {
                $key = ':category_' . $index;
                $categoryPlaceholders[] = $key;
                $params[$key] = $category;
            }
            $conditions[] = 'COALESCE(c.name, pa.category) IN (' . implode(',', $categoryPlaceholders) . ')';
        }

        if (!empty($filters['brands'])) {
            $brandPlaceholders = [];
            foreach ($filters['brands'] as $index => $brand) {
                $key = ':brand_' . $index;
                $brandPlaceholders[] = $key;
                $params[$key] = $brand;
            }
            $conditions[] = 'pa.brand IN (' . implode(',', $brandPlaceholders) . ')';
        }

        if (!empty($filters['units'])) {
            $unitPlaceholders = [];
            foreach ($filters['units'] as $index => $unit) {
                $key = ':unit_' . $index;
                $unitPlaceholders[] = $key;
                $params[$key] = $unit;
            }
            $conditions[] = 'pa.unit IN (' . implode(',', $unitPlaceholders) . ')';
        }

        if (!empty($filters['price'])) {
            switch ($filters['price']) {
                case 'under_100':
                    $conditions[] = 'p.price < 100';
                    break;
                case '100_300':
                    $conditions[] = 'p.price BETWEEN 100 AND 300';
                    break;
                case 'above_300':
                    $conditions[] = 'p.price > 300';
                    break;
            }
        }

        if (empty($conditions)) {
            return '';
        }

        return ' WHERE ' . implode(' AND ', $conditions);
    }

    private static function normalizeProductRow(array $row): array
    {
        return [
            'id' => (int)($row['id'] ?? 0),
            'name' => $row['name'] ?? '',
            'price' => (float)($row['price'] ?? 0),
            'quantity' => $row['quantity'] ?? '',
            'image' => $row['image'] ?? '',
            'shipping_type' => $row['shipping_type'] ?? 'express',
            'category' => $row['category'] ?? '',
            'brand' => $row['brand'] ?? '',
            'unit' => $row['unit'] ?? '',
            'weight_value' => isset($row['weight_value']) ? (float)$row['weight_value'] : null,
            'description' => $row['description'] ?? '',
        ];
    }
}

function getProductById(int $id, array $products): ?array
{
    foreach ($products as $product) {
        if ((int)$product['id'] === $id) {
            return $product;
        }
    }

    return null;
}
