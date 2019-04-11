#!/bin/bash 

# Loop indefinitely until system shuts-down
until [  1 -gt 10 ]; do
   /usr/bin/python /var/www/_scripts/OWL_Listener.py
   echo "Restarting listener process..."
done

