CREATE TABLE product_prices (
    price_id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT,
    product_name VARCHAR(100),
    price DECIMAL(10,2),
    price_date DATE
);
INSERT INTO product_prices (product_id, product_name, price, price_date) VALUES
(1,'Laptop',50000,'2026-01-01'),
(1,'Laptop',48000,'2026-02-01'),
(1,'Laptop',52000,'2026-03-01'),

(2,'Phone',30000,'2026-01-15'),
(2,'Phone',32000,'2026-02-10'),
(2,'Phone',31000,'2026-03-05'),

(3,'Tablet',20000,'2026-01-20'),
(3,'Tablet',21000,'2026-02-20'),
(3,'Tablet',22000,'2026-03-10');

WITH price_history AS (

    SELECT
        product_id,
        product_name,
        price,
        price_date,

        -- Previous price
        LAG(price) OVER (
            PARTITION BY product_id
            ORDER BY price_date
        ) AS previous_price,

        -- Next price (if exists)
        LEAD(price) OVER (
            PARTITION BY product_id
            ORDER BY price_date
        ) AS next_price

    FROM product_prices
),

price_changes AS (

    SELECT
        product_id,
        product_name,
        price_date,
        price AS current_price,
        previous_price,
        next_price,

        -- Calculate percent change safely
        CASE
            WHEN previous_price IS NULL THEN NULL
            ELSE ROUND(
                ((price - previous_price) / previous_price) * 100,
                2
            )
        END AS percent_change

    FROM price_history
)

SELECT *
FROM price_changes
WHERE price_date >= CURDATE() - INTERVAL 90 DAY
AND previous_price IS NOT NULL;