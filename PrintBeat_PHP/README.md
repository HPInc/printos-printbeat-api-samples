# PHP

## General Information

Code was written in PHP 5.6.24

## How To Run / Program Information

Installing XAMPP and adding the printbeat_api.php file to the htdocs folder is a quick way to run the code.

Before you can run the code, you need to provide the Key/Secret generated from within your PrintOS account. There are two baseUrls provided. Uncomment the one that your Key/Secret was created/provided in.

If you are using a web proxy in your environment you will need to configure that by uncommenting the lines in the HTTP client call in the HTTP GET call and setting your web proxy and port.  

Populate the variables at the top of the script with any devices, KPIs, or ColorBeat items you wish to call through the API.

When you are ready to call a specific API uncomment the function call in the APIs section near the top of the file then run the script. 

