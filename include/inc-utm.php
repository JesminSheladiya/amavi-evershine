<?php
$utm_source = (isset($_POST['USOURCE'])) ? mysqli_real_escape_string($connection, trim($_POST['USOURCE'])) : '';
$utm_medium = (isset($_POST['UMEDIUM'])) ? mysqli_real_escape_string($connection, trim($_POST['UMEDIUM'])) : '';
$utm_campaign = (isset($_POST['UCAMPAIGN'])) ? mysqli_real_escape_string($connection, trim($_POST['UCAMPAIGN'])) : '';
$utm_content = (isset($_POST['UCONTENT'])) ? mysqli_real_escape_string($connection, trim($_POST['UCONTENT'])) : '';
$utm_term = (isset($_POST['UTERM'])) ? mysqli_real_escape_string($connection, trim($_POST['UTERM'])) : '';

$utm_isource = (isset($_POST['IUSOURCE'])) ? mysqli_real_escape_string($connection, $_POST['IUSOURCE']) : '';
$utm_imedium = (isset($_POST['IUMEDIUM'])) ? mysqli_real_escape_string($connection, $_POST['IUMEDIUM']) : '';
$utm_icampaign = (isset($_POST['IUCAMPAIGN'])) ? mysqli_real_escape_string($connection, $_POST['IUCAMPAIGN']) : '';
$utm_icontent = (isset($_POST['IUCONTENT'])) ? mysqli_real_escape_string($connection, $_POST['IUCONTENT']) : '';
$utm_iterm = (isset($_POST['IUTERM'])) ? mysqli_real_escape_string($connection, $_POST['IUTERM']) : '';

$utm_initial_referrer = (isset($_POST['IREFERRER'])) ? mysqli_real_escape_string($connection, trim($_POST['IREFERRER'])) : '';
$utm_last_referrer = (isset($_POST['LREFERRER'])) ? mysqli_real_escape_string($connection, trim($_POST['LREFERRER'])) : '';
$utm_landing_page = (isset($_POST['ILANDPAGE'])) ? mysqli_real_escape_string($connection, trim($_POST['ILANDPAGE'])) : '';
$utm_visits = (isset($_POST['VISITS'])) ? mysqli_real_escape_string($connection, trim($_POST['VISITS'])) : '';
