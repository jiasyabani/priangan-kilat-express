
package com.example.priangankilatexpress

import androidx.compose.foundation.layout.*
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.unit.dp
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

@Composable
fun PaketApiScreen(
    token: String,
    onPaketClick: (PaketItem) -> Unit
) {
    var daftarPaket by remember {
        mutableStateOf<List<PaketItem>>(emptyList())
    }

    var loading by remember {
        mutableStateOf(true)
    }

    var pesanError by remember {
        mutableStateOf("")
    }

    fun muatPaket() {
        loading = true
        pesanError = ""

        ApiClient.apiService.getPaket(
            "Bearer $token"
        ).enqueue(object : Callback<PaketResponse> {

            override fun onResponse(
                call: Call<PaketResponse>,
                response: Response<PaketResponse>
            ) {
                loading = false

                if (response.isSuccessful) {
                    daftarPaket = response.body()?.data?.map { data ->

                        PaketItem(
                            noResi = data.no_resi,
                            namaPenerima = data.nama_penerima,
                            alamat = data.alamat_penerima,
                            kota = data.kota_tujuan,
                            status = data.status,
                            id = data.id
                        )

                    } ?: emptyList()
                } else {
                    pesanError = when (response.code()) {
                        401 -> "Sesi login berakhir. Silakan login kembali."
                        403 -> "Akses ditolak. Periksa status akun dan password."
                        else -> "Gagal mengambil data paket. Kode: ${response.code()}"
                    }
                }
            }

            override fun onFailure(
                call: Call<PaketResponse>,
                t: Throwable
            ) {
                loading = false
                pesanError = "Koneksi gagal: ${t.message}"
            }
        })
    }

    LaunchedEffect(token) {
        muatPaket()
    }

    when {
        loading -> {
            Box(
                modifier = Modifier.fillMaxSize(),
                contentAlignment = Alignment.Center
            ) {
                CircularProgressIndicator()
            }
        }

        pesanError.isNotEmpty() -> {
            Column(
                modifier = Modifier
                    .fillMaxSize()
                    .padding(24.dp),
                verticalArrangement = Arrangement.Center,
                horizontalAlignment = Alignment.CenterHorizontally
            ) {
                Text(pesanError)

                Spacer(modifier = Modifier.height(16.dp))

                Button(onClick = { muatPaket() }) {
                    Text("COBA LAGI")
                }
            }
        }

        else -> {
            PaketScreen(
                daftarPaket = daftarPaket,
                onPaketClick = onPaketClick
            )
        }
    }
}
