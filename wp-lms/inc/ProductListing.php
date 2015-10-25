1<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 4/30/15
 * Time: 1:02 PM
 */


include_once(LMS_INCLUDE_DIR.'LMS_Defines.php');
//include_once(LMS_INCLUDE_DIR.'LMS_SchoolDB.php');
include_once(LMS_PLUGIN_PATH . 'class/LMS_DBFunctions.class.php');
include_once(LMS_PLUGIN_PATH . 'class/LMS_AdminFunctions.class.php');


class ProductListing {

    protected $product_course; // Course Array containing price, name, product code
    protected $product_moduleArr; // List of Modules

    public function ProductListing($data, $byType=PRODUCTS_TABLE_PCODE){
        $this->load($data, $byType);
    }

    public function getCourse() { return $this->product_course; }

    public function getListOfModules() { return $this->product_moduleArr; }

    protected function load($data, $byType=PRODUCTS_TABLE_PCODE)
    {

        //Load Course Information
        $this->product_course = LMS_DBFunctions::get_product($data,$byType);


        //get Course Id from product courses given product id
        $product_mapping = LMS_DBFunctions::get_product_course_mapping($this->product_course[PRODUCTS_TABLE_ID]);

        //add check in case nothing is returned..
        $this->product_course["course_id"] = $product_mapping[0]->course_id;

        //get all the individual_product_modules
        $this->product_moduleArr = LMS_DBFunctions::get_individual_product_modules($product_mapping[0]->course_id);

        /**
        //get list of module_ids from module_course table
        $module_ids = LMS_SchoolDB::get_course_modules($product_mapping[0]->course_id,false);
        //foreach module id get the corresponding module product
        foreach($module_ids as $value){
            $row = LMS_SchoolDB::get_module_product_by_module_id($value->module_id);
            //echo "<pre> ID:" . print_r($row,true). "</pre>\n";
            $index = intval($row["product_order"]);
            $row["module_id"] = $value->module_id;
            $this->product_moduleArr[$index] = $row;
            //break;
        }
        ***/

    }

    public function toStr(){
        $str = "Course:";
        $str .= implode("|",$this->product_course);
        $str .= "\nModule:";
       // $str .= implode("|",$this->pset_module);
        return $str;
    }
}
?>