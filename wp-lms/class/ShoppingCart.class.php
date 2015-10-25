<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/19/15
 * Time: 10:09 AM
 */

include_once(LMS_PLUGIN_PATH . "class/LMS_DBFunctions.class.php");

define("SHOPPING_CART_MAIN_KEY", "cart");
define("SHOPPING_CART_PRICE_KEY", "price");
define("SHOPPING_CART_QTY_KEY", "qty");
define("SHOPPING_CART_PTYPE_KEY", "ptype");

define("SHOPPING_CART_COURSE_TYPE","course");
define("SHOPPING_CART_MODULE_TYPE","module");


class ShoppingCart{

    protected $cart;
    protected $total=0.0;

    public function __construct($sessionArray = null)
    {
        if($sessionArray!=null && isset($sessionArray[SHOPPING_CART_MAIN_KEY]))
            $this->load_cart($sessionArray);
        else
            $this->create();
    }

    public function create()
    {
        $this->cart[SHOPPING_CART_MAIN_KEY] = array();
    }

    public function save(){
        $_SESSION[SHOPPING_CART_MAIN_KEY] = $this->get_cart();
    }

    public function update_quantity($item, $qty)
    {
        if (isset($this->cart[SHOPPING_CART_MAIN_KEY][$item])) {
            $this->cart[SHOPPING_CART_MAIN_KEY][$item][SHOPPING_CART_QTY_KEY] = $qty;
            return true;
        }
        return false;
    }

    public function get_cart(){
       return $this->cart[SHOPPING_CART_MAIN_KEY];
    }


    public function get_total(){
        return $this->total;
    }


    public function add($item, $quantity=1, $price=null)
    {
        if($price == null){
            //retrieve from database
            $product_info = LMS_DBFunctions::get_product($item,"product_code");
            $price = $product_info[SHOPPING_CART_PRICE_KEY];
        }

        $cart_row = array(
            SHOPPING_CART_PRICE_KEY => $price,
            SHOPPING_CART_QTY_KEY => $quantity
        );

        $this->cart[SHOPPING_CART_MAIN_KEY][$item] = $cart_row;
        $this->update_total();
        return true;
    }

    public function remove($item)
    {
        if (isset($this->cart[SHOPPING_CART_MAIN_KEY][$item])) {
            unset($this->cart[SHOPPING_CART_MAIN_KEY][$item]);
            $this->update_total();
        }
    }

    protected function load_cart($sessionArray)
    {
        $this->cart[SHOPPING_CART_MAIN_KEY] = $sessionArray[SHOPPING_CART_MAIN_KEY];
        $this->update_total();
    }

    public function print_cart(){
        var_dump($this->cart[SHOPPING_CART_MAIN_KEY]);
    }

    protected function update_total(){
        $this->total=0.0;
        if(!empty($this->cart[SHOPPING_CART_MAIN_KEY])){
            foreach ($this->cart[SHOPPING_CART_MAIN_KEY] as $key => $value) {
                $this->total += $value["price"] * $value["qty"];
            }
        }
    }

    public function showCart(){

        //$total = 0;
        foreach($this->cart[SHOPPING_CART_MAIN_KEY] as $key => $value){
            print "Product: ".$key."\tQuantity: ". $value[SHOPPING_CART_QTY_KEY] . "\t Price: ". $value[SHOPPING_CART_PRICE_KEY] . "\n";
            //$total += $value["price"];
        }
        print "Total Cost: ". $this->total . "\n";
    }
}

?>