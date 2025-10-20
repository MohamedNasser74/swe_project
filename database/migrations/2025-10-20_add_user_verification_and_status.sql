-- Migration: Add user verification and status columns to users table
-- Date: 2025-10-20

USE career_counseling_platform;

-- Add status column if missing
ALTER TABLE users
    ADD COLUMN IF NOT EXISTS status ENUM('active', 'inactive', 'suspended') NOT NULL DEFAULT 'active' AFTER role;

-- Add optional email verification columns if missing
ALTER TABLE users
    ADD COLUMN IF NOT EXISTS verification_token VARCHAR(64) NULL AFTER email_verified,
    ADD COLUMN IF NOT EXISTS verification_expires DATETIME NULL AFTER verification_token;

-- Verification: describe users to confirm columns
-- DESCRIBE users;
