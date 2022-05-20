# Hotspot-Watchdog-V2
This version attempts to log every time it restarts the Moxee hotspot, using NTP to timestamp these events (since the ESP8266 has internet but no real time clock).


This will eventually have a different backend. For now, only the watchdog.ino file is different.

For now, this seems broken though. I have it running at the cabin and it's failing to reboot the Moxee, which is apparently in a crashed state.
