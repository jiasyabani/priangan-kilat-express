
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
fun CodApiScreen(token: String) {

    var daftarCod by remember {
        mutableStateOf<List<CodItem>>(emptyList())
    }

    var totalCod by remember {
        mutableIntStateOf(0)
    }

    var loading by remember {
        mutableStateOf(true)
    }

    var pesanError by remember {
        mutableStateOf("")
    }

    fun muatCod() {
        loading = true
        pesanError = ""

        ApiClient.apiService.getCod(
            "Bearer $token"
        ).enqueue(object : Callback<CodResponse> {

            override fun onResponse(
                call: Call<CodResponse>,
                response: Response<CodResponse>
            ) {
                loading = false

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

                    totalCod = hasil?.total_cod_belum_disetor ?: 0

                } else {
                    pesanError = when (response.code()) {
                        401 -> "Sesi login berakhir. Silakan login kembali."
                        403 -> "Akses ditolak. Periksa akun kurir."
                        else -> "Gagal mengambil data COD. Kode: ${response.code()}"
                    }
                }
            }

            override fun onFailure(
                call: Call<CodResponse>,
                t: Throwable
            ) {
                loading = false
                pesanError = "Koneksi gagal: ${t.message}"
            }
        })
    }

    LaunchedEffect(token) {
        muatCod()
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

                Button(onClick = { muatCod() }) {
                    Text("COBA LAGI")
                }
            }
        }

        else -> {
            CodScreen(
                daftarCod = daftarCod,
                totalCodBelumDisetor = totalCod
            )
        }
    }
}
