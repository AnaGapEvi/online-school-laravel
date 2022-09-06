<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StudentAssignmentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserCourseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::put('/forgot', [UserController::class, 'forgotPassword']);
Route::put('/reset', [UserController::class, 'resetPassword']);
Route::get('/news', [NewsController::class, 'index']);
Route::get('/subjects', [SubjectController::class, 'index']);
Route::get('/courses', [CourseController::class, 'index']);


Route::middleware('auth:api')->group(function () {
    Route::post('/update-profile', [UserController::class, 'update']);



    Route::get('me', [UserController::class, 'me']);
    Route::get('me-data', [UserController::class, 'meData']);
    Route::get('/students-course', [UserController::class, 'studentTeacherCourse']);
    Route::get('students-count', [UserController::class, 'studentsCount']);
    Route::get('/get-user-information', [UserController::class, 'getUserInformation']);
    Route::get('registered-course', [UserCourseController::class, 'registeredCourse']);
    Route::get('unregistered-course', [UserCourseController::class, 'unregisteredCourse']);
    Route::get('/announcement-count', [AnnouncementController::class, 'announcementCount']);
    Route::get('/logout', [UserController::class, 'logout']);
    Route::post('/new-news', [NewsController::class, 'createNews']);
    Route::post('/new-notification', [NotificationController::class, 'store']);

    Route::get('/news/{id}', [NewsController::class, 'getNews']);
    Route::get('/course/{id}', [CourseController::class, 'getCourse']);
    Route::get('/teacher/{id}', [UserController::class, 'getTeacher']);
    Route::get('/get-user-course/{id}', [CourseController::class, 'getUserCourse']);
    Route::get('/announcement/{id}', [AnnouncementController::class, 'getAnnouncement']);
    Route::get('/all-teachers', [UserController::class, 'getTeachers']);
    Route::get('/all-users', [UserController::class, 'getAllUsers']);
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/announcement', [AnnouncementController::class, 'index']);

    Route::put('/edit-news/{id}', [NewsController::class, 'editNews']);
    Route::put('/edit-course/{id}', [CourseController::class, 'editCourse']);
    Route::put('/edit-teacher/{id}', [UserController::class, 'editTeacher']);
    Route::put('/edit-announcement/{id}', [AnnouncementController::class, 'editAnnouncement']);
    Route::put('/edit-notification/{id}', [NotificationController::class, 'editNotification']);
    Route::put('/verify/{id}', [StudentAssignmentController::class, 'verify']);

    Route::put('/edit-assignment/{id}', [AssignmentController::class, 'editAssignment']);
    Route::get('/assignment-get/{id}', [AssignmentController::class, 'getAssignment']);
    Route::get('/get-notification/{id}', [NotificationController::class, 'getNotification']);
    Route::get('/get-teacher-notifications', [NotificationController::class, 'getTeacherNotification']);
    Route::get('/notifications-course', [NotificationController::class, 'courseNotifications']);


    Route::get('assignment-count', [AssignmentController::class, 'assignmentCount']);
    Route::get('assignment-teacher', [AssignmentController::class, 'assignmentTeacher']);
    Route::get('user-tasks-list', [AssignmentController::class, 'userTasksList']);
    Route::get('/assignments', [AssignmentController::class, 'assignments']);
    Route::get('/assignment-course', [AssignmentController::class, 'assignmentCourse']);
    Route::get('/get-user-task', [UserController::class, 'userCourse']);
    Route::get('/course-teacher', [LogsController::class, 'courseTeacher']);
    Route::get('/task/{id}', [AssignmentController::class, 'getTAsk']);
    Route::get('/not', [NotificationController::class, 'getTeacherNotification']);

    Route::delete('/delete-news/{id}', [NewsController::class, 'destroy']);
    Route::delete('/delete-announcement/{id}', [AnnouncementController::class, 'destroy']);
    Route::delete('/delete-subject/{id}', [SubjectController::class, 'destroy']);
    Route::delete('/delete-course/{id}', [CourseController::class, 'destroy']);
    Route::delete('/delete-news/{id}', [NewsController::class, 'destroy']);
    Route::delete('/delete-assignment/{id}', [AssignmentController::class, 'destroy']);
    Route::delete('/delete-notification/{id}', [NotificationController::class, 'destroy']);

    Route::post('/new-announcement', [AnnouncementController::class, 'store']);
    Route::post('/new-subject', [SubjectController::class, 'store']);
    Route::post('/new-course', [CourseController::class, 'store']);
    Route::post('/new-assignment', [AssignmentController::class, 'store']);
    Route::post('/register-course', [UserCourseController::class, 'registerCourse']);
    Route::post('/reports', [AssignmentController::class, 'reports']);
    Route::post('/answer-task', [StudentAssignmentController::class, 'answer']);
    Route::post('/log', [LogsController::class, 'logs']);

    Route::get('/name-livesearch', [AssignmentController::class, 'getTasksByTeacherName']);
    Route::get('/number-livesearch', [AssignmentController::class, 'getTasksByNumber']);
    Route::get('/subject-livesearch', [AssignmentController::class, 'getTasksBySubject']);


    Route::get('/checked-student-assignments', [StudentAssignmentController::class, 'checkedStudentAssignments']);
    Route::get('/checked-student-assignments-user', [StudentAssignmentController::class, 'allUnverifiedAnswersUser']);
    Route::get('all-checked-answers', [StudentAssignmentController::class, 'allAnswers']);
    Route::get('/all-unchecked-answers', [StudentAssignmentController::class, 'allUnverifiedAnswers']);
    Route::get('/get-logs', [LogsController::class, 'getLogs']);
    Route::get('/get-logs-user-name', [LogsController::class, 'searchLogs']);
    Route::get('/get-assignment-task', [AssignmentController::class, 'assignmentTask']);

});
