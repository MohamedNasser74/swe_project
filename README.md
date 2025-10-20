# Virtual Career Counseling Platform

A comprehensive web application built with PHP MVC architecture that provides personalized career guidance, resume building tools, interview preparation, and job search resources for students.

## Features

### 🎯 Core Features
- **User Authentication & Authorization** - Secure login system with role-based access (Students, Counselors, Admins)
- **Appointment Scheduling** - Book one-on-one sessions with career counselors
- **Interview Preparation** - Mock interviews and personalized feedback
- **Job Search Tools** - Integrated job listings and application tracking
- **Admin Dashboard** - Comprehensive management panel for administrators

### 👥 User Roles
- **Students** - Access all career development resources and book counseling sessions
- **Career Counselors** - Manage appointments, provide guidance, and moderate forums
- **Administrators** - Full platform management and analytics

## Technology Stack

- **Backend**: PHP 7.4+ with MVC Architecture
- **Database**: MySQL 5.7+
- **Frontend**: Bootstrap 5, HTML5, CSS3, JavaScript
- **Server**: Apache with mod_rewrite
- **Icons**: Font Awesome 6
- **Security**: PDO prepared statements, password hashing, CSRF protection

## Project Structure

```
project test1/
├── app/
│   ├── config/
│   │   └── config.php          # Application configuration
│   ├── constants/
│   │   └── Constants.php       # Application constants
│   ├── controllers/
│   │   ├── AuthController.php  # Authentication logic
│   │   ├── HomeController.php  # Home page logic
│   │   ├── StudentController.php # Student dashboard logic
│   │   ├── CounselorController.php # Counselor dashboard
│   │   ├── AdminController.php # Admin dashboard
│   │   └── ForumController.php # Forum logic
│   ├── core/
│   │   ├── App.php             # Front controller
│   │   ├── Controller.php      # Base controller class
│   │   ├── Database.php        # Database connection class
│   │   ├── Model.php           # Base model class
│   │   └── Mailer.php          # Email sending class
│   ├── helpers/
│   │   ├── FormHelper.php      # Form utilities
│   │   └── ValidationHelper.php # Validation utilities
│   ├── models/
│   │   ├── User.php            # User model
│   │   ├── Appointment.php     # Appointment model
│   │   ├── ForumTopic.php      # Forum topic model
│   │   └── ForumReply.php      # Forum reply model
│   └── views/
│       ├── layouts/
│       │   └── main.php        # Main layout template
│       ├── partials/
│       │   ├── navbar.php      # Navigation menu
│       │   ├── flash.php       # Flash messages
│       │   └── footer.php      # Footer
│       ├── home/
│       │   └── index.php       # Homepage view
│       ├── auth/
│       │   ├── login.php       # Login form
│       │   ├── register.php    # Registration form
│       │   ├── forgot-password.php # Forgot password
│       │   ├── enter-code.php  # Enter reset code
│       │   └── reset-password.php # Reset password
│       ├── student/
│       │   ├── dashboard.php   # Student dashboard
│       │   ├── appointments.php # Appointments list
│       │   └── profile.php     # Student profile
│       ├── counselor/
│       │   └── dashboard.php   # Counselor dashboard
│       ├── admin/
│       │   └── dashboard.php   # Admin dashboard
│       └── forum/
│           └── index.php       # Forum view
├── database/
│   └── schema.sql              # Database schema
├── dev-tools/                  # Development utilities (not in production)
│   ├── create_test_users.php  # Test user creation
│   ├── debug-login.php         # Login debugging
│   └── test-db.php             # Database testing
├── public/                     # Public web root
│   ├── css/
│   │   └── style.css           # Custom styles
│   ├── js/
│   │   └── main.js             # Custom JavaScript
│   ├── images/                 # Image assets
│   ├── uploads/                # File uploads directory
│   ├── .htaccess               # URL rewriting rules
│   └── index.php               # Application entry point
├── vendor/                     # Composer dependencies
├── composer.json               # Composer configuration
├── README.md                   # This file
└── REORGANIZATION.md           # Project structure documentation
```

## Installation & Setup

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache web server with mod_rewrite enabled
- XAMPP/WAMP/LAMP stack (recommended for development)

### Step 1: Clone/Download the Project
Place the project in your web server's document root:
- For XAMPP: `C:\xampp\htdocs\project test1\`
- For WAMP: `C:\wamp64\www\project test1\`

### Step 2: Database Setup
1. Open phpMyAdmin or your preferred MySQL client
2. Create a new database named `career_counseling_platform`
3. Import the database schema:
   ```sql
   -- Run the contents of database/schema.sql
   ```
   Or import the file directly through phpMyAdmin

### Step 3: Configuration
1. Open `app/config/config.php`
2. Update database credentials if needed:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', ''); // Your MySQL password
   define('DB_NAME', 'career_counseling_platform');
   ```
3. Update the application URL:
   ```php
   define('APP_URL', 'http://localhost/project%20test1/public');
   ```

### Step 4: File Permissions
Ensure the `public/uploads/` directory is writable:
```bash
chmod 755 public/uploads/
```

### Step 5: Access the Application
1. Start your web server (Apache) and MySQL
2. Visit: `http://localhost/project%20test1/public`
3. Default admin credentials:
   - Email: `admin@careerplatform.com`
   - Password: `admin123`

## Default User Accounts

The database comes with a pre-configured admin account:
- **Username**: admin
- **Email**: admin@careerplatform.com
- **Password**: admin123
- **Role**: Administrator

## Key Features Implementation

### 🔐 Authentication System
- Secure password hashing using PHP's `password_hash()`
- Role-based access control
- Session management

### 📅 Appointment System
- Appointment booking interface
- Appointment status management
- Session type selection (Career Guidance, Interview Prep, Job Search)

### 💼 Job Search System
- Job listing interface
- Job application tracking
- Search and filter functionality

### 📊 Admin Dashboard
- User management
- Basic platform statistics
- User role management

## Development Roadmap

### Phase 1 (Current)
- ✅ Basic MVC structure
- ✅ User authentication
- ✅ Database schema
- ✅ Basic UI/UX

### Phase 2 (Next)
- [ ] Complete appointment system
- [ ] Resume builder implementation
- [ ] Forum functionality
- [ ] Email integration

### Phase 3 (Future)
- [ ] Job search integration
- [ ] Video calling for appointments
- [ ] Mobile responsiveness
- [ ] API development

## Customization

### Adding New Controllers
1. Create new controller in `app/controllers/`
2. Extend the base `Controller` class
3. Follow naming convention: `ControllerNameController.php`

### Adding New Models
1. Create new model in `app/models/`
2. Extend the base `Model` class
3. Define table relationships and methods

### Styling
- Main CSS file: `public/css/style.css`
- Uses Bootstrap 5 for responsive design
- Custom CSS variables for easy theming

## Database Schema Overview

### Core Tables
- `users` - User accounts and authentication
- `student_profiles` - Extended student information
- `counselor_profiles` - Counselor specializations and availability
- `appointments` - Scheduled counseling sessions
- `forum_topics` & `forum_replies` - Discussion system
- `job_postings` & `job_applications` - Job search functionality
- `resume_templates` & `student_resumes` - Resume building system

### Security Features
- PDO prepared statements prevent SQL injection
- Password hashing for secure authentication
- CSRF token support (ready for implementation)
- Input validation and sanitization
- Role-based access control

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## Support

For support and questions:
- Create an issue in the repository
- Contact: admin@careerplatform.com

## License

This project is licensed under the MIT License. See LICENSE file for details.

---

**Note**: This is a development version. For production deployment, ensure proper security configurations, SSL certificates, and environment-specific settings are implemented.

## Enabling Email (PHPMailer + SMTP)

PHPMailer is recommended for reliable SMTP delivery. To enable:

1. From the project root run (PowerShell):

```powershell
composer require phpmailer/phpmailer
```

2. Configure SMTP credentials in `app/config/config.php`:

```php
define('SMTP_HOST', 'smtp.example.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-smtp-username');
define('SMTP_PASSWORD', 'your-smtp-password');
```

3. Restart your web server and test the Forgot Password flow.

If you don't install PHPMailer, the app falls back to PHP's mail(), which may require additional configuration on Windows/XAMPP.