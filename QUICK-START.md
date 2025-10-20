# 🚀 Quick Start Guide

## Why Your Website Isn't Working

**The issue:** MySQL database server is not running.

## Fix in 3 Steps

### Step 1: Start MySQL
1. Open **XAMPP Control Panel** (search for "XAMPP" in Windows Start menu)
2. Click **Start** next to **MySQL**
3. Wait until it shows "Running" status (green background)

![XAMPP Control Panel](https://i.imgur.com/example.png)

### Step 2: Create Database
1. Open your browser and go to: **http://localhost/phpmyadmin**
2. Click **"New"** in the left sidebar
3. Database name: `career_counseling_platform`
4. Collation: `utf8mb4_general_ci`
5. Click **"Create"**

### Step 3: Import Database Schema
1. In phpMyAdmin, select `career_counseling_platform` database (left sidebar)
2. Click **"Import"** tab (top menu)
3. Click **"Choose File"**
4. Navigate to: `C:\xampp\htdocs\project test1\database\schema.sql`
5. Click **"Import"** (bottom of page)
6. Wait for "Import has been successfully finished"

## Verify Everything Works

Visit the diagnostic page to check your setup:

**http://localhost/project%20test1/public/diagnostic.php**

You should see:
- ✓ MySQL server is running and accessible
- ✓ Database 'career_counseling_platform' exists  
- ✓ Found X tables

## Access Your Website

Once everything is green on the diagnostic page:

**Homepage:** http://localhost/project%20test1/public

**Login:** http://localhost/project%20test1/public/auth/login
- Email: `admin@careerplatform.com`
- Password: `admin123`

## Still Having Issues?

1. **Apache not starting?**
   - Check if port 80 is in use by another program (Skype, IIS)
   - Stop the conflicting program or change Apache port in XAMPP config

2. **MySQL not starting?**
   - Check if port 3306 is in use
   - Check `C:\xampp\mysql\data\mysql_error.log` for errors

3. **Database exists but website still fails?**
   - Visit: http://localhost/project%20test1/public/diagnostic.php
   - Check the error messages
   - Verify credentials in `app/config/config.php`

4. **Page not found (404)?**
   - Make sure Apache is running
   - Check the URL is exactly: `http://localhost/project%20test1/public`
   - Verify `.htaccess` file exists in the public folder

## Need Help?

Run the diagnostic tool: **http://localhost/project%20test1/public/diagnostic.php**

It will tell you exactly what's wrong and how to fix it.
