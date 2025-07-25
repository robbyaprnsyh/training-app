@extends('layout.modal')

@section('title', __('Form Edit Parameter'))

@section('content')

    {{ Form::open(['id' => 'my-form', 'route' => [$module . '.update', encrypt($data->id)], 'method' => 'put', 'autocomplete' => 'off']) }}
    <div class="modal-body pb-2">
        <div class="form-group row p-0 mb-1">
            <label for="code" class="col-sm-3 col-form-label">{{ __('Kode') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="code" id="code" value="{{ $data->code }}" readonly>
            </div>
        </div>

        <div class="form-group row p-0 mb-1">
            <label for="name" class="col-sm-3 col-form-label">{{ __('Nama') }}<sup
                    class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="name" id="name" value="{{ $data->name }}"
                    required>
            </div>
        </div>

        <div class="form-group row p-0 mb-1">
            <label for="tipe_penilaian" class="col-sm-3 col-form-label">{{ __('Tipe Penilaian') }}</label>
            <div class="col-sm-9">
                <select class="form-control" name="tipe_penilaian" id="tipe_penilaian">
                    <option value="" disabled>Pilih</option>
                    <option value="KUANTITATIF" {{ $data->tipe_penilaian == 'KUANTITATIF' ? 'selected' : '' }}>KUANTITATIF
                    </option>
                    <option value="KUALITATIF" {{ $data->tipe_penilaian == 'KUALITATIF' ? 'selected' : '' }}>KUALITATIF
                    </option>
                </select>
            </div>
        </div>

        <div class="form-group row p-0 mb-1">
            <label class="col-sm-3 col-form-label">{{ __('Status') }}</label>
            <div class="col-sm-9">
                <div class="form-check form-switch mb-1 mt-2">
                    <input type="checkbox" name="status" value="1" class="form-check-input"
                        {{ $data->status ? 'checked' : '' }} data-text-on="{{ __('Aktif') }}"
                        data-text-off="{{ __('Tidak Aktif') }}">
                    <label class="custom-control-label text-muted" for="status"></label>
                </div>
            </div>
        </div>

        {{-- Form Dinamis Berdasarkan Tipe Penilaian --}}
        <div id="dynamic-form-penilaian" class="mt-3">
            @if ($data->tipe_penilaian == 'KUANTITATIF')
                <button type="button" class="btn btn-sm btn-success mt-2 mb-2" id="add-range">
                    <i class="bi bi-plus-lg"></i> Tambah Range
                </button>
                @foreach ($data->rangeKuantitatif as $i => $range)
                    <div class="border rounded p-2 mb-2 range-kuantitatif">
                        <div class="text-end mb-1">
                            <button type="button" class="btn btn-sm btn-danger remove-range"><i
                                    class="bi bi-trash"></i></button>
                        </div>
                        <input type="hidden" name="range_kuantitatif[{{ $i }}][id]"
                            value="{{ $range->id }}">
                        <div class="form-group row p-0 mb-1">
                            <label class="col-sm-3 col-form-label">{{ __('Peringkat') }}</label>
                            <div class="col-sm-9">
                                <select name="range_kuantitatif[{{ $i }}][peringkat_id]" class="form-control">
                                    <option value="" disabled selected>Pilih Peringkat</option>
                                    @foreach ($peringkat as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $range->peringkat_id == $item->id ? 'selected' : '' }}>
                                            {{ $item->label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row p-0 mb-1">
                            <label class="col-sm-3 col-form-label">{{ __('Min Range') }}</label>
                            <div class="col-md-3">
                                <select name="range_kuantitatif[{{ $i }}][operator_min]" class="form-control">
                                    <option value=">" {{ $range->operator_min == '>' ? 'selected' : '' }}>&gt;
                                    </option>
                                    <option value=">=" {{ $range->operator_min == '>=' ? 'selected' : '' }}>&ge;
                                    </option>
                                    <option value="=" {{ $range->operator_min == '=' ? 'selected' : '' }}>=</option>
                                    <option value="<" {{ $range->operator_min == '<' ? 'selected' : '' }}>&lt;
                                    </option>
                                    <option value="<=" {{ $range->operator_min == '<=' ? 'selected' : '' }}>&le;
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="0.01" class="form-control"
                                    name="range_kuantitatif[{{ $i }}][nilai_min]"
                                    value="{{ $range->nilai_min }}">
                            </div>
                        </div>

                        <div class="form-group row p-0 mb-1">
                            <label class="col-sm-3 col-form-label">{{ __('Max Range') }}</label>
                            <div class="col-md-3">
                                <select name="range_kuantitatif[{{ $i }}][operator_max]" class="form-control">
                                    <option value=">" {{ $range->operator_max == '>' ? 'selected' : '' }}>&gt;
                                    </option>
                                    <option value=">=" {{ $range->operator_max == '>=' ? 'selected' : '' }}>&ge;
                                    </option>
                                    <option value="=" {{ $range->operator_max == '=' ? 'selected' : '' }}>=</option>
                                    <option value="<" {{ $range->operator_max == '<' ? 'selected' : '' }}>&lt;
                                    </option>
                                    <option value="<=" {{ $range->operator_max == '<=' ? 'selected' : '' }}>&le;
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="0.01" class="form-control"
                                    name="range_kuantitatif[{{ $i }}][nilai_max]"
                                    value="{{ $range->nilai_max }}">
                            </div>
                        </div>
                    </div>
                @endforeach
            @elseif ($data->tipe_penilaian == 'KUALITATIF')
                <button type="button" class="btn btn-sm btn-success mt-2 mb-2" id="add-kualitatif">
                    <i class="bi bi-plus-lg"></i> Tambah Analisa
                </button>
                @foreach ($data->pilihanKualitatif as $i => $range)
                    <div class="border rounded p-2 mb-2 pilihan-kualitatif">
                        <div class="text-end mb-1">
                            <button type="button" class="btn btn-sm btn-danger remove-kualitatif"><i
                                    class="bi bi-trash"></i></button>
                        </div>
                        <input type="hidden" name="pilihan_kualitatif[{{ $i }}][id]"
                            value="{{ $range->id }}">
                        <div class="row mb-2">
                            <div class="col-md-8">
                                {{-- <textarea class="form-control analisa" name="pilihan_kualitatif[{{ $i }}][analisa_default]"
                                    id="analisa-{{ $i }}">{{ $range->analisa }}</textarea> --}}
                                <textarea class="form-control analisa" name="pilihan_kualitatif[{{ $i }}][analisa_default]"
                                    id="analisa-{{ $i }}">{!! $range->analisa_default !!}</textarea>
                            </div>
                            <div class="col-md-4">
                                <select name="pilihan_kualitatif[{{ $i }}][peringkat_id]"
                                    class="form-control">
                                    <option value="">Pilih Peringkat</option>
                                    @foreach ($peringkat as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $range->peringkat_id == $item->id ? 'selected' : '' }}>
                                            {{ $item->label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Tutup') }}</button>
        <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
    </div>
    {!! Form::close() !!}

@endsection

@push('plugin-scripts')
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.3/tinymce.min.js" referrerpolicy="origin"></script>

    <script type="text/javascript">
        $(function() {
            initPage();

            let index = {{ count($data->rangeKuantitatif ?? []) }};
            let kualitatifIndex = {{ count($data->pilihanKualitatif ?? []) }};

            // function initTinyMCE(selector) {
            //     setTimeout(() => {
            //         tinymce.init({
            //             selector: selector,
            //             menubar: false,
            //             toolbar: 'undo redo | bold italic underline | bullist numlist | removeformat',
            //             height: 150,
            //             branding: false,
            //             statusbar: false,
            //             setup: function(editor) {
            //                 editor.on('init', function() {
            //                     editor.getContainer().style.transition = 'none';
            //                 });
            //             }
            //         });
            //     }, 100);
            // }

            function initTinyMCE(selector, content = '') {
                if (tinymce.get(selector.replace('#', ''))) {
                    tinymce.get(selector.replace('#', '')).remove();
                }
                setTimeout(() => {
                    tinymce.init({
                        selector: selector,
                        menubar: false,
                        plugins: 'lists',
                        toolbar: 'undo redo | bold italic underline | bullist numlist | alignleft aligncenter alignright',
                        height: 150,
                        branding: false,
                        statusbar: false,
                        setup: function(editor) {
                            editor.on('init', function() {
                                setTimeout(() => {
                                    editor.setContent(content);
                                }, 100); // Delay isi konten setelah init
                            });
                        }
                    });
                }, 300); // Delay agar dipastikan textarea sudah muncul
            }

            function generateRangeKuantitatifForm(i) {
                return `
            <div class="border rounded p-2 mb-2 range-kuantitatif">
                <div class="text-end mb-1">
                    <button type="button" class="btn btn-sm btn-danger remove-range"><i class="bi bi-trash"></i></button>
                </div>
                <input type="hidden" name="range_kuantitatif[${i}][id]" value="">
                <div class="form-group row p-0 mb-1">
                    <label class="col-sm-3 col-form-label">Peringkat</label>
                    <div class="col-sm-9">
                        <select name="range_kuantitatif[${i}][peringkat_id]" class="form-control" required>
                            <option value="">Pilih Peringkat</option>
                            @foreach ($peringkat as $item)
                                <option value="{{ $item->id }}">{{ $item->label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row p-0 mb-1">
                    <label class="col-sm-3 col-form-label">Min Range</label>
                    <div class="col-md-3">
                        <select name="range_kuantitatif[${i}][operator_min]" class="form-control" required>
                            <option value="">Pilih</option>
                            <option value=">">&gt;</option>
                            <option value=">=">&ge;</option>
                            <option value="=">=</option>
                            <option value="<">&lt;</option>
                            <option value="<=">&le;</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" step="0.01" class="form-control" name="range_kuantitatif[${i}][nilai_min]" placeholder="Nilai Min">
                    </div>
                </div>
                <div class="form-group row p-0 mb-1">
                    <label class="col-sm-3 col-form-label">Max Range</label>
                    <div class="col-md-3">
                        <select name="range_kuantitatif[${i}][operator_max]" class="form-control" required>
                            <option value="">Pilih</option>
                            <option value=">">&gt;</option>
                            <option value=">=">&ge;</option>
                            <option value="=">=</option>
                            <option value="<">&lt;</option>
                            <option value="<=">&le;</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" step="0.01" class="form-control" name="range_kuantitatif[${i}][nilai_max]" placeholder="Nilai Max">
                    </div>
                </div>
            </div>`;
            }

            function generateRangeKualitatifForm(i) {
                const textareaId = `analisa-${i}`;
                return `
            <div class="border rounded p-2 mb-2 pilihan-kualitatif">
                <div class="text-end mb-1">
                    <button type="button" class="btn btn-sm btn-danger remove-kualitatif"><i class="bi bi-trash"></i></button>
                </div>
                <input type="hidden" name="pilihan_kualitatif[${i}][id]" value="">
                <div class="row mb-2">
                    <div class="col-md-8">
                        <textarea class="form-control analisa" name="pilihan_kualitatif[${i}][analisa_default]" id="${textareaId}" placeholder="Analisa / Jawaban"></textarea>
                    </div>
                    <div class="col-md-4">
                        <select name="pilihan_kualitatif[${i}][peringkat_id]" class="form-control" required>
                            <option value="">Pilih Peringkat</option>
                            @foreach ($peringkat as $item)
                                <option value="{{ $item->id }}">{{ $item->label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>`;
            }

            $('#add-range').click(function() {
                $('#dynamic-form-penilaian').append(generateRangeKuantitatifForm(index));
                index++;
            });

            $('#add-kualitatif').click(function() {
                $('#dynamic-form-penilaian').append(generateRangeKualitatifForm(kualitatifIndex));
                initTinyMCE(`#analisa-${kualitatifIndex}`);
                kualitatifIndex++;
            });

            $(document).on('click', '.remove-range', function() {
                $(this).closest('.range-kuantitatif').remove();
            });

            $(document).on('click', '.remove-kualitatif', function() {
                const textarea = $(this).closest('.pilihan-kualitatif').find('textarea');
                const id = textarea.attr('id');
                if (tinymce.get(id)) {
                    tinymce.get(id).remove();
                }
                $(this).closest('.pilihan-kualitatif').remove();
            });

            // Inisialisasi TinyMCE untuk yang sudah ada
            // @if ($data->tipe_penilaian == 'KUALITATIF')
            //     @foreach ($data->pilihanKualitatif as $i => $range)
            //         initTinyMCE('#analisa-{{ $i }}');
            //     @endforeach
            // @endif

            @if ($data->tipe_penilaian == 'KUALITATIF')
                @foreach ($data->pilihanKualitatif as $i => $range)
                    initTinyMCE('#analisa-{{ $i }}', {!! json_encode($range->analisa_default) !!});
                @endforeach
            @endif

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
        });
    </script>
@endpush
