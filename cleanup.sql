-- ================================================================
-- Chennai Smart Care — Data Cleanup Script
-- Deletes bookings CSC-2026-00001 → CSC-2026-00007
-- along with warranties, invoices, and all consultations
--
-- USAGE:
--   mysql -u root -p smartcare_db < cleanup.sql
--   OR paste into phpMyAdmin / TablePlus / DBeaver SQL console
--
-- SAFE: wrapped in a transaction — rolls back automatically
--       if anything goes wrong. Review the SELECT preview
--       section FIRST before uncommenting the DELETE block.
-- ================================================================

START TRANSACTION;

-- ────────────────────────────────────────────────────────────────
-- STEP 1 — PREVIEW (run this first, no changes made yet)
-- ────────────────────────────────────────────────────────────────

-- Which bookings will be affected?
SELECT
    id,
    booking_number,
    guest_name,
    guest_phone,
    status,
    total_amount,
    created_at
FROM bookings
WHERE booking_number BETWEEN 'CSC-2026-00001' AND 'CSC-2026-00007'
ORDER BY booking_number;

-- How many child rows will be deleted per table?
SELECT
    (SELECT COUNT(*) FROM booking_items     WHERE booking_id IN (SELECT id FROM bookings WHERE booking_number BETWEEN 'CSC-2026-00001' AND 'CSC-2026-00007')) AS booking_items,
    (SELECT COUNT(*) FROM booking_histories WHERE booking_id IN (SELECT id FROM bookings WHERE booking_number BETWEEN 'CSC-2026-00001' AND 'CSC-2026-00007')) AS booking_histories,
    (SELECT COUNT(*) FROM reviews           WHERE booking_id IN (SELECT id FROM bookings WHERE booking_number BETWEEN 'CSC-2026-00001' AND 'CSC-2026-00007')) AS reviews,
    (SELECT COUNT(*) FROM warranties        WHERE booking_id IN (SELECT id FROM bookings WHERE booking_number BETWEEN 'CSC-2026-00001' AND 'CSC-2026-00007')) AS warranties,
    (SELECT COUNT(*) FROM invoices          WHERE booking_id IN (SELECT id FROM bookings WHERE booking_number BETWEEN 'CSC-2026-00001' AND 'CSC-2026-00007')) AS invoices,
    (SELECT COUNT(*) FROM invoice_items     WHERE invoice_id IN (SELECT id FROM invoices WHERE booking_id IN (SELECT id FROM bookings WHERE booking_number BETWEEN 'CSC-2026-00001' AND 'CSC-2026-00007'))) AS invoice_items,
    (SELECT COUNT(*) FROM consultations)    AS all_consultations;

-- ────────────────────────────────────────────────────────────────
-- STEP 2 — DELETE (uncomment after reviewing STEP 1 output)
-- ────────────────────────────────────────────────────────────────

-- Capture the booking IDs once for reuse
-- (MySQL doesn't support CTEs in older versions, so we use a temp table)
CREATE TEMPORARY TABLE _target_booking_ids AS
SELECT id FROM bookings
WHERE booking_number BETWEEN 'CSC-2026-00001' AND 'CSC-2026-00007';

-- 1. Invoice items (deepest child — must go first)
DELETE FROM invoice_items
WHERE invoice_id IN (
    SELECT id FROM invoices
    WHERE booking_id IN (SELECT id FROM _target_booking_ids)
);

-- 2. Invoices
DELETE FROM invoices
WHERE booking_id IN (SELECT id FROM _target_booking_ids);

-- 3. Warranties
DELETE FROM warranties
WHERE booking_id IN (SELECT id FROM _target_booking_ids);

-- 4. Reviews
DELETE FROM reviews
WHERE booking_id IN (SELECT id FROM _target_booking_ids);

-- 5. Booking history / activity log
DELETE FROM booking_histories
WHERE booking_id IN (SELECT id FROM _target_booking_ids);

-- 6. Booking items (services on each booking)
DELETE FROM booking_items
WHERE booking_id IN (SELECT id FROM _target_booking_ids);

-- 7. The bookings themselves
DELETE FROM bookings
WHERE id IN (SELECT id FROM _target_booking_ids);

-- 8. All consultations (no date filter — clears the whole table)
DELETE FROM consultations;

-- Clean up temp table
DROP TEMPORARY TABLE _target_booking_ids;

-- ────────────────────────────────────────────────────────────────
-- STEP 3 — VERIFY (run after deletes, before committing)
-- ────────────────────────────────────────────────────────────────

-- Should return 0 rows for bookings
SELECT COUNT(*) AS remaining_bookings
FROM bookings
WHERE booking_number BETWEEN 'CSC-2026-00001' AND 'CSC-2026-00007';

-- Should return 0
SELECT COUNT(*) AS remaining_consultations FROM consultations;

-- Should return 0
SELECT COUNT(*) AS orphaned_warranties
FROM warranties
WHERE booking_id NOT IN (SELECT id FROM bookings);

-- ────────────────────────────────────────────────────────────────
-- STEP 4 — COMMIT or ROLLBACK
-- ────────────────────────────────────────────────────────────────

-- If STEP 3 shows all zeros → run this:
COMMIT;

-- If anything looks wrong → run this instead (undoes everything):
-- ROLLBACK;