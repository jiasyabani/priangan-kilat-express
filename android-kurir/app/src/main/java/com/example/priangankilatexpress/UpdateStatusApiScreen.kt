
package com.example.priangankilatexpress

import androidx.compose.material3.AlertDialog
import androidx.compose.material3.Text
import androidx.compose.material3.TextButton
import androidx.compose.runtime.*

import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

@Composable
fun UpdateStatusApiScreen(
    token: String,
    paket: PaketItem,
    onSuccess: () -> Unit,
    onBackClick: () -> Unit
) {
    var loading by remember { mutableStateOf(false) }
    var pesanError by remember { mutableStateOf("") }

    UpdateStatusScreen(
        paket = paket,
        loading = loading,
        onBackClick = onBackClick,
        onSubmit = { status, catatan ->

            loading = true
            pesanError = ""

            ApiClient.apiService.updateStatus(
                token = "Bearer $token",
                idPaket = paket.id,
                request = UpdateStatusRequest(
                    status = status,
                    catatan = catatan
                )
            ).enqueue(object : Callback<ApiMessageResponse> {

                override fun onResponse(
                    call: Call<ApiMessageResponse>,
                    response: Response<ApiMessageResponse>
                ) {
                    loading = false

                    if (response.isSuccessful) {
                        onSuccess()
                    } else {
                        pesanError = when (response.code()) {
                            401 -> "Sesi login berakhir. Silakan login kembali."
                            403 -> "Anda tidak memiliki akses untuk paket ini."
                            422 -> "Status paket tidak dapat diperbarui. Periksa ketentuan pengiriman."
                            else -> "Gagal memperbarui status. Kode: ${response.code()}"
                        }
                    }
                }

                override fun onFailure(
                    call: Call<ApiMessageResponse>,
                    t: Throwable
                ) {
                    loading = false
                    pesanError = "Koneksi gagal: ${t.message}"
                }
            })
        }
    )

    if (pesanError.isNotEmpty()) {
        AlertDialog(
            onDismissRequest = { pesanError = "" },
            title = {
                Text("Pembaruan Gagal")
            },
            text = {
                Text(pesanError)
            },
            confirmButton = {
                TextButton(
                    onClick = { pesanError = "" }
                ) {
                    Text("OK")
                }
            }
        )
    }
}
