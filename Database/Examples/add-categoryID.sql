ALTER TABLE posts
ADD COLUMN category_id INT,
ADD CONSTRAINT fk_category_id FOREIGN KEY (category_id) REFERENCES categories(id);
