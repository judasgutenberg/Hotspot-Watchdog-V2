# Hotspot-Watchdog-V2
This version attempts to log every time it restarts the Moxee hotspot, using NTP to timestamp these events (since the ESP8266 has internet but no real time clock).


This will eventually have a different backend. For now, only the watchdog.ino file is different.

(Disparaging remarks removed; the wires hadn't been connected!)

May 29th: added a feature to check the connection very rapidly (every 20 or so seconds) in case the connection has failed.  This makes the watchdog restore connectivity fairly quickly should the fix require a couple phases (as often seems to be the case with the pattern of power button closes I've come up with).

I'm still tinkering with the Moxee reset algorithm. The earlier version would bridge the power button for enough seconds to produce a reboot, which would then make the Moxee come up in a very stupid and useless "battery charging mode" where the Moxee didn't do anything but charge its battery and show an icon of this on its screen.  It would then require a second bridging of the power button, though the timing was difficult.  I've since made it so the Moxee reboot only bridges the power button once and then quickly (in 40 seconds or so) checks the internet. If it still hasn't come up, it bridges the power button again (possibly to get out of battery charging mde), and will do this four times before rebooting the ESP8266 itself.  If the internet starts working, it switches to an every-five-minute polling routine.  

But now it's not working again so hmmm. So confused! Was it not resetting? Or not connecting?  Okay, I'm pretty sure the 40 seconds was not enough time when in "gotta try to connect" mode.
