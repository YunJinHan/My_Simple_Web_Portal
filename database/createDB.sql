
DROP TABLE IF EXISTS board;
CREATE TABLE board (
    source varchar(200) NULL,
    category varchar(200) NULL,
    title varchar(200) NULL,
    url varchar(200) NULL,
    hits int(10) NOT NULL DEFAULT 0,
    date DATETIME NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

USE crawlingdb;
