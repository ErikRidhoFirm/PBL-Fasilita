<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header bg-warning">
      <h5 class="modal-title">Edit Penilaian Alternatif</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <form id="form-edit" method="POST" action="{{ route('spk.update', $laporan->id_laporan_fasilitas) }}">
      @csrf @method('PUT')
      <div class="modal-body">
        <p><strong>Fasilitas:</strong> {{ $laporan->fasilitas->nama_fasilitas }}</p>
        <p><strong>Pelapor:</strong> {{ $laporan->laporan->pengguna->nama }}</p>
        <p><strong>Deskripsi:</strong> {{ $laporan->deskripsi }}</p>
        <p><strong>Lokasi:</strong>
          {{ $laporan->laporan->gedung->nama_gedung }} /
           {{ $laporan->laporan->lantai->nomor_lantai }} /
          {{ $laporan->laporan->ruangan->nama_ruangan }}
        </p>


        <table class="table">
          <thead>
            <tr>
              <th>Kriteria</th>
              <th>Parameter</th>
            </tr>
          </thead>
          <tbody>
            @foreach($kriterias as $k)
              @php
                $selected = $laporan->penilaian
                          ->first()?->skorKriteriaLaporan
                          ->firstWhere('id_kriteria', $k->id_kriteria)?->nilai_mentah;
              @endphp
              <tr>
                <td>{{ $k->kode_kriteria }} - {{ $k->nama_kriteria }}</td>
                <td>
                  <select name="nilai[{{ $k->id_kriteria }}]" class="form-select">
                    <option value="">-- Pilih --</option>
                    @foreach($k->skoringKriterias as $s)
                      <option value="{{ $s->nilai_referensi }}"
                        {{ $selected == $s->nilai_referensi ? 'selected' : '' }}>
                        {{ $s->parameter }} ({{ $s->nilai_referensi }})
                      </option>
                    @endforeach
                  </select>
                  <small id="error-nilai.{{ $k->id_kriteria }}" class="error-text form-text text-danger"></small>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
