# GetTorBridgesSubAPI
Helps to get Tor bridges even if torproject.org is blocked in your country. It is enough to rent a VDS in another country and place this file in Apache or Nginx server.

To get the bridge, simply follow the link in your browser or send a GET request to the following address:
http://your_domain/tor?transport=webtunnel or http://your_domain/tor?transport=obfs4
