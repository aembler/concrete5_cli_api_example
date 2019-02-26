# concrete5 API Console Example

Here's a quick example of using the Symfony Console library to connect to the new REST API available in concrete5 8.5.0+.

## Requirements

1. Make sure that your concrete5 site is running 8.5.0 or greater.
2. Enable the API from the API Settings page in the Dashboard. Make sure to enable all three grant types (if you want to test the password credentials grant, for example).
3. Install all relevant dependencies by running `composer install`  from the root of this repository.
4. Next, create an integration from the API Settings page in the Dashboard, and make sure to write down the client credentials and client secret.
5. Copy the `configuration.php.dist` file to `configuration.php` in the root of the repository, and fill in the relevant `clientId` and `clientSecret` values.

Now you should be able to run the following commands from the root of this repository.

`./client test:client_credentials`

and 

`./client test:password`

to run tests against your concrete5 client sites. The client credentials test will get system information about the concrete5 site (but will not authenticate as a specific user); the password test will authenticate against the site using a specific user and get information about the site's page trees (assuming that the user in question has access to this information.) 


## Note

This isn't meant to be a full-fledged framework for querying the REST API – it's meant as an example and a proof of concept, and to show how easy it is to create custom clients built on the concrete5 REST API (and others). Please feel free to use whatever you'd like from this repository to interact with the [concrete5 PHP Client](http://github.com/concrete5/concrete5_api_client) and [concrete5 Nightcap](http://github.com/concrete5/nightcap).
 