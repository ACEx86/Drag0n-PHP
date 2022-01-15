# Drag0n-PHP Public
Here i build an "old school" management system for servers written in PHP.

This source is not optimized to save you space or time go use a database or DB-Management system if you want.
This 'project' is currently under active build and the code updates are slow since i build it for fun.
# Compatibility
1. For better compatibility on how the php works i suggest to run this on a Linux Server with the latest php version installed.
2. PHP version requirment: 8 or newer
# Logs and Data
The php creates temp files:
1. The IP of those who contact the php files (Hashed)
2. The password users use (Hashed)
3. The access tokens that is created for the client (Hashed)
4. The data that users transfer (Can be encrypted on client side-Users can delete)
5. The Username that user use (Plaintext)
#
Data is saved for 1 day (like IP Logs) or for the period that the user is logged or until deleted/modified.
# Code Files Info
1. <b>register.php</b> is creating an account.
2. <b>login.php</b> handles the login request.
3. <b>logout.php</b> handles the logout.
4. <b>getu.php</b> handles information sended to the client. It sends all the user names contacted the selected user.
5. <b>sendm.php</b> handles information sended from the client. It saves the message but also the name of the selected users for faster mapping and results from <b>getu.php</b>.
6. <b>getm.php</b> handles information sended to client. It sends to the client all the messages from a selected user.
