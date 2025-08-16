@extends('layout.app')

@section('content')
    @include('layout.partials.breadcrumb', compact('breadcrumb'))

    <!-- Filter Card -->
    {{-- <div class="row">
        <div class="col">
            <div class="card card-small mb-1">
                <div class="card-header border-bottom pb-1 pt-2">
                    @include('components.tools-filter', ['table_id' => '#main-table'])
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            {{ Form::open(['id' => 'form-filter', 'autocomplete' => 'off']) }}
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label for="tingkat"
                                                    class="col-sm-3 col-form-label">{{ __('Unit Kerja') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" name="tingkat"
                                                        id="tingkat">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label for="status"
                                                    class="col-sm-3 col-form-label">{{ __('Posisi') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="date" class="form-control" name="tingkat"
                                                        id="tingkat">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100 btn-filter">
                                        <i class="bx bx-filter bx-xs align-middle"></i> Filters
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Table -->
    <div class="row">
        <div class="col">
            <div class="card card-small mb-4">
                <div class="card-body">
                    {{-- <div class="d-flex mb-3">
                        <button type="button" class="btn btn-light border me-2">
                            <i class="bx bx-copy-alt"></i> Duplikat Penilaian
                        </button>
                        <button type="button" class="btn btn-warning text-white">
                            <i class="bx bx-calc"></i> Auto Generate
                        </button>
                    </div> --}}

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-center custom-penilaian-table"
                            style="table-layout: fixed; width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th rowspan="2" style="width: 10%; vertical-align: middle;">No</th>
                                    <th rowspan="2" style="width: 35%; vertical-align: middle;">Parameter Penilaian</th>
                                    <th colspan="{{ $peringkats->count() }}" class="text-center" style="width: 45%;">
                                        Peringkat Risiko</th>
                                    <th rowspan="2" style="width: 10%; vertical-align: middle;">Status</th>
                                </tr>
                                <tr>
                                    @foreach ($peringkats as $p)
                                        <th class="peringkat-header text-center"
                                            style="background-color: {{ $p->color }}; color: #000; width: {{ 45 / $peringkats->count() }}%;">
                                            <div>Peringkat</div>
                                            <div class="angka-peringkat">{{ $p->tingkat }}</div>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($parameters as $i => $param)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td class="text-start">
                                            {{-- @if ($param->penilaian_id == null)
                                                <a href="{{ route($module . '.create', ['parameter_id' => $param->id]) }}"
                                                    class="open-nilai-modal" data-bs-toggle="modal"
                                                    data-bs-target="#modal-lg" data-parameter-id="{{ $param->id }}"
                                                    data-parameter-name="{{ $param->name }}">
                                                    {{ $param->name }}
                                                </a>
                                            @else
                                                <a href="{!! route($module . '.edit', ['penilaian' => encrypt($param->penilaian_id), 'parameter_id' => $param->id]) !!}" class="open-nilai-modal"
                                                    data-bs-toggle="modal" data-bs-target="#modal-lg"
                                                    data-parameter-id="{{ $param->id }}"
                                                    data-parameter-name="{{ $param->name }}">
                                                    {{ $param->name }}
                                                </a>
                                            @endif --}}
                                            @if ($param->penilaian_id == null)
                                                <a href="{{ route($module . '.create', ['parameter_id' => $param->id]) }}"
                                                    class="open-nilai-modal" data-bs-toggle="modal"
                                                    data-bs-target="#modal-lg" data-parameter-id="{{ $param->id }}"
                                                    data-parameter-name="{{ $param->name }}">
                                                    {{ $param->name }}
                                                </a>
                                            @else
                                                @if ($param->penilaian_status == false)
                                                    <a href="{!! route($module . '.edit', ['penilaian' => encrypt($param->penilaian_id), 'parameter_id' => $param->id]) !!}" class="open-nilai-modal"
                                                        data-bs-toggle="modal" data-bs-target="#modal-lg"
                                                        data-parameter-id="{{ $param->id }}"
                                                        data-parameter-name="{{ $param->name }}">
                                                        {{ $param->name }}
                                                    </a>
                                                @else
                                                    <span class="text-dark">{{ $param->name }}</span>
                                                @endif
                                            @endif
                                        </td>
                                        @foreach ($peringkats as $p)
                                            <td
                                                @if ($p->id == $param->peringkat_id) style="background-color: {{ $p->color }}; font-weight: bold; color: #000;" @endif>
                                                {{ $p->id == $param->peringkat_id ? 'v' : '' }}
                                            </td>
                                        @endforeach
                                        {{-- <td>
                                            <span class="badge bg-success-subtle text-success">Aktif</span>
                                        </td> --}}
                                        <td>
                                            @if (!is_null($param->penilaian_status))
                                                @if ($param->penilaian_status == 1)
                                                    <span class="badge bg-success-subtle text-success">SELESAI</span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger">DRAFT</span>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ 3 + $peringkats->count() }}" class="text-center">Data tidak
                                            ditemukan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Header peringkat */
        .custom-penilaian-table th.peringkat-header {
            padding: 6px 0;
            font-size: 12px;
            white-space: normal;
            line-height: 1.3;
        }

        /* Angka peringkat di bawah kata "Peringkat" */
        .custom-penilaian-table th.peringkat-header .angka-peringkat {
            font-size: 16px;
            font-weight: bold;
            display: block;
            margin-top: 2px;
        }

        .custom-penilaian-table td {
            padding: 6px 8px;
            font-size: 13px;
        }

        .custom-penilaian-table td,
        .custom-penilaian-table th {
            text-align: center;
            vertical-align: middle;
        }
    </style>
@endpush

@push('plugin-scripts')
    <script>
        //     $(document).on('click', '.open-nilai-modal', function() {
        //         const paramId = $(this).data('parameter-id');
        //         $.get(`/penilaian/create?parameter_id=${paramId}`, function(html) {
        //             showModal(html);
        //         });
        //     });

        //     function showModal(html) {
        //         $('#dynamic-modal').remove();
        //         $('body').append(`
    //     <div class="modal fade" id="dynamic-modal" tabindex="-1" role="dialog">
    //         ${html}
    //     </div>
    // `);
        //         $('#dynamic-modal').modal('show');
        //     }
        initModalAjax('.open-nilai-modal');
    </script>
@endpush
