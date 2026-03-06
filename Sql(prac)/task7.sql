CREATE DATABASE task7;
CREATE TABLE customers (
    customer_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_name VARCHAR(100)
);
CREATE TABLE categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100)
);
CREATE TABLE products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    product_name VARCHAR(100),
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
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
    product_id INT,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);
INSERT INTO customers (customer_name) VALUES
('Deep'),
('Rahul'),
('Amit');
INSERT INTO categories (category_name) VALUES
('Electronics'),
('Clothing'),
('Home Appliances');
INSERT INTO products (product_name, category_id) VALUES
('Laptop',1),
('Phone',1),
('T-Shirt',2),
('Jeans',2),
('Washing Machine',3);
INSERT INTO orders (customer_id, order_date) VALUES
(1,'2026-03-01'),
(1,'2026-03-05'),
(2,'2026-03-02'),
(3,'2026-03-04');
INSERT INTO order_items (order_id, product_id) VALUES
(1,1),
(1,3),
(2,5),
(3,1),
(4,1);

SELECT c.customer_id, c.customer_name
FROM customers c
WHERE NOT EXISTS (

    SELECT *
    FROM categories cat
    WHERE NOT EXISTS (

        SELECT *
        FROM orders o
        JOIN order_items oi 
            ON o.order_id = oi.order_id
        JOIN products p 
            ON oi.product_id = p.product_id
        WHERE o.customer_id = c.customer_id
        AND p.category_id = cat.category_id
    )
);