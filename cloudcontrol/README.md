
**Cloud Control API code base. Currently converting to a RESTful set up. ETA 72 hours.**

Current Setup Info

Example call: `https://cloud.heymugsy.com/v2/api/coffeeNowCloud.php?userID=xxxx&machineID=xxxx&authKey=xxxx`

1. Cloud Control first checks to verify that your provided authKeymatches the apiKey created by your user for your Coffee Now integration.
    
2. Next it will check to verify that your machine is online and currently ready to brew.
    
3. If ready to brew, it updates the status flag in the CoffeeNowMonitor table.  
    
4. Your local Mugsy machine pings the CoffeeNowMonitor endpoint and starts brewing the Coffee Now recipe for the specific requesting user.
    
5. Mugsy updates the CoffeeNowMonitor to "currently brewing" so no other requests can interact with the machine. 
    
6. Once brewing is complete, Mugsy updates the CoffeeNowMonitor status flag back to its original state.

