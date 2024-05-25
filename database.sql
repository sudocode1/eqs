CREATE DATABASE eqs;
USE eqs;
CREATE TABLE users (
    username text NOT NULL UNIQUE,
    password text NOT NULL,
    eqsId int
);

CREATE TABLE eqs (
    eqsId int NOT NULL UNIQUE,
    questions text,
    answers text 
);