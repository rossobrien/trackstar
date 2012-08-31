CREATE USER 'trackstar_dev'@'localhost' IDENTIFIED BY  '***';

GRANT USAGE ON * . * TO  'trackstar_dev'@'localhost' IDENTIFIED BY  '***' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;

CREATE DATABASE IF NOT EXISTS  `trackstar_dev` ;

GRANT ALL PRIVILEGES ON  `trackstar\_dev` . * TO  'trackstar_dev'@'localhost';