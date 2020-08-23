#!/usr/bin/env bash

brand="SolarPi"

# Check that the script is running as root. If not, then prompt for the sudo
# password and re-execute this script with sudo.
if [ "$(id -nu)" != "root" ]; then
    sudo -k
    pass=$(whiptail --backtitle "$brand Installer" --title "Authentication required" --passwordbox "Installing $brand requires administrative privilege. Please authenticate to begin the installation.\n\n[sudo] Password for user $USER:" 12 50 3>&2 2>&1 1>&3-)
    exec sudo -S -p '' "$0" "$@" <<< "$pass"
    exit 1
fi

cat << "EOF"
----------------------------------------------------------------------------


     Welcome to Installation of SolarPi
   _____       __           ____  _
  / ___/____  / /___ ______/ __ \(_)
  \__ \/ __ \/ / __ `/ ___/ /_/ / /
 ___/ / /_/ / / /_/ / /  / ____/ /
/____/\____/_/\__,_/_/  /_/   /_/   2020



----------------------------------------------------------------------------

EOF

sudo apt-get update && upgrade

sudo apt-get install socat 

sudo apt-get install apache2 apache2-utils -y
sudo apt-get install libapache2-mod-php5 php5 php-pear php5-xcache php5-mysql php5-curl php5-gd
sudo apt-get install php5-gd php5-mysql

sudo apt install libapache2-mod-php -y
sudo apt install ./mysql-apt-config_0.8.13-1_all.deb
sudo apt install mysql-server
sudo usermod -a -G www-data pi
sudo chown -R -f www-data:www-data /var/www/html

chmod 755 /var/www/html/pi-solar-tracer
lsusb
ls /dev/ttyXRUSB0
sudo chmod 777 /dev/ttyXRUSB0


echo "!!! The App is currently now running !!!"




