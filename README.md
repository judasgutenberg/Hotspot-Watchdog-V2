# Hotspot-Watchdog-V2
  

The reason for this project was that I have internet provided at a remote off-grid cabin via a cellular hotspot made by a company named Moxee (the cellular provider is Cricket, which works better than Verizon in the southern Adirondacks).  The Moxee device works okay when it works, but occasionally something will happen and the hotspot loses its connection. At that point, the only way to restore service is to manually reboot the hotspot.  This normally requires an actual human being to be in the cabin and to know what to do.  This is inconvenient if it's in the middle of winter and I am on a beach in Costa Rica.  So I wanted a mechanism to automatically reboot the Moxee hotspot whenever it fails to provide internet to a wireless device located nearby. A good place to start with this project was my ESP8266-based Micro-Weather project (https://github.com/judasgutenberg/ESP8266-Micro-Weather), which already consists of a device that connects to the internet to log weather data. 

This version of that system does a number of things differently.

1. It uses the BME680 environment sensor to monitor temperature, pressure, humidity, and gas characteristics. I've had to alter the backend to log the gas information along with the weather data.
2. It checks the network and if it cannot connect to its server or get a WiFi connection, it pulses line #D5 (digital line 14 for some reason) on the ESP8266 a couple times using a pattern I experimentally arrived at.   Driving a relay that bridges the power button wires on the Moxee hotspot, this will reset the Moxee and make it connect.  This means I do not have to drive to my cabin to reset the hotspot every time it craps out or otherwise gets confused. This makes it possible to reliably monitor the many sources of data that my cabin generates.

The Moxeee hotspot is easily disassembled if you have a tiny phillips-head screwdriver and perhaps some spudgers. Pop off the plastic back plate, remove the battery, and look in the back for six tiny screws that hold the chassis to the front. One of the six screws has a white paper tab on its head to tell Moxee that you've broken into their device, which you very much need to do to do what I did.  Then you can use a spudger to remove the front from the Moxee and see the following:

![alt text](moxee_inside_600.jpg?raw=true)

In this photo you see the labeled power button and the two test pads I found (using the continuity testing feature of a multimeter) that carry the signals bridged by that button.  Solder wires onto those pads and run them out of your Moxee so you can simulate pressing the power button. If you use the Arduino sketch to drive a relay attached to those two pads, your Moxee hotspot will be reset whenever it becomes uncommunicative, saving you hours of driving to your cabin or even having to think about it.


Using the NodeMCU ESP8266 board, the BME680 board, a relay board, and the Moxee, the wiring will look like this:

![alt text](watchdog.jpg?raw=true)

A good way to test if this system is working once constructed is to place the Moxee hotspot inside two stainless steel mixing bowls (arranged like the shells of a clam with the cables coming out). This will act like a Faraday cage, blocking all wireless signals. And then, next time the ESP8266 attempts to upload data, you will hear the sound of the relay clicking to reset the Moxee (assuming you used a mechanical relay).  

To be clear, regarding the files in this repository, the three PHP files belong on a web server you control that runs PHP and MySQL.  You will need to change config.php to put your MySQL login information in there and create a table called weather_data by running weather.sql.  The .ino and .h files go into a directory together in your Arduino sketch directory so you can write them to whatever board you have that is Arduino-compatible and has WiFi (I used a NodeMCU built around a ESP8266).  The two images are just to illustrate this README file.

Possible changes:  using an optoisolator instead of a relay, as this application does not require a zero-ohm-in-both-directions switch to bridge the Moxee power button signals.





This version attempts to log every time it restarts the Moxee hotspot, using NTP to timestamp these events (since the ESP8266 has internet but no real time clock).


This will eventually have a different backend. For now, only the watchdog.ino file is different.

(Disparaging remarks removed; the wires hadn't been connected!)

May 29th: added a feature to check the connection very rapidly (every 20 or so seconds) in case the connection has failed.  This makes the watchdog restore connectivity fairly quickly should the fix require a couple phases (as often seems to be the case with the pattern of power button closes I've come up with).

I'm still tinkering with the Moxee reset algorithm. The earlier version would bridge the power button for enough seconds to produce a reboot, which would then make the Moxee come up in a very stupid and useless "battery charging mode" where the Moxee didn't do anything but charge its battery and show an icon of this on its screen.  It would then require a second bridging of the power button, though the timing was difficult.  I've since made it so the Moxee reboot only bridges the power button once and then quickly (in 40 seconds or so) checks the internet. If it still hasn't come up, it bridges the power button again (possibly to get out of battery charging mde), and will do this four times before rebooting the ESP8266 itself.  If the internet starts working, it switches to an every-five-minute polling routine.  

I'm pretty sure the 40 seconds was not enough time when in "gotta try to connect" mode, so I've upped that to 70 seconds. I also eliminated an endless reboot loop that happened when the ESP8266 fails to connect to the WiFi.  The 70 seconds value has been working continually for a couple weeks now so I think that is a winner.

<b>Note:</b> the most advanced, responsive, and bug-free version of this code is now part of my ESP8266 Remote repository.  Follow the hardware instuctions above, but use the Arduino code found in this repository: https://github.com/judasgutenberg/Esp8266_RemoteControl. Obviously, that code can do a lot more than just perform Hotspot-Watchdog tasks.  But its Hotspot-Watchdog functionality works better than the old version found in this repository.

