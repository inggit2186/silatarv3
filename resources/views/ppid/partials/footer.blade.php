<footer class="ppid-footer">
    <div class="ppid-footer-inner">
        <div>
            <div class="ppid-footer-brand">
                <a class="brand-lockup" href="{{ url("/") }}">
                    <span class="brand-mark" aria-hidden="true"><span></span></span>
                    <span class="brand-word"><span>SILATAR</span><span>V2</span></span>
                </a>
            </div>
            <h4 class="ppid-footer-title">PPID Kemenag Tanah Datar</h4>
            <p class="ppid-footer-text">
                Pejabat Pengelola Informasi dan Dokumentasi Kementerian Agama Kabupaten Tanah Datar
            </p>
        </div>
        <div>
            <h4 class="ppid-footer-title">Link Cepat</h4>
            <ul class="ppid-footer-links">
                <li><a href="{{ route('ppid.formulir-permohonan') }}">Ajukan Permohonan</a></li>
                <li><a href="{{ route('ppid.formulir-keberatan') }}">Ajukan Keberatan</a></li>
                <li><a href="{{ route('ppid.pengaduan') }}">Sampaikan Pengaduan</a></li>
            </ul>
        </div>
        <div>
            <h4 class="ppid-footer-title">Kontak</h4>
            <ul class="ppid-footer-links">
                <li>Kantor Kemenag Kab. Tanah Datar</li>
                <li>Jl. Raya Batusangkar No. 1</li>
                <li>Telp: (0752) 12345</li>
                <li>Email: ppid@kemenag-tanahdatar.go.id</li>
            </ul>
        </div>
    </div>
    <div class="ppid-footer-bottom">
        &copy; {{ date('Y') }} SILATAR - Kementerian Agama Kabupaten Tanah Datar
    </div>
</footer>
