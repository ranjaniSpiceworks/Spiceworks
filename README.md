# Spiceworks

Testcases created using Phpunit for http://community.spiceworks.com/

Installation and running:

To execute the script, download phpunit following instructions in https://phpunit.de/ and you should have selenium server running in your local (Host is set to local and browser: firefox)

To execute the whole file (test suite), run --> phpunit TestSpiceworks.php on the directory where you have this file stored. 
This will run the followking test cases:
1. Login Page
2. Verify Resources Header in Networking Page
3. Verify VendorPages Under Resources in Networking Page
4. Verify Company "Cisco" is under the 'Groups' tab
5. Verify White Paper Icon is under  ResourcesTab next to 'Seven Habits of Highly Successful MSPs'.

Future Enhancements:

This is a simple Automation test suite designed to check some features in the Spiceworks community page.

This testsuite right now is designed with the DOM elements and data defined as mutli-dimesion array in the same suite. Further improvements can be done by creating a framework to run by a particular testcase, create config files to run on different browsers, environment  etc can be done.


