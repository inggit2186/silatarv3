-- =====================================================
-- App Patches - Initial Data
-- =====================================================
-- Version: 2.0.0 (Initial Release)
-- Base APK Version Code: 1
-- =====================================================

INSERT INTO `app_patches` (
    `version`,
    `version_code`,
    `file_name`,
    `file_path`,
    `file_size`,
    `md5`,
    `changelog`,
    `is_mandatory`,
    `is_active`,
    `update_type`,
    `min_app_version`,
    `max_app_version`,
    `created_at`,
    `updated_at`
) VALUES (
    '2.0.0',
    1,
    'silatar_v2.0.0.patch',
    'patches/silatar_v2.0.0.patch',
    0,
    NULL,
    '- Release awal aplikasi SILATAR V2\n- Portal Layanan Online\n- Kemenag Tanah Datar',
    0,
    1,
    'patch',
    NULL,
    NULL,
    NOW(),
    NOW()
);
