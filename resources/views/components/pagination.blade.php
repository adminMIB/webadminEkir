<div class="row mt-2 pagination">
	<div class="col-sm-6"><i>Total Record :</i>{{ $result->total() }}</div>
	<div class="col-sm-6">{{ $result->withQueryString()->links() }}</div>
	<div style="clear:both"></div>
</div>