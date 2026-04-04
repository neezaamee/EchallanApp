# Bank Integration & PSID Specification Guide

**Version**: 1.5.0
**Project**: EchallanApp - Standardized Revenue Collection System

---

## 🚀 Overview

This document outlines the technical specifications for integrating banking software and mobile wallets (1Link/1Bill) with the EchallanApp payment gateway. It details the **20-digit Bank-Grade PSID** structure and the API endpoints required for real-time inquiry and payment notification.

---

## 🛠️ 1. PSID Specification (20 Digits)

The PSID (Payment Slip ID) is a unique, 20-digit numeric string used to identify and validate payments across all channels.

### Structure Breakdown:

`[Category(1)] [City(3)] [Date(6)] [Random(10)]`

| Component     | Length | Description                                                    |
| :------------ | :----: | :------------------------------------------------------------- |
| **Category**  |   1    | `1`: Medical, `2`: Car Traffic, `3`: Bike Traffic, `4`: Others |
| **City Code** |   3    | Standard 3-digit city code (e.g., `002` for Faisalabad)        |
| **Date**      |   6    | Issuance date in `YYMMDD` format                               |
| **Random ID** |   10   | Unique random sequence to prevent collisions                   |

### ✅ Check Digit Validation (Luhn Algorithm)

All PSIDs are generated with a terminal check digit to prevent user input errors.

- **Verification**: Banks **MUST** validate the 20th digit using the Luhn Algorithm before processing the inquiry to minimize invalid requests to the server.

---

## 🔗 2. Bank API Endpoints

### 📡 A. Bill Inquiry

Used by the bank to fetch consumer details before initializing a payment.

- **Endpoint**: `POST /api/payment/inquiry`
- **Authentication**: `Authorization: Bearer <TOKEN>`

**JSON Request:**

```json
{
    "consumer_number": "10022603291234567897"
}
```

**JSON Success Response (00):**

```json
{
    "status": "00",
    "message": "Record Found",
    "data": {
        "consumer_number": "10022603291234567897",
        "consumer_name": "Nadia G",
        "amount_due": 200,
        "amount_within_due_date": 200,
        "amount_after_due_date": 200,
        "billing_month": "202603",
        "due_date": "20260428",
        "status": "U"
    }
}
```

---

### 💳 B. Payment Notification (Callback)

Used by the bank to notify EchallanApp of a successful transaction.

- **Endpoint**: `POST /api/payment/callback`
- **Authentication**: `Authorization: Bearer <TOKEN>`

**JSON Request:**

```json
{
    "consumer_number": "10022603291234567897",
    "transaction_id": "BANK-TXN-123456",
    "amount_paid": 200,
    "transaction_date": "2026-03-29"
}
```

**JSON Success Response:**

```json
{
    "status": "00",
    "message": "Payment Successful"
}
```

---

## 📱 3. 1Link / Mobile Wallet Simulation

For integrations with mobile wallets like **EasyPaisa** or **JazzCash**.

| Action      | Endpoint             | Method |
| :---------- | :------------------- | :----- |
| **Inquiry** | `/api/1link/inquiry` | POST   |
| **Payment** | `/api/1link/payment` | POST   |

---

## 🔒 4. Security & Authentication

All API requests must include a valid authentication token in the request headers:

- **Header Name**: `Authorization`
- **Format**: `Bearer dummy_token_123` (Replace with production token provided by System Admin)

---

## 🧪 5. Testing & UAT

Banking partners are encouraged to use our **Sanbox Mock Bank** for end-to-end testing:

1.  Generate a test PSID via the Echallan Dashboard (Medical/Traffic module).
2.  Use Postman to call the **Inquiry API** to verify details.
3.  Process a test payment via the **Callback API**.
4.  Verify that the status changes to "Paid" on the Echallan User Dashboard.

---

**Technical Support**: contact@ctpfsd.gop.pk
**Last Updated**: March 29, 2026
