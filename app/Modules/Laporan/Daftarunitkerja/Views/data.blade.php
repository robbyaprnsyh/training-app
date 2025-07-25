<link rel="stylesheet" media="print" href="{{ asset('css/print.min.css') }}" />
@if ($type)
    @include('components.css')
@endif
<h5 class="text-center fw-bold">
    <p>LAPORAN DAFTAR UNIT KERJA</p>
</h5>
<table class="table table-bordered">
    <thead>
        <tr>
            <th class="align-middle text-center">No</th>
            <th class="align-middle text-center">Kode</th>
            <th class="align-middle text-center">Nama Unit Kerja</th>
            <th class="align-middle text-center">Tipe Unit Kerja</th>
        </tr>
    </thead>
    <tbody>
        @if ($result['data'])
            @php $i = 1; @endphp
            @foreach ($result['data'] as $key => $value)
                <tr>
                    <td class="align-baseline">{{ $i++ }}</td>
                    <td class="align-baseline">{{ $value->code }}</td>
                    <td class="align-baseline">{{ $value->unit_kerja_name }}</td>
                    <td class="align-baseline">{{ $value->tipe_unit_kerja }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4" class="text-center">Data tidak ditemukan</td>
            </tr>
        @endif
    </tbody>
</table>
