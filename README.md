# WAAutoReply
Auto reply to incoming messages on Nexmo WhatsApp channel

# Setup

1. Load index.php in browser
2. Add your WhatsApp for business number linked to Nexmo account
3. Add the auto reply text
4. Enter the application id to which the WhatsApp number is linked
5. Upload the privatekey corresponding to application id in step 4
6. Save configuration
7. Goto dashboard.nexmo.com and configure the inbound URL of your application to autoreply.php
8. configure status callback URL to a valid URL that returns a 200 OK response

# Note
1. make sure keys and config folders are writable by the webserver user (apache, httpd etc)
2. apply security rules so no one can download the private key.
