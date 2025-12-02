# Activity Logging Documentation

## Overview

Activity logs mencatat semua aktivitas CRUD yang dilakukan oleh user di dashboard. Setiap aktivitas dicatat dengan informasi lengkap termasuk user, action, timestamp, IP address, dan metadata tambahan.

## Database Structure

**Table:** `activity_log`

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| user_id | INT | User yang melakukan action |
| action | VARCHAR | Nama action (e.g., 'create_post') |
| subject_type | VARCHAR | Tipe subject (e.g., 'post', 'user') |
| subject_id | INT | ID dari subject |
| ip_address | VARCHAR | IP address user |
| user_agent | TEXT | Browser/device info |
| meta | JSON | Additional metadata |
| created_at | TIMESTAMP | Waktu action dilakukan |

## Helper Function

**Location:** `app/Helpers/auth_helper.php`

```php
log_activity($action, $subjectType = null, $subjectId = null, $meta = null);
```

**Parameters:**
- `$action` (string): Nama action yang dilakukan
- `$subjectType` (string): Tipe subject (e.g., 'post', 'user', 'gallery')
- `$subjectId` (int): ID dari subject yang di-manipulasi
- `$meta` (array): Metadata tambahan (akan di-convert ke JSON)

## Logged Activities by Controller

### 1. Posts Controller
**File:** `app/Controllers/Admin/Posts.php`

| Action | Description | Metadata |
|--------|-------------|----------|
| `create_post` | Create new post | `title` |
| `update_post` | Update existing post | `title` |
| `delete_post` | Delete single post | `title` |
| `bulk_delete_post` | Bulk delete posts | - |
| `bulk_draft_post` | Move posts to draft | - |
| `bulk_publish_post` | Publish posts | - |
| `restore_post` | Restore from trash | - |
| `bulk_restore_post` | Bulk restore posts | - |
| `bulk_force_delete_post` | Permanently delete | - |

### 2. Categories Controller
**File:** `app/Controllers/Admin/Categories.php`

| Action | Description | Metadata |
|--------|-------------|----------|
| `create_category` | Create new category | `name` |
| `update_category` | Update category | `name` |
| `delete_category` | Delete category | `name` |
| `bulk_delete_category` | Bulk delete categories | - |

### 3. Tags Controller
**File:** `app/Controllers/Admin/Tags.php`

| Action | Description | Metadata |
|--------|-------------|----------|
| `delete_tag` | Remove tag from all posts | `slug` |
| `bulk_delete_tag` | Bulk remove tags | `slug` |

### 4. Pages Controller
**File:** `app/Controllers/Admin/Pages.php`

| Action | Description | Metadata |
|--------|-------------|----------|
| `create_page` | Create new page | `title` |
| `update_page` | Update page | `title` |
| `delete_page` | Delete page | `title` |
| `bulk_delete_page` | Bulk delete pages | - |
| `bulk_draft_page` | Move pages to draft | - |
| `bulk_publish_page` | Publish pages | - |

### 5. Galleries Controller
**File:** `app/Controllers/Admin/Galleries.php`

| Action | Description | Metadata |
|--------|-------------|----------|
| `create_gallery` | Create new gallery | `title` |
| `update_gallery` | Update gallery | `title` |
| `delete_gallery` | Delete gallery | `title` |
| `bulk_delete_gallery` | Bulk delete galleries | - |

### 6. Media Controller
**File:** `app/Controllers/Admin/Media.php`

| Action | Description | Metadata |
|--------|-------------|----------|
| `upload_media` | Upload single/multiple media | `filename` |
| `update_media_caption` | Update media caption | `caption` |
| `delete_media` | Delete single media | `path` |
| `bulk_delete_media` | Bulk delete media | - |

### 7. Users Controller
**File:** `app/Controllers/Admin/Users.php`

| Action | Description | Metadata |
|--------|-------------|----------|
| `create_user` | Create new user | `username`, `role` |
| `update_user` | Update user | `username`, `role` |
| `delete_user` | Delete user | `username` |
| `bulk_delete_user` | Bulk delete users | - |

### 8. Guru/Staff Controller
**File:** `app/Controllers/Admin/GuruStaff.php`

| Action | Description | Metadata |
|--------|-------------|----------|
| `create_guru_staff` | Create guru/staff | `nama` |
| `update_guru_staff` | Update guru/staff | `nama` |
| `delete_guru_staff` | Delete guru/staff | `nama` |
| `bulk_delete_guru_staff` | Bulk delete | - |

### 9. Extracurriculars Controller
**File:** `app/Controllers/Admin/Extracurriculars.php`

| Action | Description | Metadata |
|--------|-------------|----------|
| `create_extracurricular` | Create extracurricular | `name` |
| `update_extracurricular` | Update extracurricular | `name` |
| `delete_extracurricular` | Delete extracurricular | `name` |
| `bulk_delete_extracurricular` | Bulk delete | - |

### 10. Student Applications Controller
**File:** `app/Controllers/Admin/StudentApplications.php`

| Action | Description | Metadata |
|--------|-------------|----------|
| `update_student_application_status` | Update application status | `status` |
| `delete_student_application` | Delete application | `nama` |

### 11. Subscribers Controller
**File:** `app/Controllers/Admin/Subscribers.php`

| Action | Description | Metadata |
|--------|-------------|----------|
| `delete_subscriber` | Delete subscriber | `email` |
| `bulk_delete_subscriber` | Bulk delete subscribers | - |

### 12. Settings Controller
**File:** `app/Controllers/Admin/Settings.php`

| Action | Description | Metadata |
|--------|-------------|----------|
| `update_settings` | Update settings | `groups` (array of changed keys) |

## Viewing Activity Logs

**URL:** `/dashboard/activity-logs`

**Features:**
- Filter by user
- Filter by action type
- Filter by date range
- Search by metadata
- Pagination
- Export to CSV

## Usage Examples

### Example 1: Simple Logging
```php
helper('auth');
log_activity('create_post', 'post', $postId);
```

### Example 2: With Metadata
```php
helper('auth');
log_activity('update_user', 'user', $userId, [
    'username' => $username,
    'role' => $role,
    'previous_role' => $oldRole
]);
```

### Example 3: Without Subject ID
```php
helper('auth');
log_activity('update_settings', 'settings', null, [
    'groups' => ['general', 'social_media']
]);
```

## Security Notes

1. **Automatic User Tracking**: User ID diambil otomatis dari session
2. **IP Address Logging**: IP address dicatat untuk audit trail
3. **User Agent**: Browser/device info dicatat
4. **Immutable**: Activity logs tidak bisa diedit atau dihapus oleh user biasa
5. **Admin Only**: Hanya admin yang bisa view full activity logs

## Performance Considerations

1. **Async Logging** (Optional): Untuk high-traffic sites, consider queue-based logging
2. **Log Retention**: Set policy untuk archive/delete logs setelah periode tertentu
3. **Indexing**: Database indexes pada `user_id`, `action`, dan `created_at` untuk query performance

## Maintenance

### Clean Old Logs (Optional)
```sql
-- Delete logs older than 6 months
DELETE FROM activity_log WHERE created_at < DATE_SUB(NOW(), INTERVAL 6 MONTH);
```

### Archive Logs
```sql
-- Create archive table
CREATE TABLE activity_log_archive LIKE activity_log;

-- Move old logs to archive
INSERT INTO activity_log_archive 
SELECT * FROM activity_log 
WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 YEAR);

-- Delete from main table
DELETE FROM activity_log 
WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 YEAR);
```

## Testing

### Test Create Activity
```php
// Create test post
$postId = // create post
$this->assertDatabaseHas('activity_log', [
    'action' => 'create_post',
    'subject_type' => 'post',
    'subject_id' => $postId
]);
```

### Test Bulk Operations
```php
// Bulk delete
$ids = [1, 2, 3];
// ... perform bulk delete
$count = $this->db->table('activity_log')
    ->where('action', 'bulk_delete_post')
    ->countAllResults();
$this->assertEquals(3, $count);
```

## Troubleshooting

### Logs Not Recording

1. **Check helper is loaded:**
   ```php
   helper('auth');
   ```

2. **Check database connection:**
   ```php
   $db = \Config\Database::connect();
   $db->tableExists('activity_log');
   ```

3. **Check user session:**
   ```php
   var_dump(session('user_id')); // Should not be null
   ```

### Slow Query Performance

1. **Add indexes:**
   ```sql
   ALTER TABLE activity_log ADD INDEX idx_user_id (user_id);
   ALTER TABLE activity_log ADD INDEX idx_action (action);
   ALTER TABLE activity_log ADD INDEX idx_created_at (created_at);
   ALTER TABLE activity_log ADD INDEX idx_subject (subject_type, subject_id);
   ```

2. **Partition table by date** (for very large datasets)

---

## Summary

✅ **Total Controllers with Logging:** 12
✅ **Total Actions Logged:** 40+
✅ **Coverage:** 100% of CRUD operations

All CRUD operations are now fully logged and traceable!
