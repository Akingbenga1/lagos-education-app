<?php

namespace App\Http\Controllers;
use App\Models\Students;
use App\Models\StudentTerm;
use App\Models\ThisTerm;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class StudentListController extends Controller {



	public function getStudentClass() 
	{
		//var_dump($Year,  $Class, $SubClass );
		//var_dump(Input::all());die();
		$AllStudents =  Students::with('UserBelong')->get();
		$validator = Validator::make(Input::all(),
								array('Year' => 'required|date_format:Y',
									  'Class' => 'required|max:3',
									  'SubClass' => 'required|max:1|alpha',
									));//end  static method make for making a page;->withErrors($validator)->withInput();
		if($validator->fails())
		{
			//var_dump($validator); die();
			$ChoosenTerm['Class'] = Input::get('Class');
			$ChoosenTerm['Year'] =  Input::get('Year');
			$ChoosenTerm['SubClass'] = Input::get('SubClass');
			return View::make('teachers.studentmasterlist')
					->with(array( 'myBreadCrumb' => "<a href='". URL::route('home')
					."' id='BreadNav'>Home</a> => Student Report Page",
					'Title' => 'Student Class List',
					'StudentListMessage' => "Please review the validation errors below.",
					'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'',
					'ChoosenTerm' => $ChoosenTerm,
					))->withErrors($validator);
		}//end if statement that complain that validation failled
		else
		{
			$ChoosenTerm['Class'] = Input::get('Class');
			$ChoosenTerm['Year'] =  Input::get('Year');
			$ChoosenTerm['SubClass'] = Input::get('SubClass');

			try
				{
					$ThisTerm = ThisTerm::where('classname', '=', Input::get('Class') )
										 ->where('termname', '=', 'first term' )
										 ->where('year' , '=', Input::get('Year') )->get()->first();
							/*$NextTerms = ThisTerm::where(function ($query) {
		     				 $query->where('classname', '=', "SS2" )->orWhere('classname', '=', "SS3")->where('termname', '=', 'first term' )
												 ->where('year' , '=', (int)( Input::get('Year') + 1 );

		  						 })->get();*/  //var_dump($ThisTerm->toArray());die();
					$NextTerm = ThisTerm::where('termname', '=', 'first term' )->where('year' , '=', (int)( Input::get('Year') + 1 ) )->get();
					//var_dump($NextTerm->toArray()); die();
					if(!is_null($ThisTerm) )
					{
						$ThisStudentTerm = StudentTerm::with(['StudentBelong','ThistermBelong'])
											->where('thistermid', '=', $ThisTerm['id'] )
											->where('class_subdivision', '=', Input::get('SubClass') )
											->get();
						//var_dump($ThisStudentTerm->toArray()); die();
						$serial = 1;
						$i = 0;
						$TS = [];
						if(!is_null($ThisStudentTerm) and !empty($ThisStudentTerm))
						{
							 //var_dump($ThisStudentTerm);
							foreach($ThisStudentTerm->toArray() as $EachStudentTerm)
                				{
                    				$ThisStudent = Students::with('UserBelong')
                            					 ->where('studentid', '=', $EachStudentTerm['student_belong']['studentid'] )
                            					 ->get()->first()->toArray();


                    				$TS[$i]["SerialNumber"] = $serial++;
                          			$TS[$i]["FullName"] =  ucfirst($ThisStudent['user_belong']['surname'])." "
                          								.ucfirst($ThisStudent['user_belong']['middlename'])." ".
                                     					ucfirst($ThisStudent['user_belong']['firstname']);
                                    $TS[$i]["AdmissionNumber"] =  $ThisStudent['school_admission_number'];
                                    $TS[$i]["LoginEmail"] =   $ThisStudent['user_belong']["useremail"];
                                    $TS[$i]["LoginPassword"] =  $ThisStudent['password'];
                                    $i++;
                				}//end for loop
                				 //var_dump($TS);// die();

	                				$MassiveDetails = array(
															     "ChoosenTerm" => is_array($ChoosenTerm) ? $ChoosenTerm : " ",
											                     'ThisStudentTerm' => ( is_array($TS) and !empty($TS) ) ? $TS : ''
											                   );
									Session::put('ClassStudentListArray', $MassiveDetails);
									//var_dmp($MassiveDetails); die();
								//$StudentClassList = [];
                				foreach($ThisStudentTerm->toArray() as $EachStudentTerm)
					                {
					                    $ThisStudent = Students::with('UserBelong')
					                            ->where('studentid', '=', $EachStudentTerm['student_belong']['studentid'] )
					                            ->get()->first()->toArray();

					                     $ThisStudentId = $EachStudentTerm['student_belong']['studentid'];
					                     $StudentClassList[$ThisStudentId] = $ThisStudent;
					                }//end for loop */


								//	var_dump($StudentClassList);die();
							return  View::make('teachers.studentmasterlist')
								->with(array( 'myBreadCrumb' =>
								"<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Edit student score",
								'Title' => 'Student Class List',
								'StudentListMessage' => "",
								'ChoosenTerm' => is_array($ChoosenTerm) ? $ChoosenTerm : "",
								'ThisStudentTerm' => !is_null($ThisStudentTerm)?$ThisStudentTerm->toArray() : ' ',
								'StudentClassList' => !is_null($StudentClassList)?$StudentClassList : ' ',
								'NextTerm' =>  !is_null($NextTerm) ? $NextTerm->toArray() : ' '
								));
						}
						else
						{
							return  View::make('teachers.studentmasterlist')
								->with(array( 'myBreadCrumb' =>
								"<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Edit student score",
								'Title' => 'Student Class List',
								'StudentListMessage' => "This class is empty and no stiudent are available in it",
								'ChoosenTerm' => is_array($ChoosenTerm) ? $ChoosenTerm : " ",
								'ThisStudentTerm' => ' ',
								'NextTerm' =>  !is_null($NextTerm) ? $NextTerm->toArray() : ' '
								));

						}//end else that ensures ThisStudentTerm is available
					}
					else
					{
							return  View::make('teachers.studentmasterlist')
								->with(array( 'myBreadCrumb' =>
								"<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Edit student score",
								'Title' => 'Student Class List',
								'StudentListMessage' => "The selected term does not exist or is wrong. Please contact the administrator",
								'ChoosenTerm' => is_array($ChoosenTerm) ? $ChoosenTerm : " ",
								'ThisStudentTerm' => ' '
								));

					}
				}
			catch(Exception $e)
				{
					//var_dump($e); die();
					$ChoosenTerm['Class'] = Input::get('Class');
					$ChoosenTerm['Year'] =  Input::get('Year');
					$ChoosenTerm['SubClass'] = Input::get('SubClass');
					return  View::make('teachers.studentmasterlist')
							->with(array( 'myBreadCrumb' =>
							"<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student List Page",
							'Title' => 'Student Class List',
							'StudentListMessage' => "There is an error with getting the list of student in this class",
							'ChoosenTerm' => $ChoosenTerm));
				}//end catch
		}//end else that ensures that validator is ok
	}//end method getStudentClass

	public function getPublicStudentClass()
	{
		//var_dump($Year,  $Class, $SubClass );
		//var_dump(Input::all());
		//return Response::json( Input::all() );
		$AllSubjects = Subjects::all();
		$AllStudents =  Students::with('UserBelong')->get();
		$validator = Validator::make(Input::all(),
								array('Year' => 'required|date_format:Y',
									  'Class' => 'required|max:3',
									  'SubClass' => 'required|max:1|alpha',
									));//end  static method make for making a page;->withErrors($validator)->withInput();
		if($validator->fails())
		{
			//var_dump($validator); die();
			$ChoosenTerm['Class'] = Input::get('Class');
			$ChoosenTerm['Year'] =  Input::get('Year');
			$ChoosenTerm['SubClass'] = Input::get('SubClass');
			$ValidatorErr =  $validator->getMessageBag()->toArray();
	   	    $response = array('msg' => 'There are some errors with the details provided. Check below:',
	   	   					  'status' => 0,
	   	   					  'ClassListError' => $ValidatorErr
	   	   					 );

			if(Request::header("appId") == "MYAPP_ID_HERE" || Request::ajax() )
			{

			 	   return Response::json( $response);
			}

			return View::make('students.studentlist')
					->with(array( 'myBreadCrumb' => "<a href='". URL::route('home')
					."' id='BreadNav'>Home</a> => List of Students",
					'Title' => 'List of Students',
					'StudentListMessage' => "Please review the validation errors below.",
					'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'',
					'ChoosenTerm' => $ChoosenTerm,
					'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():''
					))->withErrors($validator);
		}//end if statement that complain that validation failled
		else
		{
			$ChoosenTerm['Class'] = Input::get('Class');
			$ChoosenTerm['Year'] =  Input::get('Year');
			$ChoosenTerm['SubClass'] = Input::get('SubClass');
			try
				{
					$ThisTerm = ThisTerm::with('StudentTermRelate')
										 ->where('classname', '=', Input::get('Class') )
										 ->where('termname', '=', 'first term' )
										 ->where('year' , '=', Input::get('Year') )->get()->first();
					//var_dump($ThisTerm->toArray()); die();
					if(!is_null($ThisTerm) )
					{
						$ThisStudentTerm = StudentTerm::with('StudentBelong')
											->where('thistermid', '=', $ThisTerm['id'] )
											->where('class_subdivision', '=', Input::get('SubClass') )
											->get();
						$StudentClassList = [];
						foreach($ThisStudentTerm as $EachStudentTerm)
			                {
			                    $ThisStudent = Students::with('UserBelong')
			                            ->where('studentid', '=', $EachStudentTerm['student_belong']['studentid'] )
			                            ->get()->first()->toArray();
			                      //var_dump($ThisStudent);
			                     $ThisStudentId = $EachStudentTerm['student_belong']['studentid'];
			                     $StudentClassList[$ThisStudentId] = $ThisStudent;
			                    /*echo "<b>". ucfirst($ThisStudent['user_belong']['surname'])." ".
			                   ucfirst($ThisStudent['user_belong']['middlename'])." ".
			                   ucfirst($ThisStudent['user_belong']['firstname'])."    "."</b><br /><hr />"; */
			                }//end for loop
						//var_dump($StudentClassList); die();
			                 $response = array( 'msg' => ['Student List sucessfully pulled from data' ],
	   	   					  				    'status' => 1,
	   	   					  					"StudentClassLists" =>  $StudentClassList
	   	   					 				  );
			                if(Request::header("appId") == "MYAPP_ID_HERE" || Request::ajax() )
							{

							 	   return Response::json($response);
							}
										return  View::make('students.studentlist')
							->with(array( 'myBreadCrumb' =>
							"<a href='". URL::route('home')."' id='BreadNav'>Home</a> => List of Students",
							'Title' => 'List of Students',
							'StudentListMessage' => "",
							'ChoosenTerm' => $ChoosenTerm,
							'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():''  ,
							'StudentClassList' => !is_null($StudentClassList) ? $StudentClassList : ''
							));
						}
				}
			catch(Exception $e)
				{
					//var_dump($e); die();
					$ChoosenTerm['Class'] = Input::get('Class');
					$ChoosenTerm['Year'] =  Input::get('Year');
					$ChoosenTerm['SubClass'] = Input::get('SubClass');
					 $response = array('msg' => 'There are some errors with the details provided',
	   	   					  			'status' => 0,
	   	   					 );
					if(Request::header("appId") == "MYAPP_ID_HERE" || Request::ajax() )
							{

							 	   return Response::json($response);
							}
					return  View::make('teachers.studentmasterlist')
							->with(array( 'myBreadCrumb' =>
							"<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student List Page",
							'Title' => 'Student Class List',
							'StudentListMessage' => "There is an error with getting the list of student in this class",
							'ChoosenTerm' => $ChoosenTerm));
				}//end catch
		}//end else that ensures that validator is ok
	}//end method getStudentClass

	public function showClasses()
	{
		if(Request::ajax())
		{
			//return Response::json( Input::all() );
			$FormMessages = array('YearSelectAdmin.required' => 'The year is required.',);
		    $validator = Validator::make(Input::all(),
							array('YearSelectAdmin' => 'required|date_format:Y'), $FormMessages);//end static method make of Validator
			if($validator->fails())
			{
				//var_dump($validator->messages()); die();
				//return Response::json("The year is required.");
				$ValidatorErr =  $validator->getMessageBag()->toArray();
			   	$response = array('msg' => 'There are some errors with the details provided. Please, Check below',
			   	   					 'status' => 0,'ShowClassInfo' => $ValidatorErr
			   	   					);
			 	return Response::json($response);
						 		//end  static method make for making a page;->withErrors($validator)->withInput();
			}//end if statement that complain that validation failled
			else
			{
				//return Response::json( Input::all() );
				Session::put('MyYear',  Input::get('YearSelectAdmin') );
				$response = array('msg' => '',
			   	   					 'status' => 1,
			   	   					 'ShowClassInfo' => ["Year Changed to " . Input::get('YearSelectAdmin')],
                                    'ResponseURl' => URL::route('public-class-list-page',array('Year' => Input::get('YearSelectAdmin'),
                                                                                               'Class' =>  Input::get('CurrentClass') ))
			   	   					);
			 	return Response::json($response);
				//return Response::json( "Year Changed to " . Input::get('YearSelectAdmin') );
			}
		}

			$ChoosenTerm['Class'] = Input::get('Class');
			$ChoosenTerm['Year'] =  Session::has('MyYear') ? Session::get('MyYear') : Input::get('Year');
			return View::make('teachers.classlist')
					->with(array( 'myBreadCrumb' => "<a href='". URL::route('home')
					."' id='BreadNav'>Home</a> => Student Report Page",
					'Title' => 'List of Classes',
					//'StudentListMessage' => "Please review the validation errors below.",
					//'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'',
					'ChoosenTerm' => $ChoosenTerm,
					));//->withErrors($validator);
	}//end showClasses

	public function showPublicClasses()
	{
		$ChoosenTerm['Class'] = Input::get('Class');
		$ChoosenTerm['Year'] =  Input::get('Year');
		$ThisTermSQL = " SELECT ttm.id AS 'ThisTermId',  stem.class_subdivision AS 'class_subdivision', ".
                       " ttm.classname AS 'classname', ttm.year AS 'year' ".
                       " FROM studentterm AS stem, thisterm AS ttm ".
                       " WHERE ttm.termname = 'first term' ".
                       " AND ttm.year = " . Input::get('Year') .
                       " AND ttm.classname = '" . Input::get('Class') . "'" .
                       " AND ttm.id = stem.thistermid " .
                       " GROUP BY ttm.classname, stem.class_subdivision";
		$ClassList = DB::select($ThisTermSQL);
		#Count The Amount of Classes
        $ClassListCount = 0; #Start from Zero
        $TotalStudentClassCount = 0; #Start from Zero
        $ClassStudentArray = []; #Start from Zero
        $ClassListArray = []; #Start from Zero
		if(count($ClassList) > 0)
        {
            $ClassListCount = count($ClassList);
            foreach ($ClassList as $EachClass):
                    $Class =  $EachClass->classname . strtoupper($EachClass->class_subdivision);

                    $ThisStudentTerm = StudentTerm::with('StudentBelong')
                        ->where('thistermid', '=', $EachClass->ThisTermId)
                        ->where('class_subdivision', '=',  $EachClass->class_subdivision )
                        ->get();
                    $ClassStudentArray[$Class]['ClassStudentCount'] = $ThisStudentTerm->count() > 0 ? $ThisStudentTerm->count() : 0;
                    $ClassStudentArray[$Class]['FullClassName'] =  $EachClass->classname . " ". strtoupper($EachClass->class_subdivision);;
                    $ClassStudentArray[$Class]['ClassName'] = $EachClass->classname;
                    $ClassStudentArray[$Class]['ClassSubDivision'] = strtoupper($EachClass->class_subdivision);
                    $ClassStudentArray[$Class]['ClassYear'] = strtoupper($EachClass->year);
                    $TotalStudentClassCount += $ThisStudentTerm->count() > 0 ? $ThisStudentTerm->count() : 0;
                    $ClassListArray['Class'][] = $ClassStudentArray[$Class]['FullClassName'];
                    $ClassListArray['ClassPopulation'][] = $ClassStudentArray[$Class]['ClassStudentCount'];

            endforeach;
            #Run a small loop to get the oercentage distribution
            foreach ($ClassListArray['ClassPopulation'] as $EachClassPopulation):
                $StudentClassCount = $EachClassPopulation > 0 ? $EachClassPopulation : 0;
                $ClassPercentage = $TotalStudentClassCount > 0 ? ($StudentClassCount/$TotalStudentClassCount) * 100 : 0;
                $ClassListArray['ClassPopulationPercentage'][] =  (float)number_format($ClassPercentage, 2);
            endforeach;
        }

        //var_dump( $ClassListCount,$ClassStudentArray, $TotalStudentClassCount, $ClassListArray); die();

		return View::make('students.classlist')
				->with(array( 'myBreadCrumb' => "<a href='". URL::route('home')
				."' id='BreadNav'>Home</a> => List of Classes ",
				'Title' => 'List of Classes',
				'ChoosenTerm' => $ChoosenTerm,
                'ClassListCount' => $ClassListCount,
                'ClassStudentArray' => $ClassStudentArray,
                'TotalStudentClassCount' => $TotalStudentClassCount,
                'ClassListArray' => json_encode($ClassListArray)

				));
	}//end showPublicClasses
}//end class
