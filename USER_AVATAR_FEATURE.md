# User Avatar Upload Feature

## 📅 Date: November 27, 2025

## 🎯 Overview
Fitur upload avatar/profile photo untuk user management dengan live preview dan validasi.

---

## ✨ Features Implemented

### 1. **Create User Page** (`users/create.php`)
- ✅ Avatar upload card dengan preview circular (150px)
- ✅ Default placeholder icon (user icon)
- ✅ Live preview saat select file
- ✅ Client-side validation (file size & type)
- ✅ File input dengan accept="image/*"
- ✅ Helper text (Max: 2MB, JPG/PNG)

### 2. **Edit User Page** (`users/edit.php`)
- ✅ Menampilkan current avatar (jika ada)
- ✅ Live preview saat select new file
- ✅ Option untuk delete current photo (checkbox)
- ✅ Helper text untuk keep current photo
- ✅ Indicator "Current photo uploaded" dengan icon
- ✅ Validation sama seperti create

---

## 🎨 UI/UX Design

### Avatar Preview Container
```html
<div class="avatar-preview mx-auto" 
     style="width: 150px; 
            height: 150px; 
            border-radius: 50%; 
            overflow: hidden; 
            border: 3px solid #e9ecef; 
            background: #f8f9fa;">
    <!-- Image or Placeholder -->
</div>
```

### Visual Features:
- 🎨 **Circular avatar** (border-radius: 50%)
- 📏 **Size**: 150x150px
- 🖼️ **Object-fit**: cover (untuk crop image dengan baik)
- 🔵 **Border**: 3px solid light gray
- 📍 **Center aligned** dengan mx-auto
- 💡 **Placeholder**: Large user icon (font-size: 4rem)

---

## 🔧 JavaScript Functions

### `previewAvatar(event)` Function

**Location**: Inline di create.php dan edit.php

**Features**:
1. ✅ File size validation (max 2MB)
2. ✅ File type validation (image/* only)
3. ✅ Live preview using FileReader API
4. ✅ Show/hide placeholder dynamically
5. ✅ Alert user jika validation gagal
6. ✅ Reset input jika invalid
7. ✅ (Edit only) Uncheck delete checkbox saat new file selected
8. ✅ (Edit only) Restore existing photo jika file input cleared

**Validations**:
```javascript
// File Size Check
if (file.size > 2097152) { // 2MB in bytes
    alert('File size exceeds 2MB...');
    event.target.value = '';
    return;
}

// File Type Check
if (!file.type.match('image.*')) {
    alert('Please select an image file...');
    event.target.value = '';
    return;
}
```

---

## 📋 Form Changes

### Before:
```html
<form action="..." method="post">
```

### After:
```html
<form action="..." method="post" enctype="multipart/form-data">
```

**Why**: `enctype="multipart/form-data"` diperlukan untuk upload file via POST.

---

## 🗂️ File Structure

### Create Page Layout:
```
Row
├─ Col-8 (Left)
│  └─ User Information Card
│     ├─ Full Name
│     ├─ Username
│     ├─ Email
│     └─ Password
│
└─ Col-4 (Right)
   ├─ Avatar Upload Card (NEW!)
   │  ├─ Preview Circle
   │  ├─ File Input
   │  └─ Helper Text
   │
   └─ Settings Card
      ├─ Role
      ├─ Status
      └─ Submit Button
```

### Edit Page Layout:
```
Row
├─ Col-8 (Left)
│  └─ User Information Card
│     ├─ Full Name
│     ├─ Username
│     ├─ Email
│     └─ Password (optional)
│
└─ Col-4 (Right)
   ├─ Avatar Upload Card (NEW!)
   │  ├─ Current Photo Preview
   │  ├─ Upload Status Indicator
   │  ├─ File Input
   │  ├─ Helper Text
   │  └─ Delete Photo Checkbox (if photo exists)
   │
   └─ Settings Card
      ├─ Role
      ├─ Status
      └─ Submit Button
```

---

## 🔍 Edit Page - Additional Features

### 1. **Current Photo Display**
```php
<?php if (!empty($user->photo) && file_exists(FCPATH . $user->photo)): ?>
    <img id="avatarPreview" 
         src="<?= base_url($user->photo) ?>" 
         alt="Current Avatar">
<?php else: ?>
    <!-- Placeholder icon -->
<?php endif; ?>
```

### 2. **Upload Status Indicator**
```html
<?php if (!empty($user->photo)): ?>
    <div class="mb-2">
        <small class="text-muted">
            <i class="fas fa-check-circle text-success me-1"></i>
            Current photo uploaded
        </small>
    </div>
<?php endif; ?>
```

### 3. **Delete Photo Option**
```html
<?php if (!empty($user->photo)): ?>
    <div class="form-check mt-2">
        <input type="checkbox" 
               id="delete_photo" 
               name="delete_photo" 
               value="1">
        <label for="delete_photo" class="small text-danger">
            <i class="fas fa-trash me-1"></i>Delete current photo
        </label>
    </div>
<?php endif; ?>
```

---

## 📊 File Input Specifications

### Input Attributes:
```html
<input type="file" 
       class="form-control" 
       id="photo" 
       name="photo" 
       accept="image/*" 
       onchange="previewAvatar(event)">
```

| Attribute | Value | Purpose |
|-----------|-------|---------|
| `type` | file | File upload input |
| `name` | photo | POST field name |
| `accept` | image/* | Filter untuk image files only |
| `onchange` | previewAvatar(event) | Trigger preview function |

### Validation Rules:
- ✅ **Max Size**: 2MB (2,097,152 bytes)
- ✅ **Allowed Types**: JPG, JPEG, PNG, GIF
- ✅ **Required**: No (optional field)

---

## 🎯 Controller Requirements

### Create Action (POST):
```php
public function create() {
    // ... existing validation ...
    
    // Handle file upload
    $photo = $this->request->getFile('photo');
    
    if ($photo && $photo->isValid() && !$photo->hasMoved()) {
        // Validate file
        $validationRule = [
            'photo' => [
                'rules' => 'uploaded[photo]|max_size[photo,2048]|is_image[photo]',
                'errors' => [
                    'uploaded' => 'Please select an image',
                    'max_size' => 'Image size cannot exceed 2MB',
                    'is_image' => 'Please select a valid image file'
                ]
            ]
        ];
        
        if ($this->validate($validationRule)) {
            // Generate unique filename
            $newName = $photo->getRandomName();
            
            // Move to uploads/users folder
            $photo->move(FCPATH . 'uploads/users', $newName);
            
            // Store path in database
            $data['photo'] = 'uploads/users/' . $newName;
        }
    }
    
    // ... save user data ...
}
```

### Update Action (POST):
```php
public function update($id) {
    // ... existing validation ...
    
    $user = $userModel->find($id);
    
    // Handle delete photo checkbox
    if ($this->request->getPost('delete_photo')) {
        if (!empty($user->photo) && file_exists(FCPATH . $user->photo)) {
            unlink(FCPATH . $user->photo);
        }
        $data['photo'] = null;
    }
    
    // Handle new file upload
    $photo = $this->request->getFile('photo');
    
    if ($photo && $photo->isValid() && !$photo->hasMoved()) {
        // Validate file
        $validationRule = [ /* same as create */ ];
        
        if ($this->validate($validationRule)) {
            // Delete old photo if exists
            if (!empty($user->photo) && file_exists(FCPATH . $user->photo)) {
                unlink(FCPATH . $user->photo);
            }
            
            // Upload new photo
            $newName = $photo->getRandomName();
            $photo->move(FCPATH . 'uploads/users', $newName);
            $data['photo'] = 'uploads/users/' . $newName;
        }
    }
    
    // ... update user data ...
}
```

---

## 📁 Directory Structure

### Upload Folder:
```
public/
└── uploads/
    └── users/
        ├── 1732012345_abc123def.jpg
        ├── 1732012346_xyz789ghi.png
        └── ...
```

**Important**: 
- ✅ Create `public/uploads/users/` folder
- ✅ Set proper permissions (755 or 775)
- ✅ Add to `.gitignore` if needed

---

## 🔒 Security Considerations

### Client-Side Validation:
1. ✅ File size check (2MB limit)
2. ✅ File type check (image/* only)
3. ✅ Visual feedback with alerts

### Server-Side Validation (Required):
1. ✅ Validate uploaded file
2. ✅ Check file size
3. ✅ Verify image type (is_image)
4. ✅ Use random filename (prevent path traversal)
5. ✅ Store in secure location (public/uploads/users)
6. ✅ Delete old file when uploading new one

### Best Practices:
- ⚠️ **Never trust** client-side validation alone
- ⚠️ Always validate on **server-side**
- ⚠️ Use **random filenames** to prevent conflicts
- ⚠️ Check file exists before **unlinking**
- ⚠️ Sanitize file paths

---

## 🧪 Testing Checklist

### Create User:
- [ ] Upload avatar saat create new user
- [ ] Preview muncul real-time
- [ ] Validation error jika file > 2MB
- [ ] Validation error jika bukan image
- [ ] Avatar tersimpan di database & filesystem
- [ ] Form submit tanpa avatar (optional)

### Edit User:
- [ ] Current avatar ditampilkan (jika ada)
- [ ] Upload new avatar mengganti yang lama
- [ ] Delete checkbox menghapus avatar
- [ ] Leave blank keeps current avatar
- [ ] Preview update saat select new file
- [ ] Old file deleted saat upload new file

### Edge Cases:
- [ ] User tanpa avatar (placeholder displayed)
- [ ] File corrupt/invalid
- [ ] Upload folder doesn't exist
- [ ] Permission denied
- [ ] Very large image (>2MB)
- [ ] Non-image file selected

---

## 📝 Database Schema

### Table: `users`
```sql
ALTER TABLE users 
ADD COLUMN photo VARCHAR(255) NULL 
AFTER status;
```

**Column Details**:
- **Name**: `photo`
- **Type**: VARCHAR(255)
- **Nullable**: YES (avatar is optional)
- **Default**: NULL
- **Stores**: Relative path (e.g., `uploads/users/filename.jpg`)

---

## 🎨 CSS Styling

### Avatar Styles (inline):
```css
.avatar-preview {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #e9ecef;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-preview .placeholder {
    font-size: 4rem;
    color: #dee2e6;
}
```

### Optional - Move to External CSS:
Create `public/assets/css/user-avatar.css` and include in base layout.

---

## 🚀 Future Enhancements

### Possible Improvements:
1. 📷 **Webcam capture** untuk ambil foto langsung
2. ✂️ **Image cropper** untuk crop avatar sebelum upload
3. 🖼️ **Multiple size generation** (thumbnail, medium, large)
4. 🔄 **Drag & drop** file upload
5. 📊 **Progress bar** untuk upload
6. 🎨 **Avatar editor** (filter, rotate, etc.)
7. 💾 **Compress image** otomatis sebelum save
8. 🔗 **Gravatar integration** sebagai fallback

---

## 📞 Support

### Common Issues:

#### 1. **Preview tidak muncul**
- Check browser console untuk errors
- Pastikan JavaScript function defined
- Verify file input has `onchange` attribute

#### 2. **File tidak terupload**
- Check form has `enctype="multipart/form-data"`
- Verify upload folder exists & writable
- Check server PHP upload limits (`upload_max_filesize`)

#### 3. **Old photo tidak terhapus**
- Check file path benar
- Verify file_exists() sebelum unlink()
- Check folder permissions

---

## 📖 Usage Guide

### For Users:
1. Click "Choose File" button
2. Select image dari device (max 2MB)
3. Preview akan muncul otomatis
4. Click "Create/Update User" untuk save

### For Admins:
1. Monitor upload folder size
2. Cleanup orphaned files periodically
3. Set proper folder permissions
4. Consider image optimization

---

**Version**: 1.0  
**Status**: ✅ Completed  
**Files Modified**: 2 (create.php, edit.php)  
**Lines Added**: ~140 lines (with JavaScript)
