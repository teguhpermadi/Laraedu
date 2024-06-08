<?php

namespace App\Http\Controllers;

use App\CustomTemplateProcessor;
use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\Attitude;
use App\Models\Exam;
use App\Models\Project;
use App\Models\ProjectNote;
use App\Models\ProjectStudent;
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
use PhpOffice\PhpWord\Shared\Html;
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

        // status naik kelas
        $status = '';
        $nextGrade = (int) $grade->grade->grade;
        switch ($attendance->status) {
            case '1':
                $nextGrade++;
                switch ($nextGrade) {
                    case '7':
                        $status = 'Berdasarkan pencapaian seluruh kompetensi, peserta didik atas nama ' . Str::of($student->name)->title() . ' dinyatakan: LULUS dari satuan pendidikan.';
                        break;
                    
                    case '10':
                        $status = 'Berdasarkan pencapaian seluruh kompetensi, peserta didik atas nama ' . Str::of($student->name)->title() . ' dinyatakan: LULUS dari satuan pendidikan.';
                        break;
                    
                    case '13':
                        $status = 'Berdasarkan pencapaian seluruh kompetensi, peserta didik atas nama ' . Str::of($student->name)->title() . ' dinyatakan: LULUS dari satuan pendidikan.';
                        break;
                    
                    default:
                        $status = 'Berdasarkan pencapaian seluruh kompetensi, peserta didik atas nama ' . Str::of($student->name)->title() . ' dinyatakan: NAIK KELAS ' . $nextGrade;
                        break;
                }
                break;
            case '0':
                $status = 'Berdasarkan pencapaian seluruh kompetensi, peserta didik atas nama ' . Str::of($student->name)->title() . ' dinyatakan: TINGGAL KELAS ' . $nextGrade;
                break;
            
            default:
                $status = '';
                break;
        }
        
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
            
            // skill
            $combinedResultDescriptionSkill = '';
            $lulusDescriptionsSkill = [];
            $tidakLulusDescriptionsSkill = [];
            
            
            // predikat
            $average_scores_predicate = '';
            $average_scores_skill_predicate = '';
            $valueStore = Valuestore::make(storage_path('app/public/settings.json'));
            // $valueStore = Valuestore::make(storage_path('app/settings.json'));
            $predicates = $valueStore->get('predicate');

            
            foreach ($subject->studentCompetency as $competency) {
                if ($competency['result'] === "LULUS") {
                    $lulusDescriptions[] = $competency['description'];
                } else {
                    $tidakLulusDescriptions[] = $competency['description'];
                }

                // skill
                if ($competency['result_skill'] === "LULUS") {
                    $lulusDescriptionsSkill[] = $competency['description_skill'];
                } else {
                    $tidakLulusDescriptionsSkill[] = $competency['description_skill'];
                }
                
            }
    
            // Gabungkan result_description untuk "LULUS" dan "TIDAK LULUS"
            $lulusDescription = implode("; ", $lulusDescriptions);
            $tidakLulusDescription = implode("; ", $tidakLulusDescriptions);

            // skill
            $lulusDescriptionsSkill = implode("; ", $lulusDescriptionsSkill);
            $tidakLulusDescriptionsSkill = implode("; ", $tidakLulusDescriptionsSkill);
            
            if($lulusDescription){
                $combinedResultDescription = 'Alhamdulillah ananda ' . Str::of($student->name)->title() . (($lulusDescription) ? ' telah MENGUASAI materi: ' . $lulusDescription : '') . (($tidakLulusDescription) ? ' Serta CUKUP MENGUASAI materi: '. $tidakLulusDescription : '');    
                $combinedResultDescriptionSkill = 'Alhamdulillah ananda ' . Str::of($student->name)->title() . (($lulusDescriptionsSkill) ? ' telah MENGUASAI materi: ' . $lulusDescriptionsSkill : '') . (($tidakLulusDescriptionsSkill) ? ' Serta CUKUP MENGUASAI materi: '. $tidakLulusDescriptionsSkill : '');    
            } else {
                $combinedResultDescription = 'Mohon maaf ananda ' . Str::of($student->name)->title() . ' belum MENGUASAI materi:' . $tidakLulusDescription;
                $combinedResultDescriptionSkill = 'Mohon maaf ananda ' . Str::of($student->name)->title() . ' belum MENGUASAI materi:' . $tidakLulusDescriptionsSkill;
            }

            // $combinedResultDescription = 'Alhamdulillah ananda ' . Str::of($student->name)->title() . (($lulusDescription) ? ' telah menguasai materi: ' . $lulusDescription : '') . (($tidakLulusDescription) ? ' cukup menguasai materi: '. $tidakLulusDescription : '');

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
            
            foreach ($predicates as $predicate) {
                // nilai pengetahuan
                if ($average_scores <= $predicate['upper_limit'] && $average_scores > $predicate['lower_limit']) {
                    $average_scores_predicate = $predicate['predicate'];
                }
                // nilai keterampilan
                if ($average_scores_skill <= $predicate['upper_limit'] && $average_scores_skill > $predicate['lower_limit']) {
                    $average_scores_skill_predicate = $predicate['predicate'];
                }
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
                'passed_description' => $lulusDescription,
                'not_pass_description' => $tidakLulusDescription,
                'combined_description' => $combinedResultDescription,
                // skill
                'score_competencies_skill' => $avg_score_student_competencies_skill,
                'average_score_skill' => round($average_scores_skill,1),
                'average_scores_skill_predicate' => $average_scores_skill_predicate,
                'passed_description_skill' => $lulusDescriptionsSkill,
                'not_pass_description_skill' => $tidakLulusDescriptionsSkill,
                'combined_description_skill' => $combinedResultDescriptionSkill,
                'data_score' => $dataScores,
                'count_competencies' => $subject->studentCompetency->count(),
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
            'status' => $status,
        ];

        // $data = $this->report($data);
        switch ($teacherGrade->curriculum) {
            case '2013':
                $data = $this->report2013($data);
                break;
            
            default:
                $data = $this->report($data);
                break;
        }
        return $data;
    }

    public function report($data)
    {
        $templateProcessor = new TemplateProcessor( storage_path('/app/public/templates/report.docx'));
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

        // block status
        if($data['academic']['semester'] == 'ganjil'){
            $templateProcessor->cloneBlock('block_status', 0, true, false, null);
        } else {
            $templateProcessor->cloneBlock('block_status', 1, true, false, null);
            $templateProcessor->setValue('status',$data['status']); 
        }
        
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
        
        // $data = $this->coverStudent($data);
        // return $data;
        return view('report.cover', ['data' => $data]);
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

        // block status
        if($data['academic']['semester'] == 'ganjil'){
            $templateProcessor->cloneBlock('block_status', 0, true, false, null);
        } else {
            $templateProcessor->cloneBlock('block_status', 1, true, false, null);
            $templateProcessor->setValue('status',$data['status']); 
        }
        
        $filename = 'Rapor '.$data['student']['name'].' - '. $data['academic']['semester'] .'.docx';
        $file_path = storage_path('/app/public/downloads/'.$filename);
        $templateProcessor->saveAs($file_path);
        return response()->download($file_path)->deleteFileAfterSend(true);; // <<< HERE
    }

    public function project($id)
    {
        $academic = AcademicYear::with('teacher')->active()->first();
        $school = School::first();

        $student = Student::find($id);
        
        $grade = StudentGrade::with('grade')->where('student_id', $id)->first();
        $teacherGrade = TeacherGrade::with('teacher')->where('grade_id', $grade->grade_id)->first();

        $project = Project::with(['projectTarget.projectStudent' => function($q) use ($id){
            $q->where('student_id', $id);
        }])->where('grade_id', $student->studentGrade->grade_id)->get();

        $data = [];

        $data = [
            'school' => $school,
            'academic' => $academic->toArray(),
            'headmaster' => $academic->teacher->name,
            'date_report' => Carbon::parse($academic->date_report)->isoFormat('D MMMM Y'),
            'teacher' => $teacherGrade->teacher,
            'student' => $student->toArray(),
            'grade' => $grade->grade->toArray(),
            'project' => $project,
        ];

        $data = $this->reportProject($data);
        return $data;
    }

    public function reportProject($data)
    {
        $templateProcessor = new TemplateProcessor( storage_path('/app/public/templates/project.docx'));
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
        $templateProcessor->setValue('teacher_name',$data['teacher']['name']);

        /* 
        clone project
        */
        $replacements = [];
        $projectNum = 1;
        
        foreach ($data['project'] as $project) {
            $note = ProjectNote::where('project_id', $project->id)->where('student_id', $data['student']['id'])->first();

            $replacements[] = [
                'project_num' => $projectNum,
                'title' => $project->name,
                'description' => $project->description,
                'num' => '${num_'.$project->id.'}',
                'project_note' => $note->note,
            ];

            $projectNum++;
        }

        $templateProcessor->cloneBlock('block_project', 0, true, false, $replacements);

        /*
        clone row berdasarkan project
        */
        foreach ($data['project'] as $project) {
            $targets = [];
            $numRow = 1;
            foreach ($project['projectTarget'] as $item) {
                $score = $item->projectStudent->first()->score;
                $targets[] = [
                    'num_'.$project->id => $numRow,
                    'dimention_desc' => $item->target->dimention->description,
                    'element_desc' => $item->target->element->description,
                    'sub_element_desc' => $item->target->subElement->description,
                    'value_desc' => $item->value->description,
                    'sub_value_desc' => $item->subValue->description,
                    'target_desc' => $item->target->description,
                    'bsb' => ($score == 4) ? '✔' : '',
                    'bsh' => ($score == 3) ? '✔' : '',
                    'mb' => ($score == 2) ? '✔' : '',
                    'bb' => ($score == 1) ? '✔' : '',
                ];
                $numRow++;
            }
            // dd($targets);
            $templateProcessor->cloneRowAndSetValues('num_'.$project->id, $targets);
        }

        $filename = 'Projek '.$data['student']['name'].' - '. $data['academic']['semester'] .'.docx';
        $file_path = storage_path('/app/public/downloads/'.$filename);
        $templateProcessor->saveAs($file_path);
        return response()->download($file_path)->deleteFileAfterSend(true);; // <<< HERE
    }

    public function calculateHalfSemester($id)
    {
        $data = [];
        $school = School::first();

        $academic = AcademicYear::with('teacher')->active()->first();

        $student = Student::find($id);
        
        $grade = StudentGrade::with('grade')->where('student_id', $id)->first();
        $teacherGrade = TeacherGrade::with('teacher')->where('grade_id', $grade->grade_id)->first();

        $attendance = Attendance::where('student_id', $id)->first();
        
        $scores = Student::with([
            'studentGrade.teacherSubject.studentCompetency' => function($q) use ($id){
                $q->where('student_id',$id)->halfSemester();
            }])->find($id);

        $subjects = $scores->studentGrade->teacherSubject;
        $result = [];

        foreach ($subjects as $subject) {
            // buat dulu deskripsinya
            $combinedResultDescription = 'Alhamdulillah ananda ' . Str::of($student->name)->title() . ' telah menguasai materi tentang ';

            // predikat
            $average_scores_predicate = '';
            $average_scores_skill_predicate = '';
            // $valueStore = Valuestore::make(storage_path('app/settings.json'));
            $valueStore = Valuestore::make(storage_path('app/public/settings.json'));
            $predicates = $valueStore->get('predicate');


            $exam = Exam::where('teacher_subject_id',$subject->id)->where('student_id', $id)->first();
            $middle = $exam->score_middle;
            // Pengecekan jika $middle atau $last null
            $middleScore = $middle ? $middle : null;

            $avg_score_student_competencies = $subject->studentCompetency->avg('score');
            $avg_score_student_competencies_skill = $subject->studentCompetency->avg('score_skill');
            $dataScores = $subject->studentCompetency;

            /* 
            HITUNG NILAI RATA-RATA KOMPETENSI, TENGAH SEMESTER DAN AKHIR SEMESTER
            */

            $scores = collect([$avg_score_student_competencies, $middleScore]);
            $scores_skill = collect($avg_score_student_competencies_skill);
            $average_scores = $scores->avg();
            $average_scores_skill = $scores_skill->avg();

            /**
             * predikat
             */
            
             foreach ($predicates as $predicate) {
                // nilai pengetahuan
                if ($average_scores <= $predicate['upper_limit'] && $average_scores > $predicate['lower_limit']) {
                    $average_scores_predicate = $predicate['predicate'];
                }
                // nilai keterampilan
                if ($average_scores_skill <= $predicate['upper_limit'] && $average_scores_skill > $predicate['lower_limit']) {
                    $average_scores_skill_predicate = $predicate['predicate'];
                }
            }

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
                'average_score' => round($average_scores,1),
                'average_scores_predicate' => $average_scores_predicate,
                // 'passed_description' => $lulusDescription,
                // 'not_pass_description' => $tidakLulusDescription,
                'combined_description' => $combinedResultDescription . $subject->subject->name,
                // skill
                'score_competencies_skill' => $avg_score_student_competencies_skill,
                'average_score_skill' => round($average_scores_skill,1),
                'average_scores_skill_predicate' => $average_scores_skill_predicate,
                // 'not_pass_description_skill' => $tidakLulusDescriptionsSkill,
                // 'combined_description_skill' => $combinedResultDescriptionSkill,
                'data_score' => $dataScores,
                'count_competencies' => $subject->studentCompetency->count(),
            ];
        }

        $resultOrder = collect($result)->sortBy('order')->values()->all();
        $resultCollection = collect($result);
        $totalAverageScore = $resultCollection->sum('average_score');
        $totalAverageScoreSkill = $resultCollection->sum('average_score_skill');
        $counting_total = readNumber($totalAverageScore);
        $counting_total_skill = readNumber($totalAverageScoreSkill);

        App::setLocale('id');

        $data = [
            'school' => $school,
            'academic' => $academic->toArray(),
            'headmaster' => $academic->teacher->name,
            'date_report' => Carbon::parse($academic->date_report)->isoFormat('D MMMM Y'),
            'date_report_half' => Carbon::parse($academic->date_report_half)->isoFormat('D MMMM Y'),
            'teacher' => $teacherGrade->teacher,
            'student' => $student->toArray(),
            'grade' => $grade->grade->toArray(),
            'attendance' => $attendance,
            'result' => $resultOrder,
            'total_average_score' => $totalAverageScore,
            'counting_total' => $counting_total,
            'total_average_score_skill' => $totalAverageScoreSkill,
            'counting_total_skill' => $counting_total_skill,
        ];

        // cari jumlah kompetensi terbanyak
        $maxCompetency = collect($result)->max('count_competencies');

        // $data = $this->report($data);
        switch ($teacherGrade->curriculum) {
            case '2013':
                $data = $this->reportHalf2013($data);
                break;
            
            default:
            // return $data;
                return view('report.halfSemester', ['data'=> $data, 'max' => ($maxCompetency) ? $maxCompetency : 1 ]);
                // $data = $this->reportHalf($data);
                break;
        }

        return $data;
    }

    public function reportHalf2013($data)
    {
        $templateProcessor = new TemplateProcessor( storage_path('/app/public/templates/reportHalf2013.docx'));
        $templateProcessor->setValue('school_name',$data['school']['name']);
        $templateProcessor->setValue('school_address',$data['school']['address']);
        $templateProcessor->setValue('headmaster',$data['headmaster']);
        $templateProcessor->setValue('date_report_half',$data['date_report_half']);
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
        $templateProcessor->setValue('teacher_name',$data['teacher']['name']);
        $templateProcessor->setValue('total_average_score',$data['total_average_score']);
        $templateProcessor->setValue('counting_total',$data['counting_total']);
        $templateProcessor->setValue('total_average_score_skill',$data['total_average_score_skill']);
        $templateProcessor->setValue('counting_total_skill',$data['counting_total_skill']);

        // tabel nilai mata pelajaran
        $templateProcessor->cloneRowAndSetValues('order', $data['result']);

        $filename = 'Rapor Tengah Semester '.$data['student']['name'].' - '. str_replace('/', ' ', $data['academic']['year']) . ' '.$data['academic']['semester'] .'.docx';
        $file_path = storage_path('/app/public/downloads/'.$filename);
        $templateProcessor->saveAs($file_path);
        return response()->download($file_path)->deleteFileAfterSend(true);; // <<< HERE
    }

    public function reportHalf($data)
    {
        $templateProcessor = new TemplateProcessor( storage_path('/app/public/templates/reportHalf.docx'));
        $templateProcessor->setValue('school_name',$data['school']['name']);
        $templateProcessor->setValue('school_address',$data['school']['address']);
        $templateProcessor->setValue('headmaster',$data['headmaster']);
        $templateProcessor->setValue('date_report_half',$data['academic']['date_report_half']);
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
        $templateProcessor->setValue('teacher_name',$data['teacher']['name']);

        // count max competency
        $result = $data['result'];
        // Array untuk menyimpan count_competencies dari setiap entri
        $countCompetenciesArray = [];
        // Loop foreach untuk mengakses setiap entri
        foreach ($result as $item) {
            // Menyimpan nilai count_competencies dari setiap entri
            $countCompetenciesArray[] = $item['count_competencies'];
        }
        // Menemukan jumlah count_competencies terbanyak
        $maxCountCompetencies = max($countCompetenciesArray);
        $numCol = 1;
        $numRow = 1;

        $table = new Table(array('borderSize' => 6, 'width' => 'auto', 'unit' => TblWidth::AUTO));
        // table header
        $table->addRow();
        $table->addCell()->addText('No');
        $table->addCell()->addText('Mata Pelajaran');
        for ($i=0; $i < $maxCountCompetencies ; $i++) { 
            $table->addCell()->addText($numCol);
            $numCol++;
        }
        $table->addCell()->addText('STS');
        $table->addCell()->addText('Rerata Nilai');

        // table row
        foreach ($data['result'] as $subject) {
            $table->addRow();
            $table->addCell()->addText($numRow);
            $table->addCell()->addText($subject['subject']);
            
            // ubah data score
            $dataScore = [];
            foreach ($subject['data_score'] as $score) {
                $dataScore[] = $score['score'];
            }

            // iterasi data score
            for ($j=0; $j < $maxCountCompetencies ; $j++) { 
                if(array_key_exists($j, $dataScore)){
                    $table->addCell()->addText($dataScore[$j]);
                } else {
                    $table->addCell()->addText('-');
                }
            }


            // nilai STS
            $table->addCell()->addText($subject['middle_score']);
            $table->addCell()->addText($subject['average_score']);

            $numRow++;
            
        }
        
        $templateProcessor->setComplexBlock('table', $table);

        $filename = 'Rapor Tengah Semester '.$data['student']['name'].' - '. str_replace('/', ' ', $data['academic']['year']) . ' '.$data['academic']['semester'] .'.docx';
        $file_path = storage_path('/app/public/downloads/'.$filename);
        $templateProcessor->saveAs($file_path);
        return response()->download($file_path)->deleteFileAfterSend(true);; // <<< HERE

    }
}
