# Bank Integration Response Details

Please provide the following information to the bank team for API connectivity and testing.

---

## 🔒 1. Nature of API & Connectivity

- **API Nature**: Publicly Accessible (RESTful) with Header-based Authentication.
- **Connectivity Model**: Direct over HTTPS (Port 443).
- **IP Whitelisting**: Currently **Public/Dynamic**. We request the bank to allow requests from our UAT domain (`wcms.ctpfsd.gop.pk`). We are prepared to implement strict IP Whitelisting once a Static IP is assigned to the production environment.
- **Security**: 
  - Mandatory **SSL/TLS 1.2+** encryption.
  - **Bearer Token Authorization**: Every request must include an `Authorization` header containing the system-generated key.

---

## 🛠️ 2. Testing Tools & Documentation

- **Detailed Documentation**: [EchallanApp API Documentation](https://wcms.ctpfsd.gop.pk/api/docs) (or refer to the attached `API_DOCUMENTATION.md`).
- **Postman Collection**: Attached as `Bank_Integration_Postman.json`.
- **Methods**: `POST` (Standard for both Inquiry and Payment).
- **Endpoint (UAT)**: `https://wcms.ctpfsd.gop.pk/api`
- **Headers**:
  - `Content-Type: application/json`
  - `Authorization: Bearer dummy_token_123`

---

## 📋 3. Test Challans (60 Records)

The following 60 records have been generated in the UAT environment for end-to-end testing of Inquiry and Payment callbacks.

### Traffic Challans (30 Records)
| PSID | Consumer Name | Amount (PKR) | Type |
| :--- | :--- | :--- | :--- |
| 30260329484437195145 | Farhan Saeed | 200.00 | Traffic (Bike) |
| 20260329249021662210 | Hassan Ali | 2000.00 | Traffic (Car) |
| 20260329306831228832 | Iftikhar Ahmad | 2000.00 | Traffic (Car) |
| 30260329178826562540 | Mohammad Nawaz | 200.00 | Traffic (Bike) |
| 20260329121616547384 | Asif Ali | 2000.00 | Traffic (Car) |
| 30260329673007304413 | Khushdil Shah | 200.00 | Traffic (Bike) |
| 30260329085884759439 | Haris Rauf | 200.00 | Traffic (Bike) |
| 20260329170783990263 | Imam-ul-Haq | 2000.00 | Traffic (Car) |
| 30260329402472922131 | Fakhar Zaman | 200.00 | Traffic (Bike) |
| 20260329493059918573 | Shadab Khan | 2000.00 | Traffic (Car) |
| 30260329725062236346 | Naseem Shah | 200.00 | Traffic (Bike) |
| 20260329583273917318 | Shaheen Afridi | 2000.00 | Traffic (Car) |
| 30260329496702825552 | Rizwan Shah | 200.00 | Traffic (Bike) |
| 20260329532151126504 | Babar Azam | 2000.00 | Traffic (Car) |
| 30260329892573349651 | Shoaib Malik | 200.00 | Traffic (Bike) |
| 20260329300826750518 | Waqar Younis | 2000.00 | Traffic (Car) |
| 30260329856315023246 | Tahira Parveen | 200.00 | Traffic (Bike) |
| 30260329098432010463 | Nadia Gul | 200.00 | Traffic (Bike) |
| 20260329147006068654 | Kashif Mehmood | 2000.00 | Traffic (Car) |
| 20260329540907699592 | Sohail Anwar | 2000.00 | Traffic (Car) |
| 30260329396903643482 | Irfan Aziz | 200.00 | Traffic (Bike) |
| 20260329868769977199 | Ayesha Malik | 2000.00 | Traffic (Car) |
| 30260329098152180425 | Zohaib Hassan | 200.00 | Traffic (Bike) |
| 30260329559172525536 | Bilal Ahmad | 200.00 | Traffic (Bike) |
| 20260329052597616529 | Hamza Javed | 2000.00 | Traffic (Car) |
| 20260329446670329608 | Fatima Zahra | 2000.00 | Traffic (Car) |
| 20260329179864831412 | Zahid Khan | 2000.00 | Traffic (Car) |
| 30260329172280104189 | Sara Bibi | 200.00 | Traffic (Bike) |
| 30260329472265389221 | M. Usman | 200.00 | Traffic (Bike) |
| 20260329281631900679 | Ahmad Ali | 2000.00 | Traffic (Car) |

### Medical Requests (30 Records)
| PSID | Consumer Name | Amount (PKR) | Type |
| :--- | :--- | :--- | :--- |
| 10260329558651728792 | Sara Bibi | 200.00 | Medical |
| 10260329181051718085 | Fatima Zahra | 200.00 | Medical |
| 10260329661513296006 | Zahid Khan | 200.00 | Medical |
| 10260329663138811462 | M. Usman | 200.00 | Medical |
| 10260329638349993150 | Farhan Saeed | 200.00 | Medical |
| 10260329720863961947 | Ahmad Ali | 200.00 | Medical |
| 10260329566998779138 | Hassan Ali | 200.00 | Medical |
| 10260329346030302376 | Iftikhar Ahmad | 200.00 | Medical |
| 10260329826684608390 | Mohammad Nawaz | 200.00 | Medical |
| 10260329812679784526 | Khushdil Shah | 200.00 | Medical |
| 10260329128541211965 | Asif Ali | 200.00 | Medical |
| 10260329469429200620 | Haris Rauf | 200.00 | Medical |
| 10260329183061697203 | Imam-ul-Haq | 200.00 | Medical |
| 10260329682476321527 | Shadab Khan | 200.00 | Medical |
| 10260329970264097581 | Fakhar Zaman | 200.00 | Medical |
| 10260329282191381440 | Naseem Shah | 200.00 | Medical |
| 10260329536379075222 | Shaheen Afridi | 200.00 | Medical |
| 10260329571463083530 | Babar Azam | 200.00 | Medical |
| 10260329715791260284 | Rizwan Shah | 200.00 | Medical |
| 10260329093055469742 | Shoaib Malik | 200.00 | Medical |
| 10260329103588378197 | Waqar Younis | 200.00 | Medical |
| 10260329646057374815 | Kashif Mehmood | 200.00 | Medical |
| 10260329925214122984 | Tahira Parveen | 200.00 | Medical |
| 10260329386438325192 | Nadia Gul | 200.00 | Medical |
| 10260329563761159310 | Sohail Anwar | 200.00 | Medical |
| 10260329500095589051 | Ayesha Malik | 200.00 | Medical |
| 10260329984844922084 | Irfan Aziz | 200.00 | Medical |
| 10260329430067447653 | Hamza Javed | 200.00 | Medical |
| 10260329806655309200 | Zohaib Hassan | 200.00 | Medical |
| 10260329834700708971 | Bilal Ahmad | 200.00 | Medical |
