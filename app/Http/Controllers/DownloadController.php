<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class DownloadController extends Controller
{
    public function __construct(  )
    {
        $this->middleware('guest');
    }

    public function downloadReportPDF()
	{
		 $html = '';
		 $MassiveDetails = Session::get('MassiveDetails');
		 $StudentName = is_array($MassiveDetails['ThisStudent']['user_belong']) ?
		 				$MassiveDetails['ThisStudent']['user_belong']['firstname']." ".
		 				$MassiveDetails['ThisStudent']['user_belong']['middlename']." ".
		 				$MassiveDetails['ThisStudent']['user_belong']['surname'] :' ';

		 $ThisTerm = is_array($MassiveDetails['SubjectScore'][0]) ?
		 				$MassiveDetails['SubjectScore'][0]['termname']." ".
		 				$MassiveDetails['SubjectScore'][0]['classname']." ".
		 				$MassiveDetails['SubjectScore'][0]['class_subdivision'] :' ';

		 $ThisStudentTerm = (isset($ThisStudent) || isset($ThisTerm))
		 					? strtoupper($StudentName." - ".$ThisTerm): 'Name unavailable';
			$view_string = View::make('includes.reportviewpdf')
									->with(array(
											'Title' => 'Student Report Page',
											'ThisStudent' =>  $MassiveDetails['ThisStudent']  ,
											'SubjectScore' => $MassiveDetails['SubjectScore'] ,
											'Attendance' => $MassiveDetails['Attendance'] ,
											'TermDuration' => $MassiveDetails['TermDuration'] ,
											'RequestedTerm'   =>$MassiveDetails['RequestedTerm'],
											"OfficialComments" => $MassiveDetails['OfficialComments'],
											'FirstTermSubjectScore'  => array_key_exists('FirstTermSubjectScore', $MassiveDetails)
																		?$MassiveDetails['FirstTermSubjectScore'] : '',
											'SecondTermSubjectScore'  => array_key_exists('SecondTermSubjectScore', $MassiveDetails)
																		?$MassiveDetails['SecondTermSubjectScore'] : '' ))
									->render();

			$view_string = preg_replace('/>\s+</', "><", $view_string);
			return PDF::load($view_string, 'A3', 'portrait')->download($ThisStudentTerm." - Result");

	}//end method downloadReportPDF

    public function downloadStudentListPDF()
	{
		 $html = '';
		 $MassiveDetails = Session::get('ClassStudentListArray');
		 //var_dump($MassiveDetails); die();
     if (count($MassiveDetails) > 0)
     {
       $ThisStudentTerm = (is_array($MassiveDetails) and  array_key_exists('ThisStudentTerm', $MassiveDetails )  ) ? $MassiveDetails['ThisStudentTerm'] : ' ';

  		 $ChoosenTerm = ((count($MassiveDetails) > 0)  and  array_key_exists('ChoosenTerm', $MassiveDetails ) and (is_array($MassiveDetails) )) ? $MassiveDetails['ChoosenTerm'] : [];
  		 $ThisClass = (count($ChoosenTerm) > 0)  and array_key_exists('Class', $ChoosenTerm ) ? $ChoosenTerm['Class']." ".strtoupper($ChoosenTerm['SubClass'])." ". $ChoosenTerm['Year'] : ' ' ;

  		 //var_dump($ThisStudentTerm);
  			$view_string = View::make('includes.studentlistpdf')
  									->with(array(	'ThisStudentTerm' => $ThisStudentTerm,
  													'ChoosenTerm' =>  $ChoosenTerm
        											))->render();
        Session::forget('ClassStudentListArray');
  			$view_string = preg_replace('/>\s+</', "><", $view_string);
  			return $view_string;

  			return PDF::load($view_string, 'A3', 'portrait')->download($ThisClass." - Student List");
     }
     else {
            return "There are no imformation in the Session to use";
     }


	}//end method downloadStudentListPDF

	public function downloadKaduna()
	{

		 //$MassiveDetails = Session::get('ClassStudentListArray');
		 //var_dump($MassiveDetails);

		// $ThisStudentTerm = is_array($MassiveDetails) ? $MassiveDetails['ThisStudentTerm'] : ' ';

		 //$ChoosenTerm = is_array($MassiveDetails) ? $MassiveDetails['ChoosenTerm'] :' ';
		 //$ThisClass = $ChoosenTerm['Class']." ".strtoupper($ChoosenTerm['SubClass'])." ". $ChoosenTerm['Year'];

		 //var_dump($ThisStudentTerm);
		$user = Users::all()->take(2);//var_dump($user->toArray());
		$view_string = View::make('includes.kadunabill.index')
								->with(array(	'Users' => $user->toArray()

      									))->render();
			$view_string = preg_replace('/>\s+</', "><", $view_string);
			return $view_string;

			return PDF::load($view_string, 'A3', 'portrait')->download("Kaduna Bill");

	}//end method downloadKaduna

	public function downloadKaduna2()
	{
		 $html = '<html><body>'
            . '<p>Put your html here, or generate it with your favourite '
            . 'templating system.</p>'
            . '</body></html>';
		 //$MassiveDetails = Session::get('ClassStudentListArray');
		 //var_dump($MassiveDetails);

		// $ThisStudentTerm = is_array($MassiveDetails) ? $MassiveDetails['ThisStudentTerm'] : ' ';

		 //$ChoosenTerm = is_array($MassiveDetails) ? $MassiveDetails['ChoosenTerm'] :' ';
		 //$ThisClass = $ChoosenTerm['Class']." ".strtoupper($ChoosenTerm['SubClass'])." ". $ChoosenTerm['Year'];

		 //var_dump($ThisStudentTerm);
			$view_string = View::make('includes.kadunabill.index')->render();
			$view_string = preg_replace('/>\s+</', "><", $view_string);
			return $view_string;

			return PDF::load($html, 'A3', 'portrait')->download("Kaduna Bill");

	}//end method downloadKaduna2

	public function goodStuff()
	{
		 //return AuthorizationServer::performAccessTokenFlow() ;

		return Response::json(    array( "Users" => Users::all()->take(15)->toArray() )  );


		 //return AuthorizationServer::performAccessTokenFlow();
		  //return "oauth secured route";
	}//end method goodStuff

	public function getToken()
	{
		 return AuthorizationServer::performAccessTokenFlow() ;

		//return Response::json(Users::all()->take(5)->toArray());


		 //return AuthorizationServer::performAccessTokenFlow();
		  //return "oauth secured route";
	}//end method getToken

}//end class
