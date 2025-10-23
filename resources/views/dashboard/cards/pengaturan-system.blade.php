<div class="glass-card p-6 mt-6">
    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-sliders-h mr-2 text-indigo-600"></i> Pengaturan Sistem
    </h3>

    <form id="formPengaturan" class="space-y-4">
        <div>
            <label class="block font-semibold text-gray-700">Batas TDS Minimum (ppm)</label>
            <input type="number" id="tds_min" name="tds_min"
                   class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-green-200 px-3 py-2" />
        </div>

        <div>
            <label class="block font-semibold text-gray-700">Batas Air Minimum (%)</label>
            <input type="number" id="air_min" name="air_min"
                   class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-green-200 px-3 py-2" />
        </div>

        <div>
            <label class="block font-semibold text-gray-700">Interval Update (detik)</label>
            <input type="number" id="interval" name="interval"
                   class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-green-200 px-3 py-2" />
        </div>

        <div>
            <label class="block font-semibold text-gray-700">Tanaman Aktif</label>
            <select id="tanaman_aktif" name="tanaman_aktif"
                    class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-green-200 px-3 py-2">
                <option value="">Pilih Tanaman</option>
            </select>
        </div>

        <button type="button" onclick="updateBatas()"
                class="w-full bg-green-600 text-white font-semibold py-2 rounded-lg shadow hover:bg-green-700 transition">
            Simpan Pengaturan
        </button>
    </form>
    <div id="formLock" 
        class="lock-overlay absolute inset-0 bg-black/40 backdrop-blur-sm flex flex-col items-center justify-center text-white rounded-2xl">
            <i class="fas fa-lock text-5xl mb-3 lock-icon"></i>
                <p class="text-lg font-semibold">Silakan login untuk mengubah pengaturan</p>
    </div>
</div>

@push('scripts')
<script>
async function loadTanamanList() {
    try {
        const res = await fetch('/api/tanaman');
        const data = await res.json();
        const select = document.getElementById('tanaman_aktif');

        select.innerHTML = '<option value="">Pilih Tanaman</option>';
        data.data.forEach(t => {
            const opt = document.createElement('option');
            opt.value = t.id;
            opt.textContent = t.nama_tanaman;
            select.appendChild(opt);
        });
    } catch (error) {
        console.error("Gagal memuat tanaman:", error);
    }
}

async function updateBatas() {
    const payload = {
        tds_min: document.getElementById('tds_min').value,
        air_min: document.getElementById('air_min').value,
        interval: document.getElementById('interval').value,
        tanaman_aktif: document.getElementById('tanaman_aktif').value
    };

    try {
        const res = await fetch('/api/batas/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        });

        const result = await res.json();
        Swal.fire('Berhasil!', result.message, 'success');
        fetchPengaturan();
    } catch (error) {
        Swal.fire('Gagal!', 'Terjadi kesalahan saat menyimpan data.', 'error');
    }
}

loadTanamanList();
</script>
<script>
    async function fetchPengaturan() {
        try {
            const res = await fetch('/api/batas');
            const data = await res.json();
            document.getElementById('tds_min').value = data.tds_min;
            document.getElementById('air_min').value = data.air_min;
            document.getElementById('interval').value = data.interval;
        } catch (error) {
            console.error("Gagal memuat pengaturan:", error);
        }
    }
    fetchPengaturan();
</script>
<script>
document.getElementById("pengaturanForm").addEventListener("submit", async function(e) {
    e.preventDefault(); // Mencegah reload halaman

    const form = e.target;
    const data = {
        tds_min: form.tds_min.value,
        air_min: form.air_min.value,
        interval: form.interval.value
    };

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Pengaturan berhasil disimpan.',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        } else {
            Swal.fire({
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat menyimpan.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }

    } catch (err) {
        console.error("Gagal simpan pengaturan:", err);
        Swal.fire({
            title: 'Gagal!',
            text: 'Terjadi kesalahan jaringan.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }
});
</script>
<script>
document.getElementById("formLock").addEventListener("click", function () {
    const overlay = this;
    
    // Ganti overlay dengan form login
    overlay.outerHTML = `
        <div class="absolute inset-0 bg-white/80 backdrop-blur-sm flex flex-col items-center justify-center rounded-2xl login-form p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Login</h2>
            <form id="loginForm" class="w-full max-w-xs space-y-4">
                <input id="username" type="text" placeholder="Username" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <input id="password" type="password" placeholder="Password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <button type="submit" 
                    class="w-full px-4 py-2 rounded-lg bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold shadow-md hover:shadow-lg transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
        </div>
    `;

    setTimeout(() => {
    document.getElementById("loginForm").addEventListener("submit", async function (e) {
        e.preventDefault();

        const username = document.getElementById("username").value.trim();
        const password = document.getElementById("password").value.trim();

        if (!username || !password) {
            alert("Username dan password wajib diisi!");
            return;
        }

        try {
            // Kirim ke server untuk dicek di database
            const res = await fetch("/api/login", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ username, password })
            });

            const data = await res.json();

            if (res.ok && data.success) {
                alert("Login berhasil!");
                document.querySelector("#pengaturanForm")
                    .querySelectorAll("input, button")
                    .forEach(el => el.disabled = false);

                document.querySelector(".login-form").remove();
            } else {
                alert(data.message || "Login gagal! Username atau password salah.");
            }
        } catch (err) {
            console.error(err);
            alert("Gagal terhubung ke server.");
        }
    });
}, 100);
});
</script>
@endpush
