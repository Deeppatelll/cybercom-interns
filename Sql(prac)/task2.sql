CREATE DATABASE task2;
CREATE TABLE products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT,
    product_name VARCHAR(100),
    revenue INT
);
INSERT INTO products (category_id, product_name, revenue) VALUES
(1,'Laptop',5000),
(1,'Phone',7000),
(1,'Tablet',7000),
(1,'TV',4000),
(2,'Shirt',2000),
(2,'Jeans',3000),
(2,'Jacket',3000),
(3,'Book A',1500),
(3,'Book B',1200),
(3,'Book C',1500);
SELECT *
FROM (
    SELECT
        product_id,
        category_id,
        product_name,
        revenue,
        DENSE_RANK() OVER(
            PARTITION BY category_id
            ORDER BY revenue DESC
        ) AS revenue_rank
    FROM products
) ranked_products
WHERE revenue_rank <= 3;