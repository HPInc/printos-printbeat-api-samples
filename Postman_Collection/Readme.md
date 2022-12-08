# Postman Collection
## General Information
This is an exported collection for the Postman application (exported as a v2.1 Collection). It allows you to import a working set of API calls to test the functionality of the current APIs.

The Postman application can be downloaded here: https://www.getpostman.com/

## How To Run / Program Information
1. Import the "PrintBeat.postman_collection.json" file in the main Import dialog of Postman.
2. Import the "PrintBeat.postman_environment.json" file in the main Import dialog of Postman.
3. In the Manage Environments dialog edit the environment you just imported and replace the "key" and "secret" values with the key and secret from the PrintOS account you are using. 
4. Change the devices environment variable to the serial number of a press connected to Print Beat in your PrintOS account.
5. Save your changes to the environment.
6. Select the Environment you just imported in the environment drop-down menu in the upper-right corner.
7. In the PrintBeat API collection in the left pane select the API call you wish to make then click the "Send" button to send the API call.

## About the Postman collection
- A Pre-request Script inside the collection uses the CryptoJS library to dynamically generate the authentication HMAC.  All calls should use SHA256 for HMAC generation.
- This Pre-request Script may also set some necessary environment variables which are used in the HTTP Headers section for each call.
- The response field can be used to capture response JSON messages from the Jobs API calls.

**NOTE:** Some of the API calls may be dependant on certain environment variables being set to valid values in your environment.  Make sure they are defined correctly if errors occur.
