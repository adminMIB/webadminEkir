<!-- Modal template -->
<div class="modal modal-slide fade modal-submit" id="modals-submit-pengajuan">
    <div class="modal-dialog">
        <form class="modal-content" method="post" action="{{ route('store_booking_pengujian') }}">
            <input type="hidden" name="booking_id" value="{{ isset($data->booking_id) ? $data->booking_id : '' }}" />
            <input type="hidden" name="kendaraan_id" value="{{ isset($kendaraan->kendaraan_id) ? $kendaraan->kendaraan_id : '' }}" />

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-body">
                @if (Session::has('error_other'))
                <p class="alert alert-warning">{!! Session::get('error_other') !!}</p>
                @else
                @csrf
                <small class="text-left text-muted">Tanggal Uji</small>
                <input type="text" name="tgl_uji" readonly required class="form-control datepicker" />
                <br><br><br><br>
                
                <button type="button" class="btn btn-success btn-block btn-submit-pengajuan">Submit Pengujian</button>
                @endif
                <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Batal</button>
            </div>
        </form>        
    </div>
</div>