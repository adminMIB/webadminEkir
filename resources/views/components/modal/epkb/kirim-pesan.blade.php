<!-- Modal template -->
<div class="modal modal-slide fade" id="modals-kirim-pesan">
    <div class="modal-dialog">
        <form class="modal-content" method="post" action="{{ route('update_booking_pengujian') }}">
            @csrf
            <input type="hidden" name="booking_id" value="{{ isset($data->booking_id) ? $data->booking_id : '' }}" />

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-body">
                @if (Session::has('error_other'))
                <p class="alert alert-warning">{!! Session::get('error_other') !!}</p>
                @else
                <textarea name="pesan" class="form-control" required placeholder="Tuliskan pesan disini..."></textarea>
                <br><br><br><br>
                
                <button type="button" class="btn btn-primary btn-block btn-kirim-notifikasi">Kirim Notifikasi Pesan</button>
                @endif
                <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>