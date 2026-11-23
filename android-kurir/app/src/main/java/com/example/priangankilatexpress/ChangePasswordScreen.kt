
package com.example.priangankilatexpress

import androidx.compose.foundation.layout.*
import androidx.compose.foundation.text.KeyboardOptions
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.text.input.KeyboardType
import androidx.compose.ui.text.input.PasswordVisualTransformation
import androidx.compose.ui.unit.dp
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

@Composable
fun ChangePasswordScreen(
    token: String,
    onPasswordChanged: () -> Unit
) {
    var passwordLama by remember { mutableStateOf("") }
    var passwordBaru by remember { mutableStateOf("") }
    var konfirmasiPassword by remember { mutableStateOf("") }

    var pesan by remember { mutableStateOf("") }
    var loading by remember { mutableStateOf(false) }

    Column(
        modifier = Modifier
            .fillMaxSize()
            .padding(24.dp),
        horizontalAlignment = Alignment.CenterHorizontally,
        verticalArrangement = Arrangement.Center
    ) {
        Text(
            text = "GANTI PASSWORD",
            style = MaterialTheme.typography.headlineSmall
        )

        Spacer(modifier = Modifier.height(8.dp))

        Text("Silakan buat password baru untuk akun kurirmu.")

        Spacer(modifier = Modifier.height(24.dp))

        OutlinedTextField(
            value = passwordLama,
            onValueChange = { passwordLama = it },
            label = { Text("Password Lama") },
            visualTransformation = PasswordVisualTransformation(),
            keyboardOptions = KeyboardOptions(
                keyboardType = KeyboardType.Password
            ),
            modifier = Modifier.fillMaxWidth(),
            singleLine = true
        )

        Spacer(modifier = Modifier.height(12.dp))

        OutlinedTextField(
            value = passwordBaru,
            onValueChange = { passwordBaru = it },
            label = { Text("Password Baru") },
            visualTransformation = PasswordVisualTransformation(),
            keyboardOptions = KeyboardOptions(
                keyboardType = KeyboardType.Password
            ),
            modifier = Modifier.fillMaxWidth(),
            singleLine = true
        )

        Spacer(modifier = Modifier.height(12.dp))

        OutlinedTextField(
            value = konfirmasiPassword,
            onValueChange = { konfirmasiPassword = it },
            label = { Text("Konfirmasi Password Baru") },
            visualTransformation = PasswordVisualTransformation(),
            keyboardOptions = KeyboardOptions(
                keyboardType = KeyboardType.Password
            ),
            modifier = Modifier.fillMaxWidth(),
            singleLine = true
        )

        Spacer(modifier = Modifier.height(16.dp))

        if (pesan.isNotEmpty()) {
            Text(text = pesan)

            Spacer(modifier = Modifier.height(12.dp))
        }

        Button(
            onClick = {
                when {
                    passwordLama.isBlank() ||
                            passwordBaru.isBlank() ||
                            konfirmasiPassword.isBlank() -> {
                        pesan = "Semua kolom wajib diisi."
                    }

                    passwordBaru.length < 8 -> {
                        pesan = "Password baru minimal 8 karakter."
                    }

                    passwordBaru != konfirmasiPassword -> {
                        pesan = "Konfirmasi password tidak cocok."
                    }

                    passwordBaru == passwordLama -> {
                        pesan = "Password baru harus berbeda dari password lama."
                    }

                    else -> {
                        loading = true
                        pesan = ""

                        val request = ChangePasswordRequest(
                            password_lama = passwordLama,
                            password_baru = passwordBaru,
                            password_baru_confirmation = konfirmasiPassword
                        )

                        ApiClient.apiService.changePassword(
                            "Bearer $token",
                            request
                        ).enqueue(
                            object : Callback<ApiMessageResponse> {

                                override fun onResponse(
                                    call: Call<ApiMessageResponse>,
                                    response: Response<ApiMessageResponse>
                                ) {
                                    loading = false

                                    if (response.isSuccessful) {
                                        pesan = "Password berhasil diganti. Silakan login kembali."
                                        onPasswordChanged()
                                    } else {
                                        pesan = "Gagal mengganti password. Periksa password lama dan data yang dimasukkan."
                                    }
                                }

                                override fun onFailure(
                                    call: Call<ApiMessageResponse>,
                                    t: Throwable
                                ) {
                                    loading = false
                                    pesan = "Koneksi gagal: ${t.message}"
                                }
                            }
                        )
                    }
                }
            },
            enabled = !loading,
            modifier = Modifier
                .fillMaxWidth()
                .height(50.dp)
        ) {
            Text(
                text = if (loading) {
                    "MEMPROSES..."
                } else {
                    "SIMPAN PASSWORD"
                }
            )
        }
    }
}
