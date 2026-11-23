
package com.example.priangankilatexpress

import androidx.compose.foundation.background
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.*
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import java.text.NumberFormat
import java.util.Locale

private val CodBrown = Color(0xFF5B3525)
private val CodOrange = Color(0xFFE98B35)
private val CodCream = Color(0xFFFFF8EF)
private val CodMuted = Color(0xFF8A817A)

data class CodItem(
    val noResi: String,
    val namaPenerima: String,
    val nilaiCod: Int,
    val sudahDisetor: Boolean
)

@Composable
fun CodScreen(
    daftarCod: List<CodItem>,
    totalCodBelumDisetor: Int
) {
    val formatRupiah = NumberFormat.getCurrencyInstance(
        Locale("id", "ID")
    )

    Column(
        modifier = Modifier
            .fillMaxSize()
            .background(CodCream)
            .padding(20.dp)
    ) {
        Text(
            text = "COD SAYA",
            style = MaterialTheme.typography.headlineSmall,
            fontWeight = FontWeight.Bold,
            color = CodBrown
        )

        Spacer(modifier = Modifier.height(6.dp))

        Text(
            text = "Pantau uang COD dari paket yang berhasil dikirim.",
            color = CodMuted
        )

        Spacer(modifier = Modifier.height(20.dp))

        Card(
            modifier = Modifier.fillMaxWidth(),
            shape = RoundedCornerShape(16.dp),
            colors = CardDefaults.cardColors(
                containerColor = CodBrown
            )
        ) {
            Column(
                modifier = Modifier.padding(20.dp)
            ) {
                Text(
                    text = "TOTAL COD BELUM DISETOR",
                    color = Color.White.copy(alpha = 0.8f),
                    style = MaterialTheme.typography.labelLarge
                )

                Spacer(modifier = Modifier.height(10.dp))

                Text(
                    text = formatRupiah.format(totalCodBelumDisetor),
                    color = Color.White,
                    style = MaterialTheme.typography.headlineMedium,
                    fontWeight = FontWeight.Bold
                )

                Spacer(modifier = Modifier.height(6.dp))

                Text(
                    text = "Jumlah uang yang masih menjadi tanggung jawabmu.",
                    color = Color.White.copy(alpha = 0.8f),
                    style = MaterialTheme.typography.bodySmall
                )
            }
        }

        Spacer(modifier = Modifier.height(24.dp))

        Text(
            text = "Riwayat Paket COD",
            fontWeight = FontWeight.Bold,
            color = CodBrown,
            style = MaterialTheme.typography.titleMedium
        )

        Spacer(modifier = Modifier.height(12.dp))

        if (daftarCod.isEmpty()) {
            Card(
                modifier = Modifier.fillMaxWidth(),
                colors = CardDefaults.cardColors(
                    containerColor = Color.White
                )
            ) {
                Text(
                    text = "Belum ada paket COD yang berhasil dikirim.",
                    modifier = Modifier.padding(20.dp),
                    color = CodMuted
                )
            }
        } else {
            LazyColumn(
                verticalArrangement = Arrangement.spacedBy(12.dp)
            ) {
                items(daftarCod) { cod ->
                    Card(
                        modifier = Modifier.fillMaxWidth(),
                        shape = RoundedCornerShape(14.dp),
                        colors = CardDefaults.cardColors(
                            containerColor = Color.White
                        )
                    ) {
                        Column(
                            modifier = Modifier.padding(16.dp)
                        ) {
                            Text(
                                text = cod.noResi,
                                fontWeight = FontWeight.Bold,
                                color = CodBrown
                            )

                            Spacer(modifier = Modifier.height(6.dp))

                            Text(
                                text = "Penerima: ${cod.namaPenerima}",
                                color = CodMuted
                            )

                            Spacer(modifier = Modifier.height(10.dp))

                            Text(
                                text = formatRupiah.format(cod.nilaiCod),
                                fontWeight = FontWeight.Bold,
                                color = CodBrown,
                                style = MaterialTheme.typography.titleMedium
                            )

                            Spacer(modifier = Modifier.height(8.dp))

                            Surface(
                                color = if (cod.sudahDisetor) {
                                    Color(0xFFE3F2E5)
                                } else {
                                    CodOrange.copy(alpha = 0.15f)
                                },
                                shape = RoundedCornerShape(8.dp)
                            ) {
                                Text(
                                    text = if (cod.sudahDisetor) {
                                        "Sudah Disetor"
                                    } else {
                                        "Belum Disetor"
                                    },
                                    modifier = Modifier.padding(
                                        horizontal = 12.dp,
                                        vertical = 6.dp
                                    ),
                                    fontWeight = FontWeight.Medium,
                                    color = if (cod.sudahDisetor) {
                                        Color(0xFF287A3D)
                                    } else {
                                        CodBrown
                                    }
                                )
                            }
                        }
                    }
                }
            }
        }
    }
}
