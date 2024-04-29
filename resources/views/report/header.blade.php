<div class="col-md-12">
    <table class="table table-borderless table-sm">
        <head>
            <tr>
                <td>Nama Lengkap</td>
                <td>:</td>
                <td>{{$data['student']['name']}}</td>
                <td></td>
                <td>Sekolah</td>
                <td>:</td>
                <td>{{Str::upper($data['school']['name'])}}</td>
            </tr>
            <tr>
                <td>NISN</td>
                <td>:</td>
                <td>{{$data['student']['nisn']}}</td>
                <td></td>
                <td>Tahun Pelajaran - Semester</td>
                <td>:</td>
                <td>{{$data['academic']['year']}} - {{$data['academic']['semester']}}</td>
            </tr>
            <tr>
                <td>NIS</td>
                <td>:</td>
                <td>{{$data['student']['nis']}}</td>
                <td></td>
                <td>Kelas</td>
                <td>:</td>
                <td>{{$data['grade']['name']}}</td>
            </tr>
        </head>
    </table>

    <hr>
</div>