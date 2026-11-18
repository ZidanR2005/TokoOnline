
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Pilih Pengiriman</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #344054;
        }

        .container {
            border: 1px solid #333;
            padding: 12px;
            max-width: 800px;
            margin: 20px auto;
        }

        h2 {
            font-size: 22px;
            margin: 0 0 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
            color: #292d32;
        }

        label {
            display: block;
            font-weight: bold;
            margin: 12px 0 6px;
        }

        select, input, textarea {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            color: #344054;
            background: white;
        }

        select:disabled {
            background: #eee;
            cursor: not-allowed;
        }

        button {
            background: #30343b;
            color: white;
            border: none;
            padding: 12px 16px;
            cursor: pointer;
            font-weight: bold;
        }

        button:disabled {
            opacity: .5;
            cursor: not-allowed;
        }

        .btn-pilih {
            background: #ff5e3a;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 700px;
            border-collapse: collapse;
            margin-top: 25px;
            text-align: left;
        }

        th, td {
            padding: 14px 8px;
            border-bottom: 1px solid #ddd;
        }

        th {
            font-size: 11px;
            white-space: nowrap;
        }

        #pesan {
            margin-top: 15px;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>PILIH PENGIRIMAN</h2>

    <label>Provinsi Tujuan</label>
    <select id="province">
        <option value="">Memuat provinsi...</option>
    </select>

    <label>Kota Tujuan</label>
    <select id="city" disabled>
        <option value="">Pilih Kota</option>
    </select>

    <label>Kecamatan Tujuan</label>
    <select id="district" disabled>
        <option value="">Pilih Kecamatan</option>
    </select>

    <label>Kurir</label>
    <select id="courier">
        <option value="">Pilih Kurir</option>
        <option value="jne">JNE</option>
        <option value="tiki">TIKI</option>
        <option value="pos">POS Indonesia</option>
    </select>

    <label>Alamat</label>
    <textarea id="alamat" rows="2"
        placeholder="Masukkan alamat tujuan"></textarea>

    <label>Kode Pos</label>
    <input type="text" id="kodepos"
        maxlength="5"
        inputmode="numeric"
        placeholder="Masukkan kode pos">

    <br><br>

    <button type="button" id="btnCek" onclick="checkOngkir()">
        CEK ONGKIR
    </button>

    <p id="pesan"></p>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>LAYANAN</th>
                    <th>BIAYA</th>
                    <th>ESTIMASI PENGIRIMAN</th>
                    <th>TOTAL BERAT</th>
                    <th>TOTAL HARGA</th>
                    <th>BAYAR</th>
                </tr>
            </thead>

            <tbody id="result">
                <tr>
                    <td colspan="6">
                        Silakan cek ongkir terlebih dahulu.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<form action="{{ route('order.updateongkir') }}"
      method="POST"
      id="formPengiriman">

    @csrf

    <input type="hidden" name="kurir" id="pilihKurir">
    <input type="hidden" name="layanan_ongkir" id="pilihLayanan">
    <input type="hidden" name="biaya_ongkir" id="pilihBiaya">
    <input type="hidden" name="estimasi_ongkir" id="pilihEstimasi">
    <input type="hidden" name="total_berat" id="pilihBerat">

    <input type="hidden" name="alamat" id="pilihAlamat">
    <input type="hidden" name="pos" id="pilihPos">

    <input type="hidden" name="city_name" id="pilihKota">
    <input type="hidden" name="province_name" id="pilihProvinsi">
    <input type="hidden" name="district_name" id="pilihKecamatan">
    <input type="hidden" name="destination_id" id="pilihTujuan">

</form>

<script>
    const province = document.getElementById('province');
    const city = document.getElementById('city');
    const district = document.getElementById('district');

    const tbody = document.getElementById('result');
    const pesan = document.getElementById('pesan');
    const btnCek = document.getElementById('btnCek');

    // Data pesanan: wajib dikirim oleh OrderController
    const totalBerat = Number(@json((int) ($totalBerat ?? 0)));
    const totalHarga = Number(@json((float) ($totalHarga ?? 0)));

    // URL Laravel
    const urlProvince = @json(route('ongkir.provinces'));
    const urlCity = @json(route('ongkir.cities'));
    const urlDistrict = @json(route('ongkir.districts'));
    const urlCost = @json(route('ongkir.cost'));

    // Format rupiah
    function rupiah(angka) {
        return 'Rp ' + Number(angka).toLocaleString('id-ID');
    }

    // Mengambil nama dan ID dari beberapa format respons API
    function getId(item) {
        return item.id ?? item.province_id ??
            item.city_id ?? item.district_id;
    }

    function getName(item) {
        return item.name ?? item.province ??
            item.city_name ?? item.district_name;
    }

    // Mengambil data provinsi
    async function loadProvince() {
        try {
            const response = await fetch(urlProvince, {
                headers: { 'Accept': 'application/json' }
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Gagal mengambil provinsi');
            }

            province.innerHTML = '<option value="">Pilih Provinsi</option>';

            result.data.forEach(item => {
                const option = document.createElement('option');
                option.value = getId(item);
                option.textContent = getName(item);
                province.appendChild(option);
            });

        } catch (error) {
            console.error(error);
            province.innerHTML =
                '<option value="">Gagal memuat provinsi</option>';
            tampilPesan(error.message, 'error');
        }
    }

    // Mengambil kota berdasarkan provinsi
    province.addEventListener('change', async function () {

        city.innerHTML = '<option value="">Memuat kota...</option>';
        district.innerHTML = '<option value="">Pilih Kecamatan</option>';

        city.disabled = true;
        district.disabled = true;

        if (!this.value) {
            city.innerHTML = '<option value="">Pilih Kota</option>';
            return;
        }

        try {
            const response = await fetch(
                urlCity + '?province_id=' + encodeURIComponent(this.value),
                { headers: { 'Accept': 'application/json' } }
            );

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Gagal mengambil kota');
            }

            city.innerHTML = '<option value="">Pilih Kota</option>';

            result.data.forEach(item => {
                const option = document.createElement('option');
                option.value = getId(item);
                option.textContent = getName(item);
                city.appendChild(option);
            });

            city.disabled = false;

        } catch (error) {
            console.error(error);
            city.innerHTML = '<option value="">Gagal memuat kota</option>';
            tampilPesan(error.message, 'error');
        }
    });

    // Mengambil kecamatan berdasarkan kota
    city.addEventListener('change', async function () {

        district.innerHTML =
            '<option value="">Memuat kecamatan...</option>';

        district.disabled = true;

        if (!this.value) {
            district.innerHTML =
                '<option value="">Pilih Kecamatan</option>';
            return;
        }

        try {
            const response = await fetch(
                urlDistrict + '?city_id=' + encodeURIComponent(this.value),
                { headers: { 'Accept': 'application/json' } }
            );

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Gagal mengambil kecamatan');
            }

            district.innerHTML =
                '<option value="">Pilih Kecamatan</option>';

            result.data.forEach(item => {
                const option = document.createElement('option');
                option.value = getId(item);
                option.textContent = getName(item);
                district.appendChild(option);
            });

            district.disabled = false;

        } catch (error) {
            console.error(error);
            district.innerHTML =
                '<option value="">Gagal memuat kecamatan</option>';
            tampilPesan(error.message, 'error');
        }
    });

    // Menampilkan pesan
    function tampilPesan(teks, jenis = '') {
        pesan.textContent = teks;
        pesan.className = jenis;
    }

    // Menghitung ongkos kirim
    async function checkOngkir() {

        const courier = document.getElementById('courier').value;
        const alamat = document.getElementById('alamat').value.trim();
        const kodepos = document.getElementById('kodepos').value.trim();

        if (!province.value || !city.value || !district.value ||
            !courier || !alamat || !/^\d{5}$/.test(kodepos)) {

            tampilPesan(
                'Lengkapi provinsi, kota, kecamatan, kurir, alamat, dan kode pos 5 digit.',
                'error'
            );
            return;
        }

        if (!Number.isInteger(totalBerat) || totalBerat < 1) {
            tampilPesan('Berat pesanan tidak valid.', 'error');
            return;
        }

        btnCek.disabled = true;
        btnCek.textContent = 'MENGHITUNG...';

        tampilPesan('Sedang menghitung ongkos kirim...');

        tbody.innerHTML = `
            <tr>
                <td colspan="6">Sedang mengambil tarif pengiriman...</td>
            </tr>
        `;

        try {
            const response = await fetch(urlCost, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN':
                        document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    destination: district.value,
                    weight: totalBerat,
                    courier: courier
                })
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Gagal menghitung ongkir');
            }

            tbody.innerHTML = '';

            if (!Array.isArray(result.data) || result.data.length === 0) {
                throw new Error('Tarif pengiriman tidak ditemukan.');
            }

            tampilPesan('Ongkos kirim berhasil dihitung.', 'success');

            result.data.forEach(ongkir => {

                const biaya = Number(ongkir.cost);

                if (!Number.isFinite(biaya) || biaya < 0) {
                    return;
                }

                const estimasi = String(ongkir.etd ?? '-')
                    .replace(/day/gi, 'hari');

                const row = document.createElement('tr');

                const layanan = document.createElement('td');
                layanan.textContent =
                    (ongkir.name ?? courier.toUpperCase()) +
                    ' - ' + (ongkir.service ?? '-');

                const harga = document.createElement('td');
                harga.textContent = rupiah(biaya);

                const estimasiCell = document.createElement('td');
                estimasiCell.textContent = estimasi;

                const berat = document.createElement('td');
                berat.textContent = totalBerat + ' Gram';

                const total = document.createElement('td');
                total.textContent = rupiah(totalHarga);

                const aksi = document.createElement('td');

                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'btn-pilih';
                button.textContent = 'PILIH PENGIRIMAN';

                button.addEventListener('click', function () {
                    pilihPengiriman(
                        ongkir.service,
                        biaya,
                        estimasi
                    );
                });

                aksi.appendChild(button);

                row.append(
                    layanan,
                    harga,
                    estimasiCell,
                    berat,
                    total,
                    aksi
                );

                tbody.appendChild(row);
            });

            if (!tbody.children.length) {
                throw new Error('Data tarif pengiriman tidak valid.');
            }

        } catch (error) {
            console.error(error);

            tbody.innerHTML = `
                <tr>
                    <td colspan="6">Ongkir tidak ditemukan.</td>
                </tr>
            `;

            tampilPesan(error.message, 'error');

        } finally {
            btnCek.disabled = false;
            btnCek.textContent = 'CEK ONGKIR';
        }
    }

    // Memilih pengiriman
    function pilihPengiriman(service, biaya, estimasi) {

        if (!service || !Number.isFinite(biaya)) {
            alert('Data pengiriman tidak valid.');
            return;
        }

        document.getElementById('pilihKurir').value =
            document.getElementById('courier').value;

        document.getElementById('pilihLayanan').value = service;
        document.getElementById('pilihBiaya').value = biaya;
        document.getElementById('pilihEstimasi').value = estimasi;
        document.getElementById('pilihBerat').value = totalBerat;

        document.getElementById('pilihAlamat').value =
            document.getElementById('alamat').value.trim();

        document.getElementById('pilihPos').value =
            document.getElementById('kodepos').value.trim();

        document.getElementById('pilihKota').value =
            city.options[city.selectedIndex].text;

        document.getElementById('pilihProvinsi').value =
            province.options[province.selectedIndex].text;

        document.getElementById('pilihKecamatan').value =
            district.options[district.selectedIndex].text;

        document.getElementById('pilihTujuan').value = district.value;

        if (confirm(
            'Pilih layanan ' + service +
            ' dengan ongkir ' + rupiah(biaya) + '?'
        )) {
            document.getElementById('formPengiriman').submit();
        }
    }

    // Jalankan saat halaman dibuka
    loadProvince();

</script>

</body>
</html>
