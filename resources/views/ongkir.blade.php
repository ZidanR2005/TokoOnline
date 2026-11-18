
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cek Ongkos Kirim</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }

        .container {
            max-width: 500px;
            margin: auto;
        }

        select, input, button {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            box-sizing: border-box;
        }

        button {
            background: #00a445;
            color: white;
            border: none;
            cursor: pointer;
        }

        #result {
            margin-top: 20px;
        }

        .ongkir-item {
            border: 1px solid #ddd;
            padding: 12px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Cek Ongkos Kirim</h2>

    <form id="ongkirForm">

        <label>Provinsi Tujuan</label>
        <select id="province" required>
            <option value="">Pilih Provinsi</option>
        </select>

        <label>Kota Tujuan</label>
        <select id="city" required disabled>
            <option value="">Pilih Kota</option>
        </select>

        <label>Kecamatan Tujuan</label>
        <select id="district" name="destination" required disabled>
            <option value="">Pilih Kecamatan</option>
        </select>

        <label>Berat Barang (gram)</label>
        <input type="number" id="weight" min="1" placeholder="Contoh: 1000" required>

        <label>Kurir</label>
        <select id="courier" required>
            <option value="">Pilih Kurir</option>
            <option value="jne">JNE</option>
            <option value="tiki">TIKI</option>
            <option value="pos">POS Indonesia</option>
        </select>

        <button type="submit" id="btnCek">Cek Ongkir</button>

    </form>

    <div id="result"></div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const province = document.getElementById('province');
    const city = document.getElementById('city');
    const district = document.getElementById('district');
    const result = document.getElementById('result');
    const form = document.getElementById('ongkirForm');

    // Mengambil data provinsi
    fetch('/provinces')
        .then(response => response.json())
        .then(data => {
            data.forEach(item => {
                let option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                province.appendChild(option);
            });
        })
        .catch(error => {
            console.error(error);
            result.textContent = 'Gagal mengambil data provinsi.';
        });

    // Mengambil kota berdasarkan provinsi
    province.addEventListener('change', function () {

        city.innerHTML = '<option value="">Pilih Kota</option>';
        district.innerHTML = '<option value="">Pilih Kecamatan</option>';

        city.disabled = true;
        district.disabled = true;

        if (!this.value) return;

        fetch('/cities?province_id=' + this.value)
            .then(response => response.json())
            .then(data => {
                data.forEach(item => {
                    let option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name;
                    city.appendChild(option);
                });

                city.disabled = false;
            })
            .catch(error => {
                console.error(error);
                result.textContent = 'Gagal mengambil data kota.';
            });
    });

    // Mengambil kecamatan berdasarkan kota
    city.addEventListener('change', function () {

        district.innerHTML = '<option value="">Pilih Kecamatan</option>';
        district.disabled = true;

        if (!this.value) return;

        fetch('/districts?city_id=' + this.value)
            .then(response => response.json())
            .then(data => {
                data.forEach(item => {
                    let option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name;
                    district.appendChild(option);
                });

                district.disabled = false;
            })
            .catch(error => {
                console.error(error);
                result.textContent = 'Gagal mengambil data kecamatan.';
            });
    });

    // Menghitung ongkos kirim
    form.addEventListener('submit', function (event) {

        event.preventDefault();

        const weight = document.getElementById('weight').value;
        const courier = document.getElementById('courier').value;

        result.innerHTML = 'Sedang menghitung ongkos kirim...';

        fetch('/cost', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({
        destination: document.getElementById('district').value,
        weight: document.getElementById('weight').value,
        courier: document.getElementById('courier').value
    })
})
.then(async response => {
    const data = await response.json();

    console.log('Respons API:', data);

    if (!response.ok) {
        throw new Error(JSON.stringify(data, null, 2));
    }

    return data;
})
.then(data => {
    const services = data.result?.data ?? data.data ?? [];

    if (!Array.isArray(services) || services.length === 0) {
        result.innerHTML = `
            <h3>Hasil Ongkos Kirim</h3>
            <p>${data.result?.meta?.message ?? data.meta?.message ?? 'Tidak ada layanan ongkir ditemukan.'}</p>
            <pre>${JSON.stringify(data, null, 2)}</pre>
        `;
        return;
    }

    result.innerHTML = `
        <h3>Hasil Ongkos Kirim</h3>
        ${services.map(item => `
            <div style="padding:10px; border-bottom:1px solid #ddd;">
                <strong>${item.name ?? item.code ?? ''} - ${item.service}</strong>
                <p>${item.description ?? ''}</p>
                <p>Ongkir: Rp ${Number(item.cost).toLocaleString('id-ID')}</p>
                <p>Estimasi: ${item.etd ?? '-'}</p>
            </div>
        `).join('')}
    `;
})
.catch(error => {
    console.error('Detail error:', error);
    result.innerHTML = `
        <h3>Gagal Menghitung Ongkir</h3>
        <pre>${error.message}</pre>
    `;
});

    });

});
</script>

</body>
</html>
