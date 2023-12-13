<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Exam;
use App\Models\Extracurricular;
use App\Models\Grade;
use App\Models\Student;
use App\Models\StudentExtracurricular;
use App\Models\StudentGrade;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherExtracurricular;
use App\Models\TeacherGrade;
use App\Models\TeacherSubject;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Protection;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportExcel extends Controller
{
    public function studentGrade()
    {
        $spreadsheet = new Spreadsheet();

        $rowStudentGrade = 2;
        $rowStudent = 2;
        $rowGrade = 2;
        
        // Membuat lembar pertama
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('StudentGrade');
        $sheet1->setCellValue('A1', 'grade_id');
        $sheet1->setCellValue('B1', 'student_id');
        
        // input data student grade
        $studentGrades = StudentGrade::all();
        foreach ($studentGrades as $studentGrade) {
            $sheet1->setCellValue('A' . $rowStudentGrade, $studentGrade->grade_id);
            $sheet1->setCellValue('B' . $rowStudentGrade, $studentGrade->student_id);
            $rowStudentGrade++;
        }

        // Membuat lembar kedua
        $spreadsheet->createSheet();
        $sheet2 = $spreadsheet->getSheet(1); // Indeks dimulai dari 0
        $sheet2->setTitle('Student');
        $sheet2->setCellValue('A1', 'student_id');
        $sheet2->setCellValue('B1', 'nama_lengkap');
        $sheet2->setCellValue('C1', 'gender');
        $sheet2->setCellValue('d1', 'nis');
        $sheet2->setCellValue('e1', 'nisn');

        // input data students
        $students = Student::all();
        foreach ($students as $student) {
            $sheet2->setCellValue('A'. $rowStudent, $student->id);
            $sheet2->setCellValue('B'. $rowStudent, $student->name);
            $sheet2->setCellValue('C'. $rowStudent, $student->gender);
            $sheet2->setCellValue('D'. $rowStudent, $student->nis);
            $sheet2->setCellValue('E'. $rowStudent, $student->nisn);
            $rowStudent++;
        }

        // Membuat lembar ketiga
        $spreadsheet->createSheet();
        $sheet3 = $spreadsheet->getSheet(2);
        $sheet3->setTitle('Grade');
        $sheet3->setCellValue('A1', 'grade_id');
        $sheet3->setCellValue('B1', 'nama_kelas');
        $sheet3->setCellValue('C1', 'jenjang');
        $sheet3->setCellValue('D1', 'fase');

        // input data grades
        $grades = Grade::all();
        foreach ($grades as $grade) {
            $sheet3->setCellValue('A'.$rowGrade, $grade->id);
            $sheet3->setCellValue('B'.$rowGrade, $grade->name);
            $sheet3->setCellValue('C'.$rowGrade, $grade->grade);
            $sheet3->setCellValue('D'.$rowGrade, $grade->fase);
            $rowGrade++;
        }
        
        $writer = new Xlsx($spreadsheet);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx'); // <<< HERE
        $filename = "studentGrade.xlsx"; // <<< HERE
        $file_path = storage_path('/app/public/downloads/'.$filename);
        $writer->save($file_path);
        return response()->download($file_path)->deleteFileAfterSend(true); // <<< HERE
    }

    public function teacherSubject()
    {
        $spreadsheet = new Spreadsheet();

        $rowGrade = 2;
        
        // Membuat lembar pertama
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('TeacherSubject');
        $sheet1->setCellValue('A1', 'teacher_id');
        $sheet1->setCellValue('B1', 'subject_id');
        $sheet1->setCellValue('C1', 'grade_id');

        // input teacher subject
        $teacherSubjects = TeacherSubject::all();
        $rowTeacherSubject = 2;
        foreach ($teacherSubjects as $teacherSubject) {
            $sheet1->setCellValue('A'.$rowTeacherSubject, $teacherSubject->teacher_id);
            $sheet1->setCellValue('B'.$rowTeacherSubject, $teacherSubject->subject_id);
            $sheet1->setCellValue('C'.$rowTeacherSubject, $teacherSubject->grade_id);  
            $rowTeacherSubject++;
        }

        // membuat lembar kedua
        $spreadsheet->createSheet();
        $sheet2 = $spreadsheet->getSheet(1); // Indeks dimulai dari 0
        $sheet2->setTitle('Teacher');
        $sheet2->setCellValue('A1', 'teacher_id');
        $sheet2->setCellValue('B1', 'nama');
        $sheet2->setCellValue('C1', 'gender');

        // input teacher
        $teachers = Teacher::all();
        $rowTeacher = 2;
        foreach ($teachers as $teacher) {
            $sheet2->setCellValue('A'.$rowTeacher, $teacher->id);
            $sheet2->setCellValue('B'.$rowTeacher, $teacher->name);
            $sheet2->setCellValue('C'.$rowTeacher, $teacher->gender);
            $rowTeacher++;
        }

        // membuat lembar ketiga
        $spreadsheet->createSheet();
        $sheet3 = $spreadsheet->getSheet(2); // Indeks dimulai dari 0
        $sheet3->setTitle('Subject');
        $sheet3->setCellValue('A1', 'id');
        $sheet3->setCellValue('B1', 'name');
        $sheet3->setCellValue('C1', 'code');

        // input subject
        $subjects = Subject::all();
        $rowSubject = 2; // Mulai dari baris kedua (A2, B2, C2, ...)
        foreach ($subjects as $subject) {
            $sheet3->setCellValue('A'.$rowSubject, $subject->id);
            $sheet3->setCellValue('B'.$rowSubject, $subject->name);
            $sheet3->setCellValue('C'.$rowSubject, $subject->code);
            $rowSubject++;
        }

        // membuat lembar ke empat
        $spreadsheet->createSheet();
        $sheet4 = $spreadsheet->getSheet(3);
        $sheet4->setTitle('Grade');
        $sheet4->setCellValue('A1', 'grade_id');
        $sheet4->setCellValue('B1', 'nama_kelas');
        $sheet4->setCellValue('C1', 'jenjang');
        $sheet4->setCellValue('D1', 'fase');

        // input data grades
        $grades = Grade::all();
        foreach ($grades as $grade) {
            $sheet4->setCellValue('A'.$rowGrade, $grade->id);
            $sheet4->setCellValue('B'.$rowGrade, $grade->name);
            $sheet4->setCellValue('C'.$rowGrade, $grade->grade);
            $sheet4->setCellValue('D'.$rowGrade, $grade->fase);
            $rowGrade++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx'); // <<< HERE
        $filename = "teacherSubject.xlsx"; // <<< HERE
        $file_path = storage_path('/app/public/downloads/'.$filename);
        $writer->save($file_path);
        return response()->download($file_path)->deleteFileAfterSend(true); // <<< HERE
    }

    public function teacherGrade()
    {
        // Inisialisasi spreadsheet
        $spreadsheet = new Spreadsheet();

        // Membuat lembar pertama
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('TeacherGrade');
        $sheet1->setCellValue('A1', 'teacher_id');
        $sheet1->setCellValue('B1', 'grade_id');

        // Input data TeacherGrade
        $teacherGrades = TeacherGrade::all();
        $rowTeacherGrade = 2;
        foreach ($teacherGrades as $teacherGrade) {
            $sheet1->setCellValue('A'.$rowTeacherGrade, $teacherGrade->teacher_id);
            $sheet1->setCellValue('B'.$rowTeacherGrade, $teacherGrade->grade_id);
            $rowTeacherGrade++;
        }

        // Membuat lembar kedua
        $spreadsheet->createSheet();
        $sheet2 = $spreadsheet->getSheet(1); // Indeks dimulai dari 0
        $sheet2->setTitle('Teacher');
        $sheet2->setCellValue('A1', 'id');
        $sheet2->setCellValue('B1', 'name');
        $sheet2->setCellValue('C1', 'gender');

        // Input data Teacher
        $teachers = Teacher::all();
        $rowTeacher = 2;
        foreach ($teachers as $teacher) {
            $sheet2->setCellValue('A'.$rowTeacher, $teacher->id);
            $sheet2->setCellValue('B'.$rowTeacher, $teacher->name);
            $sheet2->setCellValue('C'.$rowTeacher, $teacher->gender);
            $rowTeacher++;
        }

        // Membuat lembar ketiga
        $spreadsheet->createSheet();
        $sheet3 = $spreadsheet->getSheet(2);
        $sheet3->setTitle('Grade');
        $sheet3->setCellValue('A1', 'id');
        $sheet3->setCellValue('B1', 'name');
        $sheet3->setCellValue('C1', 'grade');
        $sheet3->setCellValue('D1', 'fase');

        // Input data Grade
        $grades = Grade::all();
        $rowGrade = 2;
        foreach ($grades as $grade) {
            $sheet3->setCellValue('A'.$rowGrade, $grade->id);
            $sheet3->setCellValue('B'.$rowGrade, $grade->name);
            $sheet3->setCellValue('C'.$rowGrade, $grade->grade);
            $sheet3->setCellValue('D'.$rowGrade, $grade->fase);
            $rowGrade++;
        }

        // Membuat file Excel
        $writer = new Xlsx($spreadsheet);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx'); // <<< HERE
        $filename = "teacherGrade.xlsx"; // <<< HERE
        $file_path = storage_path('/app/public/downloads/'.$filename);
        $writer->save($file_path);
        return response()->download($file_path)->deleteFileAfterSend(true); // <<< HERE
    }

    public function studentCompetencySheet($teacher_subject_id)
    {
        $teacherSubject = TeacherSubject::with([
                    'academic',
                    'teacher',
                    'grade.teacherGrade',
                    'subject',
                    'competencies.studentCompetency.student', 
                    'exam',
                ])->find($teacher_subject_id);

        $academic = $teacherSubject->academic;
        $teacher = $teacherSubject->teacher;
        $grade = $teacherSubject->grade;
        $subject = $teacherSubject->subject;
        $competencies = $teacherSubject->competencies;
        $curriculum = $teacherSubject->grade->teacherGrade->curriculum;
        $countStudent = $teacherSubject->grade->studentGrade->count();

        // Inisialisasi spreadsheet
        $spreadsheet = new Spreadsheet();
        $countSheet = 0;
        
        // buat sheet berdasarkan banyaknya kompetensi
        foreach ($competencies as $competency) {
            $spreadsheet->createSheet();
            // Membuat lembar pertama
            $sheet = $spreadsheet->getSheet($countSheet); // Indeks dimulai dari 0
            $sheet->setTitle('Sheet'. ($countSheet+1));

             // identitas
            $identitas = [
                ['Identitas pelajaran'],
                [null],
                [ null, 'Nama Guru',null, null, null, null,null,':', $teacher->name],
                [ null, 'Mata Pelajaran', null, null, null, null,null,':', $subject->name],
                [ null, 'Kelas', null, null, null, null,null,':', $grade->name],
                [ null, 'Tahun Akademik', null, null, null, null,null,':', $academic->year],
                [ null, 'Semester', null, null, null, null,null,':', $academic->semester],
                [ null, 'Kompetensi', null, null, null, null,null,':', $competency->code, $competency->description],
                [null, null, null, null, null, null, null,null, $competency->code_skill, $competency->description_skill],
            ];
            $sheet->fromArray($identitas);
            
            // kosongkan datanya
            $data = [];
            $data[] = [
                'nis','nama siswa','teacher_subject_id','student_id','competency_id','score', 'score_skill'
            ];

            foreach ($competency->studentCompetency as $studentCompetency) {
                $data[] = [
                    $studentCompetency->student->nis,
                    $studentCompetency->student->name,
                    $studentCompetency->teacher_subject_id,
                    $studentCompetency->student_id,
                    $studentCompetency->competency_id,
                    $studentCompetency->score,
                    $studentCompetency->score_skill,
                ];
            }

            $sheet->fromArray($data,null,'A13', true);

            $countSheet++;
            
            $sheet->getColumnDimension('B')->setWidth(30);
    
            // hide coloumn C D E
            $sheet->getColumnDimension('C')->setVisible(false);
            $sheet->getColumnDimension('D')->setVisible(false);
            $sheet->getColumnDimension('E')->setVisible(false);
            
            // count student
            $rowStudent = 13 + $countStudent;

            // cek kurikulum
            if($curriculum == 'merdeka'){
                $sheet->getColumnDimension('G')->setVisible(false);
            }

            // bisa di edit
            $sheet->getStyle('F14:F'.$rowStudent)->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
            // bisa di edit
            $sheet->getStyle('G14:G'.$rowStudent)->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
            // proteksi semua cell

            $sheet->getProtection()->setPassword('PhpSpreadsheet');
            $spreadsheet->getActiveSheet()->getProtection()->setSheet(true);

            // validasi tiap-tiap cell
            for ($i=14; $i <= $rowStudent; $i++) { 
                # code...
                $validation = $sheet->getCell('F'.$i)
                    ->getDataValidation();
                $validation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_WHOLE );
                $validation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setErrorTitle('Input error');
                $validation->setError('Number is not allowed!');
                $validation->setPromptTitle('Allowed input');
                $validation->setPrompt('Only numbers between 0 and 100 are allowed.');
                $validation->setFormula1(0);
                $validation->setFormula2(100);

                $validation = $sheet->getCell('G'.$i)
                    ->getDataValidation();
                $validation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_WHOLE );
                $validation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setErrorTitle('Input error');
                $validation->setError('Number is not allowed!');
                $validation->setPromptTitle('Allowed input');
                $validation->setPrompt('Only numbers between 0 and 100 are allowed.');
                $validation->setFormula1(0);
                $validation->setFormula2(100);
            }
        }
        
        // hapus sheet worksheet
        // $sheetIndex = $spreadsheet->getIndex(
        //     $spreadsheet->getSheetByName('Worksheet')
        // );
        // $spreadsheet->removeSheetByIndex($sheetIndex);

        // Membuat file Excel
        $writer = new Xlsx($spreadsheet);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx'); // <<< HERE
        // $filename = "studentCompetency-".$subject->code.".xlsx"; // <<< HERE
        $filename = "nilai-".$teacherSubject->subject->name.'-'.$teacherSubject->grade->name.".xlsx"; // <<< HERE
        $file_path = storage_path('/app/public/downloads/'.$filename);
        $writer->save($file_path);
        return response()->download($file_path)->deleteFileAfterSend(true); // <<< HERE
    }

    public function attendance($grade_id)
    {
        $academic = AcademicYear::active()->first()->id;
        $grade = Grade::with('studentGrade')->find($grade_id);
        $students = $grade->studentGrade->pluck('student');
        
        // Inisialisasi spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Sheet1');
        $sheet->setCellValue('A1', 'DAFTAR HADIR');

        $data = [];

        $data[] = [
            'nis',
            'nama siswa',
            'academic_year_id',
            'grade_id',
            'student_id',
            'sakit',
            'izin',
            'tanpa_keterangan',
            'catatan',
            'penghargaan',
        ];

        foreach ($students as $student) {
            $sick = 0;
            $permission = 0;
            $absent = 0;
            $note = "";
            $achievement = "";

            if($student->attendance){
                $sick = $student->attendance->sick;
                $permission = $student->attendance->permission;
                $absent = $student->attendance->absent;
                $note = $student->attendance->note;
                $achievement = $student->attendance->achievement;
            } else {
                $sick = 0;
                $permission = 0;
                $absent = 0;
                $note = '-';
                $achievement = '-';
            }

            $data[] = [
                $student->nis,
                $student->name,
                $academic,
                $grade_id,
                $student->id,
                $sick,
                $permission,
                $absent,
                $note,
                $achievement,
            ];
        }

        $sheet->fromArray($data, null, 'A3', true);

        $sheet->getColumnDimension('B')->setWidth(30);
    
        // hide coloumn C D E
        $sheet->getColumnDimension('C')->setVisible(false);
        $sheet->getColumnDimension('D')->setVisible(false);
        $sheet->getColumnDimension('E')->setVisible(false);

        // Membuat file Excel
        $writer = new Xlsx($spreadsheet);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx'); // <<< HERE
        $filename = "kehadiran-".$grade->name.".xlsx"; // <<< HERE
        $file_path = storage_path('/app/public/downloads/'.$filename);
        $writer->save($file_path);
        return response()->download($file_path)->deleteFileAfterSend(true); // <<< HERE
    }

    public function studentExtracurricular()
    {
        $spreadsheet = new Spreadsheet();
        
        // Membuat lembar pertama
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('StudentExtracurricular');
        $sheet1->setCellValue('A1', 'extracurricular_id');
        $sheet1->setCellValue('B1', 'student_id');
        $rowStudentExtra = 2;

        $studentExtras = StudentExtracurricular::all();
        foreach ($studentExtras as $studentExtra) {
            $sheet1->setCellValue('A'.$rowStudentExtra, $studentExtra->extracurricular_id);
            $sheet1->setCellValue('B'.$rowStudentExtra, $studentExtra->student_id);
            $rowStudentExtra++;
        }

        // membuat sheet kedua
        $spreadsheet->createSheet();
        $sheet2 = $spreadsheet->getSheet(1); // Indeks dimulai dari 0
        $sheet2->setTitle('Student');
        $sheet2->setCellValue('A1', 'student_id');
        $sheet2->setCellValue('B1', 'nama_lengkap');
        $sheet2->setCellValue('C1', 'gender');
        $sheet2->setCellValue('d1', 'nis');
        $sheet2->setCellValue('e1', 'nisn');
        $rowStudent = 2;

        // input data students
        $students = Student::all();
        foreach ($students as $student) {
            $sheet2->setCellValue('A'. $rowStudent, $student->id);
            $sheet2->setCellValue('B'. $rowStudent, $student->name);
            $sheet2->setCellValue('C'. $rowStudent, $student->gender);
            $sheet2->setCellValue('D'. $rowStudent, $student->nis);
            $sheet2->setCellValue('E'. $rowStudent, $student->nisn);
            $rowStudent++;
        }

        // Membuat lembar ketiga
        $spreadsheet->createSheet();
        $sheet3 = $spreadsheet->getSheet(2);
        $sheet3->setTitle('Extracurricular');
        $sheet3->setCellValue('A1', 'extracurricular_id');
        $sheet3->setCellValue('B1', 'extracurricular');
        $rowExtra = 2;

        $extras = Extracurricular::all();
        foreach ($extras as $extra) {
            $sheet3->setCellValue('A'.$rowExtra, $extra->id);
            $sheet3->setCellValue('B'.$rowExtra, $extra->name);
            $rowExtra++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx'); // <<< HERE
        $filename = "studentExtracurricular.xlsx"; // <<< HERE
        $file_path = storage_path('/app/public/downloads/'.$filename);
        $writer->save($file_path);
        return response()->download($file_path)->deleteFileAfterSend(true); // <<< HERE
    }

    public function teacherExtracurricular()
    {
        $spreadsheet = new Spreadsheet();
        
        // Membuat lembar pertama
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('TeacherExtracurricular');
        $sheet1->setCellValue('A1', 'extracurricular_id');
        $sheet1->setCellValue('B1', 'teacher_id');
        $rowStudentExtra = 2;

        $teacherExtras = TeacherExtracurricular::all();
        foreach ($teacherExtras as $teacherExtra) {
            $sheet1->setCellValue('A'.$rowStudentExtra, $teacherExtra->extracurricular_id);
            $sheet1->setCellValue('B'.$rowStudentExtra, $teacherExtra->teacher_id);
            $rowStudentExtra++;
        }   

         // Membuat lembar kedua
         $spreadsheet->createSheet();
         $sheet2 = $spreadsheet->getSheet(1); // Indeks dimulai dari 0
         $sheet2->setTitle('Teacher');
         $sheet2->setCellValue('A1', 'id');
         $sheet2->setCellValue('B1', 'name');
         $sheet2->setCellValue('C1', 'gender');
 
         // Input data Teacher
         $teachers = Teacher::all();
         $rowTeacher = 2;
         foreach ($teachers as $teacher) {
             $sheet2->setCellValue('A'.$rowTeacher, $teacher->id);
             $sheet2->setCellValue('B'.$rowTeacher, $teacher->name);
             $sheet2->setCellValue('C'.$rowTeacher, $teacher->gender);
             $rowTeacher++;
         }
        
        // Membuat lembar ketiga
        $spreadsheet->createSheet();
        $sheet3 = $spreadsheet->getSheet(2);
        $sheet3->setTitle('Extracurricular');
        $sheet3->setCellValue('A1', 'extracurricular_id');
        $sheet3->setCellValue('B1', 'extracurricular');
        $rowExtra = 2;

        $extras = Extracurricular::all();
        foreach ($extras as $extra) {
            $sheet3->setCellValue('A'.$rowExtra, $extra->id);
            $sheet3->setCellValue('B'.$rowExtra, $extra->name);
            $rowExtra++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx'); // <<< HERE
        $filename = "teacherExtracurricular.xlsx"; // <<< HERE
        $file_path = storage_path('/app/public/downloads/'.$filename);
        $writer->save($file_path);
        return response()->download($file_path)->deleteFileAfterSend(true); // <<< HERE
    }

    public function competency($teacher_subject_id)
    {
        $teacher_subject = TeacherSubject::with('academic','teacher','subject','grade.teacherGrade','competencies')->find($teacher_subject_id);
        
        $academic = $teacher_subject->academic;
        $teacher = $teacher_subject->teacher;
        $grade = $teacher_subject->grade;
        $subject = $teacher_subject->subject;
        $competencies = $teacher_subject->competencies;

        $spreadsheet = new Spreadsheet();
        $spreadsheet->createSheet();
        $sheet = $spreadsheet->getSheet(0); // Indeks dimulai dari 0

         // identitas
         $judulIdentitas = [
            ['Identitas pelajaran'],
            [null],
            ['Tahun Akademik'],
            ['Semester'],
            ['Nama Guru'],
            ['Mata Pelajaran'],
            ['Kelas'],
        ];
        $sheet->fromArray($judulIdentitas, null, 'B1');

        $identitas = [
            [':', $academic->year],
            [':', $academic->semester],
            [':', $teacher->name],
            [':', $subject->name],
            [':', $grade->name],
        ];

        $sheet->fromArray($identitas, null, 'H3');
        
        // Membuat lembar pertama
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Competency');

        // cek kurikulum
        // dd($teacher_subject->grade->teacherGrade->curriculum->toArray());
        $sheet1->setCellValue('A10', 'teacher_subject_id');
        $sheet1->setCellValue('B10', 'kode');
        $sheet1->setCellValue('C10', 'deskripsi');
        $sheet1->setCellValue('D10', 'kode_keterampilan');
        $sheet1->setCellValue('E10', 'deskripsi_keterampilan');
        $sheet1->setCellValue('F10', 'kkm');
        $sheet1->setCellValue('G10', 'id');

        $row = 11;
        foreach ($competencies as $competency) {
            $sheet1->setCellValue('A'.$row, $teacher_subject_id);
            $sheet1->setCellValueExplicit('B'.$row, $competency->code, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet1->setCellValue('C'.$row, $competency->description);
            $sheet1->setCellValueExplicit('D'.$row, $competency->code_skill, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet1->setCellValue('E'.$row, $competency->description_skill);
            $sheet1->setCellValue('F'.$row, $competency->passing_grade);
            $sheet1->setCellValue('G'.$row, $competency->id);
            $row++;
        }

        // tambahkan baris dengan kolom teacher_subject_id tambahan untuk yang baru
        for ($i=$row; $i < 50; $i++) { 
            $sheet1->setCellValue('A'.$row, $teacher_subject_id);
            $row++;
        }

        // setting width
        $sheet->getColumnDimension('C')->setWidth(50); 
        $sheet->getColumnDimension('E')->setWidth(50); 

        // hide column A
        $sheet->getColumnDimension('A')->setVisible(false);
        $sheet->getColumnDimension('G')->setVisible(false);

        // hide kode dan deskripsi keterampilan
        if($teacher_subject->grade->teacherGrade->curriculum == 'merdeka'){
            $sheet->getColumnDimension('D')->setVisible(false);
            $sheet->getColumnDimension('E')->setVisible(false);
            
        }
        
        // bisa di edit
        $sheet->getStyle('B:C')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
        $sheet->getStyle('D:E')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
        $sheet->getStyle('F')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
            
        // proteksi semua cell
        $sheet->getProtection()->setPassword('PhpSpreadsheet');
        $spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
        
        $writer = new Xlsx($spreadsheet);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx'); // <<< HERE
        $filename = "Kompetensi ". $teacher_subject->subject->code . ' '. $teacher_subject->grade->name.".xlsx"; // <<< HERE
        $file_path = storage_path('/app/public/downloads/'.$filename);
        $writer->save($file_path);
        return response()->download($file_path)->deleteFileAfterSend(true); // <<< HERE
    }

    public function exam($teacher_subject_id)
    {
        $teacher_subject = TeacherSubject::with('academic','teacher','subject','grade.teacherGrade','competencies')->find($teacher_subject_id);
        $data = Exam::with('student')->where('teacher_subject_id', $teacher_subject_id)->get();

        $academic = $teacher_subject->academic;
        $teacher = $teacher_subject->teacher;
        $grade = $teacher_subject->grade;
        $subject = $teacher_subject->subject;

        $spreadsheet = new Spreadsheet();
        $spreadsheet->createSheet();
        $sheet = $spreadsheet->getSheet(0); // Indeks dimulai dari 0

         // identitas
         $judulIdentitas = [
            ['Identitas pelajaran'],
            [null],
            ['Tahun Akademik'],
            ['Semester'],
            ['Nama Guru'],
            ['Mata Pelajaran'],
            ['Kelas'],
        ];
        $sheet->fromArray($judulIdentitas, null, 'C1');

        $identitas = [
            [':', $academic->year],
            [':', $academic->semester],
            [':', $teacher->name],
            [':', $subject->name],
            [':', $grade->name],
        ];

        $sheet->fromArray($identitas, null, 'E3');
        
        // Membuat lembar pertama
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Exam');

        $sheet1->setCellValue('A10', 'teacher_subject_id');
        $sheet1->setCellValue('B10', 'student_id');
        $sheet1->setCellValue('C10', 'nama');
        $sheet1->setCellValue('D10', 'score_middle');
        $sheet1->setCellValue('E10', 'score_last');

        // input data
        $students = [];
        foreach ($data as $item) {
            $students[] = [
                $item->teacher_subject_id,
                $item->student_id,
                $item->student->name,
                $item->score_middle,
                $item->score_last,
            ]; 
        }

        $sheet->fromArray($students, null, 'A11', true);

        // hide column
        $sheet->getColumnDimension('A')->setVisible(false);
        $sheet->getColumnDimension('B')->setVisible(false);

        $writer = new Xlsx($spreadsheet);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx'); // <<< HERE
        $filename = "Exam ". $teacher_subject->subject->code . ' '. $teacher_subject->grade->name.".xlsx"; // <<< HERE
        $file_path = storage_path('/app/public/downloads/'.$filename);
        $writer->save($file_path);
        return response()->download($file_path)->deleteFileAfterSend(true); // <<< HERE
    }
}
