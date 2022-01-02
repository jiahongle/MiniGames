drop table frogstats cascade;
drop table guessstats cascade;
drop table rpsstats cascade;
drop table appuser cascade;

create table appuser ( 
    userid varchar(10) PRIMARY KEY, 
    password varchar(64) NOT NULL, 
    firstname varchar(20) NOT NULL,
    lastname varchar(20) NOT NULL,
    birthdate DATE NOT NULL,
    gender varchar NOT NULL,
    favgame varchar NOT NULL
); 

create table frogStats ( 
    userid varchar(10) REFERENCES appuser(userid) ON UPDATE CASCADE NOT NULL,
    time INTEGER NOT NULL,
    PRIMARY KEY (userid, time)
);

create table guessStats (
    userid varchar(10) REFERENCES appuser(userid) ON UPDATE CASCADE NOT NULL,
    numCorrect INTEGER NOT NULL DEFAULT 1,
    PRIMARY KEY (userid)
);

create table rpsStats (
    userid varchar(10) REFERENCES appuser(userid) ON UPDATE CASCADE NOT NULL,
    numWins INTEGER NOT NULL DEFAULT 0,
    numLosses INTEGER NOT NULL DEFAULT 0,
    numGames INTEGER NOT NULL DEFAULT 0, 
    ratio NUMERIC(5, 2) NOT NULL DEFAULT 0,
    PRIMARY KEY (userid)
);
-- insert into appuser (userid, password, firstname, lastname, birthdate, gender, favgame) values('auser', 'password', 'arnold', 'rosen', '2000-01-01', 'male', 'Rock Paper Scissors');

