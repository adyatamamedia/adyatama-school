# Activity Log Display Fix

## Problem
Activity logs menampilkan "Mengupdate artikel" tanpa judul, hanya "post #2"

## Root Cause
`log_activity()` calls di Posts controller tidak menyimpan **title ke metadata**:
```php
// ❌ WRONG - No metadata
log_activity('update_post', 'post', $id);

// ✅ CORRECT - With title in metadata
log_activity('update_post', 'post', $id, ['title' => $title]);
```

## Fixed Files

### 1. Posts Controller
**File:** `app/Controllers/Admin/Posts.php`

**Fixed methods:**
- ✅ `create()` - Line 243: Added `['title' => $title]`
- ✅ `update()` - Line 483: Added `['title' => $title]`
- ✅ `delete()` - Line 498: Fetch post first, then add `['title' => $post->title ?? null]`
- ✅ `restore()` - Line 605: Fetch post first, then add `['title' => $post->title ?? null]`

**Before:**
```php
public function update($id) {
    // ... update code ...
    log_activity('update_post', 'post', $id);
}
```

**After:**
```php
public function update($id) {
    // ... update code ...
    $title = $this->request->getPost('title');
    log_activity('update_post', 'post', $id, ['title' => $title]);
}
```

**For Delete (needs to fetch first):**
```php
public function delete($id) {
    $post = $this->postModel->find($id);
    $this->postModel->delete($id);
    log_activity('delete_post', 'post', $id, ['title' => $post->title ?? null]);
}
```

## Testing

### Test 1: Create New Post
1. Create a new post with title "Test Article Laravel"
2. Go to Activity Logs (`/dashboard/activity-logs`)
3. Should see: **"Membuat artikel: Test Article Laravel"**

### Test 2: Update Post
1. Update existing post title to "Updated Article Title"
2. Go to Activity Logs
3. Should see: **"Mengupdate artikel: Updated Article Title"**

### Test 3: Delete Post
1. Delete a post titled "Old Article"
2. Go to Activity Logs
3. Should see: **"Menghapus artikel: Old Article"**

### Test 4: Restore Post
1. Restore a post from trash
2. Go to Activity Logs
3. Should see: **"Restore artikel: Restored Post Title"**

## Expected Display Format

### Before Fix:
```
Mengupdate artikel
post #2
```

### After Fix:
```
Mengupdate artikel: Test Article Laravel
post #2
```

## Other Controllers Status

All other controllers already have metadata:

| Controller | Status | Metadata Key |
|------------|--------|--------------|
| Categories | ✅ Fixed | `name` |
| Tags | ✅ Fixed | `slug` |
| Pages | ✅ Fixed | `title` |
| Galleries | ✅ Fixed | `title` |
| Media | ✅ Fixed | `filename` |
| Users | ✅ Fixed | `username`, `role` |
| GuruStaff | ✅ Fixed | `nama` |
| Extracurriculars | ✅ Fixed | `name` |
| StudentApplications | ✅ Fixed | `nama`, `status` |
| Subscribers | ✅ Fixed | `email` |

## How Helper Function Works

**File:** `app/Helpers/dashboard_helper.php`
**Function:** `getActivityDescription($log)`

The function checks metadata in priority order:
1. `title` ← Used by Posts, Pages, Galleries
2. `name` ← Used by Categories, Extracurriculars
3. `nama` ← Used by Guru/Staff
4. `username` ← Used by Users
5. `email` ← Used by Subscribers
6. `filename` ← Used by Media
7. `slug` ← Used by Tags
8. `caption` ← Used by Media caption

Example metadata:
```json
{
    "title": "My Article Title",
    "role": "admin"
}
```

Display output:
```html
Mengupdate artikel: <strong class="text-primary">My Article Title</strong>
```

## Troubleshooting

### Still showing only "post #2"?

1. **Clear old logs and create new activity:**
   ```sql
   -- Check existing logs
   SELECT * FROM activity_log WHERE action = 'update_post' ORDER BY created_at DESC LIMIT 5;
   
   -- Check metadata content
   SELECT id, action, meta FROM activity_log WHERE subject_type = 'post' ORDER BY created_at DESC LIMIT 10;
   ```

2. **Verify metadata is saved:**
   - Old logs won't have metadata
   - Only NEW activities after this fix will show titles
   - Test by creating/updating a post NOW

3. **Check helper function is loaded:**
   ```php
   // In view
   helper('dashboard');
   echo getActivityDescription($log);
   ```

## Migration Note

⚠️ **Old activity logs won't have titles** because they were created before this fix.

**Options:**
1. **Keep old logs as-is** (recommended) - Historical data
2. **Backfill titles** (optional) - Run script to update old logs:
   ```sql
   -- Update old post logs with titles
   UPDATE activity_log al
   JOIN posts p ON p.id = al.subject_id
   SET al.meta = JSON_OBJECT('title', p.title)
   WHERE al.subject_type = 'post' 
   AND al.action IN ('create_post', 'update_post', 'delete_post')
   AND (al.meta IS NULL OR al.meta = '{}');
   ```

## Verification Checklist

- ✅ Create new post → Check activity log shows title
- ✅ Update post → Check activity log shows title
- ✅ Delete post → Check activity log shows title
- ✅ Restore post → Check activity log shows title
- ✅ All displayed with blue bold styling
- ✅ Subject ID still shown as "post #X"

---

**Fixed Date:** 2024-11-30
**Affected Files:** 
- `app/Controllers/Admin/Posts.php` (4 methods)
- Documentation created

**Status:** ✅ RESOLVED
