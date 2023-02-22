CREATE TABLE bills (
                       id VARCHAR(20) NOT NULL PRIMARY KEY,
                       sum  INT DEFAULT 0,
                       bill_type SMALLINT,
                       is_paid SMALLINT DEFAULT 0,
                       createdAt TIMESTAMP DEFAULT NOW()
) CHARACTER SET utf8 COLLATE utf8_general_ci engine MyISAM;
CREATE TABLE bill_positions (
                                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                bill_id VARCHAR(20),
                                product_id INT,
                                price INT DEFAULT 0,
                                quantity INT DEFAULT 1,
                                FOREIGN KEY (bill_id) REFERENCES bills(id) ON DELETE SET NULL
) CHARACTER SET utf8 COLLATE utf8_general_ci engine MyISAM;