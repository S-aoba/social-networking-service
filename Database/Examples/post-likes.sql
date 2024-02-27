CREATE TABLE IF NOT EXISTS post_likes (
  user_id INT,
  post_id INT,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (post_id) REFERENCES posts(id)
);
