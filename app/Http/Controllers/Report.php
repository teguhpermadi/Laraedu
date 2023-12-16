<?php

namespace App\Http\Controllers;
use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\Attitude;
use App\Models\Exam;
use App\Models\School;
use App\Models\Student;
use App\Models\StudentExtracurricular;
use App\Models\StudentGrade;
use App\Models\TeacherGrade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use Illuminate\Support\Str;
use Spatie\Valuestore\Valuestore;

class Report extends Controller
{
    public function calculateReport($id)
    {
        $data = [];
        $school = School::first();

        $academic = AcademicYear::with('teacher')->active()->first();

        $student = Student::find($id);
        
        $grade = StudentGrade::with('grade')->where('student_id', $id)->first();
        $teacherGrade = TeacherGrade::with('teacher')->where('grade_id', $grade->grade_id)->first();

        $attendance = Attendance::where('student_id', $id)->first();
        
        $attitude = Attitude::where('student_id', $id)->first();

        $extracurriculars = StudentExtracurricular::where('student_id', $id)->description()->get();

        $scores = Student::with([
            'studentGrade.teacherSubject.studentCompetency' => function($q) use ($id){
                $q->where('student_id',$id)->result();
            }])->find($id);

        $subjects = $scores->studentGrade->teacherSubject;
        $result = [];
        foreach ($subjects as $subject) {
            // buat dulu deskripsinya
            $combinedResultDescription = '';
            $lulusDescriptions = [];
            $tidakLulusDescriptions = [];
            $sangatBaik = [];
            $baik = [];
            $tingkatkan = [];
            
            // skill
            // $combinedResultDescriptionSkill = '';
            // $lulusDescriptionsSkill = [];
            // $tidakLulusDescriptionsSkill = [];
            
            
            // predikat
            $average_scores_predicate = '';
            $average_scores_skill_predicate = '';
            $valueStore = Valuestore::make(storage_path('app/settings.json'));
            $predicates = $valueStore->get('predicate');

            
            foreach ($subject->studentCompetency as $competency) {
                switch ($competency['predicate']) {
                    case 'A':
                        $sangatBaik[] = $competency['description'];
                        break;
                    case 'B':
                        $baik[] = $competency['description'];
                        break;
                    
                    default:
                        $tingkatkan[] = $competency['description'];
                        break;
                }

                // if ($competency['result'] === "LULUS") {
                //     $lulusDescriptions[] = $competency['description'];
                // } else {
                //     $tidakLulusDescriptions[] = $competency['description'];
                // }

                // skill
                // if ($competency['result_skill'] === "LULUS") {
                //     $lulusDescriptionsSkill[] = $competency['description_skill'];
                // } else {
                //     $tidakLulusDescriptionsSkill[] = $competency['description_skill'];
                // }
                
            }
    
            // Gabungkan result_description untuk "LULUS" dan "TIDAK LULUS"
            // $lulusDescription = implode("; ", $lulusDescriptions);
            // $tidakLulusDescription = implode("; ", $tidakLulusDescriptions);

            $sangatBaikDescription = implode('; ', $sangatBaik);
            $baikDescription = implode('; ', $baik);
            $tingkatkanDescription = implode('; ', $tingkatkan);

            // skill
            // $lulusDescriptionsSkill = implode("; ", $lulusDescriptionsSkill);
            // $tidakLulusDescriptionsSkill = implode("; ", $tidakLulusDescriptionsSkill);
            
            // if($lulusDescription){
            //     $combinedResultDescription = 'Alhamdulillah ananda ' . Str::of($student->name)->title() . (($lulusDescription) ? ' telah MENGUASAI materi: ' . $lulusDescription : '') . (($tidakLulusDescription) ? ' Serta CUKUP MENGUASAI materi: '. $tidakLulusDescription : '');    
            //     $combinedResultDescriptionSkill = 'Alhamdulillah ananda ' . Str::of($student->name)->title() . (($lulusDescriptionsSkill) ? ' telah MENGUASAI materi: ' . $lulusDescriptionsSkill : '') . (($tidakLulusDescriptionsSkill) ? ' Serta CUKUP MENGUASAI materi: '. $tidakLulusDescriptionsSkill : '');    
            // } else {
            //     $combinedResultDescription = 'Mohon maaf ananda ' . Str::of($student->name)->title() . ' belum MENGUASAI materi:' . $tidakLulusDescription;
            //     $combinedResultDescriptionSkill = 'Mohon maaf ananda ' . Str::of($student->name)->title() . ' belum MENGUASAI materi:' . $tidakLulusDescriptionsSkill;
            // }

            $combinedResultDescription = 'Alhamdulillah ananda ' . Str::of($student->name)->title() . (($sangatBaik) ? ' sangat baik dalam hal: ' . $sangatBaikDescription : '') . (($baik) ? ' serta sudah menunjukkan dalam hal: ' . $baikDescription : '') . (($tingkatkan) ? ' tetapi perlu ditingkatkan dalam hal: ' . $tingkatkanDescription : '');

            $exam = Exam::where('teacher_subject_id',$subject->id)->where('student_id', $id)->first();
            $middle = $exam->score_middle;
            $last = $exam->score_last;

            // Pengecekan jika $middle atau $last null
            $middleScore = $middle ? $middle : null;
            $lastScore = $last ? $last : null;

            $avg_score_student_competencies = $subject->studentCompetency->avg('score');
            $avg_score_student_competencies_skill = $subject->studentCompetency->avg('score_skill');
            $dataScores = $subject->studentCompetency;

            /* 
            HITUNG NILAI RATA-RATA KOMPETENSI, TENGAH SEMESTER DAN AKHIR SEMESTER
            */

            $scores = collect([$avg_score_student_competencies, $middleScore, $lastScore]);
            $scores_skill = collect($avg_score_student_competencies_skill);
            $average_scores = $scores->avg();
            $average_scores_skill = $scores_skill->avg();

            /*
            if($avg_score_student_competencies && $middleScore && $lastScore){
                // jika ada nilai kompetensi, tengah semester, akhir semester
                $scores = collect([$avg_score_student_competencies, $middleScore, $lastScore]);
                $scores_skill = collect($avg_score_student_competencies_skill);
                $average_scores = $scores->avg();
                $average_scores_skill = $scores_skill->avg();
            } 
            else if($avg_score_student_competencies && $middleScore)  {
                // jika ada nilai kompetensi dan tengah semester
                $scores = collect([$avg_score_student_competencies, $middleScore]);
                $average_scores = $scores->avg(); 
            } 
            else if($avg_score_student_competencies && $lastScore) {
                // jika ada nilai kompetensi dan akhir semester
                $scores = collect([$avg_score_student_competencies, $lastScore]);
                $average_scores = $scores->avg(); 
            } 
            else {
                // jika ada nilai kompetensi
                $scores = collect([$avg_score_student_competencies]);
                $average_scores = $scores->avg(); 
            }
            */

            /**
             * predikat
             */
            
            // foreach ($predicates as $predicate) {
            //     // nilai pengetahuan
            //     if ($average_scores <= $predicate['upper_limit'] && $average_scores > $predicate['lower_limit']) {
            //         $average_scores_predicate = $predicate['predicate'];
            //     }
            //     // nilai keterampilan
            //     if ($average_scores_skill <= $predicate['upper_limit'] && $average_scores_skill > $predicate['lower_limit']) {
            //         $average_scores_skill_predicate = $predicate['predicate'];
            //     }
            // }

            if($average_scores <= 4){
                $average_scores_predicate = 'Berkembang Sangat Baik';
            } 
            elseif ($average_scores <= 3) {
                $average_scores_predicate = 'Berkembang Sesuai Harapan';
            } 
            elseif ($average_scores <= 2) {
                $average_scores_predicate = 'Mulai Berkembang';
            }
            else {
                $average_scores_predicate = 'Belum Berkembang';
            }

            // dd($average_scores);
    
            $result[] = [
            // $result[$subject->subject->order] = [
                // 'teacher_subject_id' => $subject->id,
                'order' => $subject->subject->order,
                'orderDes' => $subject->subject->order,
                'subject' => $subject->subject->name,
                'subject_skill' => $subject->subject->name,
                'code' => $subject->subject->code,
                'score_competencies' => $avg_score_student_competencies,
                'middle_score' => $middleScore,
                'last_score' => $lastScore,
                'average_score' => round($average_scores,1),
                'average_scores_predicate' => $average_scores_predicate,
                // 'passed_description' => $lulusDescription,
                // 'not_pass_description' => $tidakLulusDescription,
                'combined_description' => $combinedResultDescription,
                // skill
                'score_competencies_skill' => $avg_score_student_competencies_skill,
                'average_score_skill' => round($average_scores_skill,1),
                // 'average_scores_skill_predicate' => $average_scores_skill_predicate,
                // 'passed_description_skill' => $lulusDescriptionsSkill,
                // 'not_pass_description_skill' => $tidakLulusDescriptionsSkill,
                // 'combined_description_skill' => $combinedResultDescriptionSkill,
                'data_score' => $dataScores,
            ];

        }

        $resultOrder = collect($result)->sortBy('order')->values()->all();

        $resultCollection = collect($result);
        $totalAverageScore = $resultCollection->sum('average_score');
        $totalAverageScoreSkill = $resultCollection->sum('average_score_skill');
        $counting_total = readNumber($totalAverageScore);
        $counting_total_skill = readNumber($totalAverageScoreSkill);

        $extra = [];
        $numExtra = 1;
        foreach ($extracurriculars as $extracurricular) {
            $extra [] = [
                'orderEx' => $numExtra,
                'name' => $extracurricular->name,
                'score' => $extracurricular->score,
                'description' => $extracurricular->description,
            ];

            $numExtra++;
        }

        App::setLocale('id');

        $data = [
            'school' => $school,
            'academic' => $academic->toArray(),
            'headmaster' => $academic->teacher->name,
            'date_report' => Carbon::parse($academic->date_report)->isoFormat('D MMMM Y'),
            'teacher' => $teacherGrade->teacher,
            'student' => $student->toArray(),
            'grade' => $grade->grade->toArray(),
            'attendance' => $attendance,
            'attitude' => $attitude,
            'result' => $resultOrder,
            'total_average_score' => $totalAverageScore,
            'counting_total' => $counting_total,
            'total_average_score_skill' => $totalAverageScoreSkill,
            'counting_total_skill' => $counting_total_skill,
            'extracurriculars' => $extra,
        ];

        // $data = $this->report($data);
        // switch ($teacherGrade->curriculum) {
        //     case '2013':
        //         $data = $this->report2013($data);
        //         break;
            
        //     default:
        //         $data = $this->report($data);
        //         break;
        // }
        return $data;
    }

    public function report($data)
    {
        $templateProcessor = new TemplateProcessor( storage_path('/app/public/templates/reportra.docx'));
        $templateProcessor->setValue('school_name',$data['school']['name']);
        $templateProcessor->setValue('school_address',$data['school']['address']);
        $templateProcessor->setValue('headmaster',$data['headmaster']);
        $templateProcessor->setValue('date_report',$data['date_report']);
        $templateProcessor->setValue('year',$data['academic']['year']);
        $templateProcessor->setValue('semester',$data['academic']['semester']);
        $templateProcessor->setValue('student_name',$data['student']['name']);
        $templateProcessor->setValue('nisn',$data['student']['nisn']);
        $templateProcessor->setValue('nis',$data['student']['nis']);
        $templateProcessor->setValue('grade_name',$data['grade']['name']);
        $templateProcessor->setValue('grade_level',$data['grade']['fase']);
        $templateProcessor->setValue('sick',$data['attendance']['sick']);
        $templateProcessor->setValue('permission',$data['attendance']['permission']);
        $templateProcessor->setValue('absent',$data['attendance']['absent']);
        $templateProcessor->setValue('total_attendance',$data['attendance']['total_attendance']);
        $templateProcessor->setValue('note',$data['attendance']['note']);
        $templateProcessor->setValue('achievement',$data['attendance']['achievement']);
        $templateProcessor->setValue('attitude_religius',$data['attitude']['attitude_religius']);
        $templateProcessor->setValue('attitude_social',$data['attitude']['attitude_social']);
        $templateProcessor->setValue('teacher_name',$data['teacher']['name']);
        $templateProcessor->setValue('total_average_score',$data['total_average_score']);
        $templateProcessor->setValue('counting_total',$data['counting_total']);

        // tabel nilai mata pelajaran
        $templateProcessor->cloneRowAndSetValues('order', $data['result']);

        // tabel ekstrakurikuler
        $templateProcessor->cloneRowAndSetValues('orderEx', $data['extracurriculars']);
        
        $filename = 'Rapor '.$data['student']['name'].' - '. $data['academic']['semester'] .'.docx';
        $file_path = storage_path('/app/public/downloads/'.$filename);
        $templateProcessor->saveAs($file_path);
        return response()->download($file_path)->deleteFileAfterSend(true);; // <<< HERE
    }

    public function getData($id)
    {
        $academic = AcademicYear::with('teacher')->active()->first();
        $student = Student::with('dataStudent')->find($id);
        $data = [
            'student' => $student,
            'academic' => $academic,
        ];
        
        $data = $this->coverStudent($data);
        return $data;
    }

    public function coverStudent($data)
    {
        $templateProcessor = new TemplateProcessor( storage_path('/app/public/templates/cover-student.docx'));
        $templateProcessor->setValue('nama',$data['student']['name']);
        $templateProcessor->setValue('nisn',$data['student']['nisn']);
        $templateProcessor->setValue('nis',$data['student']['nis']);
        $templateProcessor->setValue('tempat_lahir',$data['student']['city_born']);
        // $templateProcessor->setValue('tanggal_lahir',$data['student']['birthday']);
        $templateProcessor->setValue('tanggal_lahir', Carbon::createFromFormat('Y-m-d', $data['student']['birthday'])->locale('id')->translatedFormat('d F Y'));
        $templateProcessor->setValue('jenis_kelamin',$data['student']['gender']);
        $templateProcessor->setValue('agama',$data['student']['dataStudent']['religion']);
        $templateProcessor->setValue('pendidikan_sebelumnya',$data['student']['dataStudent']['previous_school']);
        $templateProcessor->setValue('alamat',$data['student']['dataStudent']['student_address']);
        $templateProcessor->setValue('kelurahan',$data['student']['dataStudent']['student_village']);
        $templateProcessor->setValue('kecamatan',$data['student']['dataStudent']['student_district']);
        $templateProcessor->setValue('kota',$data['student']['dataStudent']['student_city']);
        $templateProcessor->setValue('provinsi',$data['student']['dataStudent']['student_province']);
        
        // ayah
        $templateProcessor->setValue('nama_ayah',$data['student']['dataStudent']['father_name']);
        $templateProcessor->setValue('pendidikan_ayah',$data['student']['dataStudent']['father_education']);
        $templateProcessor->setValue('pekerjaan_ayah',$data['student']['dataStudent']['father_occupation']);
        // ibu
        $templateProcessor->setValue('nama_ibu',$data['student']['dataStudent']['mother_name']);
        $templateProcessor->setValue('pendidikan_ibu',$data['student']['dataStudent']['mother_education']);
        $templateProcessor->setValue('pekerjaan_ibu',$data['student']['dataStudent']['mother_occupation']);
        // alamat
        $templateProcessor->setValue('alamat_orangtua',$data['student']['dataStudent']['parent_address']);
        $templateProcessor->setValue('kelurahan_orangtua',$data['student']['dataStudent']['parent_village']);
        $templateProcessor->setValue('kecamatan_orangtua',$data['student']['dataStudent']['parent_district']);
        $templateProcessor->setValue('kota_orangtua',$data['student']['dataStudent']['parent_city']);
        $templateProcessor->setValue('provinsi_orangtua',$data['student']['dataStudent']['parent_province']);
        // wali
        $templateProcessor->setValue('nama_wali',$data['student']['dataStudent']['guardian_name']);
        $templateProcessor->setValue('pekerjaan_wali',$data['student']['dataStudent']['guardian_occupation']);
        $templateProcessor->setValue('alamat_wali',$data['student']['dataStudent']['guardian_address']);

        // tanda tangan
        $templateProcessor->setValue('date_received', Carbon::createFromFormat('Y-m-d', $data['student']['dataStudent']['date_received'])->locale('id')->translatedFormat('d F Y'));
        $templateProcessor->setValue('headmaster',$data['academic']['teacher']['name']);

        $filename = 'Identitas '.$data['student']['name'].' - '. $data['academic']['semester'] .'.docx';
        $file_path = storage_path('/app/public/downloads/'.$filename);
        $templateProcessor->saveAs($file_path);
        return response()->download($file_path)->deleteFileAfterSend(true); // <<< HERE
    }

    public function getDataCover($id)
    {
        $student = Student::find($id);
        
        $data = $this->cover($student);
        return $data;
    }

    public function cover($data)
    {
        $templateProcessor = new TemplateProcessor( storage_path('/app/public/templates/cover.docx'));
        $templateProcessor->setValue('nama',$data['name']);
        $templateProcessor->setValue('nisn',$data['nisn']);
        $templateProcessor->setValue('nis',$data['nis']);

        $filename = 'Cover '.$data['name'] .'.docx';
        $file_path = storage_path('/app/public/downloads/'.$filename);
        $templateProcessor->saveAs($file_path);
        return response()->download($file_path)->deleteFileAfterSend(true); // <<< HERE
    }

    /**
     * kurikulum 2013
     */

    public function report2013($data)
    {
        $templateProcessor = new TemplateProcessor( storage_path('/app/public/templates/report2013.docx'));
        $templateProcessor->setValue('school_name',$data['school']['name']);
        $templateProcessor->setValue('school_address',$data['school']['address']);
        $templateProcessor->setValue('headmaster',$data['headmaster']);
        $templateProcessor->setValue('date_report',$data['date_report']);
        $templateProcessor->setValue('year',$data['academic']['year']);
        $templateProcessor->setValue('semester',$data['academic']['semester']);
        $templateProcessor->setValue('student_name',$data['student']['name']);
        $templateProcessor->setValue('nisn',$data['student']['nisn']);
        $templateProcessor->setValue('nis',$data['student']['nis']);
        $templateProcessor->setValue('grade_name',$data['grade']['name']);
        $templateProcessor->setValue('grade_level',$data['grade']['grade']);
        $templateProcessor->setValue('sick',$data['attendance']['sick']);
        $templateProcessor->setValue('permission',$data['attendance']['permission']);
        $templateProcessor->setValue('absent',$data['attendance']['absent']);
        $templateProcessor->setValue('total_attendance',$data['attendance']['total_attendance']);
        $templateProcessor->setValue('note',$data['attendance']['note']);
        $templateProcessor->setValue('achievement',$data['attendance']['achievement']);
        $templateProcessor->setValue('attitude_religius',$data['attitude']['attitude_religius']);
        $templateProcessor->setValue('attitude_social',$data['attitude']['attitude_social']);
        $templateProcessor->setValue('teacher_name',$data['teacher']['name']);
        $templateProcessor->setValue('total_average_score',$data['total_average_score']);
        $templateProcessor->setValue('counting_total',$data['counting_total']);
        $templateProcessor->setValue('total_average_score_skill',$data['total_average_score_skill']);
        $templateProcessor->setValue('counting_total_skill',$data['counting_total_skill']);

        // tabel nilai mata pelajaran
        $templateProcessor->cloneRowAndSetValues('order', $data['result']);
        $templateProcessor->cloneRowAndSetValues('orderDes', $data['result']);

        // tabel ekstrakurikuler
        $templateProcessor->cloneRowAndSetValues('orderEx', $data['extracurriculars']);
        
        $filename = 'Rapor '.$data['student']['name'].' - '. $data['academic']['semester'] .'.docx';
        $file_path = storage_path('/app/public/downloads/'.$filename);
        $templateProcessor->saveAs($file_path);
        return response()->download($file_path)->deleteFileAfterSend(true);; // <<< HERE
    }
}
