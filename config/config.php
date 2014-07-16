// =========================================================================
//    ______                                              
//   / __/ /____ ________ ____ ___ _  ___ ___             
//  _\ \/ __/ _ `/ __/ _ `/ _ `/  ' \/ -_|_-<             
// /___/\__/\_,_/_/  \_, /\_,_/_/_/_/\__/___/             
//   _____          /___/        __  ___                __
//  / ___/__  ___  / /________  / / / _ \___ ____  ___ / /
// / /__/ _ \/ _ \/ __/ __/ _ \/ / / ___/ _ `/ _ \/ -_) / 
// \___/\___/_//_/\__/_/  \___/_/ /_/   \_,_/_//_/\__/_/ 
// =========================================================================
// Copyright (c) Stargames Control Panel - Licensed under GNU GPL.
// See LICENSE File
// =========================================================================
// Project Lead by: Mysterious
// =========================================================================

<?php
// Database Settings
$CONFIG['sql_host']			=	'localhost';                    // SQL Host
$CONFIG['sql_username']		=	'localhost';                    // SQL Username
$CONFIG['sql_password']		=	'localhost';                    // SQL Password
$CONFIG['sql_dbname']		=	'localhost';                    // Ragnarok Database Name
$CONFIG['sql_cpdbname']		=	'localhost';                    // Stargames Control Panel Database
$CONFIG['mysql_charset']	=	'auto';                         // MySQL charset (auto, tis620, UTF8 and etc)
$CONFIG['server_name']		=	'Ragnarok Online';              // Server Name
$CONFIG['language']			=	'English';                      // Default CP Language (English or Thai)
$CONFIG['default_theme']	=	'MorrocGrey';                   // Default CP Theme
$CONFIG['time_offset']		=	'GMT+0700';                     // Time Zone (default : GMT+0700)
$CONFIG['admin_email']		=	'admin@localhost.com';          // Email Address
$CONFIG['gzip']				=	'0';                            // Enable Gzip (0: disable 1: enable)
$CONFIG['save_type']		=	'0';                            // Save Type (0: Cookie 1: Session)
$CONFIG['account_id_start']	=	'20000000';                     // Starting Account ID (Default: 20000000)

// SMTP Settings
$CONFIG['smtp_host']		=	'localhost';                    // SMTP Host (Default: localhost)
$CONFIG['smtp_port']		=	'25';                           // SMTP Port (default: 25)

// Main Page Settings
$CONFIG['language_select_mode']			=	'0';                // Enable Language Select? (0: Disable 1: Enable)
$CONFIG['theme_select_mode']			=	'0';                // Enable Theme Select? (0: Disable 1: Enable)
$CONFIG['show_ro_news_per']				=	'10';               // How many news lines on Homepage?
$CONFIG['show_last_topic_reply']		=	'1';                // Show Last Topic Reply on Homepage? (0: No 1: Yes)
$CONFIG['show_last_topic_reply_per']	=	'5';                // How many Last Topic reply lines on Homepage?
$CONFIG['show_guild_standing']			=	'1';                // Show Guild Castle Standing on Homepage? (0: No 1: Yes)
$CONFIG['show_all_id']					=	'0';                // Show total accounts and total characters in the left menu? (0: No 1: Yes)
$CONFIG['lost_pass_mode']				=	'1';                // Enable Lost Password? (0: Disable 1: Enable)

// Administrator Settings
$CONFIG['AM_per_page']			=	'30';                       // How many account line on Account Mangement Page?
$CONFIG['deluser_mode']			=	'1';

// Members Settings
$CONFIG['download_mode']		=	'1';                        // Can Guests use the Download Menu? (0: No 1: Yes)

// Player/Guild Ranking Settings
$CONFIG['player_rank_mode']		=	'1';            // Can members use the Player Ranking? (0: No 1: Yes 2: Administrator Only)
$CONFIG['guild_rank_mode']		=	'1';            // Can members use Guild Ranking? (0: No 1: Yes 2: Administrator Only)
$CONFIG['show_gm_ranking']		=	'0';            // Show GM users in Player Ranking? (0: No 1: Yes)
$CONFIG['min_groupid_ranking']	=	'1';            // Minimum Group Level to not show in Player Ranking (By default, players are Group: 0)
$CONFIG['guild_per_page']		=	'50';           // How many guild lines on Guild Ranking Page?

// Registration Settings
$CONFIG['register_mode']		=	'2';            // Registration Mode (0: Closed 1: Enable 2: Enable with E-Mail Activation)
$CONFIG['md5_support']			=	'0';            // Use MD5 Encryption? (0: No 1: Yes)
$CONFIG['security_mode']		=	'3';            // Registration-Security Code Mode (0: Unuse 1: Number 2: Alphabet 3: Number and Alphabet)

// Login Settings
$CONFIG['change_password']			=	'1';            // Can members change password? (0: No 1: Yes)
//$CONFIG['change_password_with_sls']	=	'1';        // Can members change SLS password? (0: No 1: Yes)
$CONFIG['change_slspassword']		=	'1';            // Can members change SLS password? (0: No 1: Yes)
$CONFIG['change_email']				=	'1';            // Can members change Email? (0: No 1: Yes)

// Character Management Settings
$CONFIG['char_manage_mode']	=	'1';                // Can members use Character Management? (0: No 1: Yes 2: Administrator Only)
$CONFIG['manage_zeny_mode']	=	'0';                // Can members manage their zeny? (0: No 1: Yes)
$CONFIG['max_zeny']			=	'1000000000';       // Max Zeny for Characters? (default: 1000000000) - EXPLOITABLE

// Status Server Settings
$CONFIG['check_server']			=	'0';            // Check Server Status? (0: No 1: Yes)
$CONFIG['maintenance']			=	'0';            // Use Maintenance Mode? (0: No 1: Yes)
$CONFIG['time_check_intervals']	=	'120';          // Time intervals for checking server status (In Seconds)
$CONFIG['server_ip']			=	'127.0.0.1';    // Server IP Address
$CONFIG['loginport']			=	'6900';         // Login Port
$CONFIG['charport']				=	'6121';         // Character Port
$CONFIG['mapport']				=	'5121';         // Map Port

// Forum Settings
$CONFIG['forum_name']			=	'Ragnarok Online Forum';       // Community Board Name
$CONFIG['uploads_mode']			=	'1';                           // Enable Uploading? (0: No 1: Yes)
$CONFIG['uploads_folder']		=	'uploads';                     // Uploads Folder
$CONFIG['avatar_folder']		=	'uploads/avatars';             // Avatar Folder Name
$CONFIG['upload_avatar']		=	'1';                           // Can members upload Avatar? (0: No 1: Yes)
$CONFIG['uploads_size']			=	'200';                         // Maximum uploads file sizes (Kb.)
$CONFIG['avatar_size']			=	'50';                          // Maximum avatar file sizes (Kb.)
$CONFIG['max_img_width']		=	'600';                         // Resize images when width is over? (In Pixels)
$CONFIG['max_img_height']		=	'0';                           // Resize images when heigh is over? (In Pixels)
$CONFIG['delay_post']			=	'30';                          // Flood Protection Timeout (seconds)
$CONFIG['t_per_page']			=	'15';                          // Maximum topic per forum page
$CONFIG['per_page']				=	'20';                          // Maximum reply per topic showing page
$CONFIG['max_post_length']		=	'20480';                       // Maximum Post Length
$CONFIG['max_signature_length']	=	'500';                         // Maximum signature length
$CONFIG['guest_can_post']		=	'0';                           // Can Guests post/reply to topics? (0: No 1: Yes)

// Log Setting
$CONFIG['log_select']		=	'0';             // Log SELECT queries?
$CONFIG['log_insert']		=	'0';             // Log INSERT queries?
$CONFIG['log_update']		=	'0';             // Log UPDATE queries?
$CONFIG['log_delete']		=	'0';             // Log DELETE queries?
$CONFIG['log_register']		=	'1';             // Log REGISTER queries?

// CP Settings
$CONFIG['height']			=	'900';           // CP Height Pixels (You can use %)
$CONFIG['width']			=	'850';           // CP Width Pixels (You can use %)

// Vote 4 Points Settings
define('VOTE_TIME', 24 );				// Time in hours until the next vote will count. Default: 24
define('VOTE_LINK', serialize( array(	// These are just testable links. Don't forget to change the
										// links below to your voting sites.
				1 => 'http://www.xtremetop100.com/in.php?site=1132328646',
				2 => 'http://www.gtop100.com/in.php?site=67574&cookie_test=true',
				3 => 'http://www.gamesites200.com/ragnarok/in.php?id=25314'
		))
	);
?>
