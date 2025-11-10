-- Tambahkan tabel admin, articles, bmi_history jika belum ada.
CREATE TABLE IF NOT EXISTS admin (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  name VARCHAR(100) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS articles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  author VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL
);

CREATE TABLE IF NOT EXISTS bmi_history (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  height_cm INT,
  weight_kg FLOAT,
  bmi FLOAT,
  category VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Insert contoh admin (password: admin123)
INSERT INTO admin (username, password_hash, name) VALUES ('admin', '$2y$10$Q9hZ0u9qYh8Jd3s5rKf8CeQ5K1l9yG8k1A7bT9uL2e6mZ0pF3xCua', 'Administrator')
/* password_hash di atas adalah bcrypt untuk 'admin123' menggunakan password_hash('admin123', PASSWORD_DEFAULT) */
