<?php

include_once 'rpc_client.php';
include_once 'config.php';

function redirect($url) {
    header('Location: '.$url);
    die();
}

function do_add_user() {

   # $_REQUEST is a superglobal and is available in all functions
   if (!isset($_REQUEST['username']) || !isset($_REQUEST['password']) || !isset($_REQUEST['email']) || !isset($_REQUEST['type'])) {
      redirect("login.html");
   }

   $current_user=$_REQUEST['username'];
   $current_password=$_REQUEST['password'];
   $current_email=$_REQUEST['email'];
   $rpc_client = new SampleRpcClient();
   $payload = array('type'=>'add_user','username'=>$current_user,'password'=>$current_password, 'email'=>$current_email);
   print_r($payload);
   $response = $rpc_client->call($payload);
   print_r($response);
   if ($response['code'] == 0) {
//------- Mail Send Code Start -------------//       
$admin_mail = 'mp797@njit.edu';
$user_mail = $_REQUEST['email'];
// Subject
$subject = 'New User Registration';

// Message
$message_admin = '
  <p><strong>Username:</strong> '.$_REQUEST['username'].'</p>
  <p><strong>Email ID:</strong> '.$_REQUEST['email'].'</p>';
$content_msg_admin= '
            <div id="wrapper" dir="ltr" style="background-color: #f5f5f5; margin: 0; padding: 70px 0 70px 0; -webkit-text-size-adjust: none !important; width: 100%;">
               <table height="100%" border="0" cellpadding="0" cellspacing="0" width="100%">
                  <tbody>
                     <tr>
                        <td align="center" valign="top">
                           <div id="template_header_image">
                              <p style="margin-top: 0;"></p>
                           </div>
                           <table id="template_container" style="box-shadow: 0 1px 4px rgba(0,0,0,0.1) !important; background-color: #fdfdfd; border: 1px solid #dcdcdc; border-radius: 3px !important;" border="0" cellpadding="0" cellspacing="0" width="600">
                              <tbody>
                                 <tr>
                                    <td align="center" valign="top">
                                       <!-- Header -->
                                       <table id="template_header" style="background-color: #007954; border-radius: 3px 3px 0 0 !important; color: #ffffff; border-bottom: 0; font-weight: bold; line-height: 100%; vertical-align: middle; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif;" border="0" cellpadding="0" cellspacing="0" width="600">
                                          <tbody>
                                             <tr>
                                                <td id="header_wrapper" style="padding: 36px 48px; display: block;">
                                                   <h1 style="color: #ffffff; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; font-size: 26px; font-weight: 300; line-height: 150%; margin: 0; text-align: left; text-shadow: 0 1px 0 #7797b4; -webkit-font-smoothing: antialiased;">New user sign up details</h1>
                                                </td>
                                             </tr>
                                          </tbody>
                                       </table>
                                       <!-- End Header -->
                                    </td>
                                 </tr>
                                 <tr>
                                    <td align="center" valign="top">
                                       <!-- Body -->
                                       <table id="template_body" border="0" cellpadding="0" cellspacing="0" width="600">
                                          <tbody>
                                             <tr>
                                                <td id="body_content" style="background-color: #fdfdfd;" valign="top">
                                                   <!-- Content -->
                                                   <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                      <tbody>
                                                         <tr>
                                                            <td style="padding: 48px;" valign="top">
                                                               <div id="body_content_inner" style="color: #737373; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; font-size: 14px; line-height: 150%; text-align: left;">
                                                                <p style="margin: 0 0 16px;">'.$message_admin.'</p>
                                                               </div>
                                                            </td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                   <!-- End Content -->
                                                </td>
                                             </tr>
                                          </tbody>
                                       </table>
                                       <!-- End Body -->
                                    </td>
                                 </tr>
                                 <tr>
                                    <td align="center" valign="top">
                                       <!-- Footer -->
                                       <table id="template_footer" border="0" cellpadding="10" cellspacing="0" width="600">
                                          <tbody>
                                             <tr>
                                                <td style="padding: 0; -webkit-border-radius: 6px;" valign="top">
                                                   <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                                      <tbody>
                                                         <tr>
                                                            <td colspan="2" id="credit" style="padding: 0 48px 48px 48px; -webkit-border-radius: 6px; border: 0; color: #99b1c7; font-family: Arial; font-size: 12px; line-height: 125%; text-align: center;" valign="middle">
                                                               <p>Email sent from https://www.siteurl.com</p>
                                                            </td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                </td>
                                             </tr>
                                          </tbody>
                                       </table>
                                       <!-- End Footer -->
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
          ';

$message_user = '
  <p>Thank you!<br>Your account successfully created.</p>
  <p><strong>Username:</strong> '.$_REQUEST['username'].'</p>
  <p><strong>Email ID:</strong> '.$_REQUEST['email'].'</p>';
$content_msg_user= '
            <div id="wrapper" dir="ltr" style="background-color: #f5f5f5; margin: 0; padding: 70px 0 70px 0; -webkit-text-size-adjust: none !important; width: 100%;">
               <table height="100%" border="0" cellpadding="0" cellspacing="0" width="100%">
                  <tbody>
                     <tr>
                        <td align="center" valign="top">
                           <div id="template_header_image">
                              <p style="margin-top: 0;"></p>
                           </div>
                           <table id="template_container" style="box-shadow: 0 1px 4px rgba(0,0,0,0.1) !important; background-color: #fdfdfd; border: 1px solid #dcdcdc; border-radius: 3px !important;" border="0" cellpadding="0" cellspacing="0" width="600">
                              <tbody>
                                 <tr>
                                    <td align="center" valign="top">
                                       <!-- Header -->
                                       <table id="template_header" style="background-color: #007954; border-radius: 3px 3px 0 0 !important; color: #ffffff; border-bottom: 0; font-weight: bold; line-height: 100%; vertical-align: middle; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif;" border="0" cellpadding="0" cellspacing="0" width="600">
                                          <tbody>
                                             <tr>
                                                <td id="header_wrapper" style="padding: 36px 48px; display: block;">
                                                   <h1 style="color: #ffffff; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; font-size: 26px; font-weight: 300; line-height: 150%; margin: 0; text-align: left; text-shadow: 0 1px 0 #7797b4; -webkit-font-smoothing: antialiased;">New user sign up details</h1>
                                                </td>
                                             </tr>
                                          </tbody>
                                       </table>
                                       <!-- End Header -->
                                    </td>
                                 </tr>
                                 <tr>
                                    <td align="center" valign="top">
                                       <!-- Body -->
                                       <table id="template_body" border="0" cellpadding="0" cellspacing="0" width="600">
                                          <tbody>
                                             <tr>
                                                <td id="body_content" style="background-color: #fdfdfd;" valign="top">
                                                   <!-- Content -->
                                                   <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                      <tbody>
                                                         <tr>
                                                            <td style="padding: 48px;" valign="top">
                                                               <div id="body_content_inner" style="color: #737373; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; font-size: 14px; line-height: 150%; text-align: left;">
                                                                <p style="margin: 0 0 16px;">'.$message_user.'</p>
                                                               </div>
                                                            </td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                   <!-- End Content -->
                                                </td>
                                             </tr>
                                          </tbody>
                                       </table>
                                       <!-- End Body -->
                                    </td>
                                 </tr>
                                 <tr>
                                    <td align="center" valign="top">
                                       <!-- Footer -->
                                       <table id="template_footer" border="0" cellpadding="10" cellspacing="0" width="600">
                                          <tbody>
                                             <tr>
                                                <td style="padding: 0; -webkit-border-radius: 6px;" valign="top">
                                                   <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                                      <tbody>
                                                         <tr>
                                                            <td colspan="2" id="credit" style="padding: 0 48px 48px 48px; -webkit-border-radius: 6px; border: 0; color: #99b1c7; font-family: Arial; font-size: 12px; line-height: 125%; text-align: center;" valign="middle">
                                                               <p>Email sent from https://www.siteurl.com</p>
                                                            </td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                </td>
                                             </tr>
                                          </tbody>
                                       </table>
                                       <!-- End Footer -->
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
          ';          
// To send HTML mail, the Content-type header must be set
$headers_mail= "MIME-Version: 1.0\n" .
              "From: PriyaPatel7 <mp797@njit.edu>\n" .
              "Content-Type:text/html;charset=utf-8";

// Mail it
mail($admin_mail, $subject, $content_msg_admin, $headers_mail);
mail($user_mail, $subject, $content_msg_user, $headers_mail);
//------- Mail Send Code End -------------//
      redirect("../login.html");
   } else {
      redirect("../signup.html");
   }
}

do_add_user();

?>
