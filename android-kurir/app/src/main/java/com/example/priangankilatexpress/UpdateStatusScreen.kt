
package com.example.priangankilatexpress

import androidx.compose.foundation.background
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp

private val UpdateBrown = Color(0xFF5B3525)
private val UpdateOrange = Color(0xFFE98B35)
private val UpdateCream = Color(0xFFFFF8EF)
private val UpdateMuted = Color(0xFF8A817A)

@Composable
fun UpdateStatusScreen(
    paket: PaketItem,
    loading: Boolean = false,
    onSubmit: (String, String?) -> Unit,
    onBackClick: () -> Unit
) {
    var statusDipilih by remember { mutableStateOf("") }
    var catatan by remember { mutableStateOf("") }
    var pesan by remember { mutableStateOf("") }

    Column(
        modifier = Modifier
            .fillMaxSize()
            .background(UpdateCream)
            .padding(20.dp)
    ) {
        TextButton(onClick = onBackClick) {
            Text("← Kembali", color = UpdateBrown)
        }

        Spacer(modifier = Modifier.height(12.dp))

        Text(
            text = "UPDATE STATUS",
            style = MaterialTheme.typography.headlineSmall,
            fontWeight = FontWeight.Bold,
            color = UpdateBrown
        )

        Spacer(modifier = Modifier.height(8.dp))

        Text(
            text = "Resi: ${paket.noResi}",
            color = UpdateMuted
        )

        Spacer(modifier = Modifier.height(24.dp))

        Text(
            text = "Pilih Status Pengiriman",
            fontWeight = FontWeight.Bold,
            color = UpdateBrown
        )

        Spacer(modifier = Modifier.height(12.dp))

        Card(
            modifier = Modifier.fillMaxWidth(),
            colors = CardDefaults.cardColors(
                containerColor = Color.White
            ),
            shape = RoundedCornerShape(12.dp)
        ) {
            Column(modifier = Modifier.padding(12.dp)) {
                Row(
                    modifier = Modifier.fillMaxWidth(),
                    horizontalArrangement = Arrangement.Start
                ) {
                    RadioButton(
                        selected = statusDipilih == "Terkirim",
                        onClick = {
                            statusDipilih = "Terkirim"
                            pesan = ""
                        }
                    )

                    Column(modifier = Modifier.padding(top = 12.dp)) {
                        Text(
                            "Terkirim",
                            fontWeight = FontWeight.Bold,
                            color = UpdateBrown
                        )
                        Text(
                            "Paket berhasil diterima.",
                            color = UpdateMuted
                        )
                    }
                }

                HorizontalDivider()

                Row(
                    modifier = Modifier.fillMaxWidth()
                ) {
                    RadioButton(
                        selected = statusDipilih == "Gagal Kirim",
                        onClick = {
                            statusDipilih = "Gagal Kirim"
                            pesan = ""
                        }
                    )

                    Column(modifier = Modifier.padding(top = 12.dp)) {
                        Text(
                            "Gagal Kirim",
                            fontWeight = FontWeight.Bold,
                            color = UpdateBrown
                        )
                        Text(
                            "Paket tidak berhasil dikirim.",
                            color = UpdateMuted
                        )
                    }
                }
            }
        }

        if (statusDipilih == "Gagal Kirim") {
            Spacer(modifier = Modifier.height(20.dp))

            Text(
                text = "Catatan Kegagalan *",
                fontWeight = FontWeight.Bold,
                color = UpdateBrown
            )

            Spacer(modifier = Modifier.height(8.dp))

            OutlinedTextField(
                value = catatan,
                onValueChange = { catatan = it },
                modifier = Modifier.fillMaxWidth(),
                placeholder = {
                    Text("Tuliskan alasan paket gagal dikirim...")
                },
                minLines = 3,
                shape = RoundedCornerShape(12.dp)
            )
        }

        if (pesan.isNotEmpty()) {
            Spacer(modifier = Modifier.height(12.dp))
            Text(pesan, color = MaterialTheme.colorScheme.error)
        }

        Spacer(modifier = Modifier.height(24.dp))

        Button(
            onClick = {
                when {
                    statusDipilih.isBlank() -> {
                        pesan = "Silakan pilih status pengiriman."
                    }

                    statusDipilih == "Gagal Kirim" && catatan.isBlank() -> {
                        pesan = "Catatan kegagalan wajib diisi."
                    }

                    else -> {
                        pesan = ""
                        onSubmit(
                            statusDipilih,
                            if (statusDipilih == "Gagal Kirim") {
                                catatan.trim()
                            } else {
                                null
                            }
                        )
                    }
                }
            },
            enabled = !loading,
            modifier = Modifier
                .fillMaxWidth()
                .height(52.dp),
            colors = ButtonDefaults.buttonColors(
                containerColor = UpdateBrown
            ),
            shape = RoundedCornerShape(12.dp)
        ) {
            Text(
                if (loading) "MEMPROSES..." else "SIMPAN STATUS"
            )
        }
    }
}

