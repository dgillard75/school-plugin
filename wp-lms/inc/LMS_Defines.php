<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 4/22/15
 * Time: 10:06 AM
 */

define("SIGNUPTYPE-FULL","1");
define("SIGNUPTYPE-SET","0");


define("USERS_TABLE", "tbl_users");
//define("COURSES_TABLE","courses");

define("DB_LMS_REG_USERS_TABLE","wp_lms_registered_users");
define("DB_LMS_REG_USERS_ID","id");
define("DB_LMS_REG_USERS_WP_ID","wp_user_id");
define("DB_LMS_REG_USERS_WP_LOGIN","wp_user_login");
define("DB_LMS_REG_USERS_DATE_REG","date_registered");

define("DB_LMS_USERS_COURSES_TABLE","wp_lms_user_courses");

define("USERS_PASSWORD","user_pass");

// Billing Fields used for Database and Form
define("USERS_BADDRESS","bill_address");
define("USERS_BCITY","bill_city");
define("USERS_BSTATE","bill_state");
define("USERS_BCOUNTRY","bill_country");
define("USERS_BPHONE","bill_phone");
define("USERS_BZIP","bill_zipcode");

// Shipping Fields used for Database and Form
define("USERS_SADDRESS","address");
define("USERS_SCITY","city");
define("USERS_SSTATE","state");
define("USERS_SCOUNTRY","country");
define("USERS_SPHONE","phone");
define("USERS_SZIP","zipcode");


define("REGISTERED_USERS_TABLE","wp_lms_registered_users");
define("REGISTERED_USERS_ID","id");
define("REGISTERED_USERS_WP_ID","wp_user_id");
define("REGISTERED_USERS_LOGIN","wp_user_login");

//*******************************************//
define("COURSES_SETS_TABLE","wp_lms_course_sets");
define("MODULE_TABLE","wp_lms_module");
define("MODULE_COURSE_TABLE","wp_lms_module_course");
define("MODULE_PACKAGE_TABLE","wp_lms_module_package");
define("PACKAGE_TABLE","wp_lms_package");
define("PACKAGE_COURSE_TABLE","wp_lms_package_course");
define("PACKAGE_SETS_TABLE","wp_lms_package_sets");
define("PACKAGE_SET_MODULE_TABLE","wp_lms_package_set_modules");
define("SET_MODULES_TABLE","wp_lms_sets_modules");


// Courses
define("COURSES_TABLE","wp_lms_course");
define("COURSES_TBL_COURSE_ID","course_id");
define("COURSES_TBL_CNAME","course_title");
define("COURSES_TBL_CIMG","course_img");
define("COURSES_TBL_DESC","course_desc");
define("COURSES_TBL_STATUS","status");
define("COURSES_TBL_ORD","tbl_order");


// MODULES
define("MODULES_TABLE","wp_lms_module");
define("MODULES_TBL_MODULE_ID","module_id");
define("MODULES_TBL_MODULE_NAME","module_name");
define("MODULES_TBL_PAGEID","PAGEID");
define("MODULES_TBL_MODULE_NUMBER","module_number");
define("MODULES_TBL_COURSE_ID","course_id");
define("MODULES_TBL_SHORT_DESC","short_descr");

// LESSONS
define("LESSONS_TABLE","wp_lms_lessons");
define("LESSONS_TBL_ID","id");
define("LESSONS_TBL_NAME","name");
define("LESSONS_TBL_COURSE_ID","course_id");
define("LESSONS_TBL_FILENAME","fname");
define("LESSONS_TBL_MODULE_ID","module_id");
define("LESSONS_TBL_UNIT_NUMBER","unit_number");


//User Course Access
define("USER_COURSES_TABLE","wp_lms_user_courses");
define("USER_COURSES_TBL_TRANS_ID","trans_id");
define("USER_COURSES_TBL_USER_ID","user_id");
define("USER_COURSES_TBL_COURSE_ID","course_id");
define("USER_COURSES_TBL_START_DATE","start_date");
define("USER_COURSES_TBL_STATUS","status");
define("USER_COURSES_TBL_GRANTOR","grantor");
define("USER_COURSES_STATUS_ACTIVE", "Active");
define("USER_COURSES_STATUS_INACTIVE", "InActive");

//Module Access
define("USER_MODULES_TABLE","wp_lms_user_modules");
define("USER_MODULES_TBL_TRANS_ID","trans_id");
define("USER_MODULES_TBL_MODULE_ID","module_id");
define("USER_MODULES_TBL_START_DATE","start_date");
define("USER_MODULES_TBL_STATUS","status");
define("USER_MODULES_TBL_GRANTOR","grantor");


define("USER_COURSES_SET_TABLE","wp_lms_user_courses_sets");
define("USER_COURSES_SET_TBL_USER_ID","user_id");
define("USER_COURSES_SET_TBL_SET_ID","set_id");
define("USER_COURSES_SET_TBL_COURSE_ID","course_id");
define("USER_COURSES_SET_TBL_START_DATE","start_date");
define("USER_COURSES_SET_TBL_END_DATE","end_date");
define("USER_COURSES_SET_TBL_PAID_AMOUNT","paid_amount");
define("USER_COURSES_SET_TBL_AMOUNT","amount");
define("USER_COURSES_SET_TBL_PAYMENT_METHOD","payment_method");
define("USER_COURSES_SET_TBL_STATUS","status");

define("PRODUCTS_TABLE",        "wp_lms_products");
define("PRODUCTS_TABLE_ID",     "product_id");
define("PRODUCTS_TABLE_NAME",   "product_name");
define("PRODUCTS_TABLE_PRICE",  "price");
define("PRODUCTS_TABLE_PCODE",  "product_code");
define("PRODUCTS_TABLE_DPRICE", "discount");
define("PRODUCTS_TABLE_PIMG",   "product_img");
define("PRODUCTS_TABLE_DESC",   "product_desc");
define("PRODUCTS_TABLE_PTYPE",  "product_type");
define("PRODUCTS_TABLE_PORDER", "product_order");
define("PRODUCTS_TABLE_STATUS", "status");

define("QUIZ_QUEST_TABLE",          "wp_lms_quiz_questions");
define("QUIZ_QUEST_TABLE_QUIZ_ID",  "quiz_id");
define("QUIZ_QUEST_TABLE_QUES_ID",  "question_id");
define("QUIZ_QUEST_TABLE_QUES",     "question");
define("QUIZ_QUEST_TABLE_CANS",     "correct_ans");
define("QUIZ_QUEST_TABLE_ANS",      "answers");
define("QUIZ_QUEST_TABLE_QORDER",   "ques_order");

define("QUIZ_TABLE",            "wp_lms_quizzes");
define("QUIZ_TABLE_ID",         "quiz_id");
define("QUIZ_TABLE_TITLE",      "quiz_title");
define("QUIZ_TABLE_DURATION",   "duration");
define("QUIZ_TABLE_QUEST",      "questions");
define("QUIZ_TABLE_MID",        "module_id");
define("QUIZ_TABLE_QORDER",     "quize_order");


define("USER_QUIZ_PROGRESS_TABLE",               "wp_lms_user_progress_quizzes");
define("USER_QUIZ_PROGRESS_TABLE_ID",            "quiz_attempt_id");
define("USER_QUIZ_PROGRESS_TABLE_UID",           "user_id");
define("USER_QUIZ_PROGRESS_TABLE_MID",           "module_id");
define("USER_QUIZ_PROGRESS_TABLE_QID",           "quiz_id");
define("USER_QUIZ_PROGRESS_TABLE_CDATE",         "quiz_completed_date");
define("USER_QUIZ_PROGRESS_TABLE_SDATE",         "quiz_start_date");
define("USER_QUIZ_PROGRESS_TABLE_CANS",          "quiz_correct_answers");
define("USER_QUIZ_PROGRESS_TABLE_GRADE",         "quiz_grade");
define("USER_QUIZ_PROGRESS_TABLE_QUEST_TOTAL",   "quiz_question_total");
define("USER_QUIZ_PROGRESS_TABLE_COMP_TIME",     "quiz_completion_time_seconds");
define("USER_QUIZ_PROGRESS_TABLE_STATUS",        "quiz_status");

define("USER_PROGRESS_TABLE",                    "wp_lms_user_progress");
define("USER_PROGRESS_TABLE_USER_ID",            "user_id");
define("USER_PROGRESS_TABLE_UNIT_ID",            "unit_id");
define("USER_PROGRESS_TABLE_COMPLETE_DATE",      "unit_completed_date");
define("USER_PROGRESS_TABLE_STATUS",             "unit_completed_status");

define("NO_COLUMN", "none");


define("PRODUCTS_COURSES_TABLE","wp_lms_product_course");
define("PRODUCTS_MODULE_TABLE", "wp_lms_product_module");


//Defines for Pages URL

define("STUDENTS_ADMIN_PAGE", "page=students");
define("ADMIN_STUDENTCOURSE_PAGE", "page=studentCourses");
define("ADMIN_SETS_PAGE", "page=sets");
define("ADMIN_COURSES_PAGE", "page=course");
define("ADMIN_MODULES_PAGE", "page=modules");


define("STUDENT_ADMIN_EDIT_PAGE",STUDENTS_ADMIN_PAGE."&action=edit");
define("STUDENT_ADMIN_ADD_PAGE",STUDENTS_ADMIN_PAGE."&action=add");
define("STUDENT_ADMIN_DELETE_PAGE",STUDENTS_ADMIN_PAGE."&action=delete");
define("STUDENT_ADMIN_COURSE_PAGE",STUDENTS_ADMIN_PAGE."&action=showcourses");
define("STUDENT_ADMIN_PACKAGES_PAGE",STUDENTS_ADMIN_PAGE."&action=showpackages");

define("ADMIN_STUDENTCOURSE_ADD_PAGE", ADMIN_STUDENTCOURSE_PAGE."&action=add&userid=");
define("ADMIN_STUDENTCOURSE_DELETE_PAGE", ADMIN_STUDENTCOURSE_PAGE."&action=delete");

define("ADMIN_COURSES_ADD_PAGE", ADMIN_COURSES_PAGE."&action=add");
define("ADMIN_COURSES_EDIT_PAGE", ADMIN_COURSES_PAGE."&action=edit");
define("ADMIN_COURSES_DELETE_PAGE", ADMIN_COURSES_PAGE."&action=delete");


class ProductStatusConstants
{

    const AVAIL = "Available";
    const PENDING = "Pending";
    const ACTIVE = "Active";
    const INACTIVE = "Inactive";
}

class ErrorCodeConstants{

    const ALREADY_EXIST = -2;
    const FAIL = 0;
    const SUCCESS = 1;

}
?>