<?php

use App\Events\CalculateReport;
use App\Exports\CompetencyExport;
use App\Http\Controllers\ExportExcel;
use App\Http\Controllers\Leger;
use App\Http\Controllers\Report;
use App\Http\Controllers\ReportProject;
use App\Http\Controllers\StudentCompetencyExcel;
use App\Imports\AttendanceImport;
use App\Imports\CompetencyImport;
use App\Imports\ExcelUtils;
use App\Imports\ExtracurricularImport;
use App\Imports\GradeImport;
use App\Imports\StudentCompetencyImport;
use App\Imports\StudentCompetencySheetImport;
use App\Imports\StudentExtracurricularImport;
use App\Imports\StudentGradeImport;
use App\Imports\StudentImport;
use App\Imports\SubjectImport;
use App\Imports\TeacherExtracurricularImport;
use App\Imports\TeacherGradeImport;
use App\Imports\TeacherGradeSheetImport;
use App\Imports\TeacherImport;
use App\Imports\TeacherSubjectImport;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\StudentCompetency;
use App\Models\Teacher;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;
use Spatie\Valuestore\Valuestore;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return redirect(route('filament.admin.auth.login'));
})->name('login');

Route::get('put', function(){
    $valueStore = Valuestore::make(storage_path('app/settings.json'));
    $valueStore->put('conjuction_a', 'sudah sangat menguasai dalam materi:');
    $valueStore->put('conjuction_b', 'sudah menguasai dalam materi:');
    $valueStore->put('conjuction_c', 'cukup menguasai materi:');
    $valueStore->put('conjuction_d', 'kurang menguasi materi:');

    // $valueStore->put('predicate', [
    //     [
    //         'predicate' => 'A',
    //         'desc' => 'Amat baik',
    //         'upper_limit' => '100',
    //         'lower_limit' => '90',
    //     ],
    //     [
    //         'predicate' => 'B',
    //         'desc' => 'Baik',
    //         'upper_limit' => '90',
    //         'lower_limit' => '80',
    //     ],
    //     [
    //         'predicate' => 'C',
    //         'desc' => 'Cukup',
    //         'upper_limit' => '80',
    //         'lower_limit' => '70',
    //     ],
    //     [
    //         'predicate' => 'D',
    //         'desc' => 'Sedang',
    //         'upper_limit' => '70',
    //         'lower_limit' => '60',
    //     ],
    //     [
    //         'predicate' => 'E',
    //         'desc' => 'Kurang',
    //         'upper_limit' => '60',
    //         'lower_limit' => '0',
    //     ],
    // ]);
});

Route::get('get', function(){
    $valueStore = Valuestore::make(storage_path('app/settings.json'));
    dd($valueStore->get('predicate'));
});

Route::controller(Report::class)->group(function(){
    Route::get('cover/{id}', 'getDataCover')->name('cover');
    Route::get('cover-student/{id}', 'getData')->name('cover.student');
    Route::get('report/{id}', 'calculateReport')->name('report');
    Route::get('project/{id}', 'project')->name('report.project');
    Route::get('half-semester/{id}', 'calculateHalfSemester')->name('report.halfsemester');
});

Route::controller(Leger::class)->group(function(){
    Route::get('leger/subject/{id}', 'subject')->name('leger.subject');
    Route::get('leger/attendance', 'attendance')->name('leger.attendance');
    Route::get('leger/grade/{id}', 'grade')->name('leger.grade');
    Route::get('leger/project/{id}', 'project')->name('leger.project');
});

// Route::controller(ReportProject::class)->group(function(){
//     Route::get('report/project/{id}', 'calculateReport')->name('report.project');
// });

// Route::controller(StudentCompetencyExcel::class)->group(function(){
//     Route::get('getdata/{teacher_subject_id}', 'getData')->name('studentCompetencyExcel.getData');
// });

Route::get('import', function(){
    Excel::import(new TeacherImport, storage_path('/app/public/uploads/guru.xlsx'));
    Excel::import(new StudentImport, storage_path('/app/public/uploads/siswa.xlsx'));
    Excel::import(new SubjectImport, storage_path('/app/public/uploads/mapel.xlsx'));
    Excel::import(new GradeImport, storage_path('/app/public/uploads/kelas.xlsx'));
    Excel::import(new ExtracurricularImport, storage_path('/app/public/uploads/ekstrakurikuler.xlsx'));
    
    // set Academic Year
    AcademicYear::firstOrCreate([
        'year' => '2023/2024',
    ],
    [
        'year' => '2023/2024',
        'semester' => 'ganjil',
        'active' => true,
        'teacher_id' => 9,
        'date_report' => '2023-12-23',
    ]);
    
    // Excel::import(new StudentGradeImport, storage_path('/app/public/uploads/studentGrade.xlsx'));
    // Excel::import(new TeacherSubjectImport, storage_path('/app/public/uploads/teacherSubject.xlsx'));
    // Excel::import(new TeacherGradeImport, storage_path('/app/public/uploads/teacherGrade.xlsx'));
    // Excel::import(new TeacherGradeImport, storage_path('/app/public/uploads/teacherGrade.xlsx'));
    // Excel::import(new TeacherExtracurricularImport, storage_path('/app/public/uploads/teacherExtracurricular.xlsx'));
    // Excel::import(new StudentExtracurricularImport, storage_path('/app/public/uploads/studentExtracurricular.xlsx'));

    return 'success';
});

Route::controller(ExportExcel::class)->group(function(){
    Route::get('export/student-grade', 'studentGrade')->name('export.studentGrade');
    Route::get('export/teacher-extracurricular', 'teacherExtracurricular')->name('export.teacherExtracurricular');
    Route::get('export/student-extracurricular', 'studentExtracurricular')->name('export.studentExtracurricular');
    Route::get('export/teacher-subject', 'teacherSubject')->name('export.teacherSubject');
    Route::get('export/teacher-grade', 'teacherGrade')->name('export.teacherGrade');
    // Route::get('export/student-competency/{teacher_subject_id}', 'studentCompetency')->name('export.studentCompetency');
    Route::get('export/student-competency-sheet/{teacher_subject_id}', 'studentCompetencySheet')->name('export.studentCompetencySheet');
    Route::get('export/exam/{teacher_subject_id}', 'exam')->name('export.exam');
    Route::get('export/attendance/{grade_id}', 'attendance')->name('export.attendance');
    Route::get('export/competency/{teacher_subject_id}', 'competency')->name('export.competency');
});