<?php
class Base_View_Helper_Iif
{
	public function Iif($condition, $value1, $value2)
	{
		$obj = new Util_Iif($condition, $value1, $value2);
		
		return $obj->iif();
	}
	
}