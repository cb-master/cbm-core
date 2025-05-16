<?php
 /**
  * Project: Laika MVC Framework
  * Author Name: Showket Ahmed
  * Author Email: riyadhtayf@gmail.com
  */
 
use CBM\Core\Filter\Filter;
use CBM\Core\Option\Option;
use CBM\Core\Helper\Helper;
use CBM\Session\Session;
 
 // Dump Data & Die
 /**
  * @param mixed $data - Required Argument
  * @param bool $die - Default is false
  */
 function dd(mixed $data, bool $die = false):void
 {
    echo '<pre style="background-color:#000;color:#fff;">';
    var_dump($data);
    echo '</pre>';
    $die ? die() : $die;
 }
 
 // Show Data & Die
 /**
  * @param mixed $data - Required Argument
  * @param bool $die - Default is false
  */
 function show(mixed $data, bool $die = false):void
 {
    echo '<pre style="background-color:#000;color:#fff;">';
    print_r($data);
    echo '</pre>';
    $die ? die() : $die;
 }
 
 // Show Decimal Number
 /**
  * @param int|string|float|null $number - Required Argument
  * @param int|string $decimal - Default is 2
  */
 function show_decimal(int|string|float|null $number, int $decimal = 2):string
 {
     $number = (float) $number;
     $thousands_separator = Option::key('thousands_separator');
     $decimal_seperator = Option::key('decimal_separator');
     return number_format($number, $decimal, $decimal_seperator, $thousands_separator);
 }

 // Convert To Float Number
 /**
  * @param int|string|float|null $number - Required Argument
  * @param int|string $decimal - Default is 2
  */
 function decimal(int|string|float|null $number, int $decimal = 2):string
 {
     return number_format((float) $number, $decimal, '.', '');
 }
 
 // Get Currency Prefix
 function currency_prefix():string
 {
     return Option::key('currencypfx');
 }
 
 // Convert to Price
 /**
  * @param int|string|float|null $price - Default is null
  * @param int|string $decimal - Default is 2
  * @return string
  */
 function to_price(string|int|float $price = null, int $decimal = 2):string
 {
    $price = decimal($price, $decimal);
    return currency_prefix() . $price;
 }
 
 // Location
 /**
  * @param string $slug - Required Argument
  */
 function location(string $slug):string
 {
    return Helper::location($slug);
 }
 
 // Redirect
 /**
  * @param string $slug - Required Argument
  * @param int $response - Default is 302
  */
 function redirect(string $slug, int $response = 302):void
 {
    Helper::redirect($slug, $response);
 }
 
 // Check Staff Has Access
 /**
  * @param string $access - Required Argument
  * @param string $for - Required Argument. Default is 'staff'
  */
 function access(string $access, string $for):bool
 {
    $list = Session::get('accesses', $for);
    return (bool) ($list[$access] ?? false);
 }
 
 // Add Filter
 /**
  * @param string $filter - Required Argument.
  * @param callable $callback - Required Argument.
  * @param int $priority - Optional Argument. Default is 10
  */
 function add_filter(string $filter, callable $callback, int $priority = 10):void
 {
    Filter::add_filter($filter, $callback, $priority);
 }
 
 // Apply Filter
 /**
  * @param string $filter - Required Argument.
  * @param mixed $value - Optional Argument. Default is Null.
  * @param mixed ...$args - Optional Arguments.
  */
 function apply_filter(string $filter, mixed $value = null, mixed ...$args):mixed
 {
     return Filter::apply_filter($filter, $value, ...$args);
 }

//////////////////////////
//////// FILTERS /////////
//////////////////////////
 
 // Theme Slug
 add_filter('load_view', function($view){
    return $view;
 });
