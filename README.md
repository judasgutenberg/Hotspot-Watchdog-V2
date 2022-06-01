# Hotspot-Watchdog-V2
This version attempts to log every time it restarts the Moxee hotspot, using NTP to timestamp these events (since the ESP8266 has internet but no real time clock).


This will eventually have a different backend. For now, only the watchdog.ino file is different.

(Disparaging remarks removed; the wires hadn't been connected!)

May 29th: added a feature to check the connection very rapidly (every 20 seconds) in case the connection has failed.  This makes the watchdog restore connectivity fairly quickly should the fix require a couple phases (as often seems to be the case with the pattern of power button closes I've come up with).
