<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 9/14/15
 * Time: 4:14 PM
 */

function wplms_database_load_tables_definitions(){
    global $wpdb;

    $database_tables = array(
        'wp_lms_lessons',
        'wp_lms_module',
        'wp_lms_module_course',
        'wp_lms_products',
        'wp_lms_product_course',
        'wp_lms_product_module',
        'wp_lms_quizzes',
        'wp_lms_quiz_questions',
        'wp_lms_user_courses',
        'wp_lms_user_modules',
        'wp_lms_user_progress',
        'wp_lms_user_progress_quizzes');

    foreach($database_tables as $table) {
        define(strtoupper($table), $table);
        $sql = "SHOW columns FROM " . $table;
        $result = $wpdb->get_results($sql, ARRAY_A);
        foreach ($result as $value) {
            $constant_name = $table . "_" . $value['Field'];
            define(strtoupper($constant_name), $value['Field']);
        }
    }
}

?>