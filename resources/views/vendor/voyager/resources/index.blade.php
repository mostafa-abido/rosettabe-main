@extends('voyager::master')

@section('content')
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="admin-section-title">
                    <h3><i class="voyager-images"></i>Resources</h3>
                </div>
                <div class="clear"></div>
                <div id="filemanager2">
                    <media-manager-2 />
                </div>
            </div><!-- .row -->
        </div><!-- .col-md-12 -->
    </div><!-- .page-content container-fluid -->
@stop

@section('javascript')
@include('vendor.voyager.resources.manager')
<script>
new Vue({
    el: '#filemanager2'
});
</script>
@endsection
