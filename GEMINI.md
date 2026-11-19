# Project Blueprint & Context: WCMS (E-Challan & Medical System)

## 1. Project Overview & Tech Stack
- **Application**: Electronic Traffic Challan & Citizen Medical Examination System.
- **Framework**: Laravel 11.x, Livewire 3.x, Alpine.js.
- **UI Theme**: Falcon Admin Theme (Bootstrap 5, FontAwesome 5).
- **Database**: MySQL (Local Laragon `wcms`, Live Shared Hosting via cPanel).
- **Timezone**: `Asia/Karachi` (Pakistan Standard Time, UTC+5) configured in `config/app.php`.
- **Git Branch**: Working branch is `home-laptop-red` on `origin` (`https://github.com/neezaamee/EchallanApp.git`).

---

## 2. Core Modules & Domain Architecture

### A. Citizen Medical Examination Requests (`MedicalRequest`, `Citizen`, `MedicalCenter`)
- **Workflow**:
  1. Super Admin or authorized Staff creates a Medical Examination request for a Citizen (`/medical-requests/create`).
  2. System generates a 24-digit 1Bill PSID under category `MEDICAL`.
  3. Citizen receives a printable 1Bill Challan Slip/Voucher or 80mm Thermal Receipt.
  4. Citizen pays via 1Link 1Bill in banking apps/ATM or at the medical center desk.
  5. Doctor reviews citizen vitals and completes the medical fitness report.
- **Key URLs & Views**:
  - Details Page: `/medical-requests/{id}` (`resources/views/app/medical-requests/show.blade.php`)
  - 80mm Thermal Receipt: `/medical-requests/{id}/thermal` (`?print=1` auto-triggers print; designed for continuous roll 72mm printable width, zero-margin `@page`).
  - 1Bill Voucher (A4 3-copy): `/medical-requests/{id}/voucher` & `/medical-requests/{id}/voucher/pdf`.

### B. Traffic Violations & Challans (`Challan`, `DumpingPoint`, `PickUpPoint`)
- **Workflow**: Traffic officers issue challans against vehicles (Cars, Bikes, Others) at designated dumping/pickup points.
- **Status lifecycle**: `pending` -> `paid` (or `impounded` -> `released`).
- **PSID Heads**: `TRAFFIC_CAR` (Car fines) and `TRAFFIC_BIKE` (Motorcycle fines).

### C. Warnings Management (`Warning`)
- Warning notices issued to vehicle owners with photo evidence, circle/sector tracking, and exportable reports.

### D. User Roles & Access Control (`spatie/laravel-permission`)
- Roles: `Super Admin`, `CTO`, `Doctor`, `Duty Officer`, `Staff`, `Citizen`.

### E. Reports & Analytics Module
- **Endpoints & Permissions**:
  - Analytics Dashboard: `/reports` & `/reports/analytics` (`reports:view` or `reports:analytics`)
  - Citizen Medical Examination Reports: `/reports/medical` (`reports:medical`)
  - Traffic Violations & Challans Report: `/reports/challans` (`reports:challans` or `reports:lifter-squad`)
  - Traffic Warnings Summary & Detailed Log: `/reports/warnings` (`reports:warnings`)
  - Financial & Revenue Collection Reports: `/reports/finance` & `/reports/payments/*` (`reports:finance` or `reports:view`)
- **Permission Management**:
  - Permissions are prefixed with `reports:` and grouped under "Reports Module" in `/admin/roles/{id}/edit` and `/admin/users/{id}/edit`.
  - Super Admin can grant or revoke specific report access individually to any role or user.
  - Sidebar dynamically hides unpermitted report links and hides the entire Reports & Analytics group if user has zero reports permissions.

---

## 3. 1Bill PSID Architecture & Bank Integration

### PSID Structure (V2 - 1Link 1Bill Compliant)
- **Total Length**: 24 digits (`[Bank Prefix (12)][Core PSID (12)]`).
- **Active Version**: Configured via `PSID_VERSION=12` in `.env` (`config/bank.php`).
- **12-Digit Core Breakdown**:
  `[Category(1)][Year yy(2)][DayOfYear ddd(3)][Random(5)][Luhn CheckDigit(1)]`
- **Category Codes (1st digit of Core)**:
  - `1` = Medical Examination (`MEDICAL`)
  - `2` = Car Traffic Challan (`TRAFFIC_CAR` / `CAR`)
  - `3` = Bike Traffic Challan (`TRAFFIC_BIKE` / `BIKE`)
  - `4` = Other (`TRAFFIC_OTHER` / `OTHER`)

### Category-Specific Bank Prefixes (`config/bank.php`)
- The system supports distinct bank prefixes per category with fallback to `BANK_PREFIX`:
  ```dotenv
  BANK_PREFIX=111787477101
  BANK_PREFIX_CAR=111787477101
  BANK_PREFIX_BIKE=111787477101
  BANK_PREFIX_MEDICAL=111787477101
  ```
- **`App\Services\BankService` Core Methods**:
  - `generatePsid(string $headType, float $amount, $cityId = null): string`: Generates full 24-digit PSID with category-specific prefix.
  - `getBankPrefix(?string $headType = null): string`: Resolves category prefix (supports aliases `CAR`, `BIKE`, `MEDICAL`, `OTHER`).
  - `getAllBankPrefixes(): array`: Returns unique list of all configured prefixes sorted by length descending.
  - `normalizePsid(string $consumerNumber): string`: Strips whichever category prefix matches, extracting the 12-digit core.
  - `validatePsid(string $psid): bool`: Validates PSID using Luhn checksum (works on 12-digit cores, 24-digit prefixed numbers, and legacy 20-digit V1).
  - `format1BillNumber(string $psid, ?string $headType = null): string`: Formats for 1Bill presentation without double-prefixing.

### 1Link Inbound APIs
- **Inquiry**: `POST /api/payment/inquiry` and `POST /api/1link/inquiry`
- **Payment**: `POST /api/payment/callback` and `POST /api/1link/payment`
- **Candidate Matching**: Resolves PSID against database by checking `$rawPsid`, normalized `$psid`, and all `$prefix . $psid` variations.
- **Validation Rule**: `App\Rules\ValidPsid` ensures incoming consumer numbers are numeric, match expected lengths (12, 20, or 12+prefixLen), and pass Luhn verification.

---

## 4. UI & Printing Conventions
- **Thermal Receipts**:
  - Designed strictly for 80mm POS thermal printers (printable body width: `72mm`, margins `0 auto`, font size `11px - 13px`).
  - Uses CSS `@media print` with `@page { size: 80mm auto; margin: 0; }`.
  - When `?print=1` is present in URL, auto-invokes `window.print()` after load.
- **1Bill Slips & PDF Generation**:
  - Generates barcodes via `App\Services\BarcodeService` or Code128 format (`*PSID*`).
  - Uses `chunk_split($psid, 4, ' ')` for human readability in bank apps.

---

## 5. Deployment & Shared Hosting Guidelines
- **Live Environment**: cPanel Shared Hosting.
- **Workflow**:
  1. Commit and push tested changes to `origin home-laptop-red`.
  2. Upload modified files to live hosting.
  3. Ensure server `.env` matches required variables.
  4. Always clear Laravel caches after deploying:
     ```bash
     php artisan config:clear
     php artisan route:clear
     php artisan view:clear
     ```
- **Local Testing Note**: When running artisan tests or scripts connecting to MySQL on Windows, run with elevated access (`BypassSandbox: true`) if standard sandbox blocks localhost socket connections.
