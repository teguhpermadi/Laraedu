<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Leger Kelas</title>
</head>
<link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>

<style>
    body {
        font-family: 'Poppins';
    }
    table, th, td {
      border: 1px solid black;
      border-collapse: collapse;
    }
    th, td {
        padding: 5px;
    }
    .center {
        text-align: center;
    }
    .text-sm {
        font-size: 9pt;
    }
    @media print {
        .pagebreak { page-break-before: always; } /* page-break-after works, as well */
    }
    </style>
<body>
    <h1>LEGER KELAS</h1>
    <p>
        Tahun Pelajaran : {{$grade->academic->year}} Semester {{$grade->academic->semester}} <br>
        Kelas : {{$grade->grade->name}} <br>
        Wali kelas {{$grade->teacher->name}} <br>
        Tanggal cetak leger: {{now()}} 
    </p>
    <table>
        <thead>
            <tr>
                <th>NIS</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                {{-- subject --}}
                @foreach ($subjects as $subject)
                    <th>{{$subject->subject->code}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{-- student --}}
            @foreach ($grade->grade->studentGrade as $student)
                <tr>
                    <td>{{$student->student->nis}}</td>
                    <td>{{$student->student->name}}</td>
                    <td>{{$student->student->gender}}</td>
                    {{-- subject --}}
                    @foreach ($subjects as $subject)
                        {{-- <td>{{$subject->id}}</td> --}}
                        <td>
                            @livewire('leger.na-score', [
                                'student_id' => $student->student->id,
                                'teacher_subject_id' => $subject->id,
                            ])
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagebreak"> </div>
    @if ($grade->grade->teacherGrade->curriculum == '2013')
    <h1>LEGER KELAS</h1>
    <p>
        Tahun Pelajaran : {{$grade->academic->year}} Semester {{$grade->academic->semester}} <br>
        Kelas : {{$grade->grade->name}} <br>
        Wali kelas {{$grade->teacher->name}} <br>
        Tanggal cetak leger: {{now()}} 
    </p>    
    <h2>Nilai keterampilan</h2>
    <table>
        <thead>
            <tr>
                <th>NIS</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                {{-- subject --}}
                @foreach ($subjects as $subject)
                    <th>{{$subject->subject->code}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{-- student --}}
            @foreach ($grade->grade->studentGrade as $student)
                <tr>
                    <td>{{$student->student->nis}}</td>
                    <td>{{$student->student->name}}</td>
                    <td>{{$student->student->gender}}</td>
                    {{-- subject --}}
                    @foreach ($subjects as $subject)
                        {{-- <td>{{$subject->id}}</td> --}}
                        <td>
                            @livewire('leger.na-score', [
                                'student_id' => $student->student->id,
                                'teacher_subject_id' => $subject->id,
                                'column' => 'score_skill'
                            ])
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    @endif
</body>
</html>