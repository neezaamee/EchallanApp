# EchallanApp API Documentation

Welcome to the **EchallanApp** API documentation. This document provides technical specifications for integrating with our payment and inquiry systems.

## 📌 General Information

- **Base URL**: `https://wcms.ctpfsd.gop.pk/api`
- **Default Format**: `JSON`
- **Timezone**: `Asia/Karachi` (UTC+5)

---

## 🔐 Authentication

All API requests (except where noted) require authentication.

### Bank & 3rd Party Integrations
Use **Bearer Token** authentication in the request header.

**Header Format:**
```http
Authorization: Bearer YOUR_CALLBACK_TOKEN
```

> [!NOTE]
> The `YOUR_CALLBACK_TOKEN` is configured in the application's `config/bank.php` file under `sandbox.callback_token`.

---

## 💳 1Link Integration (Simulation)

These endpoints simulate the 1Link bill inquiry and payment flow.

### 1. Bill Inquiry
Fetch bill details using the Consumer Number (PSID).

- **Endpoint**: `POST /1link/inquiry`
- **Authentication**: Bearer Token required.

**Request Body:**
```json
{
  "consumer_number": "99260329123456789012"
}
```

**Success Response (200 OK):**
```json
{
  "response_code": "00",
  "bill_status": "U",
  "amount_within_due_date": "500",
  "amount_after_due_date": "500",
  "created_at": "20260329",
  "due_date": "20260428",
  "consumer_name": "John Doe"
}
```

**Common Response Codes:**
| Code | Meaning |
| :--- | :--- |
| `00` | Success |
| `01` | Consumer Not Found |

---

### 2. Bill Payment
Notify the system of a successful payment via 1Link.

- **Endpoint**: `POST /1link/payment`
- **Authentication**: Bearer Token required.

**Request Body:**
```json
{
  "consumer_number": "99260329123456789012",
  "amount_paid": 500,
  "transaction_id": "ABC123XYZ789",
  "transaction_datetime": "2026-03-29 14:30:00"
}
```

**Success Response (200 OK):**
```json
{
  "response_code": "00",
  "response_message": "Payment Successful",
  "identification_parameter": "ABC123XYZ789"
}
```

---

## 🏦 Bank Payment Integration (1Bill/General)

Standard webhook endpoints for bank-direct integrations.

### 1. Payment Inquiry
Used by banks to verify PSID before processing payment.

- **Endpoint**: `POST /payment/inquiry`
- **Authentication**: Bearer Token required.

**Success Response (200 OK):**
```json
{
  "status": "00",
  "message": "Record Found",
  "data": {
    "consumer_number": "99260329123456789012",
    "consumer_name": "Jane Smith",
    "amount_due": 500,
    "billing_month": "202603",
    "due_date": "20260428",
    "status": "U"
  }
}
```

### 2. Payment Callback (Webhook)
The bank notifies the system upon successful transaction completion.

- **Endpoint**: `POST /payment/callback`
- **Authentication**: Bearer Token required.

**Request Body:**
```json
{
  "consumer_number": "99260329123456789012",
  "transaction_id": "BANK-TXN-998877",
  "amount_paid": 500,
  "transaction_date": "2026-03-29"
}
```

---

## 🧪 Developer Sandbox (Mock Bank)

Use these tools to test your integration without real banking credentials.

### 1. Generate Test PSID
Generate a valid 20-digit PSID for testing.

- **Endpoint**: `POST /mock-bank/generate-psid`
- **Authentication**: Bearer Token (Optional in sandbox)

**Request Body:**
```json
{
  "head": "MEDICAL",
  "amount": 500
}
```
*Valid Heads: `MEDICAL`, `TRAFFIC_CAR`, `TRAFFIC_BIKE`*

### 2. Force Pay (Mock Status)
Manually mark a PSID as PAID in the mock bank's database.

- **Endpoint**: `POST /mock-bank/pay`
- **Request Body**: `{"psid": "20-digit-number"}`

### 3. Sync Local Status
Simulate the application background job that checks the bank for paid PSIDs.

- **Endpoint**: `POST /mock-bank/sync-local-status`
- **Request Body**: `{"psid": "20-digit-number"}`

---

## ⚠️ Error Handling

The API uses standard HTTP status codes along with specific application codes.

| HTTP Code | Description |
| :--- | :--- |
| `200` | Success |
| `400` | Bad Request (Validation failed or invalid payload) |
| `401` | Unauthorized (Missing or invalid Bearer token) |
| `404` | Not Found (Consumer number does not exist) |
| `500` | System Error |

---

> [!TIP]
> Always verify that your `Authorization` header includes the word `Bearer` before your token. If security is disabled in `config/bank.php`, you can test without tokens.
