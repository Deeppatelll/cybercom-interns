CREATE DATABASE task3;
CREATE TABLE customers (
    customer_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100)
);
CREATE TABLE orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT,
    order_date DATE,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id)
);
CREATE TABLE order_items (
    item_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    product VARCHAR(100),
    price INT,
    FOREIGN KEY (order_id) REFERENCES orders(order_id)
);
INSERT INTO customers (name) VALUES
('Deep'),
('Rahul'),
('Amit');

INSERT INTO orders (customer_id, order_date) VALUES
(1,'2026-03-01'),
(1,'2026-03-02'),
(2,'2026-03-02'),
(3,'2026-03-01');

INSERT INTO order_items (order_id, product, price) VALUES
(1,'Laptop',50000),
(1,'Mouse',1000),
(2,'Keyboard',2000),
(3,'Phone',20000),
(4,'Tablet',30000);

WITH customer_orders AS (

    SELECT
        c.customer_id,
        c.name,
        o.order_id,
        oi.price

    FROM customers c
    JOIN orders o
        ON c.customer_id = o.customer_id
    JOIN order_items oi
        ON o.order_id = oi.order_id

    WHERE o.order_date >= CURDATE() - INTERVAL 30 DAY
),

customer_spending AS (

    SELECT
        customer_id,
        name,
        COUNT(DISTINCT order_id) AS purchase_count,
        SUM(price) AS total_spending
    FROM customer_orders
    GROUP BY customer_id, name
),

customer_avg AS (

    SELECT
        customer_id,
        name,
        purchase_count,
        total_spending,
        AVG(total_spending) OVER () AS avg_spending
    FROM customer_spending
)

SELECT
    customer_id,
    name,
    purchase_count,
    total_spending,
    total_spending - avg_spending AS above_average
FROM customer_avg
WHERE total_spending > avg_spending;