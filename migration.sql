# поменял id с varchar(20) на int: если хотим что-то на примере guid'а - можно сделать поле guid
# но здесь нужно понимать: если ручки для "внутреннего использования" - то можно ограничиться id'ами (int)
# если же для внешнего использования - то лучше наверное через guid'ы (но оставить id, а guid уже генерить при создании сущностей)
# bill_type можно сделать enum'ом, но нужно смотреть в контексте: если типы могут часто добавляться, то постоянные миграции - плохая история

# с полем is_paid - сложно, нужно полностью понимать бизнес-логику значения этого поля
CREATE TABLE bill (
                       id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                       sum INT DEFAULT 0,
                       type SMALLINT NOT NULL,
                       is_paid SMALLINT DEFAULT 0,
                       created_at TIMESTAMP DEFAULT NOW()
) CHARACTER SET utf8 COLLATE utf8_general_ci engine MyISAM;

CREATE TABLE item (
                                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                bill_id INT,
                                product_id INT,
                                price INT DEFAULT 0,
                                quantity INT DEFAULT 1,
                                FOREIGN KEY (bill_id) REFERENCES bill(id) ON DELETE SET NULL
) CHARACTER SET utf8 COLLATE utf8_general_ci engine MyISAM;