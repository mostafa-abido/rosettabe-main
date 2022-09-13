@extends('voyager::master')

@section('content')
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="admin-section-title">
                    <h3><i class="voyager-images"></i>Attachments</h3>
                </div>
                <div class="clear"></div>
                <div id="filemanager3">
                    <media-manager-3 />
                </div>
            </div><!-- .row -->
        </div><!-- .col-md-12 -->
    </div><!-- .page-content container-fluid -->
@stop

@section('javascript')
@include('vendor.voyager.attachments.manager')
<script>
new Vue({
    el: '#filemanager3'
});
</script>
@endsection
