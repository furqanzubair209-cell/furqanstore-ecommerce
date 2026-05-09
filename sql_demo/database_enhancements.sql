-- ============================================
-- SQL ENHANCEMENTS FOR FURQANSTORE
-- ============================================

USE furqanstore_db;

-- 1. JOINS & VIEW
DROP VIEW IF EXISTS v_product_details;
CREATE VIEW v_product_details AS
SELECT 
    p.id as product_id,
    p.name as product_name,
    p.price,
    p.stock,
    c.name as category_name,
    u.full_name as vendor_name,
    u.email as vendor_email
FROM products p
JOIN categories c ON p.category_id = c.id
JOIN users u ON p.vendor_id = u.id;

-- 2. VIEW for Order Details
DROP VIEW IF EXISTS v_order_summary;
CREATE VIEW v_order_summary AS
SELECT 
    o.id as order_id,
    u.full_name as customer_name,
    o.total,
    o.status,
    o.created_at,
    COUNT(oi.id) as total_items
FROM orders o
JOIN users u ON o.user_id = u.id
JOIN order_items oi ON o.id = oi.order_id
GROUP BY o.id;

-- 3. GROUP BY & AGGREGATE FUNCTIONS with HAVING
CREATE OR REPLACE VIEW v_vendor_performance AS
SELECT 
    u.full_name as vendor_name,
    COUNT(oi.id) as total_units_sold,
    SUM(oi.vendor_earning) as total_earnings,
    AVG(oi.price) as average_unit_price
FROM users u
JOIN order_items oi ON u.id = oi.vendor_id
GROUP BY u.id
HAVING total_earnings > 0
ORDER BY total_earnings DESC;

-- 4. FUNCTION
DROP FUNCTION IF EXISTS fn_calculate_tax;
CREATE FUNCTION fn_calculate_tax(amount DECIMAL(10,2)) 
RETURNS DECIMAL(10,2)
DETERMINISTIC
BEGIN
    RETURN amount * 0.05;
END;

-- 5. STORED PROCEDURE
DROP PROCEDURE IF EXISTS sp_get_user_orders;
CREATE PROCEDURE sp_get_user_orders(IN p_user_id INT)
BEGIN
    SELECT * FROM orders 
    WHERE user_id = p_user_id 
    ORDER BY created_at DESC;
END;

-- 6. TRIGGERS

-- 6a. BEFORE INSERT Guard: Block orders that exceed stock
DROP TRIGGER IF EXISTS tr_before_order_item_insert;
CREATE TRIGGER tr_before_order_item_insert
BEFORE INSERT ON order_items
FOR EACH ROW
BEGIN
    DECLARE available_stock INT;
    SELECT stock INTO available_stock FROM products WHERE id = NEW.product_id;

    IF available_stock <= 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Cannot place order: Product is out of stock.';
    END IF;

    IF NEW.quantity > available_stock THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Cannot place order: Requested quantity exceeds available stock.';
    END IF;
END;

-- 6b. AFTER INSERT: Deduct stock, then apply badge/status rules
DROP TRIGGER IF EXISTS tr_after_order_item_insert;
CREATE TRIGGER tr_after_order_item_insert
AFTER INSERT ON order_items
FOR EACH ROW
BEGIN
    DECLARE new_stock INT;

    -- Deduct ordered quantity
    UPDATE products
    SET stock = stock - NEW.quantity
    WHERE id = NEW.product_id;

    -- Read updated stock
    SELECT stock INTO new_stock FROM products WHERE id = NEW.product_id;

    -- Apply badge and status based on remaining stock
    IF new_stock <= 0 THEN
        UPDATE products
        SET badge = 'out_of_stock', status = 'inactive'
        WHERE id = NEW.product_id;
    ELSEIF new_stock <= 5 THEN
        UPDATE products
        SET badge = 'low_stock', status = 'active'
        WHERE id = NEW.product_id;
    ELSE
        UPDATE products
        SET badge = NULL
        WHERE id = NEW.product_id AND badge IN ('low_stock', 'out_of_stock');
    END IF;
END;
