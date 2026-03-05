CREATE TABLE employees (
    employee_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(128) NOT NULL,
    title VARCHAR(50),
    manager_id INT,
    CONSTRAINT fk_manager
    FOREIGN KEY (manager_id)
    REFERENCES employees(employee_id)
);

WITH RECURSIVE OrgHierarchy AS (
    
    SELECT 
        employee_id,
        name,
        title,
        manager_id,
        1 AS depth_level,
        CAST(name AS CHAR(1024)) AS manager_chain_path,
        CAST(employee_id AS CHAR(1024)) AS path_tracker 
    FROM employees
    WHERE manager_id IS NULL

    UNION ALL

    SELECT 
        e.employee_id,
        e.name,
        e.title,
        e.manager_id,
        oh.depth_level + 1 AS depth_level,
        CONCAT(oh.manager_chain_path, ' -> ', e.name) AS manager_chain_path,
        CONCAT(oh.path_tracker, ',', e.employee_id) AS path_tracker
    FROM employees e
    INNER JOIN OrgHierarchy oh ON e.manager_id = oh.employee_id
    WHERE FIND_IN_SET(e.employee_id, oh.path_tracker) = 0
)

SELECT 
    employee_id,
    name,
    title,
    manager_id,
    depth_level,
    manager_chain_path,
    COUNT(*) OVER(PARTITION BY depth_level) AS total_employees_at_this_level
FROM OrgHierarchy
ORDER BY path_tracker;