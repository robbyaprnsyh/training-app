@extends('layout.modal')

@section('title', __('Form Import Jabatan'))

@section('content')
    {{ Form::open(['id' => 'my-form', 'route' => $module . '.import', 'method' => 'post', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data']) }}
    <div class="modal-body pb-2">
        <div class="form-group row p-0">
            <label for="files" class="col-sm-3 col-form-label">{{ __('File Excel') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <input class="form-control" name="files" type="file" accept=".xlsx">
                <div id="error_files"></div>
                <a href="{{ route($module . '.download') }}" target="_blank" class="text-primary">
                    {{ __('Download Template') }}
                </a>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Tutup') }}</button>
        <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
    </div>
    {!! Form::close() !!}
@endsection

@push('plugin-scripts')
    <script type="text/javascript">
        $(function () {
            initPage();

            $('form#my-form').submit(function (e) {
                e.preventDefault();
                $(this).myAjax({
                    waitMe: '.modal-content',
                    success: function (data) {
                        $('.modal').modal('hide');
                        oTable.reload();
                    }
                }).submit();
            });
        });
    </script>
@endpush
