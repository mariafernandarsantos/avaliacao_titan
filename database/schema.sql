CREATE DATABASE IF NOT EXISTS jm_db

USE jm_db;

--  Tabela: user
CREATE TABLE IF NOT EXISTS user (
    id_user    BIGINT(20)   NOT NULL AUTO_INCREMENT,
    name       VARCHAR(150) NOT NULL,
    email      VARCHAR(100) NOT NULL,
    password   VARCHAR(255) NOT NULL,
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at  DATETIME     NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ativo      TINYINT(1)   NOT NULL DEFAULT 1,

    PRIMARY KEY (id_user),
    UNIQUE KEY uq_user_email (email)
)

--  Tabela: service
CREATE TABLE IF NOT EXISTS service (
    id_service      BIGINT(20)    NOT NULL AUTO_INCREMENT,
    description     VARCHAR(45)   NOT NULL,
    price           DECIMAL(11,3) NOT NULL,
    created_at      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at       DATETIME      NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    finished_at     DATETIME      NULL     DEFAULT NULL,
    commission_user DECIMAL(11,3) NULL     DEFAULT NULL,
    user_id_user    BIGINT(20)    NOT NULL,

    PRIMARY KEY (id_service),

    CONSTRAINT fk_service_user
        FOREIGN KEY (user_id_user) REFERENCES user (id_user)
        ON DELETE RESTRICT ON UPDATE CASCADE
)