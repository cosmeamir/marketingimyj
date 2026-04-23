CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','cliente','design') NOT NULL,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(120),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE campaigns (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(150) NOT NULL,
  descricao TEXT,
  objetivo VARCHAR(120),
  canal VARCHAR(80),
  start_date DATE,
  end_date DATE,
  budget DECIMAL(12,2) DEFAULT 0,
  spent DECIMAL(12,2) DEFAULT 0,
  status VARCHAR(40),
  responsavel VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  campaign_id INT NOT NULL,
  titulo VARCHAR(150) NOT NULL,
  tipo_conteudo VARCHAR(50),
  plataforma VARCHAR(60),
  post_date DATE,
  post_time TIME,
  legenda TEXT,
  cta VARCHAR(120),
  status VARCHAR(40),
  creative_url VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (campaign_id) REFERENCES campaigns(id) ON DELETE CASCADE
);

CREATE TABLE traffic_metrics (
  id INT AUTO_INCREMENT PRIMARY KEY,
  campaign_id INT NOT NULL,
  data_registo DATE,
  plataforma VARCHAR(80),
  impressoes INT DEFAULT 0,
  cliques INT DEFAULT 0,
  leads INT DEFAULT 0,
  conversoes INT DEFAULT 0,
  cpc DECIMAL(10,2) DEFAULT 0,
  cpm DECIMAL(10,2) DEFAULT 0,
  spent DECIMAL(12,2) DEFAULT 0,
  resultado DECIMAL(12,2) DEFAULT 0,
  FOREIGN KEY (campaign_id) REFERENCES campaigns(id) ON DELETE CASCADE
);

CREATE TABLE calendar_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  campaign_id INT,
  post_id INT,
  tipo_item VARCHAR(50),
  titulo VARCHAR(150),
  start_datetime DATETIME,
  end_datetime DATETIME,
  cor VARCHAR(20),
  observacoes TEXT,
  FOREIGN KEY (campaign_id) REFERENCES campaigns(id) ON DELETE SET NULL,
  FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE SET NULL
);

CREATE TABLE channels (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(80) NOT NULL,
  tipo VARCHAR(50),
  status VARCHAR(30)
);

CREATE TABLE app_config (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tipo VARCHAR(50) NOT NULL,
  valor VARCHAR(120) NOT NULL,
  ativo TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
