<h6><b>KETIDAKHADIRAN</b></h6>
<div class="w-50 col-md-6">
    <table class="table table-bordered table-sm" >
        <thead class="text-center fw-bold">
            <tr>
                <td>No</td>
                <td>Ketidakhadiran</td>
                <td>Hari</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Sakit</td>
                <td class="text-center">{{$data['attendance']['sick']}}</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Izin</td>
                <td class="text-center">{{$data['attendance']['permission']}}</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Tanpa Keterangan</td>
                <td class="text-center">{{$data['attendance']['absent']}}</td>
            </tr>
        </tbody>
    </table>
</div>