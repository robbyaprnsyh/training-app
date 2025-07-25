@extends('layout.app')
@push('plugin-styles')
<link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
@include('layout.partials.breadcrumb',compact('breadcrumb'))
<!-- Default Light Table -->
<div class="row">
    <div class="col">
        <div class="card card-small mb-1">
            <div class="card-header border-bottom py-1">
                @include('components.tools-filter-with-export', ['table_id' => '#main-table' ])
            </div>
            <div class="card-body pb-1 pt-2">
                <div class="row">
                    <div class="col">
                        {{ Form::open(['url' => route('laporan.laporan.daftarunitkerja.data'),'method'=>'GET','id' => 'form-filter', 'autocomplete' => 'off']) }}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row mb-0">
                                        <label for="tipe_unit_kerja_id" class="col-sm-3 col-form-label">{{ __('Tipe Unit Kerja') }}</label>
                                        <div class="col-sm-9">
                                           {!! Form::select('tipe_unit_kerja_id', $options_tipe_unit_kerja, null, ['class' => 'form-control select2 filter-data']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="card card-small mb-4">
            <div class="card-body table-responsive" id="results">
                
            </div>
        </div>
    </div>
</div>
<!-- End Default Light Table -->

@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script>
    $('.datepicker').datepicker({
            changeYear: true,
            format: 'MM yyyy',
            minViewMode: "months",
            endDate: new Date("{{ date('Y-m-t', strtotime(date('Y-m-01') . '-1 months')) }}"),
            autoclose: true,
            orientation: 'bottom',
        });
    

    $('.filter-data', $('#form-filter')).change(function (event) {
        event.preventDefault();
        let elform = $('#form-filter');
        elform.find('input[name="type"]').remove()
        $.ajax({
                waitMe: '',
                url: elform.prop('action'),
                data: elform.serialize(),
                type:elform.prop('method'),
                beforeSend: function(){
                    $('#results').html(loading)
                },
                success: function (data) {
                    $('#results').html(data)
                }
            });
    });

    $('.export-excel, .export-pdf').on('click', function(e){
        let elform   = $('#form-filter');
        let elexport = $(this).attr('id');
        let type     = $("<input>").attr("type", "hidden").attr("name", "type").val(elexport);
        
        elform.find('input[name="type"]').remove()
        elform.append(type)
        elform.attr({'target':'_blank','method':'GET'}).submit()
    });

    $('.select2-kri').select2({
        width: '100%',
        theme: "bootstrap-5",
        escapeMarkup: function(markup) {
            return markup;
        },
    });

    $(function(){
        initPage();
        $('select[name="tipe_unit_kerja_id"]', $('#form-filter')).change()
    })
</script>
@endpush
