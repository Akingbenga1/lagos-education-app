<?php

namespace App\Http\Controllers;


use App\Models\Social;
use App\Models\Students;
use App\Models\StudentTerm;
use App\Models\Subjects;
use App\Models\SubjectScore;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class AccountController extends Controller
{

    public function __construct(  )
    {

        $this->middleware('auth');
        $this->middleware('web');
    }

    public function showTeachersHomePage(Request $request)
    {
        $method = $request->isMethod('post');
        if($method)
        {


        }
        else
        {

            $AllStudents = Students::with(['UserBelong'])->get();
            $AllSubjects = Subjects::all();
            return View::make('teachers.teachershomepage')->with(
                array(
                    'Title' => 'Student Registration Form',
                    'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():[],
                    'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():[] ));

        }
    }

    public function getStudentDetailsSession(Request $request)
    {
        $method = $request->isMethod('post');
        if($method)
        {


        }
        else
        {
            $AllSubjects = Subjects::all();
            $ThisStudentId = (Session::has('ThisStudent')) ? Session::get('ThisStudent'): '';
            try{
                $ThisStudent = Students::with('UserBelong')
                    ->where('studentid', '=', $ThisStudentId )->get()->first();
                return View::make('teachers.getstudentdetails')->with(
                    array(
                        'Title' => ' Get Student Detail',
                        'myBreadCrumb' => "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Add Student Term",
                        'ThisStudent' => !is_null($ThisStudent) ?$ThisStudent->toArray():'',
                        'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():''));
            }
            catch(\Exception $e)
            {
                return View::make('teachers.getstudentdetails')->with( array(
                    'Title' => ' Get Student Detail',
                    'myBreadCrumb' => "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Add Student Term",
                    'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():''
                ));

            }//end catch block

        }

    }

    public function registerStudentDetails()
    {
        $FormMessages = array(
            'StudentSurname.required' => 'The surname of the student is required.',
            'StudentFirstname.required' => 'The firstname of the student is required.',
            'StudentAdmissionNumber.required' => 'The Student Admission Number of the student is required.',
            'StudentAdmissionNumber.size' => 'The Student Admission Number of the student must be 4 characters.',
        );
        $validator = Validator::make(Input::all(),
            array(
                'StudentSurname' => 'required|max:50',
                'StudentMiddlename' => 'max:50',
                'StudentFirstname' => 'required|max:50',
                'StudentAdmissionNumber' => 'required|size:4',
            ), $FormMessages
        );
        if($validator->fails())
        {
            return Redirect::route('teachers-home-page')->with(array(
                'AccountCreateInfo'=> 'Error! Incorrect Input of information',
                'GoodResponse' => 0))->withErrors($validator)->withInput();
        }
        else
        {
            $StudentSurname = Input::get('StudentSurname');
            $StudentMiddlename = Input::get('StudentMiddlename');
            $StudentFirstname = Input::get('StudentFirstname');
            $StudentMiddlename = Input::get('StudentMiddlename');
            $StudentAdmissionNumber = Input::get('StudentAdmissionNumber');
            $password = str_random(6);// generate random strings
            $user =  new Users;
            $user->useremail =  Input::get('StudentAdmissionNumber').'@ijayehousingestatesenior.com.ng';
            $user->firstname = $StudentFirstname;
            $user->middlename = $StudentMiddlename;
            $user->surname = $StudentSurname;
            $user->password = Hash::make($password);
            $user->activated = 1;

            try
            {
                $user->save(); // save user details to data base
                $student = new Students;
                $student->school_admission_number = $StudentAdmissionNumber;
                $student->password = $password;
                $student->userid = $user->userid;
                $student->save();

                //send activation email here
                /*Mail::send(
                            'emails.outgoing.activateaccount',
                             array(
                             'link'=> URL::route('account-activate',$code),
                             'username' => $user->username),
                             //you can continue to use users here
                             function($message) use ($user)
                             {
                               $message->to(
                                $user->useremail,
                                 $user->username)->subject(
                                 'Activate your Account')->from(
                                 'admin@hisarksoulcare.com', 'Favours Group');
                           }//end anonymous function
                       );//end send */
                return Redirect::route('teachers-home-page')->with(array(
                    'AccountCreateInfo'=> 'Student account created.',
                    'GoodResponse' => 1));
            }
            catch(\Exception $e)
            {
                if(isset($student) ) { $student->delete(); }
                if(isset($user) ) { $user->delete(); }
                return Redirect::route('teachers-home-page')->with(array(
                    'AccountCreateInfo'=>'Error! Unable to create student account.
				  		 There might be a duplicate student admission number of , <strong>'.
                        Input::get('StudentAdmissionNumber').'</strong> , in the database.',
                    'GoodResponse'=> 0));
            }//end catch block
        }//end else that ensures that the user data is validates
    }

    public function editStudentDetails()
    {
        $FormMessages = array(
            'Surname.required' => 'Surname is required.',
            'Firstname.required' => 'Firstname is required.',
            'Surname.max' => 'Surname cannot be more than :max characters.',
            'Middlename.max' => 'Middlename cannot be more than :max characters.',
            'Firstname.max' => 'Firstname cannot be more than :max characters.',
            'StudentAdmissionNumber.required' => 'Student Admission Number is required.',
            'DateOfBirth.date' => 'Date of birth is not a valid date.',
            'StudentAdmissionNumber.size'
            => 'Student Admission Number must be exactly :size characters.',
            //'SecondStudentEmail.email' => 'This  Student Admission Number of the student is required.',
            //'SecondStudentEmail.unique' => 'This  Student Admission Number of the student is required.',
        );
        $validator = Validator::make(Input::all(),
            array(
                'Surname' => 'required|max:50',
                'Middlename' => 'max:50',
                'Firstname' => 'required|max:50',
                'StudentAdmissionNumber' => 'required|size:4',
                'SecondStudentEmail' =>'max:50|email|nullable',
                'DateOfBirth' => 'date'
            ), $FormMessages
        );
        if($validator->fails())
        {
            return Redirect::route('get-student-details')->with(array(
                'EditStudentMessage'=> 'Please review the following errors below',
                'GoodResponse' => 0,

            ))->withErrors($validator)->withInput();
        }
        else
        {
            try
            {
                DB::transaction(function()
                {
                    $ThisStudentId = (Session::has('ThisStudent')) ? Session::get('ThisStudent'): '';
                    $ThisStudent = Students::with('UserBelong')
                        ->where('studentid', '=', $ThisStudentId )->get()->first();
                    $EditStudent = !is_null($ThisStudent)?$ThisStudent->toArray():'';
                    $StudentSurname = Input::get('Surname');
                    $StudentMiddlename = Input::get('Middlename');
                    $StudentFirstname = Input::get('Firstname');
                    $StudentMiddlename = Input::get('Middlename');
                    $StudentAdmissionNumber = Input::get('StudentAdmissionNumber');
                    $StudentDOB = Input::get('DateOfBirth');
                    $SecondStudentEmail = Input::get('SecondStudentEmail');
                    $Sex = Input::get('Sex');

                    $user =  Users::find($EditStudent['user_belong']['userid']);
                    $user->useremail =  Input::get('StudentAdmissionNumber').'@ijayehousingestatesenior.com.ng';
                    $user->firstname = $StudentFirstname;
                    $user->middlename = $StudentMiddlename;
                    $user->surname = $StudentSurname;
                    $user->date_of_birth = $StudentDOB;
                    $user->sex = $Sex;
                    $user->second_email = $SecondStudentEmail;

                    $student = Students::find($EditStudent['studentid']);
                    $student->school_admission_number = $StudentAdmissionNumber;
                    $user->save();
                    $student->save();
                });
                return Redirect::route('get-student-details')->with(array(
                    'EditStudentMessage'=> 'Student details editted.',
                    'GoodResponse' => 1,
                ));
            }
            catch(\Exception $e)
            {
                return Redirect::route('get-student-details')->with(array(
                    'EditStudentMessage'=>'Error! Unable to edit student details. There might be a duplicate admission number. ',
                    'GoodResponse'=> 0,
                ));
            }
        }

    }

//New List of Methods


    //Old List of Methods
	public function getLoginForm()
	{
		return View::make('account.loginform')->with(
								array(
								'Title' => 'Login'));  //end  static method make for making a page
	}//end method getLoginForm

	public function postLoginDetails()
	{
		$validator = Validator::make(Input::all(),
					array(
							'Email' => 'required|email',
							'Password' => 'required|min:6',
						)
					);//end static method make
		if($validator->fails())
		{

			if(Request::header("appId") == "MYAPP_ID_HERE")
			{
				  $ValidatorErr =  $validator->getMessageBag()->toArray();
			   	   $response = array('msg' => 'There are some errors with the details provided. <br /> Check below:',
			   	   'status' => 0,
			   	   'LoginInfo' => [$ValidatorErr] );
			 	   return Response::json($response);
			}
			return Redirect::to('login-form')->with(
				   'LoginInfo',
				   'There was a problem with the information you provided')->withErrors($validator)->withInput(Input::except('Password'));

		}//end if statement that complain that validation failled
		else
		{
			$auth = Auth::attempt( array(
					'useremail' => Input::get('Email'),
					'password' => Input::get('Password'),
					'activated' => 1
							));//end static method attempt
			if($auth) // check that auth object is created
			{

				if(Request::header("appId") == "MYAPP_ID_HERE")
							{
								$ThisUser = Users::with('StudentRelate')
					     					->where('userid', '=', Auth::user()->userid  )->get()->first();
					     		$ThisStudent = array();
					     		$ThisClass = array();
					     		$ThisTermId =" ";
					     		if(($ThisUser["student_relate"]) )
					     			{
					     				$Studentid = $ThisUser["student_relate"]["studentid"];
							     		$ThisStudent = Students::with("StudentTermRelate")->where('studentid', '=', $Studentid )
							     		->get()->last();
							     		if( ($ThisStudent["student_term_relate"])  )
								     		{
								     			$LastIndex = count($ThisStudent["student_term_relate"]) - 1;
								     			$ThisTermId = $ThisStudent["student_term_relate"][$LastIndex]["thistermid"];
								     			$ThisClass = ThisTerm::where('id', '=', $ThisTermId )->get();

								     		}
							     	}
							   	   			$response = array('msg' => ['Success'], 'status' => 1,
							   	   							  "AuthData" => $ThisUser->toArray() ,
							   	   							  'ThisStudent' => !empty($ThisStudent) ? $ThisStudent->toArray() : array(),
							   	   							  'ThisClass' => !empty($ThisClass) ? $ThisClass->toArray() : array(),
							   	   			 				  'LoginInfo' =>[ 'You have been logged in.'] );
							 	   			return Response::json($response);
							}
				return Redirect::intended('/userprofile.html');

			}//end if statement
			else
			{
				//return "did not login";
				if(Request::header("appId") == "MYAPP_ID_HERE")
							{
									  //$ValidatorErr =  $validator->getMessageBag()->toArray();
				   	   			$response = array('msg' => ['There are some errors with the details provided. Check below'],
				   	   							  'status' => 0,
				   	   							  'NoAuth' => ['Email and Password do not match in database. You are not logged in']  );
				      			// $response = array_merge($response,(array)$ValidatorErr);
				 	   			return Response::json($response);
								//return 1; // Response::json("Good to be here");
							}
				return Redirect::route('login-form')->with(
				   'LoginInfo',
				   'Username or Password do not match!')->withInput(Input::except('Password'));;
			}//end else  that redirects back with error if cant authenticate
		}//end else that ensures that validator s ok
	}//end method postLoginDetail

	public function getProfileData()
	{
	  // dd(Auth::user());
		$ThisUser = Users::with('StudentRelate')->where('userid', '=', Auth::user()->userid  )->get()->first();
		$UserThoughts = Social::with('UserBelong')->where('user_id', '=', Auth::user()->userid  )->get();
		$YourThoughts = (count($UserThoughts) > 0 ) ? $UserThoughts->first()->toArray() : [ "thoughts"=>"You have not posted any Thoughts yet."];
		//var_dump($YourThoughts);die();

		if(!is_null($ThisUser) and ( count($ThisUser) > 0 ) )
        {
            $ThisUserArray = $ThisUser->toArray();
            $ThisStudentId =  ( array_key_exists('student_relate', $ThisUser->toArray() ) and
            				    (count($ThisUser->toArray()['student_relate'])  > 0 )
            					and  array_key_exists('studentid', $ThisUser->toArray()['student_relate'] ) ) ? $ThisUserArray['student_relate']['studentid']  : 0  ;

            $ThisStudentTerm =  StudentTerm::with('ThistermBelong')->where('studentid', '=',  $ThisStudentId )->get();

            $LastStudentTerm =  ( !is_null($ThisStudentTerm) and count($ThisStudentTerm) > 0 ) ?  $ThisStudentTerm->last() : [];
           // dd($LastStudentTerm->toArray());
            if( !is_null($LastStudentTerm) and  (count($LastStudentTerm) > 0)   )
            {
                $LastStudentTermArray =  $LastStudentTerm->toArray();

                $StudentThisTerm = array_key_exists('thisterm_belong', $LastStudentTermArray ) ? $LastStudentTermArray['thisterm_belong'] : [] ;

                $ThisStudentClass = array_key_exists('classname', $StudentThisTerm) ?
                					 $StudentThisTerm['classname'] : '';
            $ThisStudentSubClass = array_key_exists('class_subdivision', $LastStudentTermArray) ?  $LastStudentTermArray['class_subdivision'] : '';

            $ThisStudentYear = array_key_exists('year', $StudentThisTerm) ?  $StudentThisTerm['year'] : '';
            $ThisStudentId = array_key_exists('studentid', $LastStudentTermArray) ?  $LastStudentTermArray['studentid'] : '';

            //dd($ThisStudentClass, $ThisStudentSubClass,  $ThisStudentYear,  $ThisStudentId );

            //Check for Result Presecnce
            $ThisStudentScores = SubjectScore::where('studentid', '=', $ThisStudentId )
                            ->where('classname', '=', $ThisStudentClass )
                            ->where('class_subdivision', '=', $ThisStudentSubClass )
                            ->where('year', '=', $ThisStudentYear )->distinct()->select('termname')->get();
            //var_dump($ThisStudentTerm->toArray());
            //dd($ThisStudentScores);
            }
        }
		return View::make('teachers.userprofile')->with(array( 'myBreadCrumb' => "<a href='". URL::route('home')
                                                                    ."' id='BreadNav'>Home</a> => Score Entry Page",
                                                                    'Title' => 'Your User Profile',
                                                                    'ThisUser' => (!is_null($ThisUser) and count($ThisUser) > 0 ) ?$ThisUser->toArray():[] ,
                                                                    'ThisStudentTerm' => ( isset($LastStudentTerm) and count($LastStudentTerm) > 0 and !is_null($LastStudentTerm) )? $LastStudentTerm->toArray(): [],
            'ThisStudentScores' => ( isset($ThisStudentScores) and  !is_null($ThisStudentScores) and count($ThisStudentScores) > 0    )? $ThisStudentScores->toArray(): [],
						'YourThoughts' => $YourThoughts
					                                        ));
	}//end method getS

	public function postProfileData()
	{
		// print_r(Input::all());die();
		Session::flash('SourceUrl', 'UserProfile');
		$FormMessages = array(
                           		'Surname.required' => 'Surname is required.',
                           		'Firstname.required' => 'First name is required.',
															'DateOfBirth.required' => 'Date of Birth is required.',
															'Sex.required' => 'Sex is required.',
                           		'Surname.max' => 'Surname cannot be more than :max characters.',
                           		'Middlename.max' => 'Middlename cannot be more than :max characters.',
                           		'Firstname.max' => 'First name cannot be more than :max characters.',
                           		'DateOfBirth.date' => 'Date of birth is not a valid date.',
                           		'SecondStudentEmail.email' => 'Second Email specified is not a valid email.
                           									   Please provide a valid email',
                           		//'SecondStudentEmail.unique' => 'This  Student Admission Number of the student is required.',
						 	);
		$validator = Validator::make(Input::all(),array(
														 'Surname' => 'required|max:50',
														 'Middlename' => 'required|max:50',
														 'Firstname' => 'required|max:50',
														 'SecondStudentEmail' =>'email',
														 'Sex' =>'required',
														 'DateOfBirth' => 'required'
														), $FormMessages);//end static method make
		if($validator->fails())
		{
			// $ValidatorErr =  $validator->getMessageBag()->toArray();
			// $response = array('msg' => 'Error! Please review the following below', 'status' => 0);
			// $response = array_merge($response,(array)$ValidatorErr);
			//  					return Response::json($response);
			// return Redirect::back()->with('ComplainError','The name of role must be provided')->withErrors($validator)->withInput();
			return Redirect::back()->withErrors($validator)->withInput();
		}//end if statement that complain that validation failled
		else
		{ //return Response::json(Input::all());
			try
			{
				DB::transaction(function()
					{
						$Surname = Input::get('Surname');
						$Middlename = Input::get('Middlename');
						$Firstname = Input::get('Firstname');
						$Formatted_DOB = explode('/', Input::get('DateOfBirth'));
						$DOB = (count($Formatted_DOB) > 0 ) ? ($Formatted_DOB[2]. '-'. $Formatted_DOB[1] . '-'.$Formatted_DOB[0] ) :  Carbon::now()->format('Y-m-d H:m:s');
						$SecondEmail = Input::get('SecondEmail');
						$Sex = Input::get('Sex');


						$User  = Users::with('StudentRelate')->where('userid', '=', Auth::user()->userid  )->get()->first();
						$User->firstname = $Firstname;
						$User->middlename = $Middlename;
						$User->surname = $Surname;
						$User->date_of_birth = $DOB;
						$User->sex = $Sex;
						$User->second_email = $SecondEmail;

    				$User->save();

				    });
				// $response = array('msg' => 'Profile details was successfully updated', 'status' => 1);
			 	// 			return Response::json($response);
					return Redirect::back()->with('success','The User details successfully updated');
			}
			catch(Exception $e)
			{
			 		//var_dump($e);die();
			 		// $response = array('msg' => 'Profile details could not be updated', 'status' => 0 );
			 		// return Response::json($response);
					return Redirect::back()->with('error','User Profile Update was not successful!')->withInput();
			}//end catch block
		}//emd else
 	}//end method postProfileData

	public function postYourThoughts()
	{
		 //print_r(Input::all());die();
		 Session::flash('SourceUrl', 'YourThoughts');
		$FormMessages = array(
                           		'Thoughts.required' => 'You need to type something and let people know whats on your mind.',
															'Thoughts.max' => 'Your message cannot be more than :max characters.'
						 								);
		$validator = Validator::make(Input::all(),array(
														 'Thoughts' => 'required|max:120',
														), $FormMessages);//end static method make
		if($validator->fails())
		{
			// $ValidatorErr =  $validator->getMessageBag()->toArray();
			// $response = array('msg' => 'Error! Please review the following below', 'status' => 0);
			// $response = array_merge($response,(array)$ValidatorErr);
			//  					return Response::json($response);
			// return Redirect::back()->with('ComplainError','The name of role must be provided')->withErrors($validator)->withInput();
			return Redirect::back()->withErrors($validator)->withInput();
		}//end if statement that complain that validation failled
		else
		{ //return Response::json(Input::all());
			try
			{
						$AllExisitingThought  = Social::where('user_id', '=', Auth::user()->userid  )->get();
						if(count($AllExisitingThought) > 0)
						{
							$ExisitingThought = $AllExisitingThought->first();
							$ExisitingThought->thoughts = Input::get('Thoughts');;
							$ExisitingThought->updated_at =  Carbon::now()->format('Y-m-d  H:m:s');
							$ExisitingThought->save();
						 return Redirect::back()->with('success','Your Message has been updated successfully')->withInput();
						}
						else
						{
							$NewThought = new Social;
							$NewThought->thoughts = Input::get('Thoughts');
							$NewThought->user_id = Auth::check() ?  Auth::user()->userid  : 1;
							$NewThought->save();
							return Redirect::back()->with('success','Your Message has been created successfully!');
						}
				// $response = array('msg' => 'Profile details was successfully updated', 'status' => 1);
			 	// 			return Response::json($response);

			}
			catch(Exception $e)
			{
			 		var_dump($e);die();
			 		// $response = array('msg' => 'Profile details could not be updated', 'status' => 0 );
			 		// return Response::json($response);
					return Redirect::back()->with('error','Your Message could not be saved!!')->withInput();
			}//end catch block
		}//emd else
 	}//end method postProfileData

	public function loadRolesForm()
	{
		$AllAssignedRoles =  AssignedRoles::with(['userBelong', 'roleBelong'])->get();
		$AllSubjects = Subjects::all();
		//var_dump($AllAssignedRoles->toArray()); die();
		$user = Auth::user();
		if ($user->ability(array('Super User', 'Administrator'), array()) )
    		{
      			$Roles = Role::getAllRoles();
				$Permissions = Permissions::getAllPermissions();
				$Users = Users::all()->toArray();
				return View::make('admin.createrole')->with(
								  array(
										  'Title' => 'Create User Roles',
								 		  'Roles' => $Roles,
								 		  'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():'' ,
								 		  'AssignedRoles' => !is_null($AllAssignedRoles) ? $AllAssignedRoles->toArray() : '' ,
								 		  'Permissions' => $Permissions,
								  		  'Users' => $Users ));  //end  static method make for making a page
			}
		else{
				return Redirect::to(URL::previous());
			}

	}//end method loadRolesForm

	public function createRoles()
	{
		//do validation here.. now
		$validator = Validator::make(Input::all(),
							array('name' => 'required|between:4,255|unique:roles'));//end static method make of Validator
		if($validator->fails())
		{
			return Redirect::route('admin-roles')->with(
				   'ComplainError',
				   'The name of role must be provided')->withErrors($validator)->withInput();
		}//end if statement that complain that validation failled
		else
		{
			try{
					$NewRole = new Roles();
					$NewRole->name = Input::get('name');
		 			$NewRole->save();
					return Redirect::route('admin-roles')->with(
				   'ComplainError',
				   'Role has been created');
				}
			catch(Exception $e)
				{
					return Redirect::route('admin-roles')->with(
				   'ComplainError',
				   'Unable to create roles. <br />Error Creating roles');
				}
		}//end else that ensures that validator s ok
	}//end method  createRoles

	public function editRoles()
	{
		//return Response::json( Input::all());
		$validator = Validator::make(Input::all(),
							array('AssignedRoleID' => 'required|integer',
								  'NewRoleId' => 'required|integer',
								  'OldRoleId' => 'required|integer',
								  'UserID' => 'required|integer'
								  ));
							//end static method make of Validator
		if($validator->fails())
		{
			if (Request::ajax())
				{
					$ValidatorErr =  $validator->getMessageBag()->toArray();
					 $response = array('msg' => 'Validator fails', 'status' => 0);
					 $response = array_merge($response,(array)$ValidatorErr);
 					 return Response::json($response);
 		        }
			return Redirect::route('admin-roles')->with(
				   'ComplainError',
				   'The name of role must be provided')->withErrors($validator)->withInput();
		}//end if statement that complain that validation failled
		else
		{
			$user = Users::find(Input::get('UserID'));	//var_dump($user); die();
			try{
					$NewRole =  Roles::find(Input::get('NewRoleId'));
					$OldRole =  Roles::find(Input::get('OldRoleId'));
					$AssignedRole =  AssignedRoles::find(Input::get('AssignedRoleID'));
					if(!is_null($NewRole) and !is_null($OldRole) and !is_null($user) and !is_null($NewRole))
						{
							$NameofUser =  $user->surname." ".$user->middlename." ".$user->firstname;
							$NameofOldRole =  $OldRole->name;
							$NameofNewRole =  $NewRole->name;
							$AssignedRole->role_id = Input::get('NewRoleId');
							$AssignedRole->save();
				 			if (Request::ajax())
								{
									$ValidatorErr =  $validator->getMessageBag()->toArray();
									 $response = array('msg' => 'Validator fails', 'status' => 0);
									 $response = array('msg' => '', 'status' => 1,'ReportChange' =>
									 	"Role <b>(" . $NameofOldRole. ")</b> for <b> ". $NameofUser . " </b> has been changed to <b>". $NameofNewRole ."</b> Role",
									 	'AlertReportChange' =>
									 	"Role (" . $NameofOldRole. ") for  ". $NameofUser . "  has been changed to ". $NameofNewRole ." Role" );
									 return Response::json($response);
				 		        }
							return Redirect::route('admin-roles')->with(
						   'ComplainError',
						   "" );
						}
					else
						{
							if (Request::ajax())
								{
									$ValidatorErr =  $validator->getMessageBag()->toArray();
									 $response = array('msg' => 'Validator fails', 'status' => 0);
									 $response = array('msg' => '', 'status' => 0,'ReportChange' =>
									 	"Cannot be chnaged to Role", 'AlertReportChange' =>"Cannot be chnaged to selected Role" );
									 return Response::json($response);
				 		        }
								return Redirect::route('admin-roles')->with(
						   'ComplainError',
						   "Role <b>(" . $NewRole->name. ")</b> for <b> ". $NameofUser . " </b> has been detached");
						}
				}
			catch(Exception $e)
				{
					if (Request::ajax())
								{
									$ValidatorErr =  $validator->getMessageBag()->toArray();
									 $response = array('msg' => 'Validator fails', 'status' => 0);
									 $response = array('msg' => '', 'status' => 0,'ReportChange' =>
									 	"Cannot be chnaged to selected Role",
									 	 'AlertReportChange' =>"Cannot be chnaged to selected Role" );
									 return Response::json($e);
				 		        }
					//var_dump($e); die();
					return Redirect::route('admin-roles')->with(
				   'ComplainError',
				   "Unable to detach roles <b> ( " . $NewRole->name . ") </b> for <b>" .  $NameofUser . "</b>. <br />Error detaching roles");
				}
		}//end else that ensures that validator s ok
	}//end method  createRoles

	public function detachRoles()
	{
		//var_dump(Input::all());die();//do validation here.. now
		$validator = Validator::make(Input::all(),
							array('EachAssignedRoles' => 'required|integer', 'EachUserId' => 'required|integer'));
							//end static method make of Validator
		if($validator->fails())
		{
			return Redirect::route('admin-roles')->with(
				   'ComplainError',
				   'The name of role must be provided')->withErrors($validator)->withInput();
		}//end if statement that complain that validation failled
		else
		{
			$user = Users::find(Input::get('EachUserId'));
			$NameofUser =  $user->surname." ".$user->middlename." ".$user->firstname;
			//var_dump($user); die();

			try{
					$NewRole =  Roles::find(Input::get('EachAssignedRoles'));
					$user->detachRole($NewRole);
		 			//$NewRole->save();
					return Redirect::route('admin-roles')->with(
				   'ComplainError',
				   "Role <b>(" . $NewRole->name. ")</b> for <b> ". $NameofUser . " </b> has been detached");
				}
			catch(Exception $e)
				{
					//var_dump($e); die();
					return Redirect::route('admin-roles')->with(
				   'ComplainError',
				   "Unable to detach roles <b> ( " . $NewRole->name . ") </b> for <b>" .  $NameofUser . "</b>. <br />Error detaching roles");
				}
		}//end else that ensures that validator s ok
	}//end method  createRoles

	public function createPermissions()
	{
		$validator = Validator::make(Input::all(),
									  array('permission_name' => 'required|between:4,255|unique:permissions,name',
											'display_name' => 'required|between:4,255'));//end static method make of Validator

		if($validator->fails())
		{
			return Redirect::route('admin-roles')->with(
				   'ComplainError',
				   'There are some error with the permission')->withErrors($validator)->withInput();
		}//end if statement that complain that validation failled
		else
		{
			try{
					$NewPermission = new Permissions();
					$NewPermission->name = Input::get('permission_name');
					$NewPermission->display_name = Input::get('display_name');
		 			$NewPermission->save();
					return Redirect::route('admin-roles')->with(
				   'ComplainError',
				   'New permission has been created');
				}
			catch(Exception $e)
				{
					return Redirect::route('admin-roles')->with(
				   'ComplainError',
				   'Unable to create new permission. <br />Error Creating roles');
				}
		}//end else that ensures that validator is ok
	}//end methpod  createPermissions

	public function attachPermissionsToRole()
	{
		//var_dump(Input::get('PermissionList'));
		//die();
		$validator = Validator::make(Input::all(),
									  array('Roles' => 'integer'));//end static method make of Validator

		if($validator->fails())
		{
			return Redirect::route('admin-roles')->with(
				   'ComplainError',
				   'There are some error with the permission')->withErrors($validator)->withInput();
		}//end if statement that complain that validation failled
		else
		{
			//die();
			try{

				$ThisRole = Roles::find(Input::get('Roles'));
			foreach ( Input::get('PermissionList') as $key => $value)
					{
							$ThisPermission = Permissions::find($value);
							$ThisRole->attachPermission($ThisPermission);
					}//end for loop
					return Redirect::route('admin-roles')->with(
				   'ComplainError',
				   'Permission(s) attached to the Role selected');
				}
			catch(Exception $e)
				{
					///var_dump($e);
					//die();
					return Redirect::route('admin-roles')->with(
				   'ComplainError',
				   'There is a problem.<br />
				   We are unable to attach permission to the selected role or the role is already atached. <br />
				   Please contact the developer ');
				}
		}//end else that ensures that validator is ok
	}//end methpod  attachPermissionsToRole

	public function attachRolesToUser()
	{
		//var_dump(Input::get('PermissionList'));
		//die();
		$validator = Validator::make(Input::all(),
									  array('Roles' => 'integer'));//end static method make of Validator

		if($validator->fails())
		{
			return Redirect::route('admin-roles')->with(
				   'ComplainError',
				   'There are some error with assigning roles to users')->withErrors($validator)->withInput();
		}//end if statement that complain that validation failled
		else
		{
			try{
					$ThisUser = Users::find( Input::get('UserName') ); // get the user as an object
					if(!is_null($ThisUser))
					{
						$ThisUser->attachRole( Roles::find( Input::get('Roles') ) ); // attach the role to tne user
						return Redirect::route('admin-roles')->with(
					   'ComplainError',
					   'Roles attached to the User selected');
					}
					else
					{
						return Redirect::route('admin-roles')->with(
					   'ComplainError',
					   	'User not found. Please choose user from list');

					}
				}
			catch(Exception $e)
				{
					//var_dump($e);die();
					return Redirect::route('admin-roles')->with(
				   'ComplainError',
				   'There is a problem.<br />
				   We are unable to attach roles to the selected user. <br />
				   Please contact the developer ');
				}
		}//end else that ensures that validator is ok
	}//end methpod  attachRolesToUser

	public function userSignOut()
	{
		if(Request::header("appId") == "MYAPP_ID_HERE")
			{
				Auth::logout();
				$response = array('logout' =>["You are Logged out"], 'status' => 1, );
			 	return Response::json($response);
			}
		Auth::logout();
		return Redirect::route('login-form');
	}//end method userSignOut

	public function getCreate()
	{
		return View::make('account.create')->with(
								array(
								'Title' => 'New Account'));
	}//end method getCreate

	public function getStudentRegForm()
	{
		return View::make('teachers.studentregistrationform')->with(
								array(
								'Title' => 'Student Registration Form'));
	}//end method getCreate




	public function editStudentDetailsForm()
	{
		$AllSubjects = Subjects::all();
		$AllStudents = Students::with('UserBelong')->get();
		return View::make('teachers.editstudentdetails')->with(
								array(
								'Title' => 'Edit Student ',
								'myBreadCrumb' => "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Add Student Term",
								'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'',
								'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():''));
	}//end method editStudentDetailsForm



	public function getStudentDetails()
	{
		$AllStudents = Students::with('UserBelong')->get();
		$AllSubjects = Subjects::all();
		//var_dump(Input::all()); die();
		$FormMessages = array('StudentId.required' => 'The name of the student is required.', 'StudentNumber.required' => 'The student admission number  is required.');
		$validator = Validator::make(Input::all(),array('StudentId' => 'integer', 'StudentNumber' => 'required',), $FormMessages);//end static method make
		if($validator->fails())
		{
			return View::make('teachers.editstudentdetails')->with(array(
								'Title' => 'Edit Student ',
								'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'',
								'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():''
								))->withErrors($validator);//end with method
		}//end if statement that checks that validator did not fail
		else
		{
			try
			{
					$StudentAdminNumberArray = explode("-", Input::get('StudentNumber' ));
					$StudentAdminNumber  = (count($StudentAdminNumberArray) > 0 ) ?  trim($StudentAdminNumberArray[0]) : "";
						//var_dump($StudentAdminNumber); die();
						$ThisStudent = Students::with('StudentTermRelate','UserBelong')->where('school_admission_number', '=', $StudentAdminNumber   )->get();
							// 	  $studentid = !is_null($ThisStudent)? $ThisStudent->toArray()['studentid']:'';
							// $StudentId = Input::get('StudentId');
							// $ThisStudent = Students::with('UserBelong')
							// 						 ->where('studentid', '=', $StudentId )->get()->first();
							if(count($ThisStudent) > 0 )
							{
								$ThisStudent = $ThisStudent->first();
								$ThisStudentId =  !is_null($ThisStudent)?$ThisStudent->toArray()['studentid']:'';
								Session::put('ThisStudent',$ThisStudentId);
								return View::make('teachers.getstudentdetails')->with(
											array(
											'Title' => ' Get Student Detail',
											'myBreadCrumb' => "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Add Student Term",
											'ThisStudent' =>  !is_null($ThisStudent)?$ThisStudent->toArray():''));
							}
							else
							{
								return View::make('teachers.editstudentdetails')->with(array(
											'Title' => 'Edit Student ',
											'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():''
											));//end with method
							}

			}
			catch(Exception $e)
			{
				return View::make('teachers.editstudentdetails')->with(
								array(
								'Title' => ' Get Student Detail',
								'myBreadCrumb' => "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Add Student Term",
								'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():''));

			}//end catch clause
		}//end else that ensure validator does not fail

	}//end method getStudentDetails



	public function postAccountDetails()
	{
		$validator = Validator::make(Input::all(),
					array(
						'UserEmail' => 'required|max:50|email|unique:users,useremail',
						'UserEmail2' => 'required|same:UserEmail',
						'UserName' => 'required|max:50|min:2',
						'Password' => 'required|min:6',
						)
					);//end static method make

		//die(var_dump($validator));
		if($validator->fails())
		{
			//return var_dump($validator->messages()->toArray());
			return Redirect::route('create-account')->with(
								'AccountCreateInfo',
								'<h3> Error!</h3><br />Unable to create your account')->withErrors($validator)->withInput();//end with method
		}//end if statement that checks that validator did not fail
		else
		{
			$email = Input::get('UserEmail');
			$username = Input::get('UserName');
			$password = Input::get('Password');
			//activation code
			$code = str_random(60);// generate random strings
			/*$user = User::create(array(
					'useremail' => $email,
					'username' => $username,
					'password' =>Hash::make($password),
					'activationcode' => $code,
					'activated' => 0)); Legacy code*/
			//$password = str_random(6)
			$user =  new Users;
			$user->useremail = $email;
			$user->surname = $username;
			$user->password = Hash::make($password);
			$user->activationcode = $code;
			$user->activated = 1;

			try // try sending the mail
				{
			 		try
			 		{
			 			$user->save(); // save user details to data base
			 			//send activation email here
			 		Mail::send(
			 					'emails.outgoing.accountcreated',
			  					array(
			  					//'link'=> URL::route('account-activate',$code),
			  					'surname' => $user->surname,
			  					'email' => $user->useremail,
			  					'password' => $password),
			  					//you can continue to use users here
			  					function($message) use ($user)
			  					{
									$message->to(
					 				$user->useremail,
					  				$user->surname)->subject(
					  				'Ijaye Housing Estate Senior Grammar School - Your account created')->from(
					  				'admin@ijayehousingestatesenior.com.ng', 'Ijaye Housing Estate Senior Grammar School');
			        			}//end anonymous function
							);//end send
			 		return Redirect::route('create-account')->with(
			   						'AccountCreateInfo',
			   						'<h1> Welcome!</h1> Your account has been created
			   						and an activation email has been sent to you now.'); // send back to register page
			 		}
			 		catch(Exception $e)
			 	{
			 	 //var_dump($e); die();
			 			return Redirect::route('create-account')->with(
						'AccountCreateInfo', '<h3> Error!</h3><br /> Unable to create your account due to database problem');
			 	}//end catch block

			}//end try block

			 	catch(Exception $e)
			 	{
			 	  //var_dump($e);
			 			return Redirect::route('create-account')->with(
						'AccountCreateInfo', '<h3> Error!</h3><br /> Unable to create your account due to mail problem');
			 	}//end catch block

		}//end else that ensures that the user data is validates

	}//end postAccountDetails method

	public function ActivateUser($code)
	{
		$registereduser = Users::where(
						'activationcode', '=', $code )->where(
						'activated', '=', 0); //find and create user with this code
		if($registereduser->count())//check that such user with code exists
			{
				$founduser = $registereduser->first();
				$founduser->activationcode= '';
				$founduser->activated = 1;
				$founduser->save();
				return Redirect::route('home')->
				with('ActivatedUserInfo','<h1>  Activated!</h2> You can now sign in with your username and password');//end static method  route
			}//end if statement

		else
		{
			return Redirect::route('home')->
			with('ActivatedUserInfo','<h1> Sorry! </h1>We are unable to activate your account');//end static method
		}//end else
	}//end method  ActivateUser


}//end class
