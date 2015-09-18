CREATE TABLE IF NOT EXISTS `#__salesforce_invoice_number` (
  `number` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `#__salesforce_invoice_number` (`number`) VALUES
(1000);


CREATE TABLE IF NOT EXISTS `#__salesforce_pdftemplate` (
`id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `templatefor` int(1) DEFAULT '0',
  `html` text,
  `srcpdf` varchar(255) DEFAULT NULL,
  `isdefault` int(1) NOT NULL DEFAULT '0',
  `margin_left` double NOT NULL DEFAULT '0',
  `margin_right` double NOT NULL DEFAULT '0',
  `margin_top` double NOT NULL DEFAULT '0',
  `margin_bottom` double NOT NULL DEFAULT '0',
  `paperformat` varchar(32) NOT NULL,
  `orientation` varchar(1) NOT NULL DEFAULT 'P'
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


INSERT INTO `#__salesforce_pdftemplate` (`id`, `name`, `templatefor`, `html`, `srcpdf`, `isdefault`, `margin_left`, `margin_right`, `margin_top`, `margin_bottom`, `paperformat`, `orientation`) VALUES
(1, 'Invoice Template', 0, '<p style="text-align: right;"><img src="images/logo/lismullin_institute_sm.png" alt="lismullin institute sm" width="58" height="110" /></p><br/><p>{CUSTOM_COMPANY}<br />{TITLE}{FIRSTNAME} {LASTNAME}<br /> {CUSTOM_STREET}<br /> {CUSTOM_ZIP} {CUSTOM_CITY}</p><br/><p style="text-align: right;"><strong>Date</strong> {INVOICE_DATE}</p><br/><p></p><p></p><p><strong>Your Invoice {INVOICE_NUMBER}</strong></p><p></p><p></p><table style="width: 100%; font-size: small;" border="1" cellpadding="5" align="center"><tbody><tr><td style="width: 10%; text-align: left;">Pos.</td><td style="width: 10%; text-align: left;">Quantity</td><td style="width: 50%; text-align: left;">Text</td><td style="width: 15%; text-align: right;">Price EUR</td><td style="width: 15%; text-align: right;">Total Price EUR</td></tr><tr><td style="text-align: left;">1</td><td style="text-align: left;">{ATTENDEES}</td><td style="text-align: left;">{COURSE_TITLE}<br />CODE: {COURSE_CODE}<br />From {COURSE_START_DATE} to {COURSE_FINISH_DATE} in {COURSE_LOCATION}</td><td style="text-align: right;">{PRICE_PER_ATTENDEE}</td><td style="text-align: right;">{PRICE_TOTAL}</td></tr><tr><td style="text-align: right;" colspan="3"><strong>TOTAL</strong></td><td style="text-align: right;" colspan="2"><strong>{PRICE_TOTAL}</strong></td></tr></tbody></table><p></p><p></p><p></p><p>Thank you for registering to attend this Lismullin event.</p><p>If you have not already paid online, you are welcome to pay on the day of the event via cash, credit card or cheque.</p><p></p><p></p><p></p><p></p><br/><br/><p style="text-align: center;"><span style="color: #003366;  font-size: 12pt;">Lismullin Institute, 44 Westland Row, Dublin 2, Ireland</span><br /><span style="color: #003366; font-size: 10pt;">Tel: +353 (0)1 676 0731 - Fax: +353 (0)1 676 0603 - Email: <a href="mailto:info@lismullin.ie"><span style="color: #003366;">info@lismullin.ie</span></a></span></p>', '', 1, 20, 20, 20, 20, 'A4', 'P');



CREATE TABLE IF NOT EXISTS `#__salesforce_emailtemplate` (
`id` int(11) NOT NULL,
  `templatefor` int(1) DEFAULT '0',
  `title` varchar(50) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `body` text,
  `recipient` varchar(255) NOT NULL,
  `bcc` varchar(255) NOT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `isdefault` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


INSERT INTO `#__salesforce_emailtemplate` (`id`, `templatefor`, `title`, `subject`, `body`, `recipient`, `bcc`, `status`, `isdefault`) VALUES
(1, 0, 'Booking Confirmation', 'Your Receipt of booking for Course: "{COURSE_TITLE}"', '<p><img style="float: left;" src="cid:logo_id" alt="lismullin Institute" width="105" height="200" /></p><br/><p>&nbsp;</p><br/><p>Dear {SALUTATION} {LASTNAME},</p><br/><p>Thank you for making a booking with Lismullin Institute.</p><br/><p>We have attached an invoice (pdf format) to this email confirming your booking and outlining the payment details. We also provide a summary of the booking details below.</p><br/><p>If you have any queries on your booking please feel free to email or call us at the contact details below.</p><br/><p>Kind regards,</p><br/><p>Anne Tighe <br />Lismullin Institute <br />44 Westland Row <br />Dublin 2</p><br/><p>P: 01 676 0731 <br />E: <a href="mailto:info@lismullin.ie">info@lismullin.ie</a><br /><a href="http://www.lismullin.ie">www.lismullin.ie</a></p><br/><p>&nbsp;</p><br/><p><strong>Billling Address:<br /></strong>{CUSTOM_COMPANY}<br />{SALUTATION} {TITLE}{FIRSTNAME} {LASTNAME}<br />{CUSTOM_STREET}<br />{CUSTOM_ZIP} {CUSTOM_CITY}<br />{CUSTOM_COUNTRY}<br /><br />Tel: {CUSTOM_PHONE}</p><br/><p><strong>&nbsp;</strong></p><br/><p><strong>Your Booking Information</strong>:<br />Title: {COURSE_TITLE}<br />Date: {COURSE_START_DATE}&nbsp;to {COURSE_FINISH_DATE}<br />Location: {COURSE_LOCATION}<br /><br /></p><br/><p>Price:&nbsp;{PRICE_TOTAL} EUR</p>', '{EMAIL}', '{ADMIN_CUSTOM_RECIPIENT}', NULL, 1),
(2, 1, 'Notification of New Course Dates', 'New date for the Course "{COURSE_TITLE}"', '<p>Hi {SALUTATION} {TITLE}{LASTNAME},</p><br/><p><span id="result_box" lang="en"><span class="hps">You are receiving this</span> <span class="hps">automatic e-mail</span> <span class="hps">notification</span> <span class="hps">because you</span> <span class="hps">are interested</span> <span class="hps">in a course</span> <span class="hps">which is available</span><span class="hps">.</span></span></p><br/><p>Code: {COURSE_CODE}<br />Title: {COURSE_TITLE}<br />Date: {COURSE_START_DATE} bis {COURSE_FINISH_DATE}<br />Location: {COURSE_LOCATION}<br />Speaker(s): {TUTOR}</p><br/><p>Price:&nbsp;{PRICE_TOTAL} EUR</p><br/><p>{COURSE_INTROTEXT}</p><br/><p>For more information about this course please <a title="Contact Lismullin Conference Centre" href="index.php?option=com_contact&amp;view=contact&amp;catid=11:general&amp;id=3-lismullin-conference-centre&amp;Itemid=128">contact us</a>.</p>', '{EMAIL}', '{ADMIN_CUSTOM_RECIPIENT}', NULL, 1);


