<table style="border:solid 1px #ccc">
    <thead>
	<tr>
        <th colspan="8" style="text-align:left;font-weight:bold;text-align:center"><h1>{{ strtoupper($title) }}</h1></th>
    </tr>
	<tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Nama</th>
        <th>Saran/Pengaduan</th>
        <th>Judul</th>
        <th>Isi&nbsp;Saran/Pengaduan</th>
        <th>No&nbsp;Handphone</th>
        <th>Email</th>
    </tr>
    </thead>
    <tbody>
    @foreach($Data as $key=>$data)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ date("Y-m-d H:i:s", strtotime($data->created_at)) }}</td>
            <td>{!! str_replace(' ', '&nbsp;', $data->nama) !!}</td>
            <td>{{ strtoupper($data->code_sp)=='S' ? 'Saran' : 'Pengaduan' }}</td>
            <td>{{ $data->subject }}</td>
            <td>{{ $data->content }}</td>
            <td>{{ $data->phone }}&nbsp;</td>
            <td>{{ $data->email }}</td>
        </tr>
    @endforeach
    </tbody>
</table>