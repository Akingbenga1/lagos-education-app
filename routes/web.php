<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//--------------------------------------Authentication Routes---------------------------------------

Route::match(['get', 'post'], '/login', ['uses' => 'AuthenticationController@processLogin', 'as' => 'login']);

Route::match(['get'], '/account/signout.html', ['uses' => 'AuthenticationController@userSignOut', 'as' => 'signout']);

Route::match(['get', 'post'], '/userprofile.html', ['uses' => 'AccountController@getProfileData', 'as' => 'user-profile']);


Route::match(['get', 'post'], '/register', ['uses' => 'AccountController@register', 'as' => 'register']);




//--------------------------------------Admin Related Routes---------------------------------------

Route::match(['get', 'post'], '/admin/TeachersHome.html', ['uses' => 'AccountController@showTeachersHomePage', 'as' => 'teachers-home-page']);

Route::match(['get', 'post'], '/admin/getstudentdetails.html', ['uses' => 'AccountController@getStudentDetailsSession', 'as' => 'get-student-details']);


Route::match(['get', 'post'], 'admin/editstudentdetails.html', ['uses' => 'AccountController@editStudentDetailsForm', 'as' => 'edit-student-details-form']);


Route::match(['get', 'post'], '/getthisstudentclass', ['uses' => 'ScoreController@getThisStudentClass', 'as' => 'get-this-student-class']);


Route::match(['get', 'post'], '/poststudentscoredetail', ['uses' => 'ScoreController@postThisStudentDetails', 'as' => 'post-this-student-details']);



Route::match(['get', 'post'], '/saveeachstudentscore', ['uses' => 'ScoreController@saveEachStudentScores', 'as' => 'save-each-student-score']);


Route::match(['get', 'post'], '/excel_upload', ['uses' => 'ExcelUploadController@excel_upload', 'as' => 'excel_uploader']);

//--------------------------------------Admin Related Routes---------------------------------------


//--------------------------------------Schools Related Routes---------------------------------------

Route::match(['get', 'post'], '/schools', ['uses' => 'SchoolController@index', 'as' => 'all-schools']);

Route::match(['get', 'post'], '/schools_options', ['uses' => 'SchoolController@schools_options', 'as' => 'schools_options']);


Route::match(['get', 'post'], '/schools_excel_upload', ['uses' => 'SchoolController@schools_excel_upload', 'as' => 'schools_excel_upload']);

Route::match(['get', 'post'], '/schools_save_scores', ['uses' => 'SchoolController@schools_save_scores', 'as' => 'schools_save_scores']);

Route::match(['get', 'post'], '/schools_registration_save_students', ['uses' => 'SchoolController@schools_registration_save_students', 'as' => 'schools_registration_save_students']);


Route::match(['get', 'post'], '/schools_registration', ['uses' => 'SchoolController@schools_registration', 'as' => 'schools_registration']);


Route::match(['get', 'post'], '/schools_registration_excel_upload/{school_id?}', ['uses' => 'SchoolController@schools_registration_excel_upload', 'as' => 'schools_registration_excel_upload']);

Route::match(['get', 'post'], '/download_excel/{session_name}', ['uses' => 'DownloadManagerController@downloadExcelOperation', 'as' => 'downloadExcelOperation']);



Route::match(['get', 'post'], '/schools_reports', ['uses' => 'ScoreReportController@schools_reports', 'as' => 'schools_reports']);

Route::match(['get', 'post'], '/school_report_download', ['uses' => 'ScoreReportController@school_report_download', 'as' => 'school_report_download']);

Route::match(['get', 'post'], '/student_score_download/{student_registration_id}/class_subdivision/{class_subdivision_loop_key}', ['uses' => 'ScoreReportController@student_score_download_pdf', 'as' => 'student_score_download_pdf']);

Route::match(['get', 'post'], '/download_all_class_subdivision/class_subdivision/{class_subdivision_loop_key}', ['uses' => 'ScoreReportController@download_all_class_subdivision', 'as' => 'download_all_class_subdivision']);



//--------------------------------------Schools Related Routes---------------------------------------









Route::get('/oldwelcome', function ()
{
    return view('welcome');
});

Route::match(['get', 'post'], '/', ['uses' => 'HomeController@home', 'as' => 'home']);
//Route::match(['get', 'post'], '/home', ['uses' => 'HomeController@home', 'as' => 'home']);






//--------------------------------------Questions and Answer Routes---------------------------------------
Route::match(['get', 'post'], '/StudentsQuestionsPage.html', ['uses' => 'ExamQuestionsController@getStudentQuestionPage', 'as' => 'student-question-page']);

Route::match(['get', 'post'], '/GetQuestions.html', ['uses' => 'ExamQuestionsController@getQuestionsFromDatabase', 'as' => 'get-questions']);


Route::match(['get', 'post'], '/SaveThisScoreQuestion', ['uses' => 'ExamQuestionsController@storeQuestionResultToDatabase', 'as' => 'save-this-score']);


Route::match(['get', 'post'], '/SubmitQuestionsPage', ['uses' => 'ExamQuestionsController@sendQuestionToDatabase', 'as' => 'submit-question-to-database']);


//--------------------------------------Student Related Routes---------------------------------------

Route::match(['get', 'post'], '/StudentPage.html', ['uses' => 'PageController@studentPage', 'as' => 'student-page']);


//--------------------------------------Utility Routes---------------------------------------

Route::match(['get', 'post'], '/StudentPDFReportPage', ['uses' => 'DownloadController@downloadReportPDF', 'as' => 'get-report-pdf']);

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

//homepage
Route::get('/', array(
    'as' => 'home',
    'uses' => 'HomeController@home'));

//Excel Upload Page
// Route::get('/ExcelUploadPage.html', array(
//     'as' => 'excel-upload-page',
//     'uses' => 'ExcelScoreController@index'));

//Excel Upload Page
Route::get('/ExcelUploadView.html', array(
    'as' => 'excel-upload-view',
    'uses' => 'ExcelScoreController@processExcelSheet'));


//Upload Excel to Server
Route::post('/UploadExcelToServer', array(
    'as' => 'excel-server-upload',
    'uses' => 'ExcelScoreController@uploadExcelToServer'));



//Upload Excel to Server
Route::post('/FixUche', array(
    'as' => 'excel-server-upload',
    'uses' => 'ExcelScoreController@uploadExcelToServer'));



//Send Student Score to Database
Route::post('/SendExcelScoreToDatabase.html', array(
    'as' => 'send-excel-score-to-database',
    'uses' => 'ExcelScoreController@sendScoresToDatabase'));


//Preview the Excel before viewing the entire excel sheet
Route::get('/PreviewExcelUpload.html', array(
    'as' => 'preview-excel-upload',
    'uses' => 'ExcelScoreController@previewExcelUpload'));


//Sort Subject in Excel Header
Route::post('/PreviewExcelUpload', array(
    'as' => 'sort-excel-header',
    'uses' => 'ExcelScoreController@sortExcelHeader'));


//homepage
Route::get('/Infograph.html', array(
    'as' => 'inforgrah',
    'uses' => 'HomeController@inforGraphs'));

//View Students Page
Route::get('/StudentReportPage.html', array(
    'as' => 'student-report-page',
    'uses'  => 'PageController@getStudentReportPage'
));//end route for student page

//View for showing the list of student in a class in the public space
Route::any('/listofstudents.html', array(
    'as' => 'public-student-term-list-page',
    'uses'  => 'StudentListController@getPublicStudentClass'
));//end route for showing administartive page

//View for showing classes
Route::get('/listofclasses.html', array(
    'as' => 'public-class-list-page',
    'uses'  => 'StudentListController@showPublicClasses'
));//end route for showing administartive page


Route::get('/StudentPDFListPage', array(
    'as'=> 'get-student-list-pdf',
    'uses'=> 'DownloadController@downloadStudentListPDF'
)); //end route for student report page

Route::get('/PrivacyPolicy.html', array(
    'as'=> 'get-privacy-policy-page',
    'uses'=> 'PageController@getPrivacyPolicyPage'
)); //end route for student report page

Route::get('/TermsofUse.html', array(
    'as'=> 'get-term-of-use-page',
    'uses'=> 'PageController@getTermOFUsePage'
)); //end route for student report page

Route::get('oauth/users', array(
    'as'=> 'good-stuff',
    'prefix' => 'api/v2',
    'before' => 'oauth',
    'uses'=> 'DownloadController@goodStuff'
)); //end route for student report page

Route::post('oauth/testme', array(
    'as'=> 'good-stuff',
    'prefix' => 'api/v2',
    //'before' => 'oauth',
    'uses'=> 'DownloadController@getToken'
)); //end route for student report page

//View for Administrative Page/{Year}/{Class}/{SubClass}
				Route::get('/listofclass.html', array(
                    'as' => 'class-list-page',
                    //'before' => 'generalteacher',
                    'uses'  => 'StudentListController@showClasses'
                ));//end route for showing administartive page

				//View for Administrative Page/{Year}/{Class}/{SubClass}
				Route::post('/listofclass', array(
                    'as' => 'class-list-ajax',
                    'before' => 'csrf',
                    'uses'  => 'StudentListController@showClasses'
                ));//end route for showing administartive page

				//View for Administrative Page/{Year}/{Class}/{SubClass}
				Route::get('/SubjectPage.html', array(
                    'as' => 'subject-page',
                    //'before' => 'csrf|generalteacher',
                    'uses'  => 'PageController@getSubjectPage'
                ));//end route for showing administartive page






/**************************  authenticated group ********************************/


Route::group(array('before' => 'auth'), function()
{


    //View Student DashBoard
    Route::get('/StudentDashboard', array(
        'as' => 'student-dashboard',
        'uses'  => 'AccountController@getStudentDashBoard'
    ));//end route for Favours Educational





    //Students Questions
    Route::post('/SubmitQuestionsPage', array(
        'as' => 'submit-question-to-database',
        'before' => 'generalteacher',
        'uses' => 'ExamQuestionsController@sendQuestionToDatabase'));

    Route::post('/EditThisQuestion', array(
        'as' => 'edit-this-question',
        'before' => 'generalteacher',
        'uses' => 'ExamQuestionsController@editQuestionInDatabase'));

    Route::get('/StudentQuestionsInputPage.html', array(
        'as' => 'student-question-input-page',
        'before' => 'generalteacher',
        'uses' => 'ExamQuestionsController@getStudentQuestionInput'));

    Route::get('/StudentQuestionEditPage.html/{id}', array(
        'as' => 'student-question-edit-page',
        'before' => 'generalteacher',
        'uses' => 'ExamQuestionsController@getStudentQuestionEditPage'));

//Students Questions






    /*******************************   Admin People **************************************************************/

    //View for Administrative Page
    Route::get('/admin', array(
        'as' => 'admin-home',
        'before' => 'admin',
        'uses'  => 'PageController@loadAdminHome'
    ));//end route for showing administartive page

    //View for Teacher Signature upload
    Route::get('admin/teachersignature.html', array(
        'as' => 'upload-teachers-signature',
        'before' => 'admin',
        'uses'  => 'PageController@teacherSignature'
    ));//end route for showing Teacher Signature upload

    //View for Administrative Page
    Route::get('/inputmasterscore.html', array(
        'as' => 'large-input-ajax',
        'before' => 'admin',
        'uses'  => 'PageController@loadAdminHome'
    ));//end route for showing administartive page

    //View for Signature list
    Route::get('/admin/signaturelist.html', array(
        'as' => 'signauture-list',
        'before' => 'admin',
        'uses'  => 'PageController@showAllOfficialSignature'
    ));//end route for showing list of signatures


    //View staff page
    Route::get('/admin/StaffPage.html', array(
        'as' => 'staff-page',
        'before' => 'admin',
        'uses'  => 'StaffController@getStaffPage'
    ));//end route for student page

    //View  staff edit page
    Route::get('admin/StaffEditPage.html/{StaffId}', array(
        'as' => 'staff-edit-page',
        'before' => 'admin',
        'uses'  => 'StaffController@getStaffEditPage'
    ));//end route for student page


    //View for A
    Route::get('/getstudentscoredetail.html', array(
        'as' => 'get-this-student-details',
        'before' => 'generalteacher',
        'uses'  => 'ScoreController@getThisStudentScoreForm'
    ));//end route for

    //View for showing the list of student in a class
    Route::get('/listofstudent.html', array(
        'as' => 'student-term-list-page',
        'before' => 'generalteacher',
        'uses'  => 'StudentListController@getStudentClass'
    ));//end route for showing administartive page


    //View for showing the list of student in a class
    Route::post('/classreportprogress.html', array(
        'as' => 'class-report-progress-page',
        'before' => 'generalteacher',
        'uses'  => 'ScoreController@getClassScoreProgress'
    ));//end route for showing administartive page




    //View for student score input
    Route::get('/admin/inputpage.html', array(
        'as' => 'score-input-form',
        'before' => 'generalteacher',
        'uses'  => 'PageController@getScoreInputForm'
    ));//end route for student score input

    //View for Teachers Day
    Route::get('teachers/teachersday.html', array(
        'as' => 'teachersday',
        'before' => 'generalteacher',
        'uses'  => 'PageController@teachersDay'
    ));//end route for student score input

    //View for student score input
    Route::get('/admin/addstudentterm.html', array(
        'as' => 'add-student-term',
        'before' => 'generalteacher',
        'uses'  => 'PageController@getStudentTermForm'
    ));//end route for student score input

    //View for getting students from any term
    Route::get('/admin/studentlist.html', array(
        'as' => 'get-students-list',
        'before' => 'generalteacher',
        'uses'  => 'PageController@getStudentList'
    ));//end route for getting students from any term



    //View for student registration page
    Route::get('/admin/studentregistration.html', array(
        'as' => 'student-registration-form',
        'before' => 'generalteacher',
        'uses'  => 'AccountController@getStudentRegForm'
    ));//end route for showing student registration form





    //View for showing roles addition form
    Route::get('/admin/roles.html', array(
        'as' => 'admin-roles',
        'before' => 'admin',
        'uses'  => 'AccountController@loadRolesForm'
    ));//end route for owing roles addition form

    //View for showing roles addition form
    Route::get('/admin/DetachRole/', array(
        'as' => 'admin-detach-roles',
        'before' => 'admin',
        'uses'  => 'AccountController@detachRoles'
    ));//end route for owing roles addition form

    //View for showing roles addition form
    Route::post('/admin/EditRole/', array(
        'as' => 'admin-edit-roles',
        'before' => 'admin',
        'uses'  => 'AccountController@editRoles'
    ));//end route for owing roles addition form

    /*
    Route::get('admin/SoftDeletePage', array(
    'as' => 'soft-delete-page',
    'before' => 'admin',
    'uses'  => 'ScoreController@getSoftDeletePage'
    ));//end route for student page
    */



    /***************************************** apply csrf on the post guys  ********************************************************/
    // CSRF protection group
    Route::group( array('before' => 'csrf'), function(){

        //Post to register student details
        Route::post('/admin/studentregistration', array(
            'as' => 'register-student-details',
            'before' => 'generalteacher',
            'uses'  => 'AccountController@registerStudentDetails'
        ));//end route to register student details

        //Add staff page
        Route::post('/admin/addstaffpage', array(
            'as' => 'add-staff-page',
            'before' => 'admin',
            'uses'  => 'StaffController@addStaffToDatabase'
        ));//end route for student page

        Route::post('/PromoteThisStudent/', array(
            'as' => 'promote-this-student',
            'before' => 'generalteacher',
            'uses'  => 'ScoreController@promoteThisStudent'
        ));//end route for



        //View for A
        Route::post('/ChangeThisStudentClass', array(
            'as' => 'change-student-class',
            'before' => 'generalteacher',
            'uses'  => 'ScoreController@changeThisStudentClass'
        ));//end route for

        //View for A

        //View for A
        Route::post('/GraduateThisStudent', array(
            'as' => 'graduate-this-student',
            'before' => 'generalteacher',
            'uses'  => 'ScoreController@graduateThisStudent'
        ));//end route for


        //View  staff edit page
        Route::post('admin/EditThisStaff', array(
            'as' => 'edit-this-staff',
            'before' => 'admin',
            'uses'  => 'StaffController@editStaffInfo'
        ));//end route for student page

        /*
        Route::post('admin/SoftDeletePage', array(
        'as' => 'soft-delete-page',
        'before' => 'admin',
        'uses'  => 'ScoreController@getSoftDeletePage'
        ));//end route for student page
        */

        //View for showing the list of student in a class
        Route::post('/classreportprogress.html', array(
            'as' => 'class-report-progress-page',
            'before' => 'generalteacher',
            'uses'  => 'ScoreController@getClassScoreProgress'
        ));//end route for showing administartive page

        //Route for Updating teacher signature upload
        Route::post('admin/updateteachersignature', array(
            'as' => 'update-teachers-signature',
            'before' => 'admin',
            'uses'  => 'PageController@updateTeacherSignature'
        ));//end Route for Updating teacher signature upload

        //Route for Deleting teacher signature from database
        Route::post('admin/deleteteachersignature', array(
            'as' => 'delete-teachers-signature',
            'before' => 'admin',
            'uses'  => 'PageController@deleteTeacherSignature'
        ));//end Route for Deleting teacher signature from database

        //Route for Posting Teacher Signature upload
        Route::post('admin/postteachersignature', array(
            'as' => 'post-teachers-signature',
            'before' => 'admin',
            'uses'  => 'PageController@postTeacherSignature'
        ));//end route Posting Teacher Signature upload


        //View for A
        Route::post('/saveofficialcomments', array(
            'as' => 'save-official-comments',
            'before' => 'admin',
            'uses'  => 'ScoreController@saveOfficialComments'
        ));//end route for

        //Post User Profile
        Route::post('/postprofile', array(
            'as' => 'post-user-profile',
            'uses'  => 'AccountController@postProfileData',
        ));//end route for student page

        //Post User Profile
        Route::post('/postthoughts', array(
            'as' => 'post-your-thoughts',
            'uses'  => 'AccountController@postYourThoughts',
        ));//end route for student page

        //View for A
        Route::post('/saveattendancerecord', array(
            'as' => 'save-student-attendance',
            'before' => 'generalteacher',
            'uses'  => 'ScoreController@saveStudentAttendance'
        ));//end route for

        //View for A
        Route::post('/savetermdurationrecord', array(
            'as' => 'save-student-termduration',
            'before' => 'generalteacher',
            'uses'  => 'ScoreController@saveTermDuration'
        ));//end route for

        //View for A
        Route::post('/savestudentscoredetail', array(
            'as' => 'save-this-student-score',
            'before' => 'generalteacher',
            'uses'  => 'ScoreController@saveStudentScores'
        ));//end route for

        //View for A
        Route::post('/saveallstudentscoredetail', array(
            'as' => 'save-all-this-student-score',
            'before' => 'generalteacher',
            'uses'  => 'ScoreController@saveAllStudentScores'
        ));//end route for



        //Route for deleting a score via ajax
        Route::post('/deletestudentscore', array(
            'as' => 'delete-this-student-score',
            'before' => 'generalteacher',
            'uses'  => 'ScoreController@deleteStudentScores'
        ));//end route for

        //Post to edit student details
        Route::post('/admin/editstudentdetails', array(
            'as' => 'edit-student-details',
            'before' => 'generalteacher',
            'uses'  => 'AccountController@editStudentDetails'
        ));//end route to register student details


        //Post for student score input
        Route::post('/admin/inputscore', array(
            'as' => 'input-student-score',
            'before' => 'generalteacher',
            'uses'  => 'PageController@inputStudentScores'
        ));//end route to input student score

        //View for editting student details
        Route::post('/admin/getstudentdetails.html', array(
            'as' => 'get-student-details',
            'before' => 'generalteacher',
            'uses'  => 'AccountController@getStudentDetails'
        ));//end route for showing student registration form

        //View for student score editting
        Route::post('/admin/editpage.html', array(
            'as' => 'edit-student-score',
            'before' => 'generalteacher',
            'uses'  => 'PageController@getScoreEditForm'
        ));//end route to edit the score of the subject for a student

        //Post for student score editting
        Route::post('/admin/editthisscore/', array(
            'as' => 'edit-this-score',
            'before' => 'generalteacher',
            'uses'  => 'PageController@editThisScore'
        ));//end route to edit the score of the subject for a student

        //Post for student score editting
        Route::post('/admin/addstudentterm/', array(
            'as' => 'post-student-term',
            'before' => 'generalteacher',
            'uses'  => 'PageController@postStudentTerm'
        ));//end route to edit the score of the subject for a student



        //Route for posting roles to database
        Route::post('/admin/createroles', array(
            'as' => 'admin-create-roles',
            'uses'  => 'AccountController@createRoles'
        )); //end route for posting roles to database

        //Route for posting permissions to database
        Route::post('/admin/createpermission', array(
            'as' => 'admin-create-permissions',
            'uses'  => 'AccountController@createPermissions'
        ));//end route for posting permission to database

        //Route for attaching permissions to roles
        Route::post('/admin/attachpermission', array(
            'as' => 'admin-attach-permissions',
            'uses'  => 'AccountController@attachPermissionsToRole'
        )); //end route for attaching permission to roles

        //Route for attaching roles to users
        Route::post('/admin/attachroles', array(
            'as' => 'admin-attach-roles',
            'uses'  => 'AccountController@attachRolesToUser'
        ));//end route for attaching roles to users

    });//end csfr closure and


});//end closure for group auth




/********************  unauthenticated group ******************************************************************/

		Route::group(  array('before' => 'guest'),function() {

            // CSRF protection group
            Route::group( array('before' => 'csrf'), function(){

                //Route for creating new user in the Users table
                Route::post('/account/create', array(
                    'as'=> 'post-new-user',
                    'uses'=> 'AccountController@postAccountDetails'
                )); //end route for creating new users




                //For Sending Email to Favours Group
                Route::post('/mail/SendMail', array(
                    'as' => 'email_favours',
                    'uses' => 'EmailController@SendToFavours'
                ));//end route for sending email to favours group

            });//end function for csrf


            //end inner group


            // create sign-in form through GET
            Route::get('account/login.html', array(
                'as'=> 'login-form',
                'uses'=> 'AccountController@getLoginForm'
            ));//end static method

            /******************************** PASSWORD REMINDER GUYS   ************************************************/

            // get password reminder form
            Route::get('/account/password-reminder', array(
                'as'=> 'password-reminder',
                'uses'=> 'RemindersController@getRemind'
            ));//end route for getting passsword reminder

            // post password reminder for database legwork
            Route::post('/account/post-password-reminder', array(
                'as'=> 'post-password-reminder',
                'uses'=> 'RemindersController@postRemind'
            ));//end route for posting password reminder

            // showing passowrd reset form
            Route::get('password/reset/{token}', array(
                'as'=> 'password-reset',
                'uses'=> 'RemindersController@getReset'
            ));//end route for showing passowrd reset form

            // post password reset details to database modification
            Route::post('/account/post-password-reset', array(
                'as'=> 'post-password-reset',
                'uses'=> 'RemindersController@postReset'
            ));// end route post password reset details to database modification

            // get password reset form
            Route::get('account/reset-confirmed', array(
                'as'=> 'reset-confirmed',
                'uses'=> 'RemindersController@getResetConfirmation'
            ));


            /******************************** PASSWORD REMINDER GUYS   ************************************************/
            // create account (GET)
            Route::get('account/newaccount.html', array(
                'as'=> 'create-account',
                'uses'=> 'AccountController@getCreate'
            ));//end static method

            // create account (GET)
            Route::get('account/changepassword', array(
                'as'=> 'change-password',
                'uses'=> 'RemindersController@changePassword'
            ));//end static method

        });//end group outer






/********************************* CSRF FOR NEUTRAL GUYS ********************************************/

		Route::group( array('before' => 'csrf'), function(){




        });// end csrf group of neutral guys//end closure for csrf

		Route::post('/StudentReportPage.html',
            array(
                'as'=> 'post-student-report',
                'uses'=> 'PageController@postStudentReportPage',
            )); //end route for student report page

		Route::get('/GetStudentReport.html',
            array(
                'as'=> 'get-student-report',
                'uses'=> 'PageController@getStudentReport',
            )); //end route for student report page




/**************************************** GET REQUEST FOR NEUTRAL GUYS *************************************************/

		// create activation processing page (GET)
		Route::get('/account/activate/{code}', array(
            'as'=> 'account-activate',
            'uses'=> 'AccountController@ActivateUser'
        ));// end route to create activation processing page (GET)


//Auth::routes();
//
Route::get('/home', 'HomeController@index')->name('home');


/********************************************* USER MANAGEMENT ****************************************************/
Route::post('/detachuserrole', 'UserManagementController@detachUserRole')->name('detachUserRole');
Route::post('/detachuserpermission', 'UserManagementController@detachUserPermission')->name('detachUserPermission');
Route::match(['get', 'post'], '/searchuser', ['uses' => 'UserManagementController@searchUser']);
Route::match(['get', 'post'], '/searcheduser', ['uses' => 'UserManagementController@searchedUser']);
Route::match(['get', 'post'], '/attachrole', ['uses' => 'RoleController@attachRole']);

Route::resource('/users', 'UserManagementController');
Route::resource('/roles', 'RoleController');
Route::resource('/permissions', 'PermissionController');