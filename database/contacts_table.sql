CREATE TABLE contacts (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(100) NOT NULL,
	phone_number VARCHAR(20) NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	status ENUM('active', 'inactive') DEFAULT 'active'
);

-- Insert some dummy data
INSERT INTO contacts (name, phone_number) VALUES
('John Doe', '+1234567890'),
('Jane Smith', '+9876543210'),
('Mike Johnson', '+1122334455'),
('Sarah Williams', '+5544332211');