@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Hasilkan Pautan Pendaftaran</div>

                <div class="card-body">
                    <form id="generateLinkForm">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label for="masjid_id">Pilih Masjid</label>
                            <select name="masjid_id" id="masjid_id" class="form-control" required>
                                <option value="">-- Pilih Masjid --</option>
                                @foreach($masjids as $masjid)
                                    <option value="{{ $masjid->id }}">{{ $masjid->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <h5 class="mt-4">Maklumat Akaun (Pra-isi)</h5>
                        <hr>

                        <div class="form-group mb-3">
                            <label for="nama">Nama Penuh</label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama">
                        </div>

                        <div class="form-group mb-3">
                            <label for="ic_number">No. Kad Pengenalan</label>
                            <input type="text" class="form-control" id="ic_number" name="ic_number" placeholder="Contoh: 990101-10-1234">
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">Emel</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="nama@email.com">
                        </div>

                        <div class="form-group mb-3">
                            <label for="telefon_bimbit">No. Telefon</label>
                            <input type="text" class="form-control" id="telefon_bimbit" name="telefon_bimbit" placeholder="012-3456789">
                        </div>

                        <div class="form-group mb-3">
                            <label for="tarikh_lahir">Tarikh Lahir</label>
                            <input type="date" class="form-control" id="tarikh_lahir" name="tarikh_lahir">
                        </div>

                        <div class="form-group mb-3">
                            <label for="jantina">Jantina</label>
                            <select class="form-control" id="jantina" name="jantina">
                                <option value="">-- Pilih Jantina --</option>
                                <option value="Lelaki">Lelaki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Hasilkan Pautan
                        </button>
                    </form>

                    <div id="linkResult" class="mt-4" style="display: none;">
                        <div class="alert alert-success">
                            <strong>Pautan Pendaftaran:</strong>
                            <div class="input-group mt-2">
                                <input type="text" class="form-control" id="shareableLink" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="copyLink()">Salin</button>
                                </div>
                            </div>
                            <small class="text-muted">Kongsi pautan ini dengan Ahli Khairat yang ingin mendaftar.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('generateLinkForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    fetch('{{ route("public.daftar.generate-link") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            masjid_id: document.getElementById('masjid_id').value,
            nama: document.getElementById('nama').value,
            ic_number: document.getElementById('ic_number').value,
            email: document.getElementById('email').value,
            telefon_bimbit: document.getElementById('telefon_bimbit').value,
            tarikh_lahir: document.getElementById('tarikh_lahir').value,
            jantina: document.getElementById('jantina').value
        })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('shareableLink').value = data.shareable_link;
        document.getElementById('linkResult').style.display = 'block';
    })
    .catch(error => console.error('Error:', error));
});

function copyLink() {
    var copyText = document.getElementById('shareableLink');
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    document.execCommand('copy');
    alert('Pautan telah disalin!');
}
</script>
@endsection