-- Criação da tabela 'users'
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Campo para o identificador único do usuário
    nome VARCHAR(255) NOT NULL,         -- Nome do usuário
    cpf VARCHAR(11) NOT NULL UNIQUE,    -- CPF do usuário (não pode ser duplicado)
    email VARCHAR(255) NOT NULL UNIQUE, -- E-mail do usuário (não pode ser duplicado)
    data_nascimento DATE NOT NULL,      -- Data de nascimento do usuário
    telefone VARCHAR(15) NOT NULL,               -- Telefone do usuário (opcional)
    senha VARCHAR(255) NOT NULL,         -- Senha do usuário (vai armazenar uma versão criptografada)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Data de criação do registro (automática)
);

-- Inserção de dados de exemplo na tabela 'users'
INSERT INTO users (nome, cpf, email, data_nascimento, telefone, senha)
VALUES
  ('Ana', '36392326073', 'ana@teste.com.br', '1996-02-25', '(51)98765-4326', '$2y$10$SPAQxhq2UUi5yzmV4d/vt.QQzCtQ2U7LxF4GyP.VDjx3sg1E1ZB16'),
  ('João', '59851065005', 'joao@teste.com.br', '1996-02-25', '(51)98450-9172', '$2y$10$SPAQxhq2UUi5yzmV4d/vt.QQzCtQ2U7LxF4GyP.VDjx3sg1E1ZB16'),
  ('Maria', '97431387067', 'maria@teste.com.br', '1996-02-25', '(51)98765-4350', '$2y$10$SPAQxhq2UUi5yzmV4d/vt.QQzCtQ2U7LxF4GyP.VDjx3sg1E1ZB16');
