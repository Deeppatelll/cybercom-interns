<?php

namespace App\Models;

use App\Core\Database;

class CategoryRepository
{
    public static function all(): array
    {
        $sql = "
            SELECT DISTINCT c.name
            FROM catalog_category_entity c
            INNER JOIN catalog_category_products ccp 
                ON c.id = ccp.category_id
            INNER JOIN catalog_product_entity p 
                ON p.id = ccp.product_id
            ORDER BY c.name
        ";

        $stmt = Database::connection()->prepare($sql);
        $stmt->execute();

        return array_column($stmt->fetchAll(), 'name');
    }
}

