<?php

namespace AI\ResponsiveBundle\Controller;
use AI\ResponsiveBundle\Model\Tracking;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;
use AI\ResponsiveBundle\Entity\WebsiteDownload;
use AI\ResponsiveBundle\Entity\WhitepaperDownload;
use AI\ResponsiveBundle\Model\Data;

class emailPreferenceCenterController extends Controller {

	private function getLanguages() {
		return array('Arabic' => 'Arabic (عربى)', 'English' => 'English',
			'French' => 'French', 'German' => 'German', 'Spanish' => 'Spanish', 
			'Chinese' => 'Chinese (中文)');
	}
	private function getCountries() {
		// TODO: We should pull this data from the countries.xml file so we're using a central list everywhere
		return array('Afghanistan', 'Albania', 'Algeria', 'American Samoa', 'Andorra', 'Angola', 'Anguilla (UK)', 'Antarctica', 'Antigua And Barbuda', 'Argentina', 'Armenia', 'Aruba', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Barbuda', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bermuda', 'Bhutan', 'Bolivia', 'Bosnia', 'Botswana', 'Brazil', 'British Virgin Islands (UK)', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia', 'Cameroon', 'Canada', 'Cape Verde', 'Central African Republic', 'Chad', 'Chile', 'China', 'Christmas Island', 'Cocos (Keeling) Islands', 'Colombia', 'Comoros', 'Congo. Democratic Republic Of', 'Congo. Republic Of', 'Cook Islands', 'Costa Rica', 'Croatia', 'Cuba', 'Curacao', 'Cyprus', 'Czech Republic', 'Denmark', 'Diego Garcia', 'Djibouti', 'Dominica', 'Dominican Republic', 'Easter Island', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Ethiopia', 'Fiji', 'Finland', 'France', 'French Guiana', 'French Polynesia', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Gibraltar (UK)', 'Greece', 'Greenland', 'Grenada', 'Guadeloupe (France)', 'Guam', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana', 'Haiti', 'Honduras', 'Hong Kong', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland', 'Israel', 'Italy', 'Ivory Coast', 'Jamaica', 'Japan', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Korea (North)', 'Korea (South)', 'Kosovo', 'Kuwait', 'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Macau', 'Macedonia', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marocco', 'Marshall Islands', 'Martinique (France)', 'Mauritania', 'Mauritius', 'Mexico', 'Micronesia', 'Moldova', 'Monaco', 'Mongolia', 'Montenegro', 'Montserrat (UK)', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia', 'Nauru', 'Nepal', 'Netherlands', 'New Caledonia', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'Northern Mariana Islands (US)', 'Norway', 'Oman', 'Pakistan', 'Palau', 'Palestine', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Poland', 'Portugal', 'Puerto Rico (US)', 'Qatar', 'Reunion', 'Romania', 'Russia', 'Rwanda', 'Saint Kitts And Nevis', 'Saint Lucia', 'Saint Pierre And Miquelon', 'Saint Vincent & The Grenadines', 'San Marino', 'Sao Tome & Principe', 'Saudi Arabia', 'Scotland', 'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 'Somaliland', 'South Africa', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Swaziland', 'Sweden', 'Switzerland', 'Syria', 'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand', 'Timor-Leste', 'Togo', 'Tonga', 'Trinidad And Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Turks And Caicos Islands (UK)', 'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican City', 'Venezuela', 'Vietnam', 'Virgin Islands (US)', 'Wales', 'Yemen', 'Yugoslavia', 'Zambia', 'Zimbabwe');
	}
	private function getIndustries() {
		return array('Bodycare, Fashion & Accessories',
			'Electrical & Electronic Products',
			'Food & Food Packaging',
			'Gifts & Premiums',
			'Homeware & Gardenware',
			'Industrial, Construction & Mechanical Items',
			'Printing & Packaging',
			'Textile, Apparel, Footwear & Accessories',
			'Toys & Recreational Items');
	}
	/**
	* @Route("/emailPreferences")
	* @Template()
	*/
	public function emailPreferenceView(Request $request) {
		$twigData = Array();
		$twigData['pageTitle'] = "Email Preference Center";
		//include Tracking data [Begin]
		$locale = $request->getLocale();
		$tracking = new Tracking();
		$twigData['tracking'] = $tracking->getTrackingCode('emailprefcenter', $locale);
		//include Tracking data [End]

		$conn = $this->container->get('DBConnector')->getDBconnection('Data');
		$salesconn = $this->container->get('DBConnector')->getDBconnection('Sales');
		$db = null;
		$salesdb = null;

		if( $conn['error'] ) {
		} else {
			$db = $conn['connection'];
		}
		if( $salesconn['error'] ) {
		} else {
			$salesdb = $salesconn['connection'];
		}

		$subscriber_email = "";
		$setRegRecap = 0;
		$setBarometer = 0;
		$setEvents = 0;
		$identified = false;
		$token = "";
		if (isset($_GET['email']) && $_GET['email'] != "") {
			$subscriber_email = mb_strtolower($_GET['email']);
			$setRegRecap = (isset($_GET['regRecap']) && $_GET['regRecap'] == 'True') ? 1 : 0;
			$setBarometer = (isset($_GET['barometer']) && $_GET['barometer'] == 'True') ? 1 : 0;
			$setEvents = (isset($_GET['events']) && $_GET['events'] == 'True') ? 1 : 0;
			if (isset($_GET['auth_token'])) {
				$token = $_GET['auth_token'];
			}
		}

		$receive_all_emails = "0";
		$rec_barometer  = "1";
		$rec_educational  = $setRegRecap ? "1" : "0";
		$rec_events  = "1";
		$rec_audits  = "1";
		$rec_inspections  = "1";
		$rec_lab  = "1";
		$unsubscribed  = "0";
		$invalid_message = "";
		$is_competitor = false;
		$first_name = "";
		$last_name = "";
		$job_title = "";
		$industry = "";
		$country = "";
		$language = 'English';
		$twigData['emailExists'] = false;
		$twigData['hasPrefs'] = false;
		$twigData['consentDate'] = false;

		if(!is_null($salesdb) && $subscriber_email != "") {
			$sql = "select n.email_status, n.unsubscribed, n.email_invalid_cause, n.prefs_token, n.first_name, n.last_name, n.country, n.job_title, n.industry, n.reg_recap, c.language from newsletters n LEFT JOIN Countries c ON n.country = c.country WHERE Email = '".$salesdb->real_escape_string($subscriber_email)."'";
			$query = mysqli_query($salesdb,$sql);
			$rows = [];
			while($r = mysqli_fetch_assoc($query)) {
				$rows[] = $r;
			}
			if (count($rows) > 0) {
				$row = $rows[0];
				$twigData['emailExists'] = true;
				$status = $row['email_status'];
				if ($row['unsubscribed'] == "Yes") {
					$unsubscribed = true;
					$receive_all_emails = "0";
					$rec_barometer  = "0";
					$rec_educational  = "0";
					$rec_events  = "0";


					if ($status == "Bad Domain") {
						$invalid_message = "Did you mis-type your email?  That domain doesn't appear to accept email.";
					} elseif ($status == "Invalid Email: Format" 
						|| $status == "Invalid Email: Repeating abc or #"
						|| $status == "Invalid Email: Common Sequence"
						|| $status == "Invalid Email: Multiple At Or Slash"
						|| $status == "Invalid Email: Short Part") {
						$invalid_message = 'Your email was found to be invalid by our email platform software.<br>If this is in error and you want to receive emails, please click "Receive All Emails" and then "Update Email Preferences".';
					} elseif ($status == 'Spam Domain') {
						$invalid_message = 'This is a known spam domain and your email has been unsubscribed.<br>Please use a valid email address.';
					} elseif ($row['email_invalid_cause'] == 'Fresh Address Comment'
						|| $row['email_invalid_cause'] == 'Suspect and Not Client'
						|| $row['email_invalid_cause'] == 'Fresh Address Suggested Comment' ) {
						$invalid_message = 'Your email was found to be invalid by our email platform software.<br>If this is in error and you want to receive emails, please click "Receive All Emails" and then "Update Email Preferences".';	
					}
				}
				if ($status == 'Competitor') {
					$is_competitor = true;
				} elseif ($status == 'Role Based') {
					if ($invalid_message != '') {
						$invalid_message .= '<br><br>';
					}
					$invalid_message .= 'This is a role based email which may impact your email deliverability.<br>There’s an increased likelihood of emails being sent to spam and ISP’s limiting the emails you receive.<br>We strongly encourage you to sign up using your professional email.';
				}

				if (! empty($row['prefs_token']) && $row['prefs_token'] === $token) {
					$identified = true;
					$first_name = $row['first_name'];
					$last_name = $row['last_name'];
					$job_title = $row['job_title'];
					$industry = $row['industry'];
				}
				if (! empty($row['language'])) {
					$language = $row['language'];
				}
				if (! empty($row['country'])) {
					$country = $row['country'];
				}
				if (empty($row['reg_recap']) or $row['unsubscribed'] == 'Yes') {
					$rec_educational = '0';
				} else {
					$rec_educational = '1';
				}
			}
		}

		if (!is_null($db) && $subscriber_email != "") {
			if ($setBarometer == 1 || $setRegRecap  == 1 || $setEvents == 1) {
				$setsql = "insert into UnsubscribeList (Email, Educational, PR_Only, Events, Unsubscribed, is_preference_updated) VALUES ("
					.'"'.$db->real_escape_string($subscriber_email).'",'
					. $setRegRecap . "," . $setBarometer . "," . $setEvents . "," 
					. "0,1) on duplicate key update " 
					. (($setRegRecap==1) ? "Educational=1," : "") 
					. (($setBarometer==1) ? "PR_Only=1," : "")
					. (($setEvents==1) ? "Events=1," : "")
					."Unsubscribed=0, is_preference_updated=1";
				$query = mysqli_query($db,$setsql);
				var_dump($setsql);
			}
			$sql = "select * from UnsubscribeList WHERE Email = '".$db->real_escape_string($subscriber_email)."'";
			$query = mysqli_query($db,$sql);
			$rows = array();
			while($r = mysqli_fetch_assoc($query)) {
				$rows[] = $r;
				$twigData['consentDate'] = $r["ActiveConsentDate"];
			}
			if (count($rows) > 0) {
				$twigData['emailExists'] = true;
				$twigData['hasPrefs'] = true;
				
				$rec_educational = (string)$rows[0]['Educational'];
				$rec_barometer = (string) $rows[0]['PR_Only'];
				$rec_events = (string) $rows[0]['Events'];
				
				
				$rec_audits = (string) $rows[0]['Audits'];
				$rec_inspections = (string) $rows[0]['Inspections'];
				$rec_lab = (string) $rows[0]['LabTesting'];

				if ($identified) {
					if (! empty($rows[0]['Industry'])) {
						$industry = $rows[0]['Industry'];
					}
					if (! empty($rows[0]['FirstName'])) {
						$first_name = $rows[0]['FirstName'];
					}
					if (! empty($rows[0]['LastName'])) {
						$last_name = $rows[0]['LastName'];
					}
					if (! empty($rows[0]['JobTitle'])) {
						$job_title = $rows[0]['JobTitle'];
					}
				}

				if ($rows[0]['Unsubscribed'] == 1) {
					$unsubscribed = true;
					$rec_barometer  = "0";
					$rec_educational  = "0";
					$rec_events  = "0";

				}
				if (! empty($rows[0]['Language'])) {
					$language = $rows[0]['Language'];
				}
				if (! empty($rows[0]['Country'])) {
					$country = $rows[0]['Country'];
				}
			}
		}

		if ($rec_educational == "1"
			&& $rec_barometer == "1"
			&& $rec_events == "1") {
			$receive_all_emails = "1";
			$unsubscribed = false;
		} elseif ($rec_educational == "0"
			&& $rec_barometer == "0"
			&& $rec_events == "0") {
			$receive_all_emails = "0";
			$unsubscribed = true;
		}

		// activeConsent
		if (isset($_GET['activeConsent']) && $_GET['activeConsent'] != "") {
			if(strtolower($_GET['activeConsent']) == "yes" || strtolower($_GET['activeConsent']) == "true") {
				$activeConsent = date("Y-m-d g:i:s");
				if($twigData['hasPrefs'] == true) {
					$db->query("UPDATE UnsubscribeList SET ActiveConsentURL = CASE WHEN ActiveConsentURL IS NULL THEN '".$_SERVER['REQUEST_URI']."' ELSE ActiveConsentURL END, ActiveConsentDate = CASE WHEN ActiveConsentDate IS NULL THEN '".$activeConsent."' ELSE ActiveConsentDate END WHERE Email='".mb_strtolower($_GET['email'])."'");
				} else {
					$db->query("INSERT INTO UnsubscribeList (Email, PR_Only, Educational, Events, Audits, Inspections, LabTesting, Unsubscribed, is_preference_updated, ActiveConsentDate, ActiveConsentURL) VALUES ('".mb_strtolower($_GET['email'])."', 1, 0, 1, 1, 1, 1, 0, 1, '".$activeConsent."', '".$_SERVER['REQUEST_URI']."')");
				}
				$twigData['activeConsent'] = true;
			}
		}

		$subscriber_email_id = 0;
		$twigData['competitor'] = $is_competitor;
		$twigData['rec_events'] = $rec_events;
		$twigData['rec_educational'] = $rec_educational;
		$twigData['rec_barometer'] = $rec_barometer;
		$twigData['rec_audits'] = $rec_audits;
		$twigData['rec_inspections'] = $rec_inspections;
		$twigData['rec_lab'] = $rec_lab;
		$twigData['unsubscribed'] = $unsubscribed;
		$twigData['invalid_message'] = $invalid_message;
		$twigData['receive_all_emails'] = $receive_all_emails;
		$twigData['subscriber_email'] = $subscriber_email;
		$twigData['subscriber_email_id'] = $subscriber_email_id;
		$twigData['first_name'] = $first_name;
		$twigData['last_name'] = $last_name;
		$twigData['language'] = $language;
		$twigData['country'] = $country;
		$twigData['industry'] = $industry;
		$twigData['job_title'] = $job_title;
		$twigData['languages'] = $this->getLanguages();
		$twigData['countries'] = $this->getCountries();
		$twigData['industries'] = $this->getIndustries();

		return $this->render('AIResponsiveBundle:emailPreferenceCenter:index.html.twig', $twigData);
	}


	private function js2bin($item) {
		if (isset($item)) {
			if ($item == "true") {
				return "1";
			} else {
				return "0";
			}
		}
		return "";
	}

	/**
	* @Route("/emailPreferences/update")
	* @Method("POST")
	*/
	public function update_email_preference() {
		$obj_result = new \stdclass();
		$obj_result->is_success = false;
		$obj_result->post = $_POST;
		$conn = $this->container->get('DBConnector')->getDBconnection('Data');
		$db = null;

		if( $conn['error'] ) {
		} else {
			$db = $conn['connection'];
		}

		$subscriber_email = "";
		if (isset($_POST['subscriber_email'])) {
			$subscriber_email = $db->real_escape_string(mb_strtolower($_POST['subscriber_email']));
		}
		$new_email = "";
		if (isset($_POST['new_email'])) {
			$new_email = $db->real_escape_string(mb_strtolower($_POST['new_email']));
		}

		$rec_barometer = $this->js2bin($_POST['rec_barometer']);
		$rec_educational = $this->js2bin($_POST['rec_educational']);
		$rec_events = $this->js2bin($_POST['rec_events']);
		$rec_audits = $this->js2bin($_POST['rec_audits']);
		$rec_inspections = $this->js2bin($_POST['rec_inspections']);
		$rec_lab = $this->js2bin($_POST['rec_lab']);

		$first_name = "";
		if (isset($_POST['first_name'])) {
			$first_name = $db->real_escape_string($_POST['first_name']);
		}
		$last_name = "";
		if (isset($_POST['last_name'])) {
			$last_name = $db->real_escape_string($_POST['last_name']);
		}
		$job_title = "";
		if (isset($_POST['job_title'])) {
			$job_title = $db->real_escape_string($_POST['job_title']);
		}

		$country  = "";
		if (isset($_POST['country'])) {
			if (in_array($_POST['country'],$this->getCountries(),true)) {
				$country = $_POST['country'];
			}
		}
		$industry  = "";
		if (isset($_POST['industry'])) {
			if (in_array($_POST['industry'],$this->getIndustries(),true)) {
				$industry = $_POST['industry'];
			}
		}
		$language  = "";
		if (isset($_POST['lang_select'])) {
			if (isset($this->getLanguages()[$_POST['lang_select']])) {
				$language = $_POST['lang_select'];
			}
		}

		
		if (!is_null($db) && $subscriber_email != "") {
			$sql = "select * from UnsubscribeList WHERE Email = '$subscriber_email'";
			$query = mysqli_query($db,$sql);
			$rows = array();
			while($r = mysqli_fetch_assoc($query)) {
				$rows[] = $r;
			}

			$existing_data = Array();
			if (count($rows) > 0) {
				$existing_data = $rows[0];
			}
			
			$sql_set = array();
			
			if ($rec_events != "")  $sql_set['Events'] = $rec_events;
			if ($rec_barometer != "") $sql_set['PR_Only'] = $rec_barometer;
			if ($rec_educational != "") $sql_set['Educational'] = $rec_educational;
			if ($rec_audits != "")  $sql_set['Audits'] = $rec_audits;
			if ($rec_inspections != "") $sql_set['Inspections'] = $rec_inspections;
			if ($rec_lab != "") $sql_set['LabTesting'] = $rec_lab;
			if ($first_name != "") $sql_set['FirstName'] = $first_name;
			if ($last_name != "") $sql_set['LastName'] = $last_name;
			if ($job_title != "") $sql_set['JobTitle'] = $job_title;
			if ($industry != "") $sql_set['Industry'] = $industry;
			if ($country != "") $sql_set['Country'] = $country;
			if ($language != "") $sql_set['Language'] = $language;

			$sql_set['is_preference_updated'] = 1;

			if ($rec_events == "0" && $rec_educational == "0" && $rec_barometer == "0") {
				$sql_set['Unsubscribed'] = 1;
			} else {
				$sql_set['Unsubscribed'] = 0;
			}


			$sql = "";
			if ( !empty($sql_set) ) {
				$sql_set['UserSet'] = "1";
				if ($new_email != "") {
					
					if($sql_set['Unsubscribed'] == 1) {
						$sql = "INSERT INTO UnsubscribeList (Email, ".implode(", ", array_keys($sql_set)).") VALUES ('$new_email', '".implode("', '", $sql_set)."') ON DUPLICATE KEY UPDATE ";
					} else {
						$sql = "INSERT INTO UnsubscribeList (Email, ActiveConsentDate, ActiveConsentURL, ".implode(", ", array_keys($sql_set)).") VALUES ('$new_email', '".date("Y-m-d g:i:s")."', '".$_SERVER['HTTP_REFERER']."', '".implode("', '", $sql_set)."') ON DUPLICATE KEY UPDATE ";
						$sql .= "ActiveConsentDate = CASE WHEN ActiveConsentDate IS NULL THEN '".date("Y-m-d g:i:s")."' ELSE ActiveConsentDate END, ActiveConsentURL = CASE WHEN ActiveConsentURL IS NULL THEN '".$_SERVER['HTTP_REFERER']."' ELSE ActiveConsentURL END, ";
					}

					foreach ($sql_set as $k => $v) {
						$sql .= "$k='".$v."', ";
					}
					$sql = substr($sql, 0, -2) . ";";

					if($db->query($sql)) {
						$obj_result->is_success = true;
					} else {
					}
					$sql_set['Unsubscribed'] = "1";
					$sql_set['Events'] = "0";
					$sql_set['PR_Only'] = '0';
					$sql_set['Educational'] = '0';
				}

				if($sql_set['Unsubscribed'] == 1) {
					$sql = "INSERT INTO UnsubscribeList (Email, ".implode(", ", array_keys($sql_set)).") VALUES ('$subscriber_email', '".implode("', '", $sql_set)."') ON DUPLICATE KEY UPDATE ";
				} else {
					$sql = "INSERT INTO UnsubscribeList (Email, ActiveConsentDate, ActiveConsentURL, ".implode(", ", array_keys($sql_set)).") VALUES ('$subscriber_email', '".date("Y-m-d g:i:s")."', '".$_SERVER['HTTP_REFERER']."', '".implode("', '", $sql_set)."') ON DUPLICATE KEY UPDATE ";
					$sql .= "ActiveConsentDate = CASE WHEN ActiveConsentDate IS NULL THEN '".date("Y-m-d g:i:s")."' ELSE ActiveConsentDate END, ActiveConsentURL = CASE WHEN ActiveConsentURL IS NULL THEN '".$_SERVER['HTTP_REFERER']."' ELSE ActiveConsentURL END, ";
				}

				foreach ($sql_set as $k => $v) {
					$sql .= "$k='".$v."', ";
				}
				$sql = substr($sql, 0, -2) . ";";

				if($db->query($sql)) {
					$obj_result->is_success = true;
				} else {
				}


				if (! $this->container->getParameter("is_prod_server")) {
					$obj_result->sql = $sql;
				}
			} else {
				$obj_result->is_success = true;
			}
		}
		
		print json_encode($obj_result);
		exit(0);
	}
}
