<?php require_once("../inc-global.php"); ?>
<?php
$page = "thankyou";
$pagename = "thankyou";
$title = "Thank You - Amavi Evershine";
$description = "";
$keywords = "";
$image = "seo";
?>
<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
  <?php if (isset($_GET['reg']) && $_GET['reg'] == 'success') { ?>
    <script>setTimeout(function(){ window.history.back(); }, 15000);</script>
    <?php
    $name = isset($_GET['name']) ? $_GET['name'] : '';
    $email = isset($_GET['email']) ? $_GET['email'] : '';
    $phone = isset($_GET['phone']) ? $_GET['phone'] : '';
    $config = isset($_GET['config']) ? $_GET['config'] : '';
    $timeline = isset($_GET['timeline']) ? $_GET['timeline'] : '';
    if ($email != "") {
      require $leadsendmail;
    }
    ?>
  <?php } else { ?>
    <script>setTimeout(function(){ window.history.back(); }, 8000);</script>
  <?php } ?>
</head>
<body>
  <div style="display:flex;align-items:center;justify-content:center;min-height:100vh;font-family:sans-serif;text-align:center;padding:20px;">
    <div>
      <?php if (isset($_GET['reg']) && $_GET['reg'] == 'success') { ?>
        <h1>Thank you for your interest!</h1>
        <p>One of our representatives will contact you shortly with more information.</p>
      <?php } else { ?>
        <h1>Something went wrong!</h1>
        <p>Your details failed to submit. Please try again.</p>
      <?php } ?>
      <button class="btn" onclick="window.history.back()">Go Back</button>
    </div>
  </div>
</body>
</html>
