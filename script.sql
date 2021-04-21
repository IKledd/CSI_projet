--CREATE DATABASE projet_CSI;

-----------------------------------------------------------------
--domaines

CREATE DOMAIN longueur AS VARCHAR
CHECK(
    char_length(VALUE) >=8
AND char_length(VALUE) <=16
);

CREATE DOMAIN positif AS INTEGER
CHECK(
   VALUE >=0
);

CREATE DOMAIN positif_decimal AS DECIMAL(10,2)
CHECK(
   VALUE >=0
);

-----------------------------------------------------------------
--énumération

CREATE TYPE Etat_Lot AS ENUM ('en attente', 'en vente', 'echec','gagne','a confirmer');

-----------------------------------------------------------------
--tables

CREATE TABLE T_compte_courant_COM (
    COM_idCompte serial Primary Key,
    COM_solde positif_decimal default '0'
);

insert into t_compte_courant_COM(com_solde) VALUES(1000);
insert into t_compte_courant_COM(com_solde) VALUES(10);
insert into t_compte_courant_COM(com_solde) VALUES(10);
insert into t_compte_courant_COM(com_solde) VALUES(10);
insert into t_compte_courant_COM(com_solde) VALUES(10);
insert into t_compte_courant_COM(com_solde) VALUES(10);

-----------------------------------------------------------------

CREATE TABLE T_Client_CLI (
    CLI_pseudo longueur Primary Key,
    CLI_nom varchar(50) NOT NULL,
    CLI_prenom varchar(50) NOT NULL,
    CLI_mdp longueur NOT NULL,
    CLI_date_inscription TimeStamp NOT NULL default current_timestamp,
    COM_idCompte int NOT NULL REFERENCES T_compte_courant_COM(COM_idCompte)
);

insert into t_client_cli(cli_pseudo,cli_nom,cli_prenom,cli_mdp,com_idcompte) values('ernesto1','dillen','ernesto','ernesto1',1);
insert into t_client_cli(cli_pseudo,cli_nom,cli_prenom,cli_mdp,com_idcompte) values('corentin','uhl','corentin','corentin',2);
insert into t_client_cli(cli_pseudo,cli_nom,cli_prenom,cli_mdp, com_idcompte) values('alielhssini','elhssini','alielhssini','password', 3);
insert into t_client_cli(cli_pseudo,cli_nom,cli_prenom,cli_mdp, com_idcompte) values('jaber123','benkirane','jaberbenk','jaberbenk', 4);
insert into t_client_cli(cli_pseudo,cli_nom,cli_prenom,cli_mdp, com_idcompte) values('paulo_ko','kolbert','polkol','motdepasse', 5);
insert into t_client_cli(cli_pseudo,cli_nom,cli_prenom,cli_mdp, com_idcompte) values('cloclo07','poirotte','clopoirotte','monchatestbeau', 6);

-----------------------------------------------------------------

CREATE TABLE T_Gestionnaire_GES (
    GES_pseudo longueur NOT NULL Primary Key,
    GES_nom varchar(50) NOT NULL,
    GES_prénom varchar(50) NOT NULL,
    GES_mdp longueur  NOT NULL,
    GES_benefice positif_decimal default '0'
);


insert into t_gestionnaire_ges values('gestionnaire','gest1','jacques','gestionnaire',0);

--verifie que le bénéfice augmente toujours
create or replace function check_benefice_gestionnaire() returns trigger as $$
declare 
begin
if (OLD.ges_benefice>NEW.ges_benefice) then
	raise exception 'Le nouveau bénéfice doit être supérieur au bénéfice précédent';
end if;
return new;
end
$$ language plpgsql;
 
create trigger ti_check_benefice before update on t_gestionnaire_ges for each row
execute procedure check_benefice_gestionnaire();

-----------------------------------------------------------------

create table T_lot_LOT
(
 LOT_id serial primary key,
 LOT_date_debut_vente TimeStamp NOT NULL check(LOT_date_debut_vente <= LOT_date_fin_vente),
 LOT_date_fin_vente TimeStamp NOT NULL ,
 LOT_etat Etat_Lot DEFAULT 'en attente' NOT NULL ,
 LOT_nombre_remise_vente INTEGER DEFAULT '0' NOT NULL check (LOT_nombre_remise_vente >= 0 AND LOT_nombre_remise_vente <3),
 --MODIF a verifier
 LOT_prix_estime positif_decimal DEFAULT '0' , 
 LOT_prix_minimal positif_decimal DEFAULT '0' check(LOT_prix_minimal <= LOT_prix_estime),
 LOT_gagnant longueur DEFAULT Null REFERENCES T_client_CLI(CLI_pseudo),
 LOT_prix_achat positif_decimal NULL
 );

--vérifie que le gagnant est renseigné si le lot est vendu
create or replace function check_gagnant_si_vendu() returns trigger as $$
declare 
begin
if (NEW.lot_etat='gagne' and NEW.lot_gagnant is null) then
	raise exception 'Le gagnant doit-être renseigné si le lot est gagné.';
end if;
return new;
end
$$ language plpgsql;

create trigger ti_check_gagnant_si_vendu before update or insert on t_lot_lot for each row
execute procedure check_gagnant_si_vendu();

--vérifie les dates des lots en vente et à confirmer
create or replace function check_remise_vente() returns trigger as $$
declare 
begin
if (OLD.lot_date_fin_vente>NEW.lot_date_debut_vente and OLD.lot_etat='a confirmer' and NEW.lot_etat='en vente') then
	raise exception 'La nouvelle date doit-être supérieur à la précédente';
end if;
return new;
end
$$ language plpgsql;

create trigger ti_check_remise_vente before update on t_lot_lot for each row
execute procedure check_remise_vente();

--calcul du bénéfice
create or replace function calcul_benefice() returns trigger as $$
declare 
benef positif_decimal =0;
intermediaire positif_decimal =0;
begin
if (NEW.lot_etat='gagne' and NEW.lot_gagnant is not null) then
	benef=NEW.lot_prix_achat*0.05;
	select SUM(pro_prix_propose*0.02) into intermediaire from t_proposition_achat_pro where lot_id=NEW.lot_id;
	benef=benef+intermediaire;
	--RAISE NOTICE 'Value: %', benef;
	--fonctionne comme il n'y a qu'un seul gestionnaire
	select ges_benefice into intermediaire from t_gestionnaire_ges;
	update t_gestionnaire_ges set ges_benefice=benef+intermediaire;
end if;
return new;
end
$$ language plpgsql;

create trigger ti_calcul_benefice before update or insert on t_lot_lot for each row
execute procedure calcul_benefice();

--vérifie que le nombre de remises en vente augmente toujours
create or replace function check_remise_superieur() returns trigger as $$
declare 
begin
if (OLD.lot_nombre_remise_vente>NEW.lot_nombre_remise_vente) then
    raise exception 'Le nombre de remises en vente doit toujours augmenter';
end if;
return new;
end
$$ language plpgsql;

create trigger ti_check_remise_superieur before update on t_lot_lot for each row
execute procedure check_remise_superieur();

--vérifie que le prix achat est inférieur au prix minimal
create or replace function check_prix_achat() returns trigger as $$
declare 
begin
if (NEW.lot_prix_achat<OLD.lot_prix_minimal) then
    raise exception E'Le prix d\'achat doit être supérieur au prix minimal';
end if;
return new;
end
$$ language plpgsql;

create trigger ti_check_prix_achat before update on t_lot_lot for each row
execute procedure check_prix_achat();

insert into t_lot_lot(lot_date_debut_vente,lot_date_fin_vente,lot_prix_estime,lot_prix_minimal) values('2021-04-06 04:05:06','2021-09-06 04:05:06',12,10);
insert into t_lot_lot(lot_date_debut_vente,lot_date_fin_vente,lot_prix_estime,lot_prix_minimal) values('2021-04-08 04:05:06','2021-05-10 04:05:06',120,100);
insert into t_lot_lot(lot_date_debut_vente,lot_date_fin_vente, lot_etat, lot_prix_estime,lot_prix_minimal) values('2021-04-06 04:05:06','2021-12-06 04:05:06', 'en vente',12,10);
insert into t_lot_lot(lot_date_debut_vente,lot_date_fin_vente, lot_etat, lot_prix_estime,lot_prix_minimal) values('2021-04-25 00:00:00','2021-05-12 23:59:59', 'en vente', 57, 42);
insert into t_lot_lot(lot_date_debut_vente,lot_date_fin_vente, lot_etat, lot_nombre_remise_vente, lot_prix_estime,lot_prix_minimal, lot_gagnant, lot_prix_achat) values('2021-04-25 00:00:00','2021-05-12 04:05:06', 'a confirmer', 1, 410, 380, 'paulo_ko', 500);
insert into t_lot_lot(lot_date_debut_vente,lot_date_fin_vente, lot_etat, lot_nombre_remise_vente, lot_prix_estime,lot_prix_minimal, lot_gagnant, lot_prix_achat) values('2021-11-21 12:00:00','2022-05-12 12:00:00', 'a confirmer', 0, 1500, 1450, 'cloclo07', 1672);

-----------------------------------------------------------------

CREATE TABLE T_Proposition_Achat_PRO(
    LOT_ID int NOT NULL REFERENCES T_LOT_LOT(LOT_ID),
    CLI_PSEUDO longueur NOT NULL  REFERENCES T_CLIENT_CLI(CLI_PSEUDO),
    PRO_CONFIRMATION boolean NOT NULL DEFAULT('false'),
    PRO_PRIX_PROPOSE positif_decimal NOT NULL  DEFAULT('0'),
    PRO_NOMBRE_MODIFICATION positif NOT NULL DEFAULT('0') CHECK(PRO_NOMBRE_MODIFICATION >=0) CHECK(PRO_NOMBRE_MODIFICATION <=2),
    PRO_DATE_PROPOSITION timestamp NOT NULL default current_timestamp
);

--vérifie que le nombre modifications augmente toujours
create or replace function check_nombre_modification() returns trigger as $$
declare 
begin
if (OLD.pro_nombre_modification>NEW.pro_nombre_modification) then
	raise exception 'Le nombre de modification doit-être augmenté';
end if;
return new;
end
$$ language plpgsql;

create trigger ti_check_nombre_modification before update on t_proposition_achat_pro for each row
execute procedure check_nombre_modification();

--vérifie que le prix proposé est cohérent
create or replace function check_prix_propose() returns trigger as $$
declare 
l_solde positif_decimal;
begin
select com_solde
from t_compte_courant_com,t_client_cli
into l_solde
where t_client_cli.com_idcompte=t_compte_courant_com.com_idcompte and cli_pseudo = new.cli_pseudo;
if (new.pro_prix_propose > l_solde) then
    raise exception 'Le prix proposé doit-être inférieur au solde';
end if;
if (OLD.pro_prix_propose>NEW.pro_prix_propose) then
	raise exception 'Le prix proposé doit augmenter';
end if;
return new;
end
$$ language plpgsql;

create trigger ti_check_prix_propose before update on t_proposition_achat_pro for each row
execute procedure check_prix_propose();

--vérifie la cohérence des dates
create or replace function check_date_proposition() returns trigger as $$
declare 
begin
if (OLD.pro_date_proposition>NEW.pro_date_proposition) then
	raise exception 'La nouvelle date doit-être supérieur à la précédente';
end if;
return new;
end
$$ language plpgsql;

create trigger ti_check_date_proposition before update on t_proposition_achat_pro for each row
execute procedure check_date_proposition();

--vérifie la cohérence des dates au niveau de la date d'inscription du client
create or replace function check_date_inscription() returns trigger as $$
declare 
l_date_inscription TimeStamp;
l_date_debut_vente TimeStamp;
begin
select cli_date_inscription
from t_client_cli
into l_date_inscription
where cli_pseudo = new.cli_pseudo;
select lot_date_debut_vente
from t_lot_lot
into l_date_debut_vente
where lot_id= new.lot_id;
if (l_date_debut_vente >= l_date_inscription) then
    raise exception 'La date_inscription doit-être antérieur à la date de debut de vente';
end if;
return new;
end
$$ language plpgsql;

create trigger ti_check_date_inscription After insert on t_proposition_achat_pro for each row
execute procedure check_date_inscription();

insert into t_proposition_achat_pro(lot_id,cli_pseudo,pro_prix_propose) values(1,'ernesto1',100);
insert into t_proposition_achat_pro(lot_id,cli_pseudo,pro_prix_propose) values(1,'ernesto1',300);
insert into t_proposition_achat_pro(lot_id,cli_pseudo,pro_prix_propose) values(3,'corentin',300);

-----------------------------------------------------------------

CREATE TABLE T_Type_Produit_TPROD(
 TPROD_id serial primary key,
 TPROD_libelle varchar(50) UNIQUE NOT NULL
);

insert into t_type_produit_tprod(tprod_libelle) values('hauts');
insert into t_type_produit_tprod(tprod_libelle) values('chaussures');
insert into t_type_produit_tprod(tprod_libelle) values('materiel informatique');
insert into t_type_produit_tprod(tprod_libelle) values('photo');

-----------------------------------------------------------------

CREATE TABLE T_Produit_PROD(
 PROD_id serial primary key,
 PROD_marque varchar(50) NOT NULL,
 PROD_datecreation timestamp DEFAULT current_timestamp,
 PROD_prix_initial positif_decimal NOT NULL DEFAULT('0'),
 TPROD_id int NOT NULL REFERENCES T_Type_Produit_TPROD(TPROD_id) 
);

insert into t_produit_prod(tprod_id,prod_marque,prod_prix_initial) values (2,'nike',2);
insert into t_produit_prod(tprod_id,prod_marque,prod_prix_initial) values (2,'kalenji',200);
insert into t_produit_prod(tprod_id,prod_marque,prod_prix_initial) values (3,'obs',55);
insert into t_produit_prod(tprod_id,prod_marque,prod_prix_initial) values (4,'nikone',895);
insert into t_produit_prod(tprod_id,prod_marque,prod_prix_initial) values (4,'photodepro',12);
insert into t_produit_prod(tprod_id,prod_marque,prod_prix_initial) values (3,'asuse',365);
insert into t_produit_prod(tprod_id,prod_marque,prod_prix_initial) values (3,'photaushop',250);
insert into t_produit_prod(tprod_id,prod_marque,prod_prix_initial) values (1,'supraime',99);

-----------------------------------------------------------------

CREATE TABLE T_quantite_QPROD(
 PROD_id int REFERENCES T_produit_PROD(PROD_id),
 LOT_id int REFERENCES T_lot_lot(LOT_id),
 QPROD_quantite positif NOT NULL DEFAULT('1'),
 PRIMARY KEY(PROD_id,LOT_id)
);

insert into t_quantite_qprod values(1,1,24);
insert into t_quantite_qprod values(5,1,3);
insert into t_quantite_qprod values(6,2,1);
insert into t_quantite_qprod values(1,3,2);
insert into t_quantite_qprod values(2,3,3);
insert into t_quantite_qprod values(8,3,1);
insert into t_quantite_qprod values(3,4,1);
insert into t_quantite_qprod values(6,4,12);
insert into t_quantite_qprod values(7,4,5);
insert into t_quantite_qprod values(4,5,3);
insert into t_quantite_qprod values(5,5,45);

--interdit la mise à jour sur la table T_quantite_QPROD
create or replace function modification_qte_lot() returns trigger as $$
declare 
begin
	raise exception 'Update interdit sur la table t_quantite_qpro.';
return new;
end
$$ language plpgsql;

create trigger ti_modification_qte_lot before update on t_quantite_qprod for each row
execute procedure modification_qte_lot();

-----------------------------------------------------------------
--fonctions et procédures stockées

--créeation d'un nouveau compte courant
create or replace function ajouter_compte_courant(solde positif_decimal) returns integer as $$
declare 
begin
	insert into t_compte_courant_com(com_solde) values(solde);
	return (select max(com_idcompte) from t_compte_courant_com);
end
$$ language plpgsql;

--recupération des types de produits
create or replace function recuperer_type_prod() returns record as $$
declare
	rec record;
begin
    SELECT tprod_id, tprod_libelle into rec from t_type_produit_tprod;
	return rec;
end
$$ language plpgsql;

--création d'un nouveau client
create or replace procedure inscription(pseudo longueur, nom varchar(50), prenom varchar(50), mdp longueur, idcompte integer default null) as $$
declare 
numcompte integer;
begin
	numcompte=idcompte;
	if (select cli_pseudo from t_client_cli where cli_pseudo=pseudo) is not null then
		raise exception 'Ce pseudo existe déjà';
	end if;
	if (select com_idcompte from t_compte_courant_com where com_idcompte=idcompte) is null then
		--raise exception E'Cet id de compte n\'existe pas';
		numcompte = (SELECT ajouter_compte_courant(0));
	end if; 
	insert into t_client_cli(cli_pseudo,cli_nom,cli_prenom,cli_mdp,com_idcompte) values(pseudo,nom,prenom,mdp,numcompte);
end
$$ language plpgsql;

--verifie la connexion, renvoie non si non connecté
create or replace function connexion(pseudo longueur, mdp longueur) returns varchar(30) as $$
declare 
begin
	if (select cli_pseudo from t_client_cli where cli_pseudo=pseudo and cli_mdp=mdp) is not null then
		return 'client';
		elsif (select ges_pseudo from t_gestionnaire_ges where ges_pseudo=pseudo and ges_mdp=mdp) is not null then
			return 'gestionnaire';
			else return 'non';
	end if;
end
$$ language plpgsql;

--ajout d'un produit
create or replace procedure ajouter_produit(marque varchar(50), prix positif_decimal, idtprod integer,dat timestamp default null) as $$
declare 
begin
	if (select tprod_id from t_type_produit_tprod where tprod_id=idtprod) is null then
		raise exception E'Cet id de type de produit n\'existe pas';
	end if;
	insert into t_produit_prod(prod_marque,prod_datecreation,prod_prix_initial,tprod_id) values(marque,dat,prix,idtprod);
end
$$ language plpgsql;

--ajout d'un type de produit
create or replace procedure ajouter_type_produit(libelle varchar(50)) as $$
declare 
begin
	insert into t_type_produit_tprod(tprod_libelle) values(libelle);
end
$$ language plpgsql;

--proposition de prix d'un client sur un lot
create or replace procedure proposer_prix(idlot integer, prix integer, pseudo longueur,dat timestamp default current_timestamp) as $$
declare 
declare 
begin
	
	if (select lot_id from t_lot_lot where lot_id=idlot) is null then
		raise exception E'Cet id de lot n\'existe pas';
	end if;
	if (select cli_pseudo from t_client_cli where cli_pseudo=pseudo) is null then
		raise exception E'Ce client n\'existe pas';
	end if;
	if (select 1 from t_proposition_achat_pro where pro_nombre_modification>=2 and lot_id=idlot and cli_pseudo=pseudo GROUP BY cli_pseudo) is not null then
		raise exception E'Vous ne pouvez plus modifier le prix de votre proposition';
	end if;
	if (select 1 from t_lot_lot where lot_date_debut_vente>=current_timestamp and lot_date_fin_vente<=current_timestamp and lot_id=idlot) is not null then
		raise exception E'Problème de date';
	end if;
	if ((select com_solde from t_compte_courant_com,t_client_cli where t_compte_courant_com.com_idcompte=t_client_cli.com_idcompte and cli_pseudo=pseudo)<prix) then
		raise exception E'Votre solde n\'est pas suffisante, veuillez réapprovisionner.';
	end if;
	if ((select max(pro_prix_propose) from t_proposition_achat_pro where cli_pseudo=pseudo and lot_id=idlot GROUP BY cli_pseudo)>prix) then
		raise exception E'Votre prix est inférieur à celui de votre proposition passée.';
	end if;
	if(select 1 from t_proposition_achat_pro where cli_pseudo=pseudo and lot_id=idlot GROUP BY cli_pseudo)is null then
		insert into t_proposition_achat_pro(lot_id,cli_pseudo,pro_prix_propose,pro_date_proposition) values(idlot,pseudo,prix,dat);
		else update t_proposition_achat_pro set pro_nombre_modification=((select pro_nombre_modification from t_proposition_achat_pro where cli_pseudo=pseudo and lot_id=idlot GROUP BY pro_nombre_modification)+1),pro_prix_propose=prix,pro_date_proposition=dat where cli_pseudo=pseudo and lot_id=idlot;
	end if;
end

$$ language plpgsql;

--recherche si un lot existe et est en vente renvoie un booléen
create or replace function rechercher_lot_en_vente(idlot integer) returns boolean as $$
declare 
	--lot t_lot_lot%ROWTYPE;
begin	
	if (select 1 from t_lot_lot where lot_id=idlot and lot_etat='en vente') is null then
		return false;
	end if;
	return true;
end
$$ language plpgsql;
--retourner un booléen ? rowtype impossible

--recherche si un lot existe et est vendu renvoie un booléen
create or replace function rechercher_lot_vendu(idlot integer) returns boolean as $$
declare 
	--lot t_lot_lot%ROWTYPE;
begin	
	if (select 1 from t_lot_lot where lot_id=idlot and lot_etat='gagne') is null then
		return false;
	end if;
	return true;
end
$$ language plpgsql;
--retourner un booléen ? rowtype impossible

--ajout d'un produit à un lot
create or replace procedure ajouter_produit_a_un_lot(idlot integer, idproduit integer, quantite positif) as $$
declare 
begin
	if (select lot_id from t_lot_lot where lot_id=idlot) is null then
		raise exception E'Cet id de lot n\'existe pas';
	end if;
	if (select prod_id from t_produit_prod where prod_id=idproduit) is null then
		raise exception E'Cet id de produit n\'existe pas';
	end if;
	if(select lot_etat from t_lot_lot where lot_id=idlot)<>'en attente' then
		raise exception E'On ne modifie plus un lot déjà en vente.';
	end	if;
	insert into t_quantite_qprod values(idproduit,idlot,quantite);
end
$$ language plpgsql;

--mise en vente d'un lot
create or replace procedure mise_en_vente_lot(prix_min positif_decimal, prix_est positif_decimal,date_fin timestamp,date_debut timestamp default current_timestamp) as $$
declare 
begin
	--les produits seront sélectionner avant avec la fonctionajouter_produit_a_un_lot
	if(date_fin<date_debut)then
		raise exception 'la date de fin doit être supérieure à la date de début.';
	end if;
	if(prix_est<prix_min)then
		raise exception 'le prix estimé ne peut pas être inférieur au prix minimal.';
	end if;
	insert into t_lot_lot(lot_date_debut_vente,lot_date_fin_vente,lot_etat,lot_nombre_remise_vente,lot_prix_estime,lot_prix_minimal) values(date_debut,date_fin,'en attente',0,prix_est,prix_min);
end
$$ language plpgsql;

--remise en vente d'un lot
create or replace procedure remettre_en_vente_lot(idlot integer) as $$
declare 
	lot t_lot_lot%ROWTYPE;
begin
	select * into lot from t_lot_lot where lot_id=idlot;
	if lot.lot_id is null then
		raise exception E'Cet id de lot n\'existe pas';
	end if;
	if lot.lot_gagnant is not null then
		raise exception E'Ce lot a été remporté.';
	end if;
	if lot.lot_nombre_remise_vente < 2 then
		lot.lot_prix_minimal=lot.lot_prix_minimal*0.9;
		lot.lot_nombre_remise_vente=lot.lot_nombre_remise_vente+1;
		lot.lot_etat='en vente';
		lot.lot_date_debut_vente=current_timestamp;
		lot.lot_date_fin_vente =lot.lot_date_fin_vente + interval '7 day';
		else
			raise notice E'La vente de ce lot est un échec.';
			lot.lot_etat='echec';
			--ATTENTION LOT_nombre_remise_vente à <= 3 pour gérer le cas echec(ligne84)
	end if;
	update t_lot_lot 
	set lot_prix_minimal = lot.lot_prix_minimal,lot_nombre_remise_vente=lot.lot_nombre_remise_vente,lot_etat=lot.lot_etat,
	lot_date_debut_vente=lot.lot_date_debut_vente,lot_date_fin_vente=lot.lot_date_fin_vente
	where lot_id=idlot;
end
$$ language plpgsql;

--verrifie si le solde du client est suffisant pour un lot passé en paramètre
create or replace procedure check_solde_suffisant(pseudo longueur,idlot integer) as $$
declare 
	prix_lot positif_decimal;
	solde positif_decimal;
begin
	prix_lot=(select lot_prix_minimal from t_lot_lot where lot_id=idlot);
	solde=(select com_solde from t_compte_courant_com,t_client_cli where t_compte_courant_com.com_idcompte=t_client_cli.com_idcompte and cli_pseudo=pseudo);
	if (solde<prix_lot) then
		raise exception E'Votre solde n\'est pas suffisante, veuillez réapprovisionner.';
	end if;
end
$$ language plpgsql;

--modification du solde d'un utilisateur
create or replace procedure modifier_solde(montant decimal(10,2), compte integer) as $$
declare 
	solde positif_decimal;
begin
	select com_solde into solde from t_compte_courant_com where com_idcompte = compte;
    if (montant < 0 and @montant >solde) then
        raise exception 'Le montant retiré est trop élevé';
    else update t_compte_courant_com set com_solde=solde+montant where com_idcompte = compte;
    end if;
end
$$ language plpgsql;

--mise à jour de l'état d'un lot selon des conditions
create or replace procedure update_etat_lot(lotid integer, etat etat_lot) as $$
declare
    etatlot etat_lot;
begin
    select lot_etat into etatlot from t_lot_lot where lot_id = lotid;
    if (etatlot = 'gagne' or etatlot = 'echec') then
        raise exception E'L\'état du lot ne peut plus être modifié';
    else update t_lot_lot set lot_etat = etat where lot_id = lotid;
    end if;
end
$$ language plpgsql;

--retorune le solde du client
create or replace function return_solde(pseudo longueur) returns positif_decimal as $$
declare 
begin
	return (select com_solde from t_compte_courant_com,t_client_cli where cli_pseudo=pseudo and t_client_cli.com_idcompte=t_compte_courant_com.com_idcompte);
end
$$ language plpgsql;

--supprimer toutes les propositions d'un lot sous certaines conditions
create or replace procedure supprimer_propositions(id_lot integer) as $$
declare 
l_date_fin_lot t_lot_lot.lot_date_fin_vente%type ;
begin
    select lot_date_fin_vente into l_date_fin_lot from t_lot_lot where lot_id=id_lot;
    if(current_timestamp > (l_date_fin_lot + INTERVAL '1 DAY')) then
        delete from t_proposition_achat_pro
        where t_proposition_achat_pro.lot_id = id_lot;
    end if;
end
$$ language plpgsql;
-- supprimer propositions
create  or replace procedure supprimer_propositions() as $$
declare 
begin
delete from t_proposition_achat_pro
where lot_id in (select lot_id from t_lot_lot where current_timestamp > (lot_date_fin_vente + INTERVAL '1 DAY'));
end
$$ language plpgsql;

--analyse des propositions d'un lot sous certaines conditions
create or replace procedure Analyse_propositions(id_lot integer) as $$
declare 
proposition RECORD;
l_prix_plus_haut  positif_decimal ;
l_gagnant t_proposition_achat_pro.cli_pseudo%type;
l_prix_minimal t_lot_lot.lot_prix_minimal%type;
l_date_fin_lot t_lot_lot.lot_date_fin_vente%type;
begin
    select lot_prix_minimal,lot_date_fin_vente into l_prix_minimal,l_date_fin_lot from t_lot_lot where lot_id=id_lot;
    if(l_date_fin_lot <= current_timestamp ) then
        l_prix_plus_haut = 0 ;
        FOR proposition IN
           SELECT max(pro_prix_propose) prix_max, cli_pseudo FROM t_proposition_achat_pro
            WHERE lot_id = id_lot
            GROUP BY cli_pseudo ,pro_date_proposition
			order by pro_date_proposition Asc 
        LOOP
            if(l_prix_plus_haut < proposition.prix_max and l_prix_minimal <= proposition.prix_max)then
                l_prix_plus_haut = proposition.prix_max;
                l_gagnant = proposition.cli_pseudo;
            end if;
        END LOOP;
        if(l_gagnant is not null )then
            update t_lot_lot 
            set  lot_gagnant = l_gagnant,
                 lot_etat = 'a confirmer'
            where lot_id=id_lot;
			else call remettre_en_vente_lot(id_lot);
        end if;
    end if;
end
$$ language plpgsql;

--analyse des propositions 
create or replace procedure analyse_lots() as $$ 
declare 
r_lot RECORD;
begin
	FOR r_lot IN
	   SELECT lot_id FROM t_lot_lot
		WHERE lot_date_fin_vente < current_timestamp
		and lot_gagnant is null
	LOOP
		call analyse_propositions(r_lot.lot_id);
	END LOOP;
end
$$ language plpgsql;

--confirmer_achat
create or replace procedure confirmer_achat(id_lot integer) as $$
declare 
l_gagnant t_proposition_achat_pro.cli_pseudo%type;
idcompte t_compte_courant_com.com_idcompte%type;
prix_achat t_lot_lot.lot_prix_achat%type;

begin
    select lot_gagnant into l_gagnant from t_lot_lot where lot_id=id_lot;
	select lot_prix_achat into prix_achat from t_lot_lot where lot_id=id_lot;
	select com_idcompte into idcompte from t_lot_lot lot ,t_client_cli cli 
	where cli.cli_pseudo = lot.lot_gagnant and lot.lot_id=id_lot;

		if(l_gagnant is not null)then
			update t_lot_lot
			set lot_etat = 'gagne'
			where lot_id=id_lot;
			update t_compte_courant_com
			set com_solde = com_solde - prix_achat
			where com_idcompte=idcompte;


		end if;
end
$$ language plpgsql;

--refuser_achat
create or replace procedure refuser_achat(id_lot integer) as $$
declare 
begin
DELETE FROM t_proposition_achat_pro 
WHERE (cli_pseudo, lot_id) = ( select lot_gagnant,lot_id from t_lot_lot
							   where lot_id = id_lot);
/*UPDATE t_lot_lot 
SET lot_gagnant = null
where lot_id = id_lot;*/

call analyse_propositions(id_lot);
end
$$ language plpgsql;

--mise en vente
create or replace procedure mise_vente() as $$
declare 
 
lot RECORD;
begin
	  update t_lot_lot set lot_etat = 'en vente' where lot_etat= 'en attente' and lot_date_debut_vente <= current_timestamp;
end
$$ language plpgsql;




-----------------------------------------------------------------
--vues

--vue à destination du client
CREATE VIEW v_affichage_client AS
    SELECT l.lot_id,lot_date_debut_vente,lot_date_fin_vente,lot_prix_estime,lot_prix_achat,
	cli_pseudo,pro_prix_propose,pro_nombre_modification,pro_date_proposition,pr.prod_id,prod_marque,prod_datecreation,
	pr.tprod_id,tprod_libelle,qprod_quantite
    FROM t_lot_lot as l, t_proposition_achat_pro as pa,t_produit_prod as pr,t_quantite_qprod as q,t_type_produit_tprod as tp
    WHERE l.lot_id=pa.lot_id and pr.tprod_id=tp.tprod_id and pr.tprod_id=q.prod_id and l.lot_id=q.lot_id;


--vue à destination du gestionnaire
CREATE VIEW v_affichage_gestionnaire AS
    SELECT l.lot_id,lot_date_debut_vente,lot_date_fin_vente,lot_etat,lot_nombre_remise_vente,lot_prix_estime,lot_prix_minimal,lot_gagnant,lot_prix_achat,
	cli_pseudo,pro_prix_propose,pro_nombre_modification,pro_date_proposition
    FROM t_lot_lot as l, t_proposition_achat_pro as pa
    WHERE l.lot_id=pa.lot_id;