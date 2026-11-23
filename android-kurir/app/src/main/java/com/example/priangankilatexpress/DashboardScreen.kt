
package com.example.priangankilatexpress

import androidx.compose.foundation.background
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.*
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.res.painterResource
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.compose.foundation.Image

private val Brown = Color(0xFF493326)
private val Orange = Color(0xFFD98A35)
private val Cream = Color(0xFFF8F3EB)
private val Muted = Color(0xFF887D73)

@Composable
fun DashboardScreen(
    namaKurir: String,
    jumlahPaket: Int,
    totalCod: String,
    onPaketClick: () -> Unit,
    onCodClick: () -> Unit,
    onProfilClick: () -> Unit
) {
    Scaffold(
        containerColor = Cream,
        bottomBar = {
            NavigationBar(
                containerColor = Color.White
            ) {
                NavigationBarItem(
                    selected = true,
                    onClick = {},
                    icon = {
                        Image(
                            painter = painterResource(R.drawable.dashboard),
                            contentDescription = "Beranda",
                            modifier = Modifier.size(24.dp)
                        )
                    },
                    label = { Text("Beranda") }
                )

                NavigationBarItem(
                    selected = false,
                    onClick = onPaketClick,
                    icon = {
                        Image(
                            painter = painterResource(R.drawable.paket),
                            contentDescription = "Paket",
                            modifier = Modifier.size(24.dp)
                        )
                    },
                    label = { Text("Paket") }
                )

                NavigationBarItem(
                    selected = false,
                    onClick = onCodClick,
                    icon = {
                        Image(
                            painter = painterResource(R.drawable.cod),
                            contentDescription = "COD",
                            modifier = Modifier.size(24.dp)
                        )
                    },
                    label = { Text("COD") }
                )

                NavigationBarItem(
                    selected = false,
                    onClick = onProfilClick,
                    icon = {
                        Image(
                            painter = painterResource(R.drawable.kurir),
                            contentDescription = "Profil",
                            modifier = Modifier.size(24.dp)
                        )
                    },
                    label = { Text("Profil") }
                )
            }
        }
    ) { paddingValues ->

        Column(
            modifier = Modifier
                .fillMaxSize()
                .padding(paddingValues)
                .padding(20.dp)
        ) {

            // HEADER LOGO
            Row(
                modifier = Modifier.fillMaxWidth(),
                horizontalArrangement = Arrangement.SpaceBetween,
                verticalAlignment = Alignment.CenterVertically
            ) {

                Row(
                    verticalAlignment = Alignment.CenterVertically
                ) {
                    Image(
                        painter = painterResource(R.drawable.logo_kujang_kilat),
                        contentDescription = "Logo Priangan Kilat Express",
                        modifier = Modifier.size(48.dp)
                    )

                    Spacer(modifier = Modifier.width(10.dp))

                    Column {
                        Text(
                            text = "PRIANGAN",
                            fontSize = 20.sp,
                            fontWeight = FontWeight.Bold,
                            color = Brown
                        )

                        Text(
                            text = "KILAT EXPRESS",
                            fontSize = 12.sp,
                            fontWeight = FontWeight.Bold,
                            color = Orange
                        )
                    }
                }

                Surface(
                    shape = RoundedCornerShape(50.dp),
                    color = Color.White,
                    modifier = Modifier.size(45.dp)
                ) {
                    Box(contentAlignment = Alignment.Center) {
                        Text(
                            text = namaKurir.take(1).uppercase(),
                            fontWeight = FontWeight.Bold,
                            color = Brown,
                            fontSize = 20.sp
                        )
                    }
                }
            }

            Spacer(modifier = Modifier.height(28.dp))

            // SAPAAN
            Text(
                text = "Selamat bertugas,",
                fontSize = 14.sp,
                color = Muted
            )

            Text(
                text = namaKurir,
                fontSize = 25.sp,
                fontWeight = FontWeight.Bold,
                color = Brown
            )

            Spacer(modifier = Modifier.height(24.dp))

            // KARTU PAKET
            Card(
                modifier = Modifier
                    .fillMaxWidth()
                    .clickable { onPaketClick() },
                shape = RoundedCornerShape(20.dp),
                colors = CardDefaults.cardColors(
                    containerColor = Brown
                )
            ) {
                Row(
                    modifier = Modifier
                        .fillMaxWidth()
                        .padding(22.dp),
                    verticalAlignment = Alignment.CenterVertically
                ) {
                    Column(
                        modifier = Modifier.weight(1f)
                    ) {
                        Text(
                            text = "PAKET DALAM PENGIRIMAN",
                            fontSize = 12.sp,
                            fontWeight = FontWeight.Bold,
                            color = Cream
                        )

                        Spacer(modifier = Modifier.height(12.dp))

                        Text(
                            text = jumlahPaket.toString(),
                            fontSize = 38.sp,
                            fontWeight = FontWeight.Bold,
                            color = Color.White
                        )

                        Text(
                            text = "Paket yang sedang kamu bawa",
                            fontSize = 12.sp,
                            color = Cream
                        )
                    }

                    Image(
                        painter = painterResource(R.drawable.dikirim),
                        contentDescription = "Paket dalam pengiriman",
                        modifier = Modifier.size(58.dp)
                    )
                }
            }

            Spacer(modifier = Modifier.height(16.dp))

            // KARTU COD
            Card(
                modifier = Modifier
                    .fillMaxWidth()
                    .clickable { onCodClick() },
                shape = RoundedCornerShape(20.dp),
                colors = CardDefaults.cardColors(
                    containerColor = Color.White
                )
            ) {
                Row(
                    modifier = Modifier
                        .fillMaxWidth()
                        .padding(22.dp),
                    verticalAlignment = Alignment.CenterVertically
                ) {
                    Column(
                        modifier = Modifier.weight(1f)
                    ) {
                        Text(
                            text = "COD BELUM DISETORKAN",
                            fontSize = 12.sp,
                            fontWeight = FontWeight.Bold,
                            color = Muted
                        )

                        Spacer(modifier = Modifier.height(10.dp))

                        Text(
                            text = totalCod,
                            fontSize = 26.sp,
                            fontWeight = FontWeight.Bold,
                            color = Orange
                        )

                        Text(
                            text = "Lihat rincian COD",
                            fontSize = 12.sp,
                            color = Brown
                        )
                    }

                    Image(
                        painter = painterResource(R.drawable.cod),
                        contentDescription = "Informasi COD",
                        modifier = Modifier.size(48.dp)
                    )
                }
            }

            Spacer(modifier = Modifier.height(26.dp))

            Text(
                text = "MENU PENGIRIMAN",
                fontSize = 13.sp,
                fontWeight = FontWeight.Bold,
                color = Brown
            )

            Spacer(modifier = Modifier.height(12.dp))

            MenuDashboard(
                judul = "Paket Saya",
                deskripsi = "Kelola dan perbarui status pengiriman",
                iconRes = R.drawable.paket,
                onClick = onPaketClick
            )

            Spacer(modifier = Modifier.height(12.dp))

            MenuDashboard(
                judul = "Informasi COD",
                deskripsi = "Lihat paket COD dan jumlah setoran",
                iconRes = R.drawable.cod,
                onClick = onCodClick
            )
        }
    }
}

@Composable
fun MenuDashboard(
    judul: String,
    deskripsi: String,
    iconRes: Int,
    onClick: () -> Unit
) {
    Card(
        modifier = Modifier
            .fillMaxWidth()
            .clickable { onClick() },
        shape = RoundedCornerShape(16.dp),
        colors = CardDefaults.cardColors(
            containerColor = Color.White
        )
    ) {
        Row(
            modifier = Modifier
                .fillMaxWidth()
                .padding(18.dp),
            verticalAlignment = Alignment.CenterVertically
        ) {
            Image(
                painter = painterResource(iconRes),
                contentDescription = judul,
                modifier = Modifier.size(36.dp)
            )

            Spacer(modifier = Modifier.width(14.dp))

            Column {
                Text(
                    text = judul,
                    fontSize = 16.sp,
                    fontWeight = FontWeight.Bold,
                    color = Brown
                )

                Spacer(modifier = Modifier.height(5.dp))

                Text(
                    text = deskripsi,
                    fontSize = 12.sp,
                    color = Muted
                )
            }
        }
    }
}
