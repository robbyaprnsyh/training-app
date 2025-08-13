@extends('layout.modal')

@section('title', __('Form Tambah Parameter'))

@section('content')
    {{ Form::open(['id' => 'my-form', 'route' => $module . '.store', 'method' => 'post', 'autocomplete' => 'off']) }}
    <div class="modal-body pb-2">
        <div class="form-group row p-0 mb-1">
            <label for="code" class="col-sm-3 col-form-label">{{ __('Code') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="code" id="code" required>
            </div>
        </div>

        <div class="form-group row p-0 mb-1">
            <label for="name" class="col-sm-3 col-form-label">{{ __('Nama') }}<sup
                    class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="name" id="name" required>
            </div>
        </div>

        <div class="form-group row p-0 mb-1">
            <label for="tipe_penilaian" class="col-sm-3 col-form-label">{{ __('Tipe Penilaian') }}</label>
            <div class="col-sm-9">
                <select class="form-control" name="tipe_penilaian" id="tipe_penilaian" required>
                    <option value="" disabled selected>Pilih</option>
                    <option value="KUANTITATIF">KUANTITATIF</option>
                    <option value="KUALITATIF">KUALITATIF</option>
                </select>
            </div>
        </div>

        <div class="form-group row p-0 mb-1">
            <label class="col-sm-3 col-form-label">{{ __('Status') }}</label>
            <div class="col-sm-9">
                <div class="form-check form-switch mb-1 mt-2">
                    <input type="checkbox" name="status" value="1" class="form-check-input" checked="checked"
                        data-text-on="{{ __('Aktif') }}" data-text-off="{{ __('Tidak Aktif') }}">
                    <label class="custom-control-label text-muted" for="status"></label>
                </div>
            </div>
        </div>

        {{-- Form Dinamis Berdasarkan Tipe Penilaian --}}
        <div id="dynamic-form-penilaian" class="mt-3"></div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Tutup') }}</button>
        <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
    </div>
    {!! Form::close() !!}
@endsection

{{-- @push('plugin-scripts')
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.3/tinymce.min.js" referrerpolicy="origin"></script>

    <script type="text/javascript">
        $(function() {
            initPage();
            let index = 1;
            let kualitatifIndex = 1;

            function initTinyMCE(selector) {
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
                                editor.getContainer().style.transition = 'none';
                            });
                        }
                    });
                }, 100);
            }

            function generateRangeKuantitatifForm(i) {
                return `
                <div class="border rounded p-2 mb-2 range-kuantitatif" data-index="${i}">
                    <div class="text-end mb-1">
                        <button type="button" class="btn btn-sm btn-danger remove-range"><i class="bi bi-trash"></i></button>
                    </div>
                    <div class="form-group row p-0 mb-1">
                        <label class="col-sm-3 col-form-label">{{ __('Peringkat') }}</label>
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
                        <label class="col-sm-3 col-form-label">{{ __('Min Range') }}</label>
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
                        <label class="col-sm-3 col-form-label">{{ __('Max Range') }}</label>
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
                </div>
                `;
            }

            function generateRangeKualitatifForm(i) {
                const textareaId = `analisa-${i}`;
                return `
                <div class="border rounded p-2 mb-2 range-kualitatif" data-index="${i}">
                    <div class="text-end mb-1">
                        <button type="button" class="btn btn-sm btn-danger remove-kualitatif"><i class="bi bi-trash"></i></button>
                    </div>
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

            $('#tipe_penilaian').on('change', function() {
                let tipe = $(this).val();
                let container = $('#dynamic-form-penilaian');
                container.html('');

                if (tipe === 'KUANTITATIF') {
                    container.append(`
                        <div id="wrapper-kuantitatif">
                            <button type="button" class="btn btn-sm btn-success mb-2" id="add-range">
                                <i class="bi bi-plus-lg"></i> Tambah Range
                            </button>
                            <div id="list-range-kuantitatif">
                                ${generateRangeKuantitatifForm(0)}
                            </div>
                        </div>
                    `);
                    index = 1;
                    $('#add-range').click(function() {
                        $('#list-range-kuantitatif').append(generateRangeKuantitatifForm(index));
                        index++;
                    });
                } else if (tipe === 'KUALITATIF') {
                    container.append(`
                        <div id="wrapper-kualitatif">
                            <button type="button" class="btn btn-sm btn-success mb-2" id="add-kualitatif"><i class="bi bi-plus-lg"></i> Tambah Analisa</button>
                            <div id="list-range-kualitatif">
                                ${generateRangeKualitatifForm(0)}
                            </div>
                        </div>
                    `);
                    initTinyMCE('#analisa-0');
                    kualitatifIndex = 1;
                    $('#add-kualitatif').click( function() {
                        const html = generateRangeKualitatifForm(kualitatifIndex);
                        $('#list-range-kualitatif').append(html);
                        initTinyMCE(`#analisa-${kualitatifIndex}`);
                        kualitatifIndex++;
                    });
                }
            });

            $(document).on('click', '.remove-range', function() {
                $(this).closest('.range-kuantitatif').remove();
            });

            $(document).on('click', '.remove-kualitatif', function() {
                // Ambil ID textarea yang ingin dihapus dan hapus instance TinyMCE-nya
                const parent = $(this).closest('.range-kualitatif');
                const textarea = parent.find('textarea');
                const id = textarea.attr('id');

                if (tinymce.get(id)) {
                    tinymce.get(id).remove(); // destroy TinyMCE instance
                }

                parent.remove(); // hapus elemen dari DOM
            });

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
@endpush --}}

@push('plugin-scripts')
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.3/tinymce.min.js" referrerpolicy="origin"></script>

    <script type="text/javascript">
        $(function() {
            initPage();
            let index = 1;
            let kualitatifIndex = 1;

            function initTinyMCE(selector) {
                setTimeout(() => {
                    tinymce.init({
                        selector: selector,
                        menubar: false,
                        plugins: 'lists',
                        toolbar: 'undo redo | bold italic underline | bullist numlist | alignleft aligncenter alignright',
                        valid_elements: '*[*]',
                        height: 150,
                        branding: false,
                        statusbar: false,
                        setup: function(editor) {
                            editor.on('init', function() {
                                editor.getContainer().style.transition = 'none';
                            });
                        }
                    });
                }, 100);
            }

            function filterPeringkatOptions(containerSelector) {
                let selectedValues = [];
                $(`${containerSelector} select[name*="[peringkat_id]"]`).each(function() {
                    let val = $(this).val();
                    if (val) selectedValues.push(val);
                });

                $(`${containerSelector} select[name*="[peringkat_id]"] option`).show();
                $(`${containerSelector} select[name*="[peringkat_id]"]`).each(function() {
                    let currentSelect = $(this);
                    selectedValues.forEach(function(val) {
                        if (currentSelect.val() != val) {
                            currentSelect.find(`option[value="${val}"]`).hide();
                        }
                    });
                });
            }

            function toggleAddButton(containerSelector, buttonSelector) {
                let totalForms = $(
                    `${containerSelector} .range-kuantitatif, ${containerSelector} .range-kualitatif`).length;
                if (totalForms >= 5) {
                    $(buttonSelector).hide();
                } else {
                    $(buttonSelector).show();
                }
            }

            function generateRangeKuantitatifForm(i) {
                return `
                <div class="border rounded p-2 mb-2 range-kuantitatif" data-index="${i}">
                    <div class="text-end mb-1">
                        <button type="button" class="btn btn-sm btn-danger remove-range"><i class="bi bi-trash"></i></button>
                    </div>
                    <div class="form-group row p-0 mb-1">
                        <label class="col-sm-3 col-form-label">{{ __('Peringkat') }}</label>
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
                        <label class="col-sm-3 col-form-label">{{ __('Min Range') }}</label>
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
                        <label class="col-sm-3 col-form-label">{{ __('Max Range') }}</label>
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
                </div>
                `;
            }

            function generateRangeKualitatifForm(i) {
                const textareaId = `analisa-${i}`;
                return `
                <div class="border rounded p-2 mb-2 range-kualitatif" data-index="${i}">
                    <div class="text-end mb-1">
                        <button type="button" class="btn btn-sm btn-danger remove-kualitatif"><i class="bi bi-trash"></i></button>
                    </div>
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

            $('#tipe_penilaian').on('change', function() {
                let tipe = $(this).val();
                let container = $('#dynamic-form-penilaian');
                container.html('');

                if (tipe === 'KUANTITATIF') {
                    container.append(`
                        <div id="wrapper-kuantitatif">
                            <button type="button" class="btn btn-sm btn-success mb-2" id="add-range">
                                <i class="bi bi-plus-lg"></i> Tambah Range
                            </button>
                            <div id="list-range-kuantitatif">
                                ${generateRangeKuantitatifForm(0)}
                            </div>
                        </div>
                    `);
                    filterPeringkatOptions('#list-range-kuantitatif');
                    toggleAddButton('#list-range-kuantitatif', '#add-range');
                    index = 1;

                    $('#add-range').click(function() {
                        $('#list-range-kuantitatif').append(generateRangeKuantitatifForm(index));
                        index++;
                        filterPeringkatOptions('#list-range-kuantitatif');
                        toggleAddButton('#list-range-kuantitatif', '#add-range');
                    });
                } else if (tipe === 'KUALITATIF') {
                    container.append(`
                        <div id="wrapper-kualitatif">
                            <button type="button" class="btn btn-sm btn-success mb-2" id="add-kualitatif"><i class="bi bi-plus-lg"></i> Tambah Analisa</button>
                            <div id="list-range-kualitatif">
                                ${generateRangeKualitatifForm(0)}
                            </div>
                        </div>
                    `);
                    initTinyMCE('#analisa-0');
                    filterPeringkatOptions('#list-range-kualitatif');
                    toggleAddButton('#list-range-kualitatif', '#add-kualitatif');
                    kualitatifIndex = 1;

                    $('#add-kualitatif').click(function() {
                        const html = generateRangeKualitatifForm(kualitatifIndex);
                        $('#list-range-kualitatif').append(html);
                        initTinyMCE(`#analisa-${kualitatifIndex}`);
                        kualitatifIndex++;
                        filterPeringkatOptions('#list-range-kualitatif');
                        toggleAddButton('#list-range-kualitatif', '#add-kualitatif');
                    });
                }
            });

            // Filter & toggle setelah change dropdown peringkat
            $(document).on('change', 'select[name*="[peringkat_id]"]', function() {
                filterPeringkatOptions('#list-range-kuantitatif');
                filterPeringkatOptions('#list-range-kualitatif');
                toggleAddButton('#list-range-kuantitatif', '#add-range');
                toggleAddButton('#list-range-kualitatif', '#add-kualitatif');
            });

            $(document).on('click', '.remove-range', function() {
                $(this).closest('.range-kuantitatif').remove();
                filterPeringkatOptions('#list-range-kuantitatif');
                toggleAddButton('#list-range-kuantitatif', '#add-range');
            });

            $(document).on('click', '.remove-kualitatif', function() {
                const parent = $(this).closest('.range-kualitatif');
                const textarea = parent.find('textarea');
                const id = textarea.attr('id');
                if (tinymce.get(id)) tinymce.get(id).remove();
                parent.remove();
                filterPeringkatOptions('#list-range-kualitatif');
                toggleAddButton('#list-range-kualitatif', '#add-kualitatif');
            });

            // $('form#my-form').submit(function(e) {
            //     e.preventDefault();
            //     tinymce.triggerSave();
            //     $(this).myAjax({
            //         waitMe: '.modal-content',
            //         success: function(data) {
            //             $('.modal').modal('hide');
            //             oTable.reload();
            //         }
            //     }).submit();
            // });

            $('form#my-form').submit(function(e) {
                e.preventDefault();
                tinymce.triggerSave();

                // Hitung jumlah form dinamis
                const jumlahKuantitatif = $('.range-kuantitatif').length;
                const jumlahKualitatif = $('.range-kualitatif').length;

                if (jumlahKuantitatif < 5 && jumlahKualitatif < 5) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Jumlah Form Kurang',
                        text: 'Minimal harus ada 5 form!',
                    });
                    return;
                }

                if (jumlahKuantitatif > 0 && jumlahKuantitatif < 5) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Form Kuantitatif Belum Lengkap',
                        text: 'Form kuantitatif harus berjumlah tepat 5!',
                    });
                    return;
                }

                if (jumlahKualitatif > 0 && jumlahKualitatif < 5) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Form Kualitatif Belum Lengkap',
                        text: 'Form kualitatif harus berjumlah tepat 5!',
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
