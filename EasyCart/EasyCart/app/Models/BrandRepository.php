<?php

namespace App\Models;

use App\Core\Database;

class BrandRepository
{
    public static function all(): array
    {
        $sql = 'SELECT DISTINCT pa.brand AS name'
            . ' FROM catalog_product_attribute pa'
            . ' WHERE pa.brand IS NOT NULL'
            . ' ORDER BY pa.brand';

        $stmt = Database::connection()->prepare($sql);
        $stmt->execute();

        return array_values(array_filter(array_column($stmt->fetchAll(), 'name')));
    }
}
