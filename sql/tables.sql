DROP TABLE IF EXISTS Comment;
DROP TABLE IF EXISTS Account;
DROP TABLE IF EXISTS Picture;

CREATE TABLE Account (
	username VARCHAR(20) PRIMARY KEY,
	password CHAR(64) NOT NULL,
	salt CHAR(8) NOT NULL
);

CREATE TABLE Picture (
	id TEXT PRIMARY KEY,
	uploaded TIMESTAMP NOT NULL DEFAULT NOW(),
	url TEXT NOT NULL DEFAULT '/',
	filesize INTEGER DEFAULT 0,
	ownedby VARCHAR(20)
		REFERENCES Account (username)
		ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE Comment (
	id SERIAL PRIMARY KEY,
	content TEXT NOT NULL,
	picture TEXT NOT NULL
		REFERENCES Picture (id)
		ON UPDATE CASCADE ON DELETE CASCADE,
	parent INTEGER,
	byuser VARCHAR(20)
		REFERENCES Account (username)
		ON UPDATE CASCADE ON DELETE SET NULL,
	posted TIMESTAMP NOT NULL DEFAULT NOW(),
	edited BOOLEAN NOT NULL DEFAULT FALSE,
	FOREIGN KEY (parent) REFERENCES Comment (id)
		ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE OR REPLACE FUNCTION base62encode (input BIGINT) RETURNS TEXT AS $$
DECLARE
	charset VARCHAR(62) := '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	output TEXT := '';
BEGIN
	LOOP
		output := output || substr(charset, 1 + (input % 62)::int, 1);
		input := input / 62;
		EXIT WHEN input = 0;
	END LOOP;
	RETURN output;
	
END
$$ LANGUAGE plpgsql;

DROP SEQUENCE IF EXISTS picture_id_seq;
CREATE SEQUENCE picture_id_seq;
ALTER SEQUENCE picture_id_seq OWNED BY Picture.id;
ALTER TABLE Picture ALTER COLUMN id SET DEFAULT base62encode(nextval('picture_id_seq'));

