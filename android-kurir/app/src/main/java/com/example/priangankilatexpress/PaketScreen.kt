
package com.example.priangankilatexpress

import androidx.compose.foundation.background
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp

private val PaketBrown = Color(0xFF5B3525)
private val PaketOrange = Color(0xFFE98B35)
private val PaketCream = Color(0xFFFFF8EF)
private val PaketMuted = Color(0xFF8A817A)


data class PaketItem(
    val noResi: String,
    val namaPenerima: String,
    val alamat: String,
    val kota: String,
    val status: String,
    val id: Int = 0
)

@Composable
fun PaketScreen(
    daftarPaket: List<PaketItem>,
    onPaketClick: (PaketItem) -> Unit
) {
    Column(
        modifier = Modifier
            .fillMaxSize()
            .background(PaketCream)
            .padding(20.dp)
    ) {
        Text(
            text = "PAKET SAYA",
            style = MaterialTheme.typography.headlineSmall,
            fontWeight = FontWeight.Bold,
            color = PaketBrown
        )

        Spacer(modifier = Modifier.height(6.dp))

        Text(
            text = "Daftar paket yang harus kamu antarkan.",
            color = PaketMuted
        )

        Spacer(modifier = Modifier.height(20.dp))

        Text(
            text = "Total Paket: ${daftarPaket.size}",
            fontWeight = FontWeight.Bold,
            color = PaketBrown
        )

        Spacer(modifier = Modifier.height(12.dp))

        if (daftarPaket.isEmpty()) {
            Card(
                modifier = Modifier.fillMaxWidth(),
                colors = CardDefaults.cardColors(
                    containerColor = Color.White
                )
            ) {
                Text(
                    text = "Belum ada paket yang harus diantarkan.",
                    modifier = Modifier.padding(20.dp),
                    color = PaketMuted
                )
            }
        } else {
            LazyColumn(
                verticalArrangement = Arrangement.spacedBy(12.dp)
            ) {
                items(daftarPaket) { paket ->
                    Card(
                        modifier = Modifier.fillMaxWidth(),
                        shape = RoundedCornerShape(14.dp),
                        colors = CardDefaults.cardColors(
                            containerColor = Color.White
                        ),
                        onClick = { onPaketClick(paket) }
                    ) {
                        Column(
                            modifier = Modifier.padding(16.dp)
                        ) {
                            Text(
                                text = paket.noResi,
                                fontWeight = FontWeight.Bold,
                                color = PaketBrown
                            )

                            Spacer(modifier = Modifier.height(8.dp))

                            Text("Penerima: ${paket.namaPenerima}")

                            Text(
                                text = "${paket.alamat}, ${paket.kota}",
                                color = PaketMuted
                            )

                            Spacer(modifier = Modifier.height(12.dp))

                            Surface(
                                color = PaketOrange.copy(alpha = 0.15f),
                                shape = RoundedCornerShape(8.dp)
                            ) {
                                Text(
                                    text = paket.status,
                                    color = PaketBrown,
                                    modifier = Modifier.padding(
                                        horizontal = 12.dp,
                                        vertical = 6.dp
                                    ),
                                    fontWeight = FontWeight.Medium
                                )
                            }
                        }
                    }
                }
            }
        }
    }
}
