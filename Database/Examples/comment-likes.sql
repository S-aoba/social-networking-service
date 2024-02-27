CREATE TABLE IF NOT EXISTS comment_likes (
  user_id INT,
  comment_id INT,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (comment_id) REFERENCES comments(id)
);
