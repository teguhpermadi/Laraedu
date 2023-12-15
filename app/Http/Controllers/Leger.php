<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Grade;
use App\Models\Student;
use App\Models\TeacherGrade;
use App\Models\TeacherSubject;
use Illuminate\Http\Request;

class Leger extends Controller
{
    public function subject($id)
    {
        $data = TeacherSubject::with('academic','teacher', 'grade.teacherGrade', 'grade.studentGrade.student', 'subject', 'competencies', 'exam')->find($id);
        // return $teacher_subject;
        return view('leger.subject', ['data' => $data]);
    }

    public function attendance()
    {
        $grade = TeacherGrade::with('grade', 'academic')->where('teacher_id', auth()->user()->userable->userable_id)->first();
        $students = Student::with('attendance')->myStudentGrade()->get();
        // return $grade;
        // return $students;
        return view('leger.attendance', ['students' => $students, 'grade' => $grade]);
    }

    public function grade($id)
    {
        $grade = TeacherGrade::with('academic','grade.studentGrade.student', 'teacher')->find($id);
        $subjects = TeacherSubject::with('subject')->where('grade_id', $grade->grade_id)->get();

        // $data = [
        //     'grade' => $grade->grade,
        //     'teacher' => $grade->teacher,
        //     'subjects' => $subjects,
        // ];

        // return $data;
        return view('leger.grade', ['subjects' => $subjects, 'grade' => $grade]);
    }
}
