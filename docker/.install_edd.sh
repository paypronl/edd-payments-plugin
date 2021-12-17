#!/bin/bash

edd_link="https://downloads.wordpress.org/plugin/easy-digital-downloads.2.11.3.1.zip"

wordpress_plugins_folder="/var/www/html/wp-content/plugins"
temp_folder_edd="/var/tmp/easy-digital-downloads"

# check if tmp folder exists if not create folder
[ ! -d "/var/tmp" ] && mkdir "/var/tmp"

# check if tmp folder for edd exists if not create folder
[ ! -d $temp_folder_edd ] && mkdir $temp_folder_edd

# download edd<version>.zip and save as easy-digital-downloads.zip in tmp folder
curl -L $edd_link > $temp_folder_edd/easy-digital-downloads.zip

# unzip downloaded easy-digital-downloads.zip
unzip -q $temp_folder_edd/easy-digital-downloads.zip -d $temp_folder_edd

# move zip content to plugins folder
mv $temp_folder_edd/easy-digital-downloads $wordpress_plugins_folder

# remove tmp zip
rm $temp_folder_edd/easy-digital-downloads.zip


# install paypro edd plugin

git clone https://github.com/paypronl/edd-payments-plugin.git $wordpress_plugins_folder/paypro-gateways-edd