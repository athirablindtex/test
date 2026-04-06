<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

require_once __DIR__.'/../src/SimpleXLSX.php';

//echo '<h1>Parse books.xslx</h1><pre>';
if ( $xlsx = SimpleXLSX::parse('employee.xlsx') ) {
    $arr=$xlsx->rows();
    $i=0;
    echo "INSERT INTO `employee` ( `name`, `employee_id`,`email`, `active`, `created_date`) VALUES";
    foreach($arr as $ar){
            
            if($i!=0){
                    //print_r($ar);
                    echo "('".$ar[2]."','".$ar[1]."','".$ar[3]."' , '1','".date('Y-m-d H:i:s')."'),";
                }
            $i++;

        }
        echo ";";    
} else {
	echo SimpleXLSX::parseError();
}
echo '<pre>';