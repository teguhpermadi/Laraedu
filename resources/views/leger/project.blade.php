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
    <h1>Leger Proyek {{$data->name}}</h1>
    <p>
        Tahun Pelajaran : {{$data->academic->year}} <br>
        Semester : {{$data->academic->semester}} <br>
        Kelas : {{$data->grade->name}} <br>
        Fase : {{$data->grade->fase}} <br>
        Koordinator Proyek : {{$data->teacher->name}} <br>        
    </p>

    <p>
        <b>Deskripsi Proyek: </b> {{$data->description}}
    </p>

    <h4>Cara membaca tabel</h4>
    <p>
        <b>BSB</b> : Berkembang sangat baik <br>
        <b>BSH</b> : Berkembang sesuai harapan <br>
        <b>MB</b> : Mulai berkembang <br>
        <b>BB</b> : Belum berkembang
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
                {{-- note --}}
                <th>Catatan</th>
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
                                    'project_target_id' => $target->id,
                                ])
                        </td>
                    @endforeach
                    {{-- note --}}
                    <td>
                        @livewire('leger.project-note', [
                            'student_id' => $student->student->id,
                            'project_id' => $data->id,
                            ])
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Keterangan Capaian</h3>
    <table>
        <thead>
            <tr>
                <th>Kode Capaian</th>
                <th>Deskripsi Capaian</th>
                <th>Fase</th>
                <th>Sub Elemen</th>
                <th>Elemen</th>
                <th>Dimensi</th>
                <th>Sub Nilai</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data->projectTarget as $target)
                <tr>
                    <td>{{$target->target->code}}</td>
                    <td>{{$target->target->description}}</td>
                    <td>{{$target->target->phase}}</td>
                    <td>{{$target->target->subElement->description}}</td>
                    <td>{{$target->target->element->description}}</td>
                    <td>{{$target->target->dimention->description}}</td>
                    <td>{{$target->subValue->description}}</td>
                    <td>{{$target->value->description}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>