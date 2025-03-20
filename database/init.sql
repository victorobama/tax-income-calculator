CREATE DATABASE IF NOT EXISTS tax_calculator;
USE tax_calculator;

CREATE TABLE IF NOT EXISTS tax_brackets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    band_name VARCHAR(10) NOT NULL,
    min_income DECIMAL(15,2) NOT NULL,
    max_income DECIMAL(15,2) NULL,
    rate DECIMAL(5,2) NOT NULL,
    description VARCHAR(255)
);

-- Insert the tax bands as specified
-- Each band takes its upper limit to be the lower limit of the next band
-- The uppermost tax band has no upper limit (NULL)
INSERT INTO tax_brackets (band_name, min_income, max_income, rate, description) VALUES
('A', 0, 5000, 0.00, 'Tax Band A: 0% tax rate for income up to £5,000'),
('B', 5000, 20000, 20.00, 'Tax Band B: 20% tax rate for income between £5,000 and £20,000'),
('C', 20000, NULL, 40.00, 'Tax Band C: 40% tax rate for income above £20,000');
