CREATE TABLE orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    order_date DATE
);
CREATE TABLE order_items (
    item_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    product_id INT,
    product_name VARCHAR(100),
    FOREIGN KEY (order_id) REFERENCES orders(order_id)
);

INSERT INTO orders (order_date) VALUES
('2026-03-01'),
('2026-03-02'),
('2026-03-03'),
('2026-03-04'),
('2026-03-05');

INSERT INTO order_items (order_id, product_id, product_name) VALUES
(1,1,'Laptop'),
(1,2,'Mouse'),
(1,3,'Keyboard'),

(2,1,'Laptop'),
(2,2,'Mouse'),

(3,2,'Mouse'),
(3,3,'Keyboard'),

(4,1,'Laptop'),
(4,3,'Keyboard'),

(5,1,'Laptop'),
(5,2,'Mouse');

WITH product_pairs AS (

    SELECT
        oi1.order_id,
        oi1.product_name AS product_1,
        oi2.product_name AS product_2

    FROM order_items oi1

    JOIN order_items oi2
        ON oi1.order_id = oi2.order_id
        AND oi1.product_id < oi2.product_id
),

pair_counts AS (

    SELECT
        product_1,
        product_2,
        COUNT(DISTINCT order_id) AS times_bought_together
    FROM product_pairs
    GROUP BY product_1, product_2
),

total_orders AS (

    SELECT COUNT(*) AS total_orders
    FROM orders
)

SELECT
    pc.product_1,
    pc.product_2,
    pc.times_bought_together,

    ROUND(
        (pc.times_bought_together / to2.total_orders) * 100,
        2
    ) AS percentage_of_orders

FROM pair_counts pc
CROSS JOIN total_orders to2

WHERE pc.times_bought_together > 3;