@extends('layout.app')

@section('content')
    @include('layout.partials.breadcrumb', compact('breadcrumb'))

    <!-- Table -->
    <div class="row">
        <div class="col">
            <div class="card card-small mb-4">
                {{-- <div class="card-header">
                    <h4 class="m-0">Atur Bobot Parameter</h4>
                </div> --}}
                <div class="card-body">
                    <form id="form-bobot">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle text-center" id="bobot-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Parameter</th>
                                        <th>Bobot</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($parameters as $param)
                                        <tr>
                                            <td>{{ $param->name }}</td>
                                            <td>
                                                <input type="number"
                                                    class="form-control form-control-sm text-end input-bobot"
                                                    name="bobot[{{ $param->id }}]" step="0.01" min="0"
                                                    max="100"
                                                    value="{{ old('bobot.' . $param->id, isset($bobot[$param->id]) ? number_format($bobot[$param->id], 2, '.', '') : '0.00') }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th>Total</th>
                                        <th><span id="total-bobot">0.00</span> %</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="text-end">
                            <button id="generate-bobot" class="btn btn-warning me-2" type="button">
                                <i class="bx bx-calculator"></i> Generate Bobot
                            </button>
                            <button id="simpan-bobot" class="btn btn-primary" type="button">
                                <i class="bx bx-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function hitungTotalBobot() {
            let total = 0;
            $('.input-bobot').each(function() {
                let val = parseFloat($(this).val()) || 0;
                total += val;
            });
            $('#total-bobot').text(total.toFixed());
            return total;
        }

        $(document).ready(function() {
            hitungTotalBobot();

            $('.input-bobot').on('input', function() {
                hitungTotalBobot();
            });

            $('#generate-bobot').on('click', function(e) {
                e.preventDefault();
                let count = $('.input-bobot').length;
                let bobot = (100 / count).toFixed(2);
                $('.input-bobot').val(bobot);
                hitungTotalBobot();
            });

            $('#simpan-bobot').on('click', function(e) {
                e.preventDefault();
                let total = hitungTotalBobot();

                if (parseFloat(total.toFixed(2)) !== 100.00) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Total Bobot Harus 100%',
                        text: 'Silakan sesuaikan nilai bobot.',
                    });
                    return;
                }

                // Validasi nilai bobot harus sama
                let bobotList = [];
                $('.input-bobot').each(function() {
                    bobotList.push(parseFloat($(this).val()) || 0);
                });

                let firstValue = bobotList[0];
                let allEqual = bobotList.every(v => v === firstValue);

                if (!allEqual) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Bobot Harus Sama',
                        text: 'Semua bobot parameter harus memiliki nilai yang sama.',
                    });
                    return;
                }

                $.ajax({
                    url: '{{ route($module . '.store') }}',
                    method: 'POST',
                    data: $('#form-bobot').serialize(),
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: 'Data bobot berhasil disimpan',
                        }).then(() => location.reload());
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat menyimpan.',
                        });
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endpush
