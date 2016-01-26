<html>
<!--
   Copyright 2015 Jason A. Everling

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
-->
<!--

    For Connecting CAS SSO to SonisWeb
    Using phpCAS to pass attributes to script

    By: Jason A. Everling
    Email: jeverling@bshp.edu
-->
<body>

<?php
// Load the settings from config file
require_once '../config.php';

// Load the CAS lib
require_once '../cas/CAS.php';

// Enable debugging
//phpCAS::setDebug();

// Initialize phpCAS
phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context);

// For production use set the CA certificate
//phpCAS::setCasServerCACert(../CACert.pem);
phpCAS::setNoCasServerValidation();

// force CAS authentication
phpCAS::forceAuthentication();

//Get Attribute, Change this to match your Student ID Attribute
$user = phpCAS::getUser();

//Prepared Statement
$stmt = $con->prepare("SELECT soc_sec,pin FROM name WHERE ldap_id = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$stmt->bind_result($id, $pass);
$stmt->fetch();
$stmt->close();

?>
<div id="postForm">
   <form action="<?PHP echo $studentURL;?>" method="post" id="preSSO" >
   <input type="hidden" name="SOC_SEC" value="<?PHP echo $id;?>" />
   <input type="hidden" name="PIN" value="<?PHP echo $pass;?>" />
   <input type="submit" style="display:none;"/>
   </form>
</div>
<div id="notice">
   <p style="font-size:1.5em;" >Please wait while you are signed in......</p>
</div>
</body>
<script type="text/javascript">
    function postSSOForm () {
        var frm = document.getElementById("preSSO");
        frm.submit();
    }
    window.onload = postSSOForm;
</script>
</html>

$name = $_GET['username'];
 
if ($stmt = $mysqli->prepare("SELECT password FROM tbl_users WHERE name=?")) {
 
    // Bind a variable to the parameter as a string. 
    $stmt->bind_param("s", $name);
 
    // Execute the statement.
    $stmt->execute();
 
    // Get the variables from the query.
    $stmt->bind_result($pass);
 
    // Fetch the data.
    $stmt->fetch();
 
    // Display the data.
    printf("Password for user %s is %s\n", $name, $pass);
 
    // Close the prepared statement.
    $stmt->close();
 
}
