If running this script on a local machine with a self-signed cert, you'll need to get a pem file (full chain) for that cert and provide it to the requests post call in the `verify` parameter. Refer to this stackoverflow article for an overview of how to get the pem file using firefox: https://stackoverflow.com/questions/30405867/how-to-get-python-requests-to-trust-a-self-signed-ssl-certificate

