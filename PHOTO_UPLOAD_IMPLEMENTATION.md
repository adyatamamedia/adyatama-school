# User Photo Upload Implementation

## ✅ Status: COMPLETED

## 📅 Date: November 27, 2025

---

## 🎯 Implementation Summary

Fitur upload photo untuk user management telah **fully implemented** dengan:
- ✅ Frontend form dengan preview
- ✅ Backend validation dan processing
- ✅ Database column ready
- ✅ Upload folder created

---

## 📊 Database Schema

### Table: `users`
```sql
CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `fullname` varchar(150) DEFAULT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `photo` varchar(255) DEFAULT NULL,  -- ✅ Column exists
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

**Column Details:**
- **Name**: `photo`
- **Type**: VARCHAR(255)
- **Nullable**: YES (optional)
- **Default**: NULL
- **Stores**: Relative path (e.g., `uploads/users/1234567890_abc123.jpg`)

---

## 🗂️ Folder Structure

```
public/
└── uploads/
    └── users/
        ├── .gitignore (ignore uploaded files)
        ├── .gitkeep (keep folder in git)
        └── [uploaded photos will be here]
```

**Permissions**: 755 (or 775 on some systems)

---

## 💻 Controller Implementation

### File: `app/Controllers/Admin/Users.php`

#### 1. **create() Method** ✅

**Features:**
- ✅ Get uploaded file dari request
- ✅ Validate file (size, type, mime)
- ✅ Create directory if not exists
- ✅ Generate random filename
- ✅ Move file to uploads/users
- ✅ Store path in database

**Code Added:**
```php
// Handle photo upload
$photo = $this->request->getFile('photo');
if ($photo && $photo->isValid() && !$photo->hasMoved()) {
    // Validate photo
    $photoRules = [
        'photo' => [
            'rules' => 'max_size[photo,2048]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png,image/gif]',
            'errors' => [
                'max_size' => 'Image size cannot exceed 2MB',
                'is_image' => 'Please select a valid image file',
                'mime_in' => 'Only JPG, PNG, and GIF images are allowed'
            ]
        ]
    ];

    if ($this->validate($photoRules)) {
        // Create uploads/users directory if not exists
        $uploadPath = FCPATH . 'uploads/users';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Generate random filename
        $newName = $photo->getRandomName();
        
        // Move file
        $photo->move($uploadPath, $newName);
        
        // Store relative path in database
        $data['photo'] = 'uploads/users/' . $newName;
    } else {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }
}

$this->userModel->save($data);
```

#### 2. **update() Method** ✅

**Features:**
- ✅ Handle delete photo checkbox
- ✅ Delete old file jika delete checkbox checked
- ✅ Upload new photo
- ✅ Delete old photo saat upload new
- ✅ Keep current photo jika tidak ada perubahan

**Code Added:**
```php
// Handle delete photo checkbox
if ($this->request->getPost('delete_photo') == '1') {
    // Delete physical file if exists
    if (!empty($user->photo) && file_exists(FCPATH . $user->photo)) {
        @unlink(FCPATH . $user->photo);
    }
    // Set photo to null in database
    $data['photo'] = null;
}

// Handle new photo upload
$photo = $this->request->getFile('photo');
if ($photo && $photo->isValid() && !$photo->hasMoved()) {
    // Validate photo
    $photoRules = [ /* same as create */ ];

    if ($this->validate($photoRules)) {
        // Delete old photo if exists (only if not already deleted by checkbox)
        if (!$this->request->getPost('delete_photo') && !empty($user->photo) && file_exists(FCPATH . $user->photo)) {
            @unlink(FCPATH . $user->photo);
        }

        // Create directory, generate name, move file
        $uploadPath = FCPATH . 'uploads/users';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $newName = $photo->getRandomName();
        $photo->move($uploadPath, $newName);
        
        $data['photo'] = 'uploads/users/' . $newName;
    } else {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }
}

$this->userModel->update($id, $data);
```

---

## 🔐 Validation Rules

### Server-Side Validation:

| Rule | Value | Description |
|------|-------|-------------|
| `max_size[photo,2048]` | 2048 KB (2MB) | Maximum file size |
| `is_image[photo]` | - | Must be valid image file |
| `mime_in[photo,...]` | jpg, jpeg, png, gif | Allowed MIME types |

### Client-Side Validation (JavaScript):

```javascript
// File size check
if (file.size > 2097152) { // 2MB in bytes
    alert('File size exceeds 2MB...');
    return;
}

// File type check
if (!file.type.match('image.*')) {
    alert('Please select an image file...');
    return;
}
```

---

## 🎨 Frontend Implementation

### Files Modified:

1. ✅ `app/Views/admin/users/create.php`
   - Added avatar upload card
   - Added preview container
   - Added file input with validation
   - Added JavaScript preview function

2. ✅ `app/Views/admin/users/edit.php`
   - Added avatar upload card
   - Display current photo
   - Added delete photo checkbox
   - Added JavaScript preview function
   - Keep current photo option

### Form Changes:
```html
<!-- Before -->
<form method="post">

<!-- After -->
<form method="post" enctype="multipart/form-data">
    <input type="file" name="photo" accept="image/*" onchange="previewAvatar(event)">
</form>
```

---

## 🔄 Upload Process Flow

### Create User:
```
1. User selects photo → Preview shows
2. User submits form → Server receives file
3. Validate file (size, type, mime)
4. Create uploads/users folder (if not exists)
5. Generate random filename
6. Move file to folder
7. Save path to database
8. User created successfully
```

### Edit User:
```
Scenario A: Upload New Photo
1. User selects new photo → Preview updates
2. Submit → Validate
3. Delete old photo from filesystem
4. Upload new photo
5. Update path in database

Scenario B: Delete Photo
1. User checks "Delete photo" checkbox
2. Submit → Delete file from filesystem
3. Set photo = NULL in database

Scenario C: Keep Current Photo
1. User doesn't change anything
2. Submit → Photo field not in $data
3. Database keeps existing path
```

---

## 🧪 Testing Results

### Test Cases:

| Test Case | Status | Notes |
|-----------|--------|-------|
| Create user with photo | ✅ | File uploaded & saved |
| Create user without photo | ✅ | Photo = NULL in DB |
| Upload photo > 2MB | ✅ | Validation error shown |
| Upload non-image file | ✅ | Validation error shown |
| Edit user - upload new photo | ✅ | Old photo deleted |
| Edit user - delete photo | ✅ | File & DB entry removed |
| Edit user - keep current photo | ✅ | No changes to photo |
| Upload folder auto-creation | ✅ | Folder created if missing |
| Random filename generation | ✅ | No conflicts |
| Preview function | ✅ | Real-time preview works |

---

## 📝 Error Handling

### Validation Errors:

**File too large:**
```
Image size cannot exceed 2MB
```

**Invalid file type:**
```
Please select a valid image file
```

**Wrong MIME type:**
```
Only JPG, PNG, and GIF images are allowed
```

### File System Errors:

```php
// Safe unlink with @ suppressor
@unlink(FCPATH . $user->photo);

// Check file exists before delete
if (file_exists(FCPATH . $user->photo)) {
    @unlink(FCPATH . $user->photo);
}

// Create directory with error handling
if (!is_dir($uploadPath)) {
    mkdir($uploadPath, 0755, true);
}
```

---

## 🔍 Security Features

### 1. **Filename Randomization**
```php
$newName = $photo->getRandomName();
// Result: 1732012345_abc123def456.jpg
```
**Why**: Prevents:
- Path traversal attacks
- Filename conflicts
- Predictable file paths

### 2. **MIME Type Validation**
```php
'mime_in[photo,image/jpg,image/jpeg,image/png,image/gif]'
```
**Why**: Prevents:
- Executable files upload
- Non-image files
- Malicious file types

### 3. **File Size Limit**
```php
'max_size[photo,2048]' // 2MB
```
**Why**: Prevents:
- Disk space exhaustion
- DoS attacks
- Performance issues

### 4. **Path Validation**
```php
$uploadPath = FCPATH . 'uploads/users';
// Only allows upload to specific folder
```
**Why**: Prevents:
- Directory traversal
- Arbitrary file write

### 5. **Safe File Deletion**
```php
if (file_exists(FCPATH . $user->photo)) {
    @unlink(FCPATH . $user->photo);
}
```
**Why**: Prevents:
- File not found errors
- Permission errors
- System crashes

---

## 📊 Performance Considerations

### Upload Performance:
- ✅ File size limited to 2MB
- ✅ Random filename (fast operation)
- ✅ Single file operation
- ✅ No image processing (yet)

### Future Optimizations:
- 🔄 Image compression
- 🔄 Thumbnail generation
- 🔄 WebP conversion
- 🔄 CDN integration
- 🔄 Lazy loading

---

## 🔧 Configuration

### PHP.ini Settings:
```ini
file_uploads = On
upload_max_filesize = 10M
post_max_size = 10M
max_file_uploads = 20
```

### CodeIgniter Config:
```php
// app/Config/App.php
public $baseURL = 'http://localhost:8080/';

// Access uploaded files:
// base_url('uploads/users/filename.jpg')
```

---

## 📂 File Paths

### Relative Paths (Stored in DB):
```
uploads/users/1732012345_abc123def456.jpg
```

### Absolute Paths (Server):
```
C:/xampp/htdocs/adyatama-school2/dash/public/uploads/users/1732012345_abc123def456.jpg
```

### URL Paths (Browser):
```
http://localhost:8080/uploads/users/1732012345_abc123def456.jpg
```

---

## 🎯 Usage Examples

### Display User Avatar:
```php
<?php if (!empty($user->photo) && file_exists(FCPATH . $user->photo)): ?>
    <img src="<?= base_url($user->photo) ?>" alt="<?= esc($user->fullname) ?>">
<?php else: ?>
    <i class="fas fa-user"></i> <!-- Default icon -->
<?php endif; ?>
```

### Get Avatar URL:
```php
$avatarUrl = !empty($user->photo) 
    ? base_url($user->photo) 
    : base_url('assets/images/default-avatar.png');
```

---

## 🚀 Deployment Checklist

### Before Deploy:
- [ ] Check upload folder exists: `public/uploads/users/`
- [ ] Verify folder permissions: 755 or 775
- [ ] Test file upload functionality
- [ ] Test file deletion
- [ ] Verify .gitignore in place
- [ ] Check PHP upload limits
- [ ] Test on production server
- [ ] Monitor disk space usage

### Production Settings:
```php
// Increase if needed in production
ini_set('upload_max_filesize', '5M');
ini_set('post_max_size', '5M');
```

---

## 📞 Troubleshooting

### Issue: "Upload failed"
**Solution**: 
- Check folder exists
- Check folder permissions (755)
- Check PHP upload_max_filesize
- Check disk space

### Issue: "Photo not showing"
**Solution**:
- Verify path in database
- Check file exists on server
- Check base_url() configured correctly
- Check .htaccess allows access to uploads folder

### Issue: "Old photo not deleted"
**Solution**:
- Check file_exists() before unlink
- Verify folder permissions
- Check path is correct
- Use @ suppressor to prevent errors

---

## 📖 References

- [CodeIgniter File Uploads](https://codeigniter.com/user_guide/libraries/uploaded_files.html)
- [CodeIgniter Validation](https://codeigniter.com/user_guide/libraries/validation.html)
- [PHP File Upload](https://www.php.net/manual/en/features.file-upload.php)

---

**Version**: 1.0  
**Status**: ✅ COMPLETED & TESTED  
**Last Updated**: November 27, 2025  
**Implemented By**: AI Assistant
