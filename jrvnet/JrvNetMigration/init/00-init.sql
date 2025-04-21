-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS jrvnet_db;

-- Switch to the database
USE jrvnet_db;

-- Import structure from original database
SOURCE /docker-entrypoint-initdb.d/db/structure.sql;