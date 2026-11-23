
package com.example.priangankilatexpress

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.BackHandler
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.input.PasswordVisualTransformation
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response
import java.text.NumberFormat
import java.util.Locale

private val Brown = Color(0xFF493326)
private val Orange = Color(0xFFD98A35)
private val Cream = Color(0xFFF8F3EB)
private val Muted = Color(0xFF887D73)

class MainActivity : ComponentActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        setContent {
            MaterialTheme {

                var halaman by remember { mutableStateOf("login") }

                var tokenKurir by remember { mutableStateOf("") }
                var namaKurir by remember { mutableStateOf("") }
                var kodeKurir by remember { mutableStateOf("") }

                var paketTerpilih by remember {
                    mutableStateOf<PaketItem?>(null)
                }

                var daftarPaket by remember {
                    mutableStateOf<List<PaketItem>>(emptyList())
                }

                var daftarCod by remember {
                    mutableStateOf<List<CodItem>>(emptyList())
                }

                var totalCod by remember {
                    mutableIntStateOf(0)
                }

                var pesanLogout by remember {
                    mutableStateOf("")
                }

                // FORMAT RUPIAH
                fun formatRupiah(nilai: Int): String {
                    return NumberFormat.getCurrencyInstance(
                        Locale("id", "ID")
                    ).format(nilai)
                }

                // AMBIL DATA PAKET DARI API
                fun muatPaket() {

                    if (tokenKurir.isBlank()) return

                    ApiClient.apiService.getPaket(
                        "Bearer $tokenKurir"
                    ).enqueue(object : Callback<PaketResponse> {

                        override fun onResponse(
                            call: Call<PaketResponse>,
                            response: Response<PaketResponse>
                        ) {
                            if (response.isSuccessful) {

                                daftarPaket =
                                    response.body()?.data?.map { data ->

                                        PaketItem(
                                            id = data.id,
                                            noResi = data.no_resi,
                                            namaPenerima = data.nama_penerima,
                                            alamat = data.alamat_penerima,
                                            kota = data.kota_tujuan,
                                            status = data.status
                                        )

                                    } ?: emptyList()

                            }
                        }

                        override fun onFailure(
                            call: Call<PaketResponse>,
                            t: Throwable
                        ) {
                            pesanLogout = "Gagal memuat paket: ${t.message}"
                        }
                    })
                }

                // AMBIL DATA COD DARI API
                fun muatCod() {

                    if (tokenKurir.isBlank()) return

                    ApiClient.apiService.getCod(
                        "Bearer $tokenKurir"
                    ).enqueue(object : Callback<CodResponse> {

                        override fun onResponse(
                            call: Call<CodResponse>,
                            response: Response<CodResponse>
                        ) {
                            if (response.isSuccessful) {

                                val hasil = response.body()

                                daftarCod = hasil?.data?.map { data ->

                                    CodItem(
                                        noResi = data.no_resi,
                                        namaPenerima = data.nama_penerima,
                                        nilaiCod = data.nilai_cod,
                                        sudahDisetor = data.cod_disetor
                                    )

                                } ?: emptyList()

                                totalCod =
                                    hasil?.total_cod_belum_disetor ?: 0
                            }
                        }

                        override fun onFailure(
                            call: Call<CodResponse>,
                            t: Throwable
                        ) {
                            pesanLogout = "Gagal memuat COD: ${t.message}"
                        }
                    })
                }

                // MUAT DATA SAAT MEMASUKI HALAMAN UTAMA
                LaunchedEffect(tokenKurir, halaman) {

                    if (
                        tokenKurir.isNotBlank() &&
                        halaman in listOf(
                            "dashboard",
                            "paket",
                            "cod"
                        )
                    ) {
                        muatPaket()
                        muatCod()
                    }
                }

                // NAVIGASI TOMBOL KEMBALI ANDROID
                BackHandler(
                    enabled = halaman !in listOf("login", "dashboard")
                ) {
                    halaman = when (halaman) {
                        "ganti_password" -> "login"
                        "paket" -> "dashboard"
                        "detail" -> "paket"
                        "update_status" -> "detail"
                        "cod" -> "dashboard"
                        "profil" -> "dashboard"
                        else -> "dashboard"
                    }
                }

                when (halaman) {

                    // LOGIN
                    "login" -> {

                        LoginScreen(
                            onLoginSuccess = { hasil ->

                                if (hasil.wajib_ganti_password) {

                                    tokenKurir = hasil.token
                                    namaKurir = hasil.kurir.nama
                                    kodeKurir = hasil.kurir.kode_kurir

                                    halaman = "ganti_password"

                                } else {

                                    tokenKurir = hasil.token
                                    namaKurir = hasil.kurir.nama
                                    kodeKurir = hasil.kurir.kode_kurir

                                    halaman = "dashboard"
                                }
                            }
                        )
                    }

                    // GANTI PASSWORD
                    "ganti_password" -> {

                        ChangePasswordScreen(
                            token = tokenKurir,

                            onPasswordChanged = {
                                tokenKurir = ""
                                halaman = "login"
                            }
                        )
                    }

                    // DASHBOARD
                    "dashboard" -> {

                        DashboardScreen(
                            namaKurir = namaKurir,
                            jumlahPaket = daftarPaket.size,
                            totalCod = formatRupiah(totalCod),

                            onPaketClick = {
                                halaman = "paket"
                            },

                            onCodClick = {
                                halaman = "cod"
                            },

                            onProfilClick = {
                                halaman = "profil"
                            }
                        )
                    }

                    // DAFTAR PAKET
                    "paket" -> {

                        PaketScreen(
                            daftarPaket = daftarPaket,

                            onPaketClick = { paket ->
                                paketTerpilih = paket
                                halaman = "detail"
                            }
                        )
                    }

                    // DETAIL PAKET
                    "detail" -> {

                        val paket = paketTerpilih

                        if (paket != null) {

                            DetailPaketScreen(
                                paket = paket,

                                onUpdateStatusClick = {
                                    halaman = "update_status"
                                },

                                onBackClick = {
                                    halaman = "paket"
                                }
                            )
                        }
                    }

                    // UPDATE STATUS
                    "update_status" -> {

                        val paket = paketTerpilih

                        if (paket != null) {

                            UpdateStatusApiScreen(
                                token = tokenKurir,
                                paket = paket,

                                onSuccess = {

                                    paketTerpilih = null

                                    muatPaket()
                                    muatCod()

                                    halaman = "paket"
                                },

                                onBackClick = {
                                    halaman = "detail"
                                }
                            )
                        }
                    }

                    // REKAP COD
                    "cod" -> {

                        CodScreen(
                            daftarCod = daftarCod,
                            totalCodBelumDisetor = totalCod
                        )
                    }

                    // PROFIL
                    "profil" -> {

                        ProfilScreen(
                            namaKurir = namaKurir,
                            kodeKurir = kodeKurir,

                            onChangePasswordClick = {
                                halaman = "ganti_password"
                            },

                            onLogoutClick = {

                                val tokenKeluar = tokenKurir

                                ApiClient.apiService.logout(
                                    "Bearer $tokenKeluar"
                                ).enqueue(object : Callback<ApiMessageResponse> {

                                    override fun onResponse(
                                        call: Call<ApiMessageResponse>,
                                        response: Response<ApiMessageResponse>
                                    ) {
                                        // Sesi lokal sudah dihapus.
                                    }

                                    override fun onFailure(
                                        call: Call<ApiMessageResponse>,
                                        t: Throwable
                                    ) {
                                        // Tetap keluarkan sesi lokal.
                                    }
                                })

                                tokenKurir = ""
                                namaKurir = ""
                                kodeKurir = ""
                                daftarPaket = emptyList()
                                daftarCod = emptyList()
                                totalCod = 0
                                paketTerpilih = null

                                halaman = "login"
                            }
                        )
                    }
                }
            }
        }
    }
}

// ========================================
// HALAMAN LOGIN
// ========================================

@Composable
fun LoginScreen(
    onLoginSuccess: (LoginResponse) -> Unit
) {

    var kodeKurir by remember { mutableStateOf("") }
    var password by remember { mutableStateOf("") }
    var isLoading by remember { mutableStateOf(false) }
    var pesan by remember { mutableStateOf("") }

    Surface(
        modifier = Modifier.fillMaxSize(),
        color = Cream
    ) {

        Column(
            modifier = Modifier
                .fillMaxSize()
                .padding(24.dp),
            horizontalAlignment = Alignment.CenterHorizontally,
            verticalArrangement = Arrangement.Center
        ) {

            Text(
                text = "PRIANGAN",
                fontSize = 27.sp,
                fontWeight = FontWeight.Bold,
                color = Brown
            )

            Text(
                text = "KILAT EXPRESS",
                fontSize = 15.sp,
                fontWeight = FontWeight.Bold,
                color = Orange
            )

            Spacer(modifier = Modifier.height(12.dp))

            Text(
                text = "PORTAL KURIR",
                fontSize = 13.sp,
                color = Muted
            )

            Spacer(modifier = Modifier.height(36.dp))

            Card(
                modifier = Modifier.fillMaxWidth(),
                shape = RoundedCornerShape(18.dp),
                colors = CardDefaults.cardColors(
                    containerColor = Color.White
                ),
                elevation = CardDefaults.cardElevation(4.dp)
            ) {

                Column(
                    modifier = Modifier.padding(24.dp)
                ) {

                    Text(
                        text = "Selamat Datang!",
                        fontSize = 22.sp,
                        fontWeight = FontWeight.Bold,
                        color = Brown
                    )

                    Text(
                        text = "Silakan masuk menggunakan akun kurir Anda.",
                        fontSize = 12.sp,
                        color = Muted
                    )

                    Spacer(modifier = Modifier.height(24.dp))

                    Text(
                        text = "Kode Kurir",
                        fontWeight = FontWeight.SemiBold,
                        color = Brown
                    )

                    Spacer(modifier = Modifier.height(8.dp))

                    OutlinedTextField(
                        value = kodeKurir,
                        onValueChange = { kodeKurir = it },
                        modifier = Modifier.fillMaxWidth(),
                        placeholder = {
                            Text("Contoh: KUR-01")
                        },
                        singleLine = true,
                        shape = RoundedCornerShape(10.dp)
                    )

                    Spacer(modifier = Modifier.height(16.dp))

                    Text(
                        text = "Password",
                        fontWeight = FontWeight.SemiBold,
                        color = Brown
                    )

                    Spacer(modifier = Modifier.height(8.dp))

                    OutlinedTextField(
                        value = password,
                        onValueChange = { password = it },
                        modifier = Modifier.fillMaxWidth(),
                        placeholder = {
                            Text("Masukkan password")
                        },
                        visualTransformation =
                            PasswordVisualTransformation(),
                        singleLine = true,
                        shape = RoundedCornerShape(10.dp)
                    )

                    Spacer(modifier = Modifier.height(24.dp))

                    Button(
                        onClick = {

                            when {
                                kodeKurir.isBlank() || password.isBlank() -> {
                                    pesan = "Kode kurir dan password wajib diisi."
                                }

                                password.length < 8 -> {
                                    pesan = "Password minimal 8 karakter."
                                }

                                else -> {

                                    isLoading = true
                                    pesan = ""

                                    ApiClient.apiService.login(
                                        LoginRequest(
                                            kode_kurir = kodeKurir.trim(),
                                            password = password
                                        )
                                    ).enqueue(object : Callback<LoginResponse> {

                                        override fun onResponse(
                                            call: Call<LoginResponse>,
                                            response: Response<LoginResponse>
                                        ) {

                                            isLoading = false

                                            if (response.isSuccessful) {

                                                val hasil = response.body()

                                                if (hasil != null) {
                                                    onLoginSuccess(hasil)
                                                } else {
                                                    pesan = "Respons server kosong."
                                                }

                                            } else {

                                                pesan = "Login gagal. Periksa kode kurir dan password."
                                            }
                                        }

                                        override fun onFailure(
                                            call: Call<LoginResponse>,
                                            t: Throwable
                                        ) {

                                            isLoading = false
                                            pesan = "Koneksi gagal: ${t.message}"
                                        }
                                    })
                                }
                            }
                        },

                        modifier = Modifier
                            .fillMaxWidth()
                            .height(50.dp),

                        enabled = !isLoading,

                        colors = ButtonDefaults.buttonColors(
                            containerColor = Orange
                        ),

                        shape = RoundedCornerShape(10.dp)

                    ) {

                        if (isLoading) {

                            CircularProgressIndicator(
                                modifier = Modifier.size(22.dp),
                                color = Color.White,
                                strokeWidth = 2.dp
                            )

                        } else {

                            Text(
                                text = "MASUK",
                                fontWeight = FontWeight.Bold,
                                color = Color.White
                            )
                        }
                    }

                    if (pesan.isNotEmpty()) {

                        Spacer(modifier = Modifier.height(12.dp))

                        Text(
                            text = pesan,
                            color = Brown,
                            fontSize = 12.sp
                        )
                    }

                    Spacer(modifier = Modifier.height(16.dp))

                    Text(
                        text = "Lupa password? Hubungi Admin.",
                        modifier = Modifier.align(Alignment.CenterHorizontally),
                        fontSize = 12.sp,
                        color = Muted
                    )
                }
            }

            Spacer(modifier = Modifier.height(28.dp))

            Text(
                text = "Priangan Kilat Express © 2026",
                fontSize = 11.sp,
                color = Muted
            )
        }
    }
}
