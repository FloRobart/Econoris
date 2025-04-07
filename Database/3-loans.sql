-- Création de la table loans
CREATE TABLE loans (
    id SERIAL PRIMARY KEY NOT NULL,
    loans_date DATE DEFAULT CURRENT_DATE,
    borrower VARCHAR(255) NOT NULL,
    amount NUMERIC(12, 2) NOT NULL,
    refunded_amount NUMERIC(12, 2) DEFAULT 0.0,
    loan_reason VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INTEGER REFERENCES users(id)
);
