-- Sample test data for Contact Tracing System

USE contact_tracing;

-- Insert sample users
INSERT INTO users (usc_id, first_name, middle_name, last_name, barangay, city, province, contact_number, email) VALUES
('2024-001', 'Juan', 'Dela', 'Cruz', 'Barangay 1', 'Manila', 'Metro Manila', '09123456789', 'juan@email.com'),
('2024-002', 'Maria', 'Santos', 'Garcia', 'Barangay 2', 'Manila', 'Metro Manila', '09234567890', 'maria@email.com'),
('2024-003', 'Jose', 'Reyes', 'Lopez', 'Barangay 3', 'Makati', 'Metro Manila', '09345678901', 'jose@email.com'),
('2024-004', 'Rosa', 'Flores', 'Mendoza', 'Barangay San Juan', 'Quezon City', 'Metro Manila', '09456789012', 'rosa@email.com'),
('2024-005', 'Pedro', 'Ortiz', 'Rivas', 'Barangay Alabang', 'Muntinlupa', 'Metro Manila', '09567890123', 'pedro@email.com');

-- Insert sample sign logs (some signed in, some signed out)
INSERT INTO sign_logs (user_id, action, timestamp) VALUES
(1, 'IN', DATE_SUB(NOW(), INTERVAL 2 HOUR)),
(1, 'OUT', DATE_SUB(NOW(), INTERVAL 1 HOUR)),
(2, 'IN', DATE_SUB(NOW(), INTERVAL 3 HOUR)),
(2, 'OUT', DATE_SUB(NOW(), INTERVAL 2.5 HOUR)),
(3, 'IN', DATE_SUB(NOW(), INTERVAL 1.5 HOUR)),
(4, 'IN', DATE_SUB(NOW(), INTERVAL 30 MINUTE)),
(5, 'IN', NOW());
