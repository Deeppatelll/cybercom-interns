CREATE TABLE customers (
    customer_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_name VARCHAR(100),
    email VARCHAR(100)
);
CREATE TABLE products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    product_name VARCHAR(100),
    price DECIMAL(10,2)
);
CREATE TABLE orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT,
    order_date DATE,
    
    FOREIGN KEY (customer_id)
    REFERENCES customers(customer_id)
);
CREATE TABLE order_items (
    item_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    product_id INT,
    quantity INT,

    FOREIGN KEY (order_id)
    REFERENCES orders(order_id),

    FOREIGN KEY (product_id)
    REFERENCES products(product_id)
);
INSERT INTO customers (customer_name,email) VALUES
('Deep','deep@mail.com'),
('Rahul','rahul@mail.com');
INSERT INTO products (product_name,price) VALUES
('Laptop',50000),
('Mouse',1000),
('Keyboard',2000);
INSERT INTO orders (customer_id,order_date) VALUES
(1,'2025-01-10'),
(1,'2025-02-15'),
(2,'2025-03-10');
INSERT INTO order_items (order_id,product_id,quantity) VALUES
(1,1,1),
(1,2,2),
(2,3,1),
(3,2,1);

SELECT 
JSON_OBJECT(
'customer_id', c.customer_id,
'customer_name', c.customer_name,
'orders',
JSON_ARRAYAGG(
JSON_OBJECT(
'order_id', o.order_id,
'order_date', o.order_date,
'items',
(
SELECT JSON_ARRAYAGG(
JSON_OBJECT(
'product_id', p.product_id,
'product_name', p.product_name,
'price', p.price,
'quantity', oi.quantity
))
FROM order_items oi
JOIN products p
ON oi.product_id = p.product_id
WHERE oi.order_id = o.order_id
)
)
)
) AS customer_json
FROM customers c
JOIN orders o 
ON c.customer_id = o.customer_id
GROUP BY c.customer_id;