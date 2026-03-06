CREATE TABLE transactions (
    transaction_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT,
    transaction_date DATE,
    amount DECIMAL(10,2)
);
INSERT INTO transactions (customer_id, transaction_date, amount) VALUES
(1,'2024-01-10',5000),
(2,'2024-02-12',7000),
(3,'2024-03-18',6000),
(1,'2024-04-15',8000),
(2,'2024-05-20',7500),
(3,'2024-06-10',9000),
(1,'2024-07-05',10000),
(2,'2024-08-12',8500),
(3,'2024-09-15',9200),
(1,'2024-10-08',11000),
(2,'2024-11-11',10500),
(3,'2024-12-20',12000),

(1,'2025-01-15',13000),
(2,'2025-02-14',12500),
(3,'2025-03-18',14000),
(1,'2025-04-10',15000),
(2,'2025-05-05',16000),
(3,'2025-06-12',17000),
(1,'2025-07-20',16500),
(2,'2025-08-25',17500),
(3,'2025-09-30',18000),
(1,'2025-10-10',19000),
(2,'2025-11-18',20000),
(3,'2025-12-22',21000);

WITH monthly_revenue AS (

    SELECT
        DATE_FORMAT(transaction_date, '%Y-%m') AS month,
        SUM(amount) AS monthly_revenue
    FROM transactions
    WHERE transaction_date >= DATE_SUB(CURDATE(), INTERVAL 24 MONTH)
    GROUP BY DATE_FORMAT(transaction_date, '%Y-%m')

)

SELECT
    month,
    monthly_revenue,

    LAG(monthly_revenue) OVER (ORDER BY month) AS previous_month_revenue,

    SUM(monthly_revenue) OVER (
        ORDER BY month
        ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW
    ) AS running_total,

    SUM(monthly_revenue) OVER (
        PARTITION BY YEAR(STR_TO_DATE(month,'%Y-%m'))
        ORDER BY month
    ) AS ytd_revenue

FROM monthly_revenue
ORDER BY month;