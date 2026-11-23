
package com.example.priangankilatexpress

import retrofit2.Call
import retrofit2.http.Body
import retrofit2.http.GET
import retrofit2.http.Header
import retrofit2.http.PATCH
import retrofit2.http.POST
import retrofit2.http.Path

// ================================
// LOGIN
// ================================

data class LoginRequest(
    val kode_kurir: String,
    val password: String
)

data class KurirData(
    val id: Int,
    val kode_kurir: String,
    val nama: String
)

data class LoginResponse(
    val message: String,
    val token: String,
    val wajib_ganti_password: Boolean,
    val kurir: KurirData
)

// ================================
// GANTI PASSWORD
// ================================

data class ChangePasswordRequest(
    val password_lama: String,
    val password_baru: String,
    val password_baru_confirmation: String
)

data class ApiMessageResponse(
    val message: String
)

// ================================
// DATA PAKET
// ================================

data class PaketApiData(
    val id: Int,
    val no_resi: String,
    val nama_penerima: String,
    val no_telepon_penerima: String,
    val alamat_penerima: String,
    val kota_tujuan: String,
    val berat: Double,
    val layanan: String,
    val nilai_cod: Int,
    val status: String,
    val jumlah_gagal: Int
)

data class PaketResponse(
    val message: String,
    val data: List<PaketApiData>
)

// ================================
// UPDATE STATUS
// ================================

data class UpdateStatusRequest(
    val status: String,
    val catatan: String? = null
)

// ================================
// DATA COD
// ================================

data class CodApiData(
    val id: Int,
    val no_resi: String,
    val nama_penerima: String,
    val nilai_cod: Int,
    val status: String,
    val cod_disetor: Boolean
)

data class CodResponse(
    val message: String,
    val jumlah_paket: Int,
    val total_cod_belum_disetor: Int,
    val data: List<CodApiData>
)

// ================================
// API SERVICE
// ================================

interface ApiService {

    // Login kurir
    @POST("kurir/login")
    fun login(
        @Body request: LoginRequest
    ): Call<LoginResponse>

    // Ganti password
    @POST("kurir/ganti-password")
    fun changePassword(
        @Header("Authorization") token: String,
        @Body request: ChangePasswordRequest
    ): Call<ApiMessageResponse>

    // Mengambil daftar paket kurir
    @GET("kurir/paket")
    fun getPaket(
        @Header("Authorization") token: String
    ): Call<PaketResponse>

    // Mengambil data COD kurir
    @GET("kurir/cod")
    fun getCod(
        @Header("Authorization") token: String
    ): Call<CodResponse>

    // Memperbarui status paket
    @PATCH("kurir/paket/{paket}/status")
    fun updateStatus(
        @Header("Authorization") token: String,
        @Path("paket") idPaket: Int,
        @Body request: UpdateStatusRequest
    ): Call<ApiMessageResponse>

    // Logout kurir
    @POST("kurir/logout")
    fun logout(
        @Header("Authorization") token: String
    ): Call<ApiMessageResponse>
}
