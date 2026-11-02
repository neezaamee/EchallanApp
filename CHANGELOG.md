# Changelog - Welfare CMS

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.2.0] - 2025-11-29

### Added

-   **Feedback System**: Complete CRUD system for user feedback
    -   5 feedback types: Suggestion, Complaint, Bug Report, Feature Request, Other
    -   Users can submit feedback with subject and detailed message
    -   Admin can view all feedback with user details (name, email)
    -   Admin can update feedback status (pending, reviewed, resolved)
    -   Admin can add response notes visible to users
    -   Users can view admin responses on their feedback
    -   Feedback accessible via profile dropdown
-   **Role-Specific Dashboards**: Dynamic dashboards for Doctor and Citizen roles
    -   Doctor Dashboard: Statistics for pending unpaid, actionable requests, passed/failed this month
    -   Doctor Dashboard: Medical center information display
    -   Doctor Dashboard: Recent requests table with quick actions
    -   Citizen Dashboard: Overview of total, pending, approved, and unpaid requests
    -   Citizen Dashboard: Recent requests with quick create button
-   **CNIC Lookup for Doctors**: Auto-populate citizen details when creating medical requests
    -   Search by CNIC to find existing citizens
    -   Auto-fill name, father name, phone, and gender if found
    -   Manual entry if citizen not found
-   **Changelog System**: Dual changelog management
    -   Static CHANGELOG.md file for developers
    -   Database-driven changelog with admin management (upcoming)
    -   Public changelog view for users (upcoming)

### Changed

-   Medical request creation flow enhanced for doctors
-   Doctor form now prioritizes CNIC input with search functionality
-   Citizens can be created as "shadow citizens" by doctors before registration
-   Dashboard content now displays role-specific information
-   Feedback link hidden from super_admin role in navigation

### Fixed

-   Admin notes now visible to citizens in feedback detail view
-   Medical request form validation for citizen details
-   Feedback submission form validation (minimum 10 characters for message)
-   Navigation structure for medical requests

## [1.1.0] - 2025-11-28

### Added

-   **Medical Request System**: Complete CRUD for medical requests
    -   Doctors can create requests on behalf of citizens
    -   Citizens can create their own requests
    -   Payment status tracking (paid/unpaid)
    -   Request status workflow (pending, passed, failed)
    -   Doctor action tracking with timestamps
    -   PSID generation for each request
    -   Fixed amount of 500 per request
-   **Shadow Citizen Feature**: Doctors can create citizen records without user accounts
    -   Citizens created by doctors are linked when they register
    -   CNIC-based matching during registration
    -   Automatic user account linking
-   **Creator Tracking**: Track who created each medical request
    -   `created_by` field in medical_requests table
    -   Relationship to User model

### Changed

-   User registration process now checks for existing shadow citizens
-   Medical request index view shows citizen details (name, CNIC)
-   Navigation updated with medical request links

### Fixed

-   Medical request migration with proper foreign keys
-   Citizen model fillable attributes (added father_name)

## [1.0.0] - 2025-11-21

### Added

-   **Initial Release**: Core Welfare CMS functionality
-   **Authentication System**: Laravel Breeze-based authentication
    -   User registration and login
    -   Email verification
    -   Password reset
-   **Authorization System**: Spatie Laravel Permission integration
    -   Role-based access control
    -   Permission management
    -   Roles: Super Admin, Admin, Doctor, Citizen, Challan Officer, Accountant
-   **User Management**: Complete CRUD for users
    -   User creation with role assignment
    -   User profile management
    -   User listing with filters
-   **Staff Management**: CRUD for staff members
    -   Staff creation with designation
    -   Doctor posting management
    -   Active posting tracking
    -   Staff status management
-   **Medical Center Management**: CRUD for medical centers
    -   Medical center creation
    -   Location assignment (province, city, circle)
    -   Center listing and management
-   **Location Management**: Hierarchical location system
    -   Province management
    -   City management (linked to provinces)
    -   Circle management (linked to cities)
-   **Dashboard**: Role-based dashboard system
    -   Welcome header with role display
    -   Date range selector
    -   Placeholder for future statistics
-   **Navigation**: Comprehensive navigation system
    -   Top navbar with profile dropdown
    -   Side navbar with role-based menu items
    -   Responsive design with mobile support

### Security

-   CSRF protection on all forms
-   Authentication middleware on protected routes
-   Role-based authorization on sensitive operations
-   Password hashing with bcrypt
-   SQL injection protection via Eloquent ORM

---

## Version History

-   **1.2.0** (2025-11-29): Feedback system, role-specific dashboards, CNIC lookup
-   **1.1.0** (2025-11-28): Medical request system, shadow citizens
-   **1.0.0** (2025-11-21): Initial release with core functionality

## Contributing

When adding new features or fixes, please update this changelog following the format above.

## Categories

-   **Added**: New features
-   **Changed**: Changes in existing functionality
-   **Deprecated**: Soon-to-be removed features
-   **Removed**: Removed features
-   **Fixed**: Bug fixes
-   **Security**: Security fixes
