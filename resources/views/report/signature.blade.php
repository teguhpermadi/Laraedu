<table class="table table-borderless table-sm">
    <tr>
        <td>
            Mengetahui,
        </td>
        <td></td>
        <td>
            Malang, {{Carbon\Carbon::parse($data['academic']['date_report_half'])->isoFormat('D MMMM Y')}}
        </td>
    </tr>
    <tr>
        <td>Kepala Madrasah</td>
        <td>Wali Kelas {{$data['grade']['grade']}}</td>
        <td>Orang Tua</td>
    </tr>
    <tr>
        <td><div class="m-5"></div></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td><b>{{$data['headmaster']}}</b></td>
        <td><b>{{$data['teacher']['name']}}</b></td>
        <td>..................................................</td>
    </tr>
</table>