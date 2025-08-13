@extends('layout.modal')

@section('title', __('Form Edit'))

@section('content')
    {{ Form::open(['id' => 'my-form', 'route' => [$module . '.update', encrypt($data->id)], 'method' => 'put', 'autocomplete' => 'off']) }}
    <div class="modal-body pb-2">
        {{-- Hidden --}}
        <input type="hidden" name="parameter_id" value="{{ $data->parameter_id }}">
        <input type="hidden" name="tipe_penilaian" value="{{ $data->parameter->tipe_penilaian }}">

        <div class="form-group row p-0 mb-1">
            <label class="col-sm-3 col-form-label">{{ __('Parameter') }}</label>
            <div class="col-sm-9">
                {{ Form::text('parameter_name', $data->parameter->name ?? '-', ['class' => 'form-control', 'readonly']) }}
            </div>
        </div>

        {{-- Input Nilai untuk KUANTITATIF --}}
        <div class="form-group row p-0 mb-1" @if ($data->parameter->tipe_penilaian !== 'KUANTITATIF') hidden @endif>
            <label class="col-sm-3 col-form-label">{{ __('Nilai') }}</label>
            <div class="col-sm-9">
                <input type="number" name="nilai" value="{{ old('nilai',$data->nilai ?? '') }}" class="form-control"
                    step="any">
            </div>
        </div>

        {{-- Input Peringkat untuk KUALITATIF --}}
        <div class="form-group row p-0 mb-1" @if ($data->parameter->tipe_penilaian !== 'KUALITATIF') hidden @endif>
            <label class="col-sm-3 col-form-label">{{ __('Peringkat') }}</label>
            <div class="col-sm-9">
                @foreach ($peringkat as $p)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="peringkat_id"
                            id="peringkat_{{ $p->id }}" value="{{ $p->id }}"
                            {{ $p->id == old('peringkat_id', $data->peringkat_id) ? 'checked' : '' }}>
                        <label class="form-check-label" for="peringkat_{{ $p->id }}">
                            {{ $p->label }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="form-group row p-0 mb-1">
            <label for="analisa" class="col-sm-3 col-form-label">{{ __('Analisa') }}</label>
            <div class="col-sm-9">
                {{ Form::textarea('analisa', old('analisa', $data->analisa), ['class' => 'form-control', 'rows' => 2]) }}
            </div>
        </div>

        <div class="form-group row p-0 mb-1">
            <label class="col-sm-3 col-form-label">{{ __('Status') }}</label>
            <div class="col-sm-9 d-flex align-items-center">
                <div class="form-check me-3">
                    <input class="form-check-input" type="radio" name="status" id="status_draft" value="0"
                        {{ old('status', $data->status) == 0 ? 'checked' : '' }}>
                    <label class="form-check-label" for="status_draft">
                        Draft
                    </label>
                </div>
                <div class="form-check me-3">
                    <input class="form-check-input" type="radio" name="status" id="status_selesai" value="1"
                        {{ old('status', $data->status) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="status_selesai">
                        Selesai
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Tutup') }}</button>
        <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
    </div>
    {{ Form::close() }}
@endsection

@push('plugin-scripts')
    <script>
        $(function() {
            $('form#my-form').submit(function(e) {
                e.preventDefault();
                $(this).myAjax({
                    waitMe: '.modal-content',
                    success: function(data) {
                        $('.modal').modal('hide');
                        location.reload();
                    }
                }).submit();
            });
        });
    </script>
@endpush
