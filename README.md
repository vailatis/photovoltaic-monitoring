# photovoltaic-monitoring
Monitor offgrid photovoltaic production, storage, consumptions and alerts through a web portal, storicize data and retreive for analysis.

This environment allow you to collect live data from OffGrid devices like PIP4048, Advance MKS5000 and similar products that allow querying live data through a serial interface.
These data are then stored inside a MySQL database and used by a web interface to display all your implant parameters like: consumption of energy, production, storage on batteries, performance, and previsions.
In addition, is possible to estimate day by day, month by month the amout of money saved.

The infrastructure is composed by three pieces:

- MYSQL DATABASE: contain all the collected data from the Inverter.

- DATA COLLECTOR: is responsible to collect live data from Inverter via serial connection, in a minute basis, and store it inside the database.  It can also process Inverter alarms, by sending a Push notification message via "Catapush" free service.

- WEB SERVER: contain all the web pages used to serve and display collected data and dispose Inverter parameters changes (only when accessed from a local network).

All the above components can be hosted inside a single machine, if there is room enought and the dinstance from the Inverter allow it (serial connection is better not be longer than 10 meters).

My implementation is divided in two separated objects, since i already have server hosting a MySQL DB and an Apache web server, i used this machine for Database and Web frontend, and built-up a Raspberry Mini W (with wifi connectivity) for the Datacollector, directly mounted near the inverter inside a plastic enclosure.

In this implementation, since i had room, i connected to the Raspberry a DS18B20, a One-Wire Temperature sensor that sense the temperature of the Copper Bar where all my PV strings collapse via Diodes in order to sense the diodes temperature bar, that normally is keept cool via two 40mm micro-fans placed at the ends of the bar, covered by a plastic tunnel in order to optimize the air-flow.

![Alt text](./example-images/Home-1.png?raw=true "Home-Page first part")

![Alt text](./example-images/Home-2.png?raw=true "Home-Page second part")


# WebServer Installation steps (based on CentOS):
- check and install OS updates (yum upgrade -y)
- install Apache web server (yum install -y httpd)
- install MySQL or MariaDB (yum install -y mariadb mariadb-server)
- install php (yum install -y php-cli php-common php-devel php-mysql)
- enable web server (systemctl enable httpd && systemctl start httpd)
- enable MySQL (systemctl enable mariadb && systemctl start mariadb)
- enable MySQL InnoDB tables outside system datafile
  - vi /etc/my.cnf
  - add the following line: innodb_file_per_table=1
  - save the file and exit
- pre-configure MySQL in a secure installation (mysql_secure_installation)
  - remove and disable all demo accounts and database
  - configure a root password
  - if you plan to administer remotely MySQL, enable remote root access
- enable web server through firewall (firewall-cmd --permanent --add-port=80/tcp)
- enable mysql management through firewall (firewall-cmd --permanent --add-port=3306/tcp)
- commit firewall config (firewall-cmd --reload)
- copy the contents of "_web_interface_" folder into webserver root (normally located under /var/www/html or /var/www)
- copy the content of "_\_scripts_" folder in a location where you normally keep maintenance scripts (for example /usr/local/bin)
- edit the crontab in order to add the execution of the two provided scripts (there is an example inside the folder)
- execute the "_MySQL_CreateDatabase.sql_" script to create databaes, tables, view and procedures
- create on MySQL the account and give "_GRANT ALL_" trole to schema "_solar_" you just created
- go under "_webroot/_library_" and edit the file "_\_config.php_" in order to provide credentials for MySQL database and OpenWeather
- if everything gone without errors, connecting with a browser to your webserver, should display the Solar website
