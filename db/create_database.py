#!/bin/sh

def create_database():

    conn = cymysql.connect(servername, username, password)
    curs = conn.cursor()
    curs.execute("SET sql_notes = 0; ")  # Hide Warnings

    curs.execute("CREATE DATABASE IF NOT EXISTS {}".format(dbname))

    curs.execute("SET sql_notes = 1; ")  # Show Warnings
    conn.commit()
    conn.close()
    return


def open_database_connection():

    conn = cymysql.connect(servername, username, password, dbname)
    curs = conn.cursor()
    curs.execute("SET sql_notes = 0; ")  # Hide Warnings

    return conn, curs




#CREATE DATABASE IF NOT EXISTS solardata;

CREATE TABLE `status` (
  `Controller` int(2) NOT NULL,
  `timestamp` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `PV_array_voltage` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PV_array_current` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PV_array_power` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Battery_voltage` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Battery_charging_current` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Battery_charging_power` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Load_voltage` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Load_current` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Load_power` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Charger_temperature` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Heat_sink_temperature` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Battery_status` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Equipment_status` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`Controller`,`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `status` CHANGE `timestamp` `timestamp` TIMESTAMP(6) NOT NULL;

INSERT INTO `status` (`Controller`, `timestamp`, `PV_array_voltage`, `PV_array_current`, `PV_array_power`, `Battery_voltage`, `Battery_charging_current`, `Battery_charging_power`, `Load_voltage`, `Load_current`, `Load_power`, `Charger_temperature`, `Heat_sink_temperature`, `Battery_status`, `Equipment_status`) VALUES ('1', '2020-08-12 08:23:25.444444', '12', '23', '2', '4', '32', '12', '2', '12', '21', '12', '21', '12', '12');

//Statistical Data table
CREATE TABLE `stats_status` (
  `Controller` int(2) NOT NULL,
  `timestamp` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Max_volt_today` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Min_volt_today` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Max_batt_volt_today` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Min_batt_volt_today` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Consumed_ener_today` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Consumed_energy_month` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Consumed_energy_year` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Total_generated_energy` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Load_power` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Charger_temperature` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Heat_sink_temperature` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Battery_status` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Equipment_status` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`Controller`,`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

//Info tracer Data table
CREATE TABLE `tracer` (
  `Controller` int(2) NOT NULL,
  `timestamp` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `PV_rate_voltage` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PV_rate_current` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PV_rate_power` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Battery_rate_voltage` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Rate_charging_current` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Rate_charging_power` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Charging_mode` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Rate_load_current` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`Controller`,`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `tracer` CHANGE `timestamp` `timestamp` TIMESTAMP(6) NOT NULL;

def close_database_connection(conn, curs):

    curs.execute("SET sql_notes = 1; ")
    conn.commit()
    conn.close()


#################
#               #
# Main Program  #
#               #
#################

# Define MySQL database login settings

servername = "localhost"
username = "root"
password = "toor"
dbname = "solardata"

