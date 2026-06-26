<?php
require $smtp;

$subject = $emailsendername . " | " . $emailsubject;

$toname = $name;
$toemail =  $email;
$mail->setFrom($sendemailid, $emailsendername);

if ($site == "live") {
    if (strpos($bcc, ",") !== false) {
        $bcclist = explode(",", $bcc);
        for ($i = 0; $i < count($bcclist); $i++) {
            $mail->addBCC($bcclist[$i], $emailsendername);
        }
    } else {
        if ($bcc != "") {
            $mail->addBCC($bcc, $emailsendername);
        }
    }
} else {
    if (strpos($testbcc, ",") !== false) {
        $testbcclist = explode(",", $testbcc);
        for ($i = 0; $i < count($testbcclist); $i++) {
            $mail->addBCC($testbcclist[$i], $emailsendername);
        }
    } else {
        if ($testbcc != "") {
            $mail->addBCC($testbcc, $emailsendername);
        }
    }
}
$mail->Subject = $subject;

$body = "<table width='100%' border='0'>
    <tr>
        <td align='center'><table style='max-width:600px;margin:0 auto;width:100%; background-color:#f4f4f4; padding:13px;' cellpadding='0' cellspacing='0' border='0' width='600px'>
            <tbody style='background-color:#fff;'>
              <tr>
                <td>
                    <a href='" . $siteurl . "' style='text-decoration:none;vertical-align:top;' target='_blank'>
                        <img src='" . $emailbanner . "' width='600' alt='" . $sitename . "' style='border-bottom: 12px solid #f4f4f4;'>
                    </a>
                </td>
              </tr>
              <tr>
                <td style='padding:30px;'><div style='font-size:26px;font-weight:bold;text-align:center;color:#000000;margin-bottom:20px;font-family:Tahoma, Geneva, sans-serif;'> Thank You!</div>
                </td>
              </tr>
              <tr>
                <td>
                    <table width='100%' border='0' cellspacing='0' cellpadding='0' style='max-width: 420px;margin: 0 auto;'>
                        <tr>
                          <td style='border-bottom:1px solid #E4E4E4;font-size: 15px;padding-top: 9px;padding-bottom:9px;width: 50%;font-weight: bold;color: #999999;font-family: Tahoma,Geneva,sans-serif;'>Name</td>
                          <td style='border-bottom: 1px solid #E4E4E4;font-size: 15px;padding-top: 9px;padding-bottom:9px;width: 50%;color: #999999;font-family: Tahoma,Geneva,sans-serif;'>" . $name . "</td>
                        </tr>
                        <tr>
                          <td style='border-bottom:1px solid #E4E4E4;font-size: 15px;padding-top: 9px;padding-bottom:9px;width: 50%;font-weight: bold;color: #999999;font-family: Tahoma,Geneva,sans-serif;'>Phone Number</td>
                          <td style='border-bottom: 1px solid #E4E4E4;font-size: 15px;padding-top: 9px;padding-bottom:9px;width: 50%;color: #999999;font-family: Tahoma,Geneva,sans-serif;'>" . $phone . "</td>
                        </tr>
                        <tr>
                          <td style='border-bottom:1px solid #E4E4E4;font-size: 15px;padding-top: 9px;padding-bottom:9px;width: 50%;font-weight: bold;color: #999999;font-family: Tahoma,Geneva,sans-serif;'>Email</td>
                          <td style='border-bottom: 1px solid #E4E4E4;font-size: 15px;padding-top: 9px;padding-bottom:9px;width: 50%;color: #999999;font-family: Tahoma,Geneva,sans-serif;'>" . $email . "</td>
                        </tr>";
$config_display = !empty($config) ? "<tr>
  <td style='border-bottom:1px solid #E4E4E4;font-size: 15px;padding-top: 9px;padding-bottom:9px;width: 50%;font-weight: bold;color: #999999;font-family: Tahoma,Geneva,sans-serif;'>Configuration</td>
  <td style='border-bottom: 1px solid #E4E4E4;font-size: 15px;padding-top: 9px;padding-bottom:9px;width: 50%;color: #999999;font-family: Tahoma,Geneva,sans-serif;'>" . ($config ?? '') . "</td>
</tr>" : "";

$timeline_display = !empty($timeline) ? "<tr>
  <td style='border-bottom:1px solid #E4E4E4;font-size: 15px;padding-top: 9px;padding-bottom:9px;width: 50%;font-weight: bold;color: #999999;font-family: Tahoma,Geneva,sans-serif;'>Timeline</td>
  <td style='border-bottom: 1px solid #E4E4E4;font-size: 15px;padding-top: 9px;padding-bottom:9px;width: 50%;color: #999999;font-family: Tahoma,Geneva,sans-serif;'>" . ($timeline ?? '') . "</td>
</tr>" : "";

$body .= "$config_display$timeline_display</table>
                </td>
              </tr>
              <tr>
                <td style='padding:30px;'><div style='font-size:14px;text-align:center;color:#000000;margin-bottom:20px;font-family:Tahoma, Geneva, sans-serif;'>" . $mailmessage . "</div></td>
              </tr>
            </tbody>
          </table>
        </td>
    </tr>
</table>";
$mail->MsgHTML($body);
$sendEmail = $mail->Send();
