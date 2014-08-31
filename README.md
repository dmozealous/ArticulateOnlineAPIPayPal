Articulate Online API and PayPal integration
===========================================

Requirements for this to work:

A webserver that supports PHP
NuSoap  -You can download NuSoap from from SourceForge.
How it works:

A user is directed to PayPal to purchase a course.
The user pays you via PayPal
PayPal automatically forwards the user back to your site
Behind the scenes my code validates the transaction was successful (verifies they paid you)
My code then creates a user in your AO account and adds them to the specified group
My code then forwards the user an email with login instructions to access the course
The user is given a nice status message telling them about the transaction
Here is what we are going to do:

Create a Paypal business account (it is free)
Enable the AO API on your account
Configure your Paypal business account
Setup your courses in AO
Configure the sample code I have provided to work with your account
Setup Buy Now links for your content items
Test everything
Notes About Paypal:

I am not the biggest fan of PayPal’s fee structure.  They make it overly complex to figure out what the actual fee is that they charge and seems arbitrary that they charge based on a percent of the transaction, but as a general rule, each transaction will cost you (the seller) $0.30 up front, and then %3 of the total transaction.  All the charges for using Paypal go to the seller, so you will be paying for any fees.  You can find a detailed fee structure description on Paypals site.

Some of the code I use in this example is actually code given by Paypal.  I have noted in each of my files if the code comes from me, or Paypal.

1. Creating a Paypal business account

First thing you need to do is create a PayPal Business account.

Create a Paypal account at www.paypal.com if you have not already created one
When setting up the account create a ‘Business’ account or if you already have a personal account, convert it to a Business account.  Business accounts are free to setup.
2. Enable the AO API on your account

Contact your Articulate sales representative to have the AO API feature added to your account.  You can contact your sales represntative here.
Once it is enabled on your account, turn it on. (Go to Settings> Other Settings>Enable API Access in your Articulate Online account.)
3. Configure your paypal business account:

In order to use my method, you will need to enable Payment Data Transfer (PDT) on your Paypal account.  PDT allows you to recieve notification of successful payments as they are made.

Follow these steps to configure your account in PayPal

Log in to your PayPal account.
Click the Profile subtab.
Click Website Payment Preferences in the Seller Preferences column.
Under Auto Return for Website Payments, click the On radio button.
For the Return URL, enter the URL on your site that will receive the transaction ID posted by PayPal after a customer payment.  You want to point this to the “return.php” page that I have included in this sample.  So remember this location for later.
Under Payment Data Transfer, click the On radio button.
Click Save.
Click Website Payment Preferences in the Seller Preferences column.
Important: Scroll down to the Payment Data Transfer section of the page to view your PDT identity token.  Note this for later.
4. Setup your courses in Articulate Online

I have setup the sample code to automatically add a user to a specified group on your account, you can then setup your content so only specified groups have access to the content you specify.

Here is what you need to do on the Articulate Online side to get this working:

Create a group in Articulate Online (People>Groups tab)
Upload your content to Articulate Online
Set the Permissions on that content is Private and select the group you created in step 1
Save your Permissions changes
Now you need to get the GroupID of the group that you created so you can configure the sample code:

Go to the People tab
Select the Groups tab
Select the group you created
Click the “View activity report for this group”
In the URL field of the browser, you should see a URL like this:
http://blah.articulate-online.com/Reports/GroupActivity.aspx?Cust=89134&GroupID=846dd7a2-bc35-4fa6-957c-7d1dea56599b&Range=all+time

The GroupID is the letter/number combination between the “GroupID=” and the “&Range=all+time”, so in this example it is:
846dd7a2-bc35-4fa6-957c-7d1dea56599b
Note the GroupID for later

5. Configure the sample code I have provided to work with your account

The sample code that I provided comes with 5 files:

config.php – this contains your configuration options.  This is the only file that you need to edit.
findUser.php – checks to see if the user already exists on the account.
createuser.php – creates the user on the account if they don’t already exist as a user
addToGroup.php – adds the user to the group that you created.
return.php – This is the page that you specify as the return URL.  This will handle the verification of the transaction, then will call findUser.php, createuser.php, addToGroup.php to add the user to your account.
The only file that you need to edit is the config.php file:

Once you install NuSoap, change this the soapLoc to point to the location you installed NuSoap:
$soapLoc = ‘../../lib/nusoap.php’;

Replace the “hRUxKv_GCIFIASioHVFuG4DEJ97kUwdEWIuqhxh1U8z7B1pHbg-n8-xWMJLq” with the authorization token from your account (from Step 3.8 above):
$auth_token = “hRUxKv_GCIFIASioHVFuG4DEJ97kUwdEWIuqhxh1U8z7B1pHbg-n8-xWMJLq”;

Replace the email address, password, and CustomerID with the Customer ID from your Articulate Online account.  Your customer ID can be found by looking at the URL in your browser’s address bar after logging in to Articulate Online. The numeric value specified in the title bar for CustID is your customer id. For example, if the URL in your address bar is https://training.articulate-online.com/Content/Manage.aspx?CustID=12345, your customer id is 12345.

$AOemail = ‘dmozealous@articulate.com’;
$AOpassword = ‘apple’;
$AOCustomerID = ’89134′;

Replace the GroupID with the GroupID noted in Step 4.9 above:
$AOGroupID = ’9204f95a-a6e4-422c-b9b4-e4ff837a905a’;

Replace the Account URL with the Account URL for your account (must be HTTPS://):
$AOAccountURL = ‘https://blah.articulate-online.com’;

An email will be sent to the purchaser with login instructions on how to access the account, and contain their email address.  You can configure the text of that email by replacing the comment text with your specified text:
$comment = ‘Thank you for purchasing access to this site.  You can login to view your training with the login details below.’;

Now upload the sample content to your website.  Remember that the return.php file location must be at the location that you specified above in step 3.5.

6. Setup Buy Now links for your content items

Now it is time to setup “Buy Now” links for the content items you want to sell.

To do this:

Login to PayPal
Select the Merchant Services tab
Click the “Buy Now” button
Configure the options as you chose
Click Create Button
Tip: Make the Item ID a unique value.  This will help you in the long run in keeping track of what people purchased.

Tip 2: During the testing phase of this as I tried to get it working, I setup my Buy Now links to only charge $.10, this is helpful so you don’t get screwed on the transaction fees during the testing phase.

After you click the Create Button link, it will give you the code to incorporate the Buy Now button in your website.

7. Test Everything

You should now be able to test it out.  If everything works as expected, great, you are ready to roll.  If something went wrong, go back over these instructions and verify that you followed them correctly.  If you still have problems, let me know.

