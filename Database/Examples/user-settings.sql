CREATE TABLE IF NOT EXISTS user_settings (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT,
  setting VARCHAR(50),
  meta_key VARCHAR(50),
  meta_value VARCHAR(50),
  FOREIGN KEY (user_id) REFERENCES users(id)
);
