@extends('layout.modal')

@section('title', __('Form Edit'))

@section('content')

    {{ Form::open(['id' => 'my-form', 'route' => [$module . '.update', encrypt($data->id)], 'method' => 'put', 'autocomplete' => 'off']) }}
    <div class="modal-body pb-2">
        <div class="form-group row p-0 mb-1">
            <label for="label" class="col-sm-3 col-form-label">{{ __('Label') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="label" id="label" value="{{ $data->label }}">
            </div>
        </div>

        <div class="form-group row p-0 mb-1">
            <label for="tingkat" class="col-sm-3 col-form-label">{{ __('Tingkat') }}<sup
                    class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <input type="number" class="form-control" name="tingkat" id="tingkat" value="{{ $data->tingkat }}">
            </div>
        </div>

        <div class="form-group row p-0 mb-1">
            <label for="color" class="col-sm-3 col-form-label">{{ __('Color') }}<sup
                    class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <input type="color" class="form-control w-50" name="color" id="color" value="{{ $data->color }}">
            </div>
        </div>

        <div class="form-group row p-0 mb-1">
            <label class="col-sm-3 col-form-label">{{ __('Status') }}</label>
            <div class="col-sm-9">
                <div class="form-check form-switch mb-1 mt-2">
                    <input type="checkbox" id="" name="status" value="1" class="form-check-input"
                        checked="checked" data-text-on="{{ __('Aktif') }}" data-text-off="{{ __('Tidak Aktif') }}">
                    <label class="custom-control-label text-muted" for="status"></label>
                </div>
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
        $(function() {
            initPage();

            $('form#my-form').submit(function(e) {
                e.preventDefault();
                $(this).myAjax({
                    waitMe: '.modal-content',
                    success: function(data) {
                        $('.modal').modal('hide');
                        oTable.reload();
                    }
                }).submit();
            });
        })
    </script>
@endpush
