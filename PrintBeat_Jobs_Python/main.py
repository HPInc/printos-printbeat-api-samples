# © Copyright 2023 HP Development Company, L.P.
# SPDX-License-Identifier: MIT

# !/usr/bin/python

__author__ = 'printbeat'

import requests, json, hmac, hashlib, datetime, base64, string, time

# access credentials
#baseUrl = "https://stg.printopt.org"  # use for production server account
baseUrl = "https://printos.api.hp.com/printbeat/"

key = ''  # The API Key generated from your PrintOS account for the Jobs API
secret = ''  # The API Secret generated from your PrintOS account for the Jobs API

marker = 0  # last marker value from previous query_jobs
jobsList = []  # List of jobs (list of dictionaries)

# --------------------------------------------------------------#


'''
Queries Jobs
Params:
  context - The context you want to get property specifications for (e.g. 'press', 'printrun', 'historic')
  startMarker - marker value to start the query from
  limit - the number of entries to be returned for this query (1000 - 10000)

 Returns:
  response - the respone data from the API query
'''


def query_jobs(startMarker, limit):
    path = '/externalApi/jobs'
    timestamp = datetime.datetime.utcnow().strftime('%Y-%m-%dT%H:%M:%SZ')
    headers = create_headers("GET", '/externalApi/jobs', timestamp)
    url = baseUrl + path
    if startMarker is not None:
        url = url + "?startMarker=" + str(startMarker)  #+ "&devices=" + "XXXXXXXX" + "&sortOrder=" + "DESC"

    print("URL: " + url)
    response = requests.get(url, headers=headers)
    print(response)
    return response


'''
Get Last Job Marker
Params:
  response - The response data from the latest query
Returns:
  last_marker - the marker value of the last entry in the response

'''


def get_last_marker(response):
    last_marker = None
    response_json = json.loads(response.text)

    attempts = response_json['attempts']
    length = len(attempts)

    last_marker = attempts[length - 1]['marker']
    print("Last marker = " + str(last_marker))
    return last_marker


'''
Creates the header using the key/secret which
allows you to make the API calls
Params:
  method - type of method (POST, GET, PUT, etc)
  path - api path (excluding the base url)
  timestamp - current time in specified format
'''


def create_headers(method, path, timestamp):
    string_to_sign = method + ' ' + path + timestamp
    local_secret = secret.encode('utf-8')
    string_to_sign = string_to_sign.encode('utf-8')
    signature = hmac.new(local_secret, string_to_sign, hashlib.sha256).hexdigest()
    auth = key + ':' + signature
    return {'content-type': 'application/json',
            'x-hp-hmac-authentication': auth,
            'x-hp-hmac-date': timestamp,
            'x-hp-hmac-algorithm': 'SHA256'
            }


'''
Prints the data into a cleaner JSON format
Params:
  data - data that needs to be printed into JSON format
'''


def print_json(data):
    print(json.dumps(data.json(), indent=4, sort_keys=True))


'''
GET call
Params:
  path - api path (excluding the base url)
'''


def request_get(path):
    print("In request_get() function")
    timestamp = datetime.datetime.utcnow().strftime('%Y-%m-%dT%H:%M:%SZ')
    print(" Timestamp: ", timestamp)
    url = baseUrl + path
    headers = create_headers("GET", path, timestamp)
    result = requests.get(url, headers=headers)
    return result


'''
POST call
Params:
  path - api path (excluding the base url)
  data - data to be posted
'''


def request_post(path, data):
    print("In request_post() function")
    timestamp = datetime.datetime.utcnow().strftime('%Y-%m-%dT%H:%M:%SZ')
    print(" Timestamp: ", timestamp)
    url = baseUrl + path
    headers = create_headers("POST", path, timestamp)
    result = requests.post(url, data, headers=headers)
    return result


# --------------------------------------------------------------#

# Call the list of property specfications for the current context
# print_json(propertyspecs(context))

# Make an initial set of queries to capture all current jobs +
while True:
    response = query_jobs( marker, 10000)
    print_json(response)

    marker = get_last_marker(response)
    response_json = json.loads(response.text)

    attempts = response_json['attempts']
    attempts_len = len(attempts)
    if attempts_len < 100:
        print("All current jobs have been captured")
        break
    else:
        print("Waiting 1 second for next query")
        time.sleep(1)

# Once the initial set of data has been collected query at a given interval to get any new job data
while True:
    print("Waiting 5 minutes for next query")
    time.sleep(300)
    response = query_jobs(marker, 1000)
    print_json(response)
    print(str(len(response.json())) + " entries were returned.")
    if (len(response.json()) > 0):
        jobsList = jobsList + response.json()
        # marker = get_last_marker(response)