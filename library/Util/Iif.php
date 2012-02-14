<?php
class Util_Iif
{
	private $condition;
	private $value1;
	private $value2;

	public function __construct($condition, $value1, $value2)
	{
		$this->condition = $condition;
		$this->value1    = $value1;
		$this->value2    = $value2;
	}

	public function iif()
	{
		if ($this->condition)
		{
			return $this->value1;
		}
		else
		{
			return $this->value2;
		}
	}
}