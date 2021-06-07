# Script per la creazione del database gestione_allarmi.
# @version 07.05.2021
# @author Pierpaolo Casati

# Elimina il database gestione_allarmi se esiste.
drop database if exists gestione_allarmi;

# Crea il database gestione_allarmi.
create database gestione_allarmi;

# Usa database gestione_allarmi.
use gestione_allarmi;


#################### CREAZIONE TABELLE ####################

# Elimina la tabella utenti se esiste.
drop table if exists utente;

# Crea la tabella utente.
create table utente (
  id int  primary key auto_increment not null,
  email varchar(50) unique not null,
  nome varchar(50) not null,
  cognome varchar(50) not null,
  passwd varchar(255) not null,
  tipo enum('amministratore', 'limitato') not null,
  token varchar(32) not null
);


# Elimina la tabella allarme se esiste.
drop table if exists allarme;

# Crea la tabella allarme.
create table allarme (
  id int  primary key auto_increment not null,
  host varchar(255) not null,
  servizio varchar(255) not null,
  stato varchar(50) not null,
  last_check datetime not null,
  durata varchar(20) not null,
  stato_informazione varchar(255) not null
);


# Elimina la tabella checkCampi.
drop table if exists campiCheck;

create table campiCheck(
	id int primary key auto_increment not null,
	isHost bool not null,
    isStatus bool not null,
    isLastCheck bool not null,
    isDuration bool not null,
    isStatusInformation bool not null,
	isMap bool not null
);
