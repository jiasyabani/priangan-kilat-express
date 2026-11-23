
package com.example.priangankilatexpress

import androidx.compose.foundation.background
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.*
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp

private val DetailBrown = Color(0xFF5B3525)
private val DetailOrange = Color(0xFFE98B35)
private val DetailCream = Color(0xFFFFF8EF)
private val DetailMuted = Color(0xFF8A817A)

@Composable
fun DetailPaketScreen(
    paket: PaketItem,
    onUpdateStatusClick: () -> Unit,
    onBackClick: () -> Unit
) {
    Column(
        modifier = Modifier
            .fillMaxSize()
            .background(DetailCream)
            .padding(20.dp)
    ) {
        TextButton(onClick = onBackClick) {
            Text(
                text = "← Kembali",
                color = DetailBrown
            )
        }

        Spacer(modifier = Modifier.height(12.dp))

        Text(
            text = "DETAIL PAKET",
            style = MaterialTheme.typography.headlineSmall,
            fontWeight = FontWeight.Bold,
            color = DetailBrown
        )

        Spacer(modifier = Modifier.height(20.dp))

        Card(
            modifier = Modifier.fillMaxWidth(),
            shape = RoundedCornerShape(16.dp),
            colors = CardDefaults.cardColors(
                containerColor = Color.White
            )
        ) {
            Column(
                modifier = Modifier.padding(18.dp)
            ) {
                Text(
                    text = "Nomor Resi",
                    color = DetailMuted
                )

                Text(
                    text = paket.noResi,
                    fontWeight = FontWeight.Bold,
                    color = DetailBrown,
                    style = MaterialTheme.typography.titleLarge
                )

                HorizontalDivider(
                    modifier = Modifier.padding(vertical = 16.dp)
                )

                InfoPaket("Nama Penerima", paket.namaPenerima)
                InfoPaket("Nomor Resi", paket.noResi)
                InfoPaket("Alamat", paket.alamat)
                InfoPaket("Kota Tujuan", paket.kota)

                Spacer(modifier = Modifier.height(16.dp))

                Text(
                    text = "Status Pengiriman",
                    color = DetailMuted
                )

                Text(
                    text = paket.status,
                    fontWeight = FontWeight.Bold,
                    color = DetailOrange
                )
            }
        }

        Spacer(modifier = Modifier.height(24.dp))

        Button(
            onClick = onUpdateStatusClick,
            modifier = Modifier
                .fillMaxWidth()
                .height(52.dp),
            colors = ButtonDefaults.buttonColors(
                containerColor = DetailBrown
            ),
            shape = RoundedCornerShape(12.dp)
        ) {
            Text("PERBARUI STATUS PAKET")
        }
    }
}

@Composable
private fun InfoPaket(
    judul: String,
    isi: String
) {
    Column(
        modifier = Modifier
            .fillMaxWidth()
            .padding(bottom = 14.dp)
    ) {
        Text(
            text = judul,
            color = DetailMuted,
            style = MaterialTheme.typography.bodySmall
        )

        Text(
            text = isi,
            color = DetailBrown,
            fontWeight = FontWeight.Medium
        )
    }
}
