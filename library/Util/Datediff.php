<?php
class Util_Datediff
{
	private $date1;
	private $date2;
	private $datePart;

	public function __construct($date1, $date2, $date_part = 'mi')
	{
		$this->date1    = $date1;
		$this->date2    = $date2;
		$this->datePart = $date_part;
	}

	public function datediff()
	{

//        Compatibilizar essa data com essa função;
//        gregoriantojd($month, $day, $year);

//		$dataHora1 = '2008-10-02 09:47:15';
//		$dataHora2 = '2008-10-08 17:23:25';
		
		$dataHora1 = $this->date1;
		$dataHora2 = $this->date2;
		$datePart  = $this->datePart;

		$dia1 = substr($dataHora1,8,2);
		$mes1 = substr($dataHora1,5,2);
		$ano1 = substr($dataHora1,0,4);
		$hor1 = substr($dataHora1,11,2);
		$min1 = substr($dataHora1,14,2);
		$seg1 = substr($dataHora1,17,2);

		$dia2 = substr($dataHora2,8,2);
		$mes2 = substr($dataHora2,5,2);
		$ano2 = substr($dataHora2,0,4);
		$hor2 = substr($dataHora2,11,2);
		$min2 = substr($dataHora2,14,2);
		$seg2 = substr($dataHora2,17,2);


		$time1 = mktime($hor1,$min1,$seg1,$mes1,$dia1,$ano1);
		$time2 = mktime($hor2,$min2,$seg2,$mes2,$dia2,$ano2);

		if ($datePart == 'mi'){
			$diff = round( ($time2 - $time1)/60 , 0 );
		}
		if ($datePart == 'h'){
			$diff = round((($time2 - $time1)/60)/60,0);
		}
		if ($datePart == 'd'){
			$diff = round(((($time2 - $time1)/60)/60)/24,0);
		}
		if ($datePart == 'm'){
			$diff = round((((($time2 - $time1)/60)/60)/24)/30,0);
		}
		return $diff;
	}
}