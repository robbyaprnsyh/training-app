@extends('layout.modal')

@section('title', __('Form Input Bobot Parameter'))

@section('content')
    {{ Form::open(['id' => 'my-form', 'route' => $module . '.store', 'method' => 'post', 'autocomplete' => 'off']) }}
    <div class="modal-body pb-2">

        <div class="mb-2 text-end">
            <button type="button" class="btn btn-warning btn-sm" id="generate-bobot">
                <i class="bx bx-calculator"></i> {{ __('Generate Bobot') }}
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-sm table-bordered text-center">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Parameter') }}</th>
                        <th width="30%">{{ __('Bobot (%)') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($parameters as $param)
                        <tr>
                            <td class="text-start">{{ $param->label }}</td>
                            <td>
                                <input type="number" class="form-control form-control-sm text-end input-bobot"
                                    name="bobot[{{ $param->id }}]" step="0.01" min="0" max="100"
                                    value="{{ old('bobot.' . $param->id, '0.00') }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th class="text-end">{{ __('Total') }}</th>
                        <th><span id="total-bobot">0.00</span> %</th>
                    </tr>
                </tfoot>
            </table>
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
        function hitungTotal() {
            let total = 0;
            $('.input-bobot').each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            $('#total-bobot').text(total.toFixed(2));
            return total;
        }

        $(function() {
            initPage();
            hitungTotal();

            $('.input-bobot').on('input', function() {
                hitungTotal();
            });

            $('#generate-bobot').on('click', function() {
                let count = $('.input-bobot').length;
                let value = (100 / count).toFixed(2);
                $('.input-bobot').val(value);
                hitungTotal();
            });

            $('form#my-form').submit(function(e) {
                e.preventDefault();
                let total = hitungTotal();

                if (parseFloat(total.toFixed(2)) !== 100.00) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Total Bobot Harus 100%',
                        text: 'Mohon sesuaikan bobot hingga total 100%',
                    });
                    return;
                }

                $(this).myAjax({
                    waitMe: '.modal-content',
                    success: function(data) {
                        $('.modal').modal('hide');
                        oTable.reload();
                    }
                }).submit();
            });
        });
    </script>
@endpush
