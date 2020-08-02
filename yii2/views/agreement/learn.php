
<div class="col-1">
<div class="col-10">
<?php
  
  
    function bubbleSort($arr) {
		
		$count = count($arr);
		if ($count <= 1) {
			return $arr;
		}
	 
		for ($i = 0; $i < $count; $i++) {
			for ($j = $count - 1; $j > $i; $j--) {
				if ($arr[$j] < $arr[$j - 1]) {
					$tmp = $arr[$j];
					$arr[$j] = $arr[$j - 1];
					$arr[$j - 1] = $tmp;
				}
			}
		}
        return $arr;
    }
	
	$arr = [3,4,6,7,8,9,90,0,6,5,43,3,4,554,56,56,56,36,35,45,354,34,4,7,47,467,];
	$one = [1];

//    echo '<pre>'. print_r($arr,true) . '</pre>';
	

    
	function mySort($arr)
	{
	    echo '<pre>'. print_r($arr,true) . '</pre>';
		
		$count = count($arr);
		if ($count <= 1) {
			return $arr;
		}
		
		
      
		
	}
	
	mySort($arr);
	

?>
</div>	
</div>

