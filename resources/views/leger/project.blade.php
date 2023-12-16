<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Leger Project</title>
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
    </style>
<body>
    <h1>Leger Proyek</h1>
    <p>
        Tahun Pelajaran : {{$data->academic->year}} <br>
        Semester : {{$data->academic->semester}} <br>
        Kelas : {{$data->grade->name}} <br>
        Fase : {{$data->grade->fase}} <br>
        Koordinator Proyek : {{$data->teacher->name}} <br>
        <b>Nama Proyek : {{$data->name}}</b><br>
        Deskripsi : {{$data->description}} <br>
    </p>

    <table>
        <thead>
            <tr>
                <th>NIS</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                {{-- target --}}
                @foreach ($data->projectTarget as $target)
                    <th>{{$target->target->code}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($data->grade->studentGrade as $student)
                <tr>
                    <td>{{$student->student->nis}}</td>
                    <td>{{$student->student->name}}</td>
                    <td>{{$student->student->gender}}</td>
                    {{-- target --}}
                    @foreach ($data->projectTarget as $target)
                        <td>
                            @livewire('leger.score-project', [
                                    'student_id' => $student->student->id,
                                    'project_target_id' => $target->target->id,
                                ])
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Keterangan Capaian</h3>
</body>
</html>