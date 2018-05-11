<?php


namespace App\Http\Controllers;
class GradeController extends Controller {

public static function getGrade($Grade)
	{
		if( ($Grade >= 0 && $Grade <=100) && is_int($Grade) && isset($Grade) && !is_null($Grade) )
		{
			if( ($Grade >=0)  && ($Grade <= 39)  )
			{
				$ScoreStatemnet['Grade'] = 'F9';
				$ScoreStatemnet['Comment'] = 'FAIL';
				return $ScoreStatemnet;
			}

			elseif( ($Grade >= 40) && ($Grade <= 44)    )
			{
				$ScoreStatemnet['Grade'] = 'D8';
				$ScoreStatemnet['Comment'] = 'PASS';
				return $ScoreStatemnet;
			}

			elseif( ($Grade >= 45) && ($Grade <= 49) )
			{
				$ScoreStatemnet['Grade'] = 'D7';
				$ScoreStatemnet['Comment'] = 'PASS';
				return $ScoreStatemnet;
			}
			elseif( ($Grade >= 50) && ($Grade <=54) )
			{
				$ScoreStatemnet['Grade'] = 'C6';
				$ScoreStatemnet['Comment'] = 'CREDIT';
				return $ScoreStatemnet;
			}
			elseif( ($Grade >= 55) && ($Grade <= 59) )
			{
				$ScoreStatemnet['Grade'] = 'C5';
				$ScoreStatemnet['Comment'] = 'CREDIT';
				return $ScoreStatemnet;
			}
			elseif( ($Grade >= 60) && ($Grade <= 64) )
			{
				$ScoreStatemnet['Grade'] = 'C4';
				$ScoreStatemnet['Comment'] = 'CREDIT';
				return $ScoreStatemnet;
			}
			elseif( ($Grade >= 65) && ($Grade <= 69) )
			{
				$ScoreStatemnet['Grade'] = 'B3';
				$ScoreStatemnet['Comment'] = 'GOOD';
				return $ScoreStatemnet;
			}
			elseif( ($Grade >= 70) && ($Grade <= 74) )
			{
				$ScoreStatemnet['Grade'] = 'B2';
				$ScoreStatemnet['Comment'] = 'VERY GOOD';
				return $ScoreStatemnet;
			}
			elseif( ($Grade >= 75) && ($Grade <= 100) )
			{
				$ScoreStatemnet['Grade'] = 'A1';
				$ScoreStatemnet['Comment'] = 'EXCELLENT';
				return $ScoreStatemnet;
			}
			else
			{
				return '';
			}
		}//end if statement that ensures that 
		else
		{
			return '';
		}


	}//end method checkOutPage


}//end class