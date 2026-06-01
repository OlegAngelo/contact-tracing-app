-- Migration: Add non-USC visitor support
-- This script adds columns to support non-USC (temporary visitor) registration

-- Modify usc_id to allow NULL values for non-USC visitors
ALTER TABLE users MODIFY COLUMN usc_id VARCHAR(20) UNIQUE NULL;

-- Add visitor_type column to distinguish between USC and non-USC visitors
ALTER TABLE users ADD COLUMN visitor_type ENUM('USC', 'NON_USC') DEFAULT 'USC' AFTER usc_id;

-- Add visitor_id column for non-USC temporary visitor IDs
ALTER TABLE users ADD COLUMN visitor_id VARCHAR(20) UNIQUE NULL AFTER visitor_type;

-- Add is_signed_in column to track current sign status
ALTER TABLE users ADD COLUMN is_signed_in BOOLEAN DEFAULT FALSE AFTER visitor_id;

-- Create index on visitor_id for faster lookups
CREATE INDEX idx_visitor_id ON users(visitor_id);

-- Create index on visitor_type for filtering
CREATE INDEX idx_visitor_type ON users(visitor_type);
