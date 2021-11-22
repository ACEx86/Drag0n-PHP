# Drag0n-PHP Public
Here i build an "old school" management system for servers written in PHP. This source is not optimized to save you space or time go use a database or DB-Management system if you want.
This 'project' is currently under active build and the code updates are slow since i build it for fun.
# Compatibility
1. For better compatibility on how the php works i suggest to run this on a Linux Server with the latest php version installed.
# Logs and Data
The php creates temp files:
1. The IP of those who contact the php files (Hashed)
2. The password users use (Hashed)
3. The session key that is created for the client (Hashed)
4. The data that users transfers (Encrypted On Client-Users can delete)
5. The Username that user use (Plaintext)
#
Data is saved for 1 day (IP Logs) or for the period that the user is logged or until deleted/modified.
# Code Files Info
1. register.php is creating an account
2. login.php handles the login request
3. logout.php handles the logout
4. getu.php handles information sended to the client. It sends all the user names contacted the selected user.
5. sendm.php handles information sended from the client. It saves the user name to the selected users for faster mapping and results from getu.php and also the sended message.
6. getm.php handles information sended to client. It sends to the client all the messages from a selected user.
