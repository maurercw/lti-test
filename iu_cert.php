<?php

$lmsdata_common = array(
      "launch_presentation_locale" => "en_us",
      "tool_consumer_info_product_family_code" => "sakai-unit",
      "tool_consumer_info_version" => "0.9",
      "tool_consumer_instance_guid" => "lmsng.school.edu",
      "tool_consumer_instance_description" => "University of School (LMSng)",
      // user data
      "user_id" => "6024933",
      "roles" => "Instructor",  
      // "lis_person_name_full" => 'UISO Scan',
      "lis_person_name_family" => 'Scan',
      "lis_person_name_given" => 'UISO',
      "lis_person_contact_email_primary" => "uisoscan@iu.edu",
      // "lis_person_sourcedid" => "school.edu:user",
      // course data
      "context_id" => "1992552",
      "context_title" => "uisoscan Test Course",
      "context_label" => "uisoscan"
);

$lmsdata_custom = array(
      "ck1" => "canvas_user_id",
      "cv1" => "6024933",
      "ck2" => "canvas_user_login_id",
      "cv2" => "uisoscan",
      "ck3" => "canvas_course_id",
      "cv3" => "1992552"
);

$lmsdata_special = array(
      "key" => "uisoscan"
);

$lmsdata_tmp = array(
	0 => array( 
		"lmsdata" => 
                  array_merge($lmsdata_common, 
		            array("ext_note" => "Apply Course Template") ),
            "custom_fields" => array_merge($lmsdata_custom),
            "special_fields" => array_merge($lmsdata_special, array(
                  "endpoint" => "https://hierarchyresourcemanager-stg.apps-test.iu.edu/lti")
            )
      ),
      1 => array( 
		"lmsdata" => 
                  array_merge($lmsdata_common, 
		            array("ext_note" => "Blueprint Manager") ),
            "custom_fields" => array_merge($lmsdata_custom),
            "special_fields" => array_merge($lmsdata_special, array(
                  "endpoint" => "https://blueprint-manager-stg.apps-test.iu.edu/lti")
            )
      ),
      2 => array( 
		"lmsdata" => 
                  array_merge($lmsdata_common, 
		            array("ext_note" => "Multicourse Messages") ),
            "custom_fields" => array_merge($lmsdata_custom, 
                  array("ck4" => "mcm_tool_id", "cv4" => "msg") ),
            "special_fields" => array_merge($lmsdata_special, array(
                  "endpoint" => "https://lms-lti-multiclassmessenger-stg.apps-test.iu.edu/lti")
            )
      ),
      3 => array( 
		"lmsdata" => 
                  array_merge($lmsdata_common, 
		            array("ext_note" => "Multicourse Announcements") ),
            "custom_fields" => array_merge($lmsdata_custom, 
                  array("ck4" => "mcm_tool_id", "cv4" => "annc") ),
            "special_fields" => array_merge($lmsdata_special, array(
                  "endpoint" => "https://lms-lti-multiclassmessenger-stg.apps-test.iu.edu/lti")
            )
      ),

);


$lmsdata_cert = array();
foreach($lmsdata_tmp as $k => $v) {
	ksort($v);
	$lmsdata_cert[$k] = $v;
}
