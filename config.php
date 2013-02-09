<?php
// Database Settings
$CONFIG['sql_host']			=	'localhost';			// ไม่ต้องทำอะไร โดยปกติจะเป็น localhost
$CONFIG['sql_username']		=	'localhost';			// ระบุ User ของ SQL
$CONFIG['sql_password']		=	'localhost';			// ระบุ Password ของ SQL
$CONFIG['sql_dbname']		=	'localhost';			// ระบุชื่อฐานข้อมูลของ Ragnarok
$CONFIG['sql_cpdbname']		=	'localhost';			// ชื่อของฐานข้อมูล CP
$CONFIG['mysql_charset']		=	'auto';			// ตั้งค่า Charset ของ Mysql (auto, disable, tis620, UTF8 and etc.)
$CONFIG['server_name']		=	'Ragnarok Online';		// ระบุชื่อเซิร์ฟเวอร์
$CONFIG['language']		=	'English';			// ระบุภาษาของ CP (Thai, English)
$CONFIG['default_theme']		=	'SGCP_Default';		// ระบุชื่อโฟลเดอร์ของ Theme เพื่อให้ใช้เป็น Theme หลัก
$CONFIG['time_offset']		=	'GMT+0700';		// ตั้งค่าโซนเวลา (ค่าปกติ: GMT+0700)
$CONFIG['admin_email']		=	'admin@localhost.com';	// ระบุ E-Mail Address
$CONFIG['gzip']			=	'0';			// ระบุโหมดของ Gzip (0: ไม่ใช้ง่าน 1: เพื่อเปิดใช้งาน Gzip)
$CONFIG['save_type']		=	'0';			// ระบุโหมดของการเก็บข้อมูล (0: Cookie 1: Session)
$CONFIG['account_id_start']		=	'20000000';			// ระบุหมายเลข Account ID เริ่มต้นในฐานข้อมูล Ragnarok (ค่าปกติ: 2000000)

// SMTP Settings
$CONFIG['smtp_host']		=	'localhost';			// SMTP Host (ค่าปกติ: localhost)
$CONFIG['smtp_port']		=	'25';			// SMTP Port (ค่าปกติ: 25)

// Main Page Settings
$CONFIG['language_select_mode']	=	'0';			// ระบุโหมด Language Select (0: ไม่ใช้งาน 1: ใช้งาน)
$CONFIG['theme_select_mode']	=	'0';			// ระบุโหมด Theme Select (0: ไม่ใช้งาน 1: ใช้งาน)
$CONFIG['show_ro_news_per']		=	'10';			// ระบุตัวเลขเพื่อแสดงจำนวนข่าวต่อหนึ่งหน้า
$CONFIG['show_last_topic_reply']	=	'1';			// ระบุโหมดของการแสดงกระทู้ล่าสุดที่มีการตอบใหม่ในหน้าแรก (0: ไม่แสดง 1: เพื่อแสดง)
$CONFIG['show_last_topic_reply_per']	=	'5';			// ระบุตัวเลขเพื่อแสดงจำนวนกระทู้ล่าสุดในหน้าแรก
$CONFIG['show_guild_standing']	=	'1';			// ระบุโหมดของการแสดงกิลดิ์ที่ครอบครองปราสาทในหน้าแรก (0: ไม่แสดง 1: เพื่อแสดง)
$CONFIG['show_all_id']		=	'0';			// ระบุโหมดของการแสดงจำนวนไอดีทั้งหมด (0: ไม่แสดง 1: เพื่อแสดง)
$CONFIG['lost_pass_mode']		=	'0';			// ระบุโหมดระบบลืมรหัสผ่าน (0: ไม่ใช้งาน 1: เปิดใช้งาน)

// Administrator Settings
$CONFIG['AM_per_page']		=	'30';			// ระบุตัวเลขเพื่อแสดงจำนวนไอดีต่อหนึ่งหน้าของ Account Management
$CONFIG['deluser_mode']		=	'1';			// ระบุโหมดสำหรับหน้าลบไอดี (0: ไม่ใช้งาน 1: ใช้งาน)

// Members Settings
$CONFIG['download_mode']		=	'1';			// อนุญาตให้ Guest ใช้งาน Download หรือไม่ (0: ไม่อนุญาต 1: อนุญาต)

// Player/Guild Ranking Settings
$CONFIG['player_rank_mode']		=	'1';			// อนุญาตให้สมาชิกใช้งาน Player Ranking หรือไม่ (0: ไม่อนุญาต 1: อนุญาต 2: เฉพาะ Administrator)
$CONFIG['guild_rank_mode']		=	'1';			// อนุญาตให้สมาชิกใช้งาน Guild Ranking หรือไม่ (0: ไม่อนุญาต 1: อนุญาต 2: เฉพาะ Administrator)
$CONFIG['show_gm_ranking']		=	'0';			// ระบุโหมดของการแสดงรายชื่อ GM ในระบบ Player Ranking (0: ไม่แสดง 1: เพื่อแสดง)
$CONFIG['min_gmlv_ranking']		=	'50';			// ระบุลำดับขั้นต่ำ LEVEL ของ GM เพื่อที่จะไม่ให้แสดงรายชื่อในระบบ Player Ranking
$CONFIG['guild_per_page']		=	'50';			// ระบุจำนวนกิลดิ์ที่ให้แสดงต่อหนึ่งหน้าจัดอันดับกิลดิ์

// Registration Settings
$CONFIG['register_mode']		=	'1';			// ระบุโหมดระบบสมัครสมาชิก (0: ปิดรับสมัคร 1: เปิดรับสมัคร 2: เปิดรับสมัครและใช้ระบบ E-Mail Activation)
$CONFIG['md5_support']		=	'0';			// ระบบ MD5 (0: ไม่ใช้งาน 1: เพื่อเปิดใช้งาน)
$CONFIG['security_mode']		=	'1';			// ระบุโหมดรหัสรักษาความปลอดภัย (0: ไม่ใช้งาน 1: ใช้รหัสเป็นตัวเลข 2: ใช้รหัสเป็นอักษรภาษาอังกฤษตัวพิมพ์ใหญ่ 3: ใช้รหัสเป็นตัวเลขและภาษาอังกฤษตัวพิมพ์ใหญ่)

// Login Settings
$CONFIG['change_password']		=	'1';			// ระบุโหมดสำหรับให้สมาชิกสมารถเปลี่ยนแปลงพาสเวิร์ด (0: เปลี่ยนแปลงไม่ได้ 1: เปลี่ยนแปลงได้)
$CONFIG['change_password_with_sls']	=	'1';			// ให้ตรวจสอบ SLS Password เมื่อเปลี่ยนรหัสผ่านหรือไม่ (0: ไม่ใช้งาน 1: ใช้งาน)
$CONFIG['change_slspassword']	=	'1';			// ระบุโหมดสำหรับให้สมาชิกสมารถเปลี่ยนแปลง SLS พาสเวิร์ด (0: เปลี่ยนแปลงไม่ได้ 1: เปลี่ยนแปลงได้)
$CONFIG['change_email']		=	'1';			// ระบุโหมดสำหรับให้สมาชิกสมารถเปลี่ยนแปลงอีเมล์ (0: เปลี่ยนแปลงไม่ได้ 1: เปลี่ยนแปลงได้)

// Character Management Settings
$CONFIG['char_manage_mode']	=	'1';			// อนุญาตให้สมาชิกใช้งาน Character Management หรือไม่ (0: ไม่อนุญาต 1: อนุญาต 2: เฉพาะ Administrator)
$CONFIG['manage_zeny_mode']	=	'0';			// อนุญาตให้สมาชิกจัดการเงินภายในไอดีหรือไม่ (0: ไม่ใช้งาน 1: ใช้งาน)
$CONFIG['max_zeny']		=	'1000000000';		// ระบุจำนวนเงินมากที่สุดต่อหนึ่งตัวละคร เพื่อป้องกันการเกิดปัญหาในการจัดการเงินภายในไอดี (ค่าปกติ: 1000000000)

// Status Server Settings
$CONFIG['check_server']		=	'0';			// ระบุโหมดของการตรวจสอบสถานะของเซิร์ฟเวอร์ (0: ปิดบังสถานะ 1:เพื่อให้ตรวจสอบสถานะ)
$CONFIG['maintenance']		=	'0';			// หากตั้งค่าเป็น 1 ระบบจะบอกสถานะเซิร์ฟเวอร์ว่ากำลังปรับปรุง
$CONFIG['time_check_intervals']	=	'120';			// หน่วงเวลาในการตรวจสอบสถานะเซิร์ฟเวอร์ (หน่วยเป็นวินาที)
$CONFIG['server_ip']			=	'127.0.0.1';			// ระบุ IP Address ของเครื่องคุณที่ใช้ในการตรวจสอบสถานะเซิร์ฟเวอร์
$CONFIG['loginport']			=	'6900';			// ระบุ Login Port ของเครื่องคุณ
$CONFIG['charport']			=	'6121';			// ระบุ Char Port ของเครื่องคุณ
$CONFIG['mapport']			=	'5121';			// ระบุ Map Port ของเครื่องคุณ

// Forum Settings
$CONFIG['forum_name']		=	'Ragnarok Online Forum';	// ระบุชื่อฟอรั่ม
$CONFIG['uploads_mode']		=	'1';			// เปิดให้ใช้งานอัพโหลดไฟล์บนบอร์ดหรือไม่ (0: ปิดใช้งาน 1: เปิดใช้งาน)
$CONFIG['uploads_folder']		=	'uploads';			// ชื่อโฟลเดอร์ที่ใช้ในการเก็บไฟล์ Uploads (ไม่ต้องแก้อะไร)
$CONFIG['avatar_folder']		=	'uploads/avatars';		// ชื่อโฟลเดอร์ที่ใช้ในการเก็บไฟล์ Avatar (ไม่ต้องแก้อะไร)
$CONFIG['upload_avatar']		=	'1';			// ให้สมาชิกสามารถอัพโหลดรูป Avatar จากเครื่องได้หรือไม่ (0: ไม่ได้ 1: ได้)
$CONFIG['uploads_size']		=	'200';			// จำกัดขนาดของไฟล์ที่ใช้ในการ Uploads (หน่วย Kb.)
$CONFIG['avatar_size']		=	'50';			// จำกัดขนาดของไฟล์รูปภาพ Avatar (หน่วย Kb.)
$CONFIG['max_img_width']		=	'600';			// ให้ย่อรูปภาพเมื่อมีขนาดความกว้างเกินกี่ pixels (0: ไม่ใช้งาน)
$CONFIG['max_img_height']		=	'0';			// ให้ย่อรูปภาพเมื่อมีขนาดความสูงเกินกี่ pixels (0: ไม่ใช้งาน)
$CONFIG['delay_post']		=	'30';			// ระบุตัวเลขเพื่อหน่วงเวลาหลังจากโพสต์กระทู้ (หน่วยเป็นวินาที)
$CONFIG['t_per_page']		=	'15';			// ระบุตัวเลขเพื่อแสดงจำนวนกระทู้ต่อหนึ่งหน้า
$CONFIG['per_page']		=	'20';			// ระบุตัวเลขเพื่อแสดงจำนวนผู้ตอบกระทู้ต่อหนึ่งหน้า
$CONFIG['max_post_length']		=	'20480';			// ระบุจำนวนตัวอักษรที่ให้โพสต์ได้
$CONFIG['max_signature_length']	=	'500';			// ระบุจำนวนตัวอักษรของลายเซ็น
$CONFIG['guest_can_post']		=	'1';			// ระบุโหมดการตั้ง/โพสต์กระทู้ของ Guest (0: Guest ไม่สามารถตั้ง/โพสต์กระทู้ได้ 1: Guest สามารถตั้ง/โพสต์กระทู้ได้)

// Log Setting
$CONFIG['log_select']		=	'0';			// เก็บข้อมูล SELECT queries หรือไม่
$CONFIG['log_insert']		=	'0';			// เก็บข้อมูล INSERT queries หรือไม่
$CONFIG['log_update']		=	'0';			// เก็บข้อมูล UPDATE queries หรือไม่
$CONFIG['log_delete']		=	'0';			// เก็บข้อมูล DELETE queries หรือไม่
$CONFIG['log_register']		=	'1';			// เก็บข้อมูลการสมัครสมาชิกหรือไม่

// CP Settings
$CONFIG['height']			=	'900';			// ระบุความสูงของหน้าเวบ (สามารถระบุเป็น % ได้)
$CONFIG['width']			=	'850';			// ระบุความกว้างของหน้าเวบ (สามารถระบุเป็น % ได้)
?>