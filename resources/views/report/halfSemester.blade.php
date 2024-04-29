<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/custom.css')}}">
    <title>LAPORAN HASIL BELAJAR TENGAH SEMESTER - {{$data['student']['name']}} | {{$data['academic']['year']}} | {{$data['academic']['semester']}}</title>
</head>
<body>

    {{-- @dump($data) --}}
    @include('report.header')
    

    <h3 class="text-center"><b>LAPORAN HASIL BELAJAR TENGAH SEMESTER</b></h3>

    <div class="col-md-12">
        <table class="table table-bordered table-sm">
            <thead class="text-center text-bold align-middle fw-bold">
                <tr>
                    <td rowspan="2">No</td>
                    <td rowspan="2">Mata Pelajaran</td>
                    <td rowspan="2">KKTP</td>
                    <td colspan="{{$max}}">Rincian Nilai Sumatif</td>
                    <td rowspan="2">STS</td>
                    <td rowspan="2">Rerata</td>
                </tr>
                <tr>
                    @for ($i = 1; $i <= $max; $i++)
                        <td>{{ $i }}</td>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @php
                    $no=1;
                @endphp
                @foreach ($data['result'] as $subject)
                    <tr>
                        <td>{{$no++}}</td>
                        <td>{{$subject['subject']}}</td>
                        <td class="text-center">70</td>
                        {{-- ubah data score --}}
                        @php
                            $scores = [];
                            foreach ($subject['data_score'] as $score) {
                                $scores[] = $score['score'];
                            }
                        @endphp

                        {{-- iterasi nilai sumatif --}}
                        @for ($i = 0; $i < $max; $i++)
                            <td class="text-center">
                                @if (array_key_exists($i, $scores))
                                        {{$scores[$i]}}
                                @else
                                    -
                                @endif

                            </td>
                        @endfor
                        <td class="text-center">{{$subject['middle_score']}}</td>
                        <td class="text-center">{{$subject['average_score']}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('report.attendance')
    <br>
    <div class="row">
        <div class="justify-content-center">
            @include('report.signature')
        </div>
    </div>

    <div class="footer">
        <hr>
        <p>Laporan Hasil Belajar Tengah Semester - {{$data['student']['name']}} | {{$data['academic']['year']}} | {{$data['academic']['semester']}}</p>
    </div>


    <script type="text/javascript">
        window.print();
    </script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> --}}
</body>
</html>