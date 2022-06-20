# Hotspot-Watchdog-V2
This version attempts to log every time it restarts the Moxee hotspot, using NTP to timestamp these events (since the ESP8266 has internet but no real time clock).


This will eventually have a different backend. For now, only the watchdog.ino file is different.

(Disparaging remarks removed; the wires hadn't been connected!)

May 29th: added a feature to check the connection very rapidly (every 20 or so seconds) in case the connection has failed.  This makes the watchdog restore connectivity fairly quickly should the fix require a couple phases (as often seems to be the case with the pattern of power button closes I've come up with).

Still tinkering with the Moxee reset algorithm. I decided to make it so it only does one button cycle per attempt, that way it typically takes two cycles to get the Moxee back in working order (the first puts in in the stupid and useless "battery charging mode" and the second gets it back up).
