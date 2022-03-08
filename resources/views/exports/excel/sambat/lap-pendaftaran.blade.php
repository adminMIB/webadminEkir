<table style="border:solid 1px #ccc">
    <thead>
	<tr>
        <th colspan="11" style="text-align:left;font-weight:bold;text-align:center"><h1>{{ strtoupper($title) }}</h1></th>
    </tr>
	<tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>No&nbsp;Polisi</th>
        <th>No&nbsp;Identitas</th>
        <th>No&nbsp;Rangka</th>
        <th>Kode&nbsp;Bayar</th>
        <th>Jatuh&nbsp;Tempo</th>
        <th>Jumlah&nbsp;(Rp)</th>
        <th>Keterangan</th>
        <th>Device</th>
        <th>Remote Address</th>
    </tr>
    </thead>
    <tbody>
    @foreach($Data as $key=>$data)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ date("Y-m-d H:i:s", strtotime($data->created_at)) }}</td>
            <td>{{ $data->no_polisi }}</td>
            <td>{{ strval($data->no_identitas) }}&nbsp;</td>
            <td>{{ $data->no_rangka }}&nbsp;</td>
            <td>{{ $data->kd_bayar }}&nbsp;</td>
            <td>{{ $data->jatuh_tempo }}</td>
            <td>{{ $data->jumlah }}</td>
            <td>{{ $data->keterangan }}</td>
            <td>{{ $data->device }}</td>
            <td>{{ $data->remote_address }}</td>
        </tr>
    @endforeach
    </tbody>
</table>