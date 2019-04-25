import urllib.request, json 
#gets current integration status, pings integration endpoint every 10 seconds

with open('integrationsConfig.json') as config_file:
    data = json.load(config_file)

endpointUrl = data['integrationStatus']['statusEndpoint']
machineId = data['machineInfo']['machineId']
integrationBrewUrl = data['machineInfo']['integrationBrewUrl']
combined = endpointUrl + "machineId?=" + machineId


with urllib.request.urlopen(endpointUrl) as url:
    data = json.loads(url.read().decode())
    brewFlag = data['brewFlag']


if (brewFlag == 1):
        with urllib.request.urlopen(integrationBrewUrl) as url2:
            data2 = json.loads(url2.read().decode())
            #todo: change this to log instead of print
            #todo: decide where you want to reset the brew flag from
            print(data2)        
else:
        #todo: change this to log instead of print
        print("Brew flag has not changed")
        




