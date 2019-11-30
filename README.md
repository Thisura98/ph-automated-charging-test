# PayHere Automated Charging Test

This repo contains source for a little testing tool I wrote. It makes requests to the Automated Charging APIs of the IPG, [PayHere](https://www.payhere.lk). Mainly the following APIs.

- [Pre-approval API](https://support.payhere.lk/api-&-mobile-sdk/payhere-preapproval)
- [Charging API](https://support.payhere.lk/api-&-mobile-sdk/payhere-charging)

The tool is written using barebones PHP and HTML and has just 4 files. It uses `.txt` files to save responses from the different APIs so you can do a test run without worrying about Databases.

[See Demo](http://orangegrounds.space/test/preapproval/)

## Screenshot ##

![Demo Screenshot](https://github.com/Thisura98/ph-automated-charging-test/raw/master/screenshot.png)

## Test Payment Credentials ##

As per [this link](https://support.payhere.lk/faq/sandbox-and-testing) you can use the following credentials to make test payments when required.

| FIELD     | VALUE             |
| --------- | ----------------- |
| Card Name | THX               |
| Card Num  | 4916217501611292  |
| CVV       | 123               |
| Expiry    | 12/23             |

## How to use ##

It's good to know which buttons to press if you're using this tool.

- Clear States - Clears all .txt files that are related to responses from the PayHere APIs.
- Show Preapproval Form - Shows the hidden HTML form you are submitting when you press "Preapprove"
- Preapprove - Submits an HTML form to the PayHere Preapproval API. 
- Perform Auth - Performs a REST call to the PayHere Charging API's to get an access token.
- Perform Charge - Performs a REST call to the PayHEre Charging API to perform a test charge.

### Using your own PayHere Sandbox Account ###

This example is hardcoded to my own PayHere Sandbox Account and auth key for consuming the Automated Charging APIs. If you want to use it with your own, make these changes.

1. interface.html - On line `104`, change the `merchant_id` input's value to your own Sandbox Merchant ID.
2. chargeauth.php - On line `3`, change the `$authCode` variable to your own auth token. This can be generated according to the instructions [here](https://support.payhere.lk/api-&-mobile-sdk/payhere-charging).

### Hosting the tool on your own server ###

This example is hosted on my own server in the following directory.
```
http://orangegrounds.space/test/preapproval
```
Replace all instances of this string with the upload directory of your choosing and you should be set. Relative locations are used where applicable therefore, there should be only about 4 search results for this string.

## Structure ##

This is how (and why) the files of the tool are structured as is.

```
DOC ROOT
| - index.php
| - interface.html
| - chargeauth.php
| - chargecharge.php
| - any other temporary logs ...
```

- __index.php__
This acts like the main controller. It does the routing, some html templating for interface.html and includes core constant and function definitions which are used by the other PHP scripts.

For the routing tricks, search for the "Routing based on GET parameters" mark on the file. Routing happens __ONLY__ when there is a `method` query parameter on the request. If invalid or no parameters are specified, interface.html is rendered.

Some very basic string find and replacing is done to inject values to the interface.html file. Values like `{{log}}` will be replaced with the contents of the "log.txt" file that is created when the tool runs.

- __interface.html__
This is how you're going to interace with tool. It lets you perform the requests to the PayHere APIs (literally) with a click of a button. It also lists the response from the last API response. 

- __chargeauth.php__
This PHP script is invoked when you click the "Perform Auth" button.

It uses a predefined authorization code (see Charging API documentation for 'Business App ID and App Secret') to get an OAuth `access_token` that will be used in the chargecharge.php script.

- __chargecharge.php__
This PHP script is invoked when you click the "Perform Charge" button.

It uses the `access_token` saved from the charge_auth.php script and a `customer_token` saved when you "Preapprove" a customer.

- __Auto Generated Text Files__
When you run the tool, it will create the following files at different stages. Just plain 'ol raw text files - nothing binary.

    - log.txt
    - preappstatus.txt
    - chargeauthstatus.txt
    - chargingcharge.txt

If you're running the tool on a hosted server, see if the document directory has write permissions. Otherwise the tool won't work.

## ETC ##

PRs and issues are welcome!