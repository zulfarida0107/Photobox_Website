# Postman Quick Start

## Files
- `Photobox Desktop Flow.postman_collection.json`
- `Photobox Desktop Flow.postman_environment.json`

## Import
1. Open Postman.
2. Import kedua file di folder ini.
3. Pilih environment `Photobox Local`.

## Before Run
1. Jalankan seeder demo:
	`php spark db:seed DemoFlowSeeder`
1. Isi `bookingCode` di environment.
2. Pastikan `baseUrl` sesuai URL CI4 kamu.
3. Jika HTTPS self-signed, nonaktifkan SSL verification di Postman untuk local test.
4. Isi path file valid untuk `photoPath1` dan `photoPath2`.

## Run Order
1. `1) Verify Booking`
2. `2) List Print Options`
3. `3) Create Payment QR`
4. `4) Check Payment Status`
5. `5) Simulate Midtrans Callback (Paid)`
6. `4) Check Payment Status` (ulang untuk pastikan paid)
7. `6) List Frames`
8. `7) Start Session`
9. `8) Upload Photos`
10. `9) Submit Email`
11. `10) Complete Session`

## Notes
- Collection akan auto-set sebagian variable (`bookingId`, `printOptionId`, `paymentId`, `frameId`, `filterId`, `sessionId`) dari response.
- Endpoint callback Midtrans di collection ini untuk simulasi local, belum validasi signature Midtrans.
- Seeder demo membuat booking code: `BOOK-DEMO-001`.
