<table>
    <thead>
        <tr>
            <th colspan="{{ count($jenis) + 17 }}" style="text-align: center; font-size: 18pt; font-weight: bold;">
                REKAP TUKIN - {{ $date }}
            </th>
        </tr>
        <tr>
            <th colspan="{{ count($jenis) + 17 }}" style="text-align: center; font-size: 11pt;">
                Periode: {{ $date }}
            </th>
        </tr>
        <tr></tr>
        <!-- OLD DATA HEADER (PUSAKA) -->
        <tr style="background-color: #4472C4; color: white; font-weight: bold;">
            <th rowspan="2" style="border: 1px solid black; text-align: center; vertical-align: middle;">No</th>
            <th rowspan="2" style="border: 1px solid black; text-align: center; vertical-align: middle;">NIP</th>
            <th rowspan="2" style="border: 1px solid black; text-align: center; vertical-align: middle;">Nama</th>
            <th rowspan="2" style="border: 1px solid black; text-align: center; vertical-align: middle;">Unit Kerja</th>
            <th rowspan="2" style="border: 1px solid black; text-align: center; vertical-align: middle;">Status</th>
            <th rowspan="2" style="border: 1px solid black; text-align: center; vertical-align: middle;">Gol</th>
            <th rowspan="2" style="border: 1px solid black; text-align: center; vertical-align: middle;">Grade</th>
            <th colspan="8" style="border: 1px solid black; text-align: center; vertical-align: middle;">DATA LAMA (PUSAKA)</th>
            <th colspan="{{ count($jenis) }}" style="border: 1px solid black; text-align: center; vertical-align: middle;">PERHITUNGAN BARU</th>
            <th colspan="4" style="border: 1px solid black; text-align: center; vertical-align: middle;">HASIL AKHIR</th>
        </tr>
        <tr style="background-color: #4472C4; color: white; font-weight: bold;">
            <!-- OLD DATA COLUMNS -->
            <th style="border: 1px solid black; text-align: center; vertical-align: middle; font-size: 10px;">Tukin</th>
            <th style="border: 1px solid black; text-align: center; vertical-align: middle; font-size: 10px;">TK Jml</th>
            <th style="border: 1px solid black; text-align: center; vertical-align: middle; font-size: 10px;">TL</th>
            <th style="border: 1px solid black; text-align: center; vertical-align: middle; font-size: 10px;">PSW</th>
            <th style="border: 1px solid black; text-align: center; vertical-align: middle; font-size: 10px;">Hukdis</th>
            <th style="border: 1px solid black; text-align: center; vertical-align: middle; font-size: 10px;">CPNS</th>
            <th style="border: 1px solid black; text-align: center; vertical-align: middle; font-size: 10px;">Total Pot</th>
            <th style="border: 1px solid black; text-align: center; vertical-align: middle; font-size: 10px;">Tukin Final</th>
            <!-- NEW CALCULATION COLUMNS -->
            @foreach($jenis as $j)
                <th style="border: 1px solid black; text-align: center; vertical-align: middle; font-size: 10px;">{{ $j->jenis }}</th>
            @endforeach
            <!-- RESULT COLUMNS -->
            <th style="border: 1px solid black; text-align: center; vertical-align: middle; font-size: 10px;">Total Potongan</th>
            <th style="border: 1px solid black; text-align: center; vertical-align: middle; font-size: 10px;">Tukin Final</th>
            <th style="border: 1px solid black; text-align: center; vertical-align: middle; font-size: 10px;">Nett Lama</th>
            <th style="border: 1px solid black; text-align: center; vertical-align: middle; font-size: 10px;">Nett Baru</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @foreach($asn as $a)
            @if(isset($a->tukin_final))
                @php
                    // Check if any ketidakhadiran has libur dates
                    $hasLibur = false;
                    $detailCalc = $a->detail_potongan_calc;
                    // Decode JSON if it's a string
                    if (is_string($detailCalc)) {
                        $detailCalc = json_decode($detailCalc, true) ?? [];
                    }
                    if (!empty($detailCalc)) {
                        // Check if any absence falls on a holiday
                        foreach ($detailCalc as $key => $jml) {
                            if ($jml > 0 && in_array($key, ['libur', 'cuti'])) {
                                $hasLibur = true;
                                break;
                            }
                        }
                    }
                @endphp
                <tr>
                    <td style="border: 1px solid black; text-align: center;">{{ $no++ }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $a->nomor_induk }}</td>
                    <td style="border: 1px solid black; text-align: left;">{{ $a->name }}</td>
                    <td style="border: 1px solid black; text-align: left;">{{ $a->dept->nama ?? '-' }}</td>
                    <td style="border: 1px solid black; text-align: center; {{ $a->asn_status === 'CPNS (80%)' ? 'background-color: #FFE4B5;' : '' }}">{{ $a->asn_status ?? '-' }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $a->gol ?? '-' }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $a->grade ?? '-' }}</td>
                    <!-- OLD DATA (PUSAKA) -->
                    <td style="border: 1px solid black; text-align: right; mso-number-format:'\#,\#\#0'; background-color: #F0F8FF;">{{ number_format($a->tukin_lama ?? 0, 0, ',', '.') }}</td>
                    <td style="border: 1px solid black; text-align: right; mso-number-format:'\#,\#\#0'; background-color: #F0F8FF;">{{ number_format($a->tk_jumlah_lama ?? 0, 0, ',', '.') }}</td>
                    <td style="border: 1px solid black; text-align: right; mso-number-format:'\#,\#\#0'; background-color: #F0F8FF;">{{ number_format($a->tl_lama ?? 0, 0, ',', '.') }}</td>
                    <td style="border: 1px solid black; text-align: right; mso-number-format:'\#,\#\#0'; background-color: #F0F8FF;">{{ number_format($a->psw_lama ?? 0, 0, ',', '.') }}</td>
                    <td style="border: 1px solid black; text-align: right; mso-number-format:'\#,\#\#0'; background-color: #F0F8FF;">{{ number_format($a->hukdis_lama ?? 0, 0, ',', '.') }}</td>
                    <td style="border: 1px solid black; text-align: right; mso-number-format:'\#,\#\#0'; background-color: #F0F8FF;">{{ number_format($a->cpns_lama ?? 0, 0, ',', '.') }}</td>
                    <td style="border: 1px solid black; text-align: right; mso-number-format:'\#,\#\#0'; background-color: #F0F8FF; font-weight: bold;">{{ number_format($a->total_potongan_lama ?? 0, 0, ',', '.') }}</td>
                    <td style="border: 1px solid black; text-align: right; mso-number-format:'\#,\#\#0'; background-color: #F0F8FF; font-weight: bold;">{{ number_format($a->nett_lama ?? 0, 0, ',', '.') }}</td>
                    <!-- NEW CALCULATION (from presensi) -->
                    @foreach($jenis as $j)
                        @php
                            $detail = $a->detail_potongan_calc ?? [];
                            // Decode JSON if it's a string
                            if (is_string($detail)) {
                                $detail = json_decode($detail, true) ?? [];
                            }
                            $key = strtolower(str_replace(' ', '_', $j->jenis));
                            $jml = $detail[$key] ?? 0;
                            // Check if this is a libur/cuti type
                            $isLiburType = in_array(strtolower($j->jenis), ['libur', 'cuti', 'hari raya', 'hari libur']);
                        @endphp
                        <td style="border: 1px solid black; text-align: center; {{ $isLiburType && $jml > 0 ? 'background-color: #FFCCCC;' : '' }}">{{ $jml }}</td>
                    @endforeach
                    <!-- RESULT COLUMNS -->
                    <td style="border: 1px solid black; text-align: right; mso-number-format:'\#,\#\#0'; background-color: #FFE4E1; font-weight: bold;">{{ number_format($a->total_potongan_final ?? 0, 0, ',', '.') }}</td>
                    <td style="border: 1px solid black; text-align: right; mso-number-format:'\#,\#\#0'; background-color: #FFFACD; font-weight: bold;">{{ number_format($a->tukin_final ?? 0, 0, ',', '.') }}</td>
                    <td style="border: 1px solid black; text-align: right; mso-number-format:'\#,\#\#0'; background-color: #F0F8FF; font-weight: bold;">{{ number_format($a->nett_lama ?? 0, 0, ',', '.') }}</td>
                    <td style="border: 1px solid black; text-align: right; mso-number-format:'\#,\#\#0'; background-color: #E8FFE8; font-weight: bold;">{{ number_format($a->nett ?? 0, 0, ',', '.') }}</td>
                </tr>
            @endif
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="{{ count($jenis) + 17 }}" style="border: 1px solid black; padding: 10px; font-size: 11px; color: #666;">
                <strong>Keterangan:</strong>
                <span style="background-color: #FFCCCC; padding: 2px 6px; margin-left: 10px; border: 1px solid #CC0000;">&nbsp;</span> = Hari Libur / Cuti Bersama
                <span style="margin-left: 20px;">|</span>
                <span style="margin-left: 20px;"><strong>Catatan:</strong> Hari libur dan cuti bersama dihitung sebagai ketidakhadiran (tidak masuk kerja)</span>
            </td>
        </tr>
    </tfoot>
</table>
