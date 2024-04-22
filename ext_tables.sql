#
# Table structure for table 'tx_dmnorm_domain_model_work'
#
CREATE TABLE tx_dmnorm_domain_model_gndwork (

	firstcomposer int(11) unsigned DEFAULT '0' NOT NULL,
	gnd_id varchar(255) DEFAULT '' NOT NULL,
	generic_title varchar(255) DEFAULT '' NOT NULL,
	individual_title varchar(255) DEFAULT '' NOT NULL,
	date_of_production date DEFAULT NULL,
	geographic_area_code varchar(255) DEFAULT '' NOT NULL,
	opus_no varchar(255) DEFAULT '' NOT NULL,
	index_no varchar(255) DEFAULT '' NOT NULL,
	tonality varchar(255) DEFAULT '' NOT NULL,
	title_no varchar(255) DEFAULT '' NOT NULL,
	title_instrument varchar(255) DEFAULT '' NOT NULL,
	alt_titles text,
	language varchar(255) DEFAULT '' NOT NULL,
	instrument_ids varchar(255) DEFAULT '' NOT NULL,
	alt_instrument_names varchar(255) DEFAULT '' NOT NULL,
	genre_ids varchar(255) DEFAULT '' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	full_title varchar(255) DEFAULT '' NOT NULL,
	intertextual_entity int(11) unsigned DEFAULT '0',
	super_work int(11) unsigned DEFAULT '0',
	instruments int(11) unsigned DEFAULT '0' NOT NULL,
	gnd_genres int(11) unsigned DEFAULT '0' NOT NULL,
	main_instrumentation int(11) unsigned DEFAULT '0'

);

#
# Table structure for table 'tx_dmnorm_domain_model_person'
#
CREATE TABLE tx_dmnorm_domain_model_gndperson (

	gnd_id varchar(255) DEFAULT '' NOT NULL,
	name varchar(255) DEFAULT '' NOT NULL,
	date_of_birth date DEFAULT NULL,
	date_of_death date DEFAULT NULL,
	geographic_area_code varchar(255) DEFAULT '' NOT NULL,
	gender varchar(255) DEFAULT '' NOT NULL,
	works int(11) unsigned DEFAULT '0' NOT NULL,
	place_of_birth int(11) unsigned DEFAULT '0',
	place_of_death int(11) unsigned DEFAULT '0',
	place_of_activity int(11) unsigned DEFAULT '0' NOT NULL

);
#
# Table structure for table 'tx_dmnorm_domain_model_gndinstrument'
#
CREATE TABLE tx_dmnorm_domain_model_gndinstrument (

	name varchar(255) DEFAULT '' NOT NULL,
	display_as text,
	gnd_id varchar(255) DEFAULT '' NOT NULL,
	super_instrument int(11) unsigned DEFAULT '0' NOT NULL

);

#
# Table structure for table 'tx_dmnorm_domain_model_gndgenre'
#
CREATE TABLE tx_dmnorm_domain_model_gndgenre (

	name varchar(255) DEFAULT '' NOT NULL,
	display_as text,
	gnd_id varchar(255) DEFAULT '' NOT NULL,
	super_genre int(11) unsigned DEFAULT '0' NOT NULL

);

#
# Table structure for table 'tx_dmnorm_domain_model_place'
#
CREATE TABLE tx_dmnorm_domain_model_gndplace (

	name varchar(255) DEFAULT '' NOT NULL,
	alt_names varchar(255) DEFAULT '' NOT NULL,
	longitude int(11) DEFAULT '0' NOT NULL,
	latitude int(11) DEFAULT '0' NOT NULL,
	gnd_id varchar(255) DEFAULT '' NOT NULL

);

#
# Table structure for table 'tx_dmnorm_work_gndinstrument_mm'
#
CREATE TABLE tx_dmnorm_gndwork_gndinstrument_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid_local,uid_foreign),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_dmnorm_work_gndgenre_mm'
#
CREATE TABLE tx_dmnorm_gndwork_gndgenre_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid_local,uid_foreign),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_dmnorm_person_placeofactivity_gndplace_mm'
#
CREATE TABLE tx_dmnorm_gndperson_placeofactivity_place_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid_local,uid_foreign),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_dmnorm_gndinstrument_gndinstrument_mm'
#
CREATE TABLE tx_dmnorm_gndinstrument_gndinstrument_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid_local,uid_foreign),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_dmnorm_gndgenre_gndgenre_mm'
#
CREATE TABLE tx_dmnorm_gndgenre_gndgenre_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid_local,uid_foreign),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);
