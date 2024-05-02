<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/custom.css')}}">
    <title>COVER RAPOR - {{$data['student']['name']}} | {{$data['academic']['year']}} | {{$data['academic']['semester']}}</title>
</head>
<body>

    {{-- @dump($data) --}}

    <h3 class="text-center mb-5 mt-5">IDENTITAS PESERTA DIDIK</h3>

    <div class="mt-3">
        <div class="col-md-12">
            <div class="float-right">
                <table class="table table-stripped table-sm">
                    <tr>
                        <td>1.</td>
                        <td>Nama</td>
                        <td>:</td>
                        <td>{{$data['student']['name']}}</td>
                    </tr>
                    <tr>
                        <td>2.</td>
                        <td>NISN</td>
                        <td>:</td>
                        <td>{{$data['student']['nisn']}}</td>
                    </tr>
                    <tr>
                        <td>3.</td>
                        <td>NIS</td>
                        <td>:</td>
                        <td>{{$data['student']['nis']}}</td>
                    </tr>
                    <tr>
                        <td>4.</td>
                        <td>Tempat Lahir</td>
                        <td>:</td>
                        <td>{{$data['student']['city_born']}}</td>
                    </tr>
                    <tr>
                        <td>5.</td>
                        <td>Tanggal Lahir</td>
                        <td>:</td>
                        <td>{{Carbon\Carbon::parse($data['student']['birthday'])->isoFormat('D MMMM Y')}}</td>
                    </tr>
                    <tr>
                        <td>6.</td>
                        <td>Jenis Kelamin</td>
                        <td>:</td>
                        <td>{{$data['student']['gender']}}</td>
                    </tr>
                    <tr>
                        <td>7.</td>
                        <td>Agama</td>
                        <td>:</td>
                        <td>{{$data['student']['dataStudent']['religion']}}</td>
                    </tr>
                    <tr>
                        <td>8.</td>
                        <td>Pendidikan Sebelumnya</td>
                        <td>:</td>
                        <td>{{$data['student']['dataStudent']['previous_school']}}</td>
                    </tr>
                    <tr>
                        <td>9.</td>
                        <td>Alamat Peserta Didik</td>
                        <td>:</td>
                        <td>
                            {{$data['student']['dataStudent']['student_address']}}
                            {{$data['student']['dataStudent']['student_village']}}
                            {{$data['student']['dataStudent']['student_district']}}
                            {{$data['student']['dataStudent']['student_city']}}
                            {{$data['student']['dataStudent']['student_province']}}
                        </td>
                    </tr>
    
                    {{-- ayah --}}
                    <tr>
                        <td>10.</td>
                        <td>Identitas Ayah</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Nama</td>
                        <td>:</td>
                        <td>{{$data['student']['dataStudent']['father_name']}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Pendidikan</td>
                        <td>:</td>
                        <td>{{$data['student']['dataStudent']['father_education']}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Pekerjaan</td>
                        <td>:</td>
                        <td>{{$data['student']['dataStudent']['father_occupation']}}</td>
                    </tr>
    
                    {{-- ibu --}}
                    <tr>
                        <td>11.</td>
                        <td>Identitas Ibu</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Nama</td>
                        <td>:</td>
                        <td>{{$data['student']['dataStudent']['mother_name']}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Pendidikan</td>
                        <td>:</td>
                        <td>{{$data['student']['dataStudent']['mother_education']}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Pekerjaan</td>
                        <td>:</td>
                        <td>{{$data['student']['dataStudent']['mother_occupation']}}</td>
                    </tr>
    
                    {{-- alamat orang tua --}}
                    <tr>
                        <td>12.</td>
                        <td>Alamat Orang Tua</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Jalan</td>
                        <td>:</td>
                        <td>{{$data['student']['dataStudent']['parent_address']}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Kelurahan</td>
                        <td>:</td>
                        <td>{{$data['student']['dataStudent']['parent_village']}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Kecamatan</td>
                        <td>:</td>
                        <td>{{$data['student']['dataStudent']['parent_district']}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Kota</td>
                        <td>:</td>
                        <td>{{$data['student']['dataStudent']['parent_city']}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Provinsi</td>
                        <td>:</td>
                        <td>{{$data['student']['dataStudent']['parent_province']}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm">
            <div class="text-center">
                <img src="{{asset('storage/images/bingkai-pas-foto.png')}}" alt="bingkai-pas-foto" style="height: 4cm">
            </div>
        </div>
        <div class="col-sm">
            Malang, {{Carbon\Carbon::createFromFormat('Y-m-d', $data['student']['dataStudent']['date_received'])->locale('id')->translatedFormat('d F Y')}}
            <br>
            Kepala Madrasah
            <br>
            <br>
            <br>            
            <div class="mt-5">
                <p class="fw-bold">{{$data['academic']['teacher']['name']}}</p>
            </div>
        </div>
    </div>
    
</body>
</html>