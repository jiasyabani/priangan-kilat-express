
package com.example.priangankilatexpress

import androidx.compose.foundation.background
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.*
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp

private val ProfilBrown = Color(0xFF5B3525)
private val ProfilOrange = Color(0xFFE98B35)
private val ProfilCream = Color(0xFFFFF8EF)
private val ProfilMuted = Color(0xFF8A817A)

@Composable
fun ProfilScreen(
    namaKurir: String,
    kodeKurir: String,
    onChangePasswordClick: () -> Unit,
    onLogoutClick: () -> Unit
) {
    Column(
        modifier = Modifier
            .fillMaxSize()
            .background(ProfilCream)
            .padding(20.dp),
        horizontalAlignment = Alignment.CenterHorizontally
    ) {
        Text(
            text = "PROFIL KURIR",
            style = MaterialTheme.typography.headlineSmall,
            fontWeight = FontWeight.Bold,
            color = ProfilBrown,
            modifier = Modifier.align(Alignment.Start)
        )

        Spacer(modifier = Modifier.height(32.dp))

        Surface(
            modifier = Modifier.size(100.dp),
            shape = CircleShape,
            color = ProfilBrown
        ) {
            Box(contentAlignment = Alignment.Center) {
                Text(
                    text = namaKurir.firstOrNull()?.uppercase() ?: "K",
                    color = Color.White,
                    style = MaterialTheme.typography.headlineLarge,
                    fontWeight = FontWeight.Bold
                )
            }
        }

        Spacer(modifier = Modifier.height(16.dp))

        Text(
            text = namaKurir,
            style = MaterialTheme.typography.titleLarge,
            fontWeight = FontWeight.Bold,
            color = ProfilBrown
        )

        Text(
            text = "Kurir Priangan Kilat Express",
            color = ProfilMuted
        )

        Spacer(modifier = Modifier.height(28.dp))

        Card(
            modifier = Modifier.fillMaxWidth(),
            shape = RoundedCornerShape(14.dp),
            colors = CardDefaults.cardColors(
                containerColor = Color.White
            )
        ) {
            Column(
                modifier = Modifier.padding(18.dp)
            ) {
                Text(
                    text = "Informasi Akun",
                    fontWeight = FontWeight.Bold,
                    color = ProfilBrown
                )

                Spacer(modifier = Modifier.height(16.dp))

                Text(
                    text = "Nama Kurir",
                    color = ProfilMuted
                )

                Text(
                    text = namaKurir,
                    fontWeight = FontWeight.Medium,
                    color = ProfilBrown
                )

                Spacer(modifier = Modifier.height(14.dp))

                Text(
                    text = "Kode Kurir",
                    color = ProfilMuted
                )

                Text(
                    text = kodeKurir,
                    fontWeight = FontWeight.Medium,
                    color = ProfilBrown
                )
            }
        }

        Spacer(modifier = Modifier.height(20.dp))

        OutlinedButton(
            onClick = onChangePasswordClick,
            modifier = Modifier
                .fillMaxWidth()
                .height(52.dp),
            shape = RoundedCornerShape(12.dp)
        ) {
            Text(
                text = "GANTI PASSWORD",
                color = ProfilBrown
            )
        }

        Spacer(modifier = Modifier.height(12.dp))

        Button(
            onClick = onLogoutClick,
            modifier = Modifier
                .fillMaxWidth()
                .height(52.dp),
            shape = RoundedCornerShape(12.dp),
            colors = ButtonDefaults.buttonColors(
                containerColor = ProfilBrown
            )
        ) {
            Text("LOGOUT")
        }
    }
}
