<table style="border:solid 1px #ccc">
    <thead>
	<tr>
        <th colspan="17" style="text-align:left;font-weight:bold;"><h1>{{ strtoupper($title) }}</h1></th>
    </tr>
	<tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>No&nbsp;Uji</th>
        <th>Nama&nbsp;Pelanggan</th>
        <th>Jenis&nbsp;Uji</th>
        <th>Status&nbsp;Booking</th>
        <th>Tgl&nbsp;Uji</th>
        <th>Lokasi&nbsp;Uji</th>
        
        <th>ID&nbsp;Billing</th>
        <th>Pembayaran</th>
        <th>Retribusi(Rp)</th>
        <th>Kartu&nbsp;Hilang(Rp)</th>
        <th>Denda(Rp)</th>
        <th>Total&nbsp;Tagihan(Rp)</th>
        
        <th>No&nbsp;Kendaraan</th>
        <th>Nama&nbsp;Pemilik</th>
        <th>Alamat&nbsp;Pemilik</th>
    </tr>
    </thead>
    <tbody>
    @foreach($Data as $key=>$data)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ date("Y-m-d H:i:s", strtotime($data->created_at)) }}</td>
            <td>{{ $data->no_uji }}</td>
            <td>{{ isset($data->member->nama) ? $data->member->nama : '' }}</td>
            <td>{{ $data->jenis_pengujian }}</td>
            <td>{{ isset($data->stsBooking->status_keterangan) ? $data->stsBooking->status_keterangan : '' }}</td>
            <td>{{ date("Y-m-d", strtotime($data->tgl_uji)) }}</td>
            <td>{{ $data->lokasi_pengujian }}</td>

            <td>{{ strval($data->billing_id) }}&nbsp;</td>
            <td>{{ isset($data->statusBayar->flag_keterangan) ? $data->statusBayar->flag_keterangan : '' }}</td>
            <td>{{ $data->retribusi_rp }}</td>
            <td>{{ $data->kartu_hilang_rp }}</td>
            <td>{{ $data->denda_rp }}</td>
            <td>{{ $data->total_tagihan_rp }}</td>

            <td>{{ $data->no_kendaraan }}</td>
            <td>{{ $data->nama }}</td>
            <td>{{ $data->alamat }}</td>

        </tr>
    @endforeach
    </tbody>
</table>