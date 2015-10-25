<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 7/10/15
 * Time: 12:54 PM
 */

class LMS_OnlineSchoolLibrary
{

    protected $courses;
    protected $unit_count=0;

    public function LMS_OnlineSchoolLibrary($cid = NULL)
    {
        $this->load_library($cid);
    }

    /**
     * Return array of Courses
     * @return mixed
     */
    public function get_courses(){
        return $this->courses;
    }

    /**
     * Return one Course
     * @return mixed
     */
    public function get_course(){
        return $this->courses[0];
    }


    /**
     * Get Total Number of courses
     * @return int
     */
    public function get_total_number_of_courses() {
        return count( $this->courses );
    }


    /**
     * Get Total Number of Units
     * @return int
     */
    public function get_total_number_of_units(){
        return $this->unit_count;
    }

    /**
     * Load Modules and Lessons
     *
     * @param $modules
     * @param $lesson_cnt
     * @return array
     */
    private function load_modules_and_lessons($modules, &$lesson_cnt)
    {
        $mArr = array();
        $lesson_cnt = 0;
        foreach ($modules as $m) {
            $mArr[$m->module_id] = array_merge((array)$m,$this->get_product_info($m->module_id));
            $lessons = LMS_DBFunctions::retrieve_lesson_by_module($m->module_id);
            if (!empty($lessons)) {
                $mArr[$m->module_id]['lessons'] = $lessons;
                $lesson_cnt = $lesson_cnt + count($lessons);
            }
        }

        return $mArr;
    }

    /**
     * Load Library
     * @param $cid
     */
    protected function load_library($cid)
    {

        if (isset($cid)) {
            //only load a course
            $courses_from_db = LMS_DBFunctions::retrieve_course($cid);
            $modules = LMS_DBFunctions::get_course_modules($courses_from_db[COURSES_TBL_COURSE_ID]);
            $mArr = $this->load_modules_and_lessons($modules,$lesson_count);
            $c = $courses_from_db;
            $c['modules'] = $mArr;
            $c['total_units'] = $lesson_count;
            $this->courses[] = array_merge($c,$this->get_product_info($courses_from_db['course_id']));
            //LMS_Log::print_r($this->courses);
        } else {
            //load the entire library
            $courses_from_db = LMS_DBFunctions::get_active_courses(ARRAY_A);
            foreach ($courses_from_db as $c) {
                //LMS_Log::print_r($c);
                $modules =  LMS_DBFunctions::get_course_modules($c[COURSES_TBL_COURSE_ID]);
                $mArr = $this->load_modules_and_lessons($modules,$lesson_count);
                $c['modules'] = $mArr;
                $c['total_units'] = $lesson_count;
                $this->courses[] = $c;
            }
        }
    }

    protected function get_product_info($id){
        $product_info = array();
        //Get the Product Id from Access Settings
        $access_settings = LMS_DBFunctions::retrieve_product_access_settings($id,"wp_lms_id");
        if($access_settings) {
            $post_info = get_post($access_settings[0]['product_id'] );
            if ($post_info) {
                $product_info["product_name"] = $post_info->post_title;
                $product_info["product_id"] = $post_info->ID;
                //Get Meta Data - Extract Pricing information
                $meta = get_post_meta($post_info->ID);
                $product_info["product_price"] = $meta['edd_price'][0];
            }
        }
        return $product_info;
    }


    /*
     * Loads Product information to Courses and Modules
     * - Sets Array Field
     */
    function load_product_info()
    {
        if (self::get_total_number_of_courses() > 0) {
            //cycle through course(s), load productInfo if available
            foreach ($this->courses as $key => $course) {
                $this->courses[$key] = array_merge($course,$this->get_product_info($course['course_id']));
                foreach  ($course['modules'] as $module){
                    $course['modules'][$module['module_id']]['product_info'] = $this->get_product_info($module['module_id']);
                }
            }
        }
    }

}
?>