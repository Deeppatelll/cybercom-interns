CREATE TABLE categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100)
);
CREATE TABLE products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    product_name VARCHAR(100),
    category_id INT,
    price DECIMAL(10,2),

    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);
CREATE TABLE orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    order_date DATE,
    order_status VARCHAR(20)
);
CREATE TABLE order_items (
    item_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    product_id INT,
    quantity INT,

    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);
INSERT INTO categories (category_name) VALUES
('Electronics'),
('Clothing'),
('Home Appliances');
INSERT INTO products (product_name, category_id, price) VALUES
('Laptop',1,50000),
('Phone',1,30000),
('T-Shirt',2,1500),
('Jeans',2,3000),
('Washing Machine',3,25000);

INSERT INTO orders (order_date, order_status) VALUES
('2026-01-10','completed'),
('2026-02-15','completed'),
('2026-03-05','pending'),
('2026-04-20','cancelled'),
('2026-05-18','completed');

INSERT INTO order_items (order_id, product_id, quantity) VALUES
(1,1,1),
(1,3,2),

(2,2,1),

(3,4,1),

(4,5,1),

(5,1,1),
(5,2,1);


WITH sales_data AS (

    SELECT
        c.category_name,
        QUARTER(o.order_date) AS quarter,
        o.order_status,
        p.price * oi.quantity AS amount

    FROM orders o

    JOIN order_items oi
        ON o.order_id = oi.order_id

    JOIN products p
        ON oi.product_id = p.product_id

    JOIN categories c
        ON p.category_id = c.category_id
)

SELECT
    category_name,
    quarter,

    COUNT(CASE WHEN order_status = 'completed' THEN 1 END) 
        AS completed_orders,

    SUM(CASE WHEN order_status = 'completed' THEN amount END) 
        AS completed_amount,

    COUNT(CASE WHEN order_status = 'pending' THEN 1 END) 
        AS pending_orders,

    SUM(CASE WHEN order_status = 'pending' THEN amount END) 
        AS pending_amount,

    COUNT(CASE WHEN order_status = 'cancelled' THEN 1 END) 
        AS cancelled_orders,

    SUM(CASE WHEN order_status = 'cancelled' THEN amount END) 
        AS cancelled_amount

FROM sales_data

GROUP BY
    category_name,
    quarter

ORDER BY
    category_name,
    quarter;