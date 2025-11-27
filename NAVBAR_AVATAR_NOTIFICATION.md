# Navbar Avatar & Notification Feature

## 📅 Date: November 27, 2025

## 🎯 Overview
Navbar admin panel telah diupdate dengan:
1. **User Avatar dari Database** - Menampilkan foto user yang diupload
2. **Dynamic Notification Icon** - Notifikasi real-time untuk pending items

---

## 🖼️ User Avatar Feature

### Implementation:

#### 1. **Helper Function: `get_user_avatar()`**
Location: `app/Helpers/auth_helper.php`

```php
function get_user_avatar($user = null)
{
    if ($user === null) {
        $user = current_user();
    }

    if (!$user) {
        return 'https://ui-avatars.com/api/?name=Guest&background=random';
    }

    // Check if user has uploaded photo
    if (!empty($user->photo) && file_exists(FCPATH . $user->photo)) {
        return base_url($user->photo);
    }

    // Fallback to generated avatar
    $name = $user->fullname ?? $user->username ?? 'User';
    return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=random';
}
```

**Features**:
- ✅ Check if photo exists in database
- ✅ Verify file exists on server
- ✅ Fallback to generated avatar (UI Avatars API)
- ✅ Support for guest users

#### 2. **Session Update**
Location: `app/Controllers/Auth.php`

```php
// Updated session data to include photo
$sessionData = [
    'user_id'   => $user->id,
    'username'  => $user->username,
    'fullname'  => $user->fullname,
    'photo'     => $user->photo,  // ✅ Added
    'role'      => $roleName,
    'logged_in' => true,
];
```

#### 3. **current_user() Update**
Location: `app/Helpers/auth_helper.php`

```php
return (object) [
    'id' => session('user_id'),
    'username' => session('username'),
    'role' => session('role'),
    'fullname' => session('fullname'),
    'photo' => session('photo'),  // ✅ Added
];
```

#### 4. **Navbar Display**
Location: `app/Views/layout/admin_header.php`

```html
<!-- Navbar Avatar -->
<img src="<?= get_user_avatar() ?>" class="user-image rounded-circle shadow" alt="User Image">

<!-- Dropdown Avatar -->
<img src="<?= get_user_avatar() ?>" class="rounded-circle shadow" alt="User Image">
```

**CSS Styling**:
```css
.user-image {
    width: 32px;
    height: 32px;
    object-fit: cover;
}

.user-header img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border: 3px solid rgba(255, 255, 255, 0.2);
}
```

---

## 🔔 Notification System

### Notification Sources:

1. **Pending Comments** (`comments` table)
   - Condition: `is_approved = 0` AND `is_spam = 0`
   - Link: `/dashboard/comments`

2. **Pending Student Applications** (`student_applications` table)
   - Condition: `status = 'pending'`
   - Link: `/dashboard/pendaftaran`

### Helper Functions:

#### 1. **get_notifications_count()**
Location: `app/Helpers/auth_helper.php`

```php
function get_notifications_count()
{
    $db = \Config\Database::connect();
    $count = 0;

    // Count pending comments
    if ($db->tableExists('comments')) {
        $count += $db->table('comments')
            ->where('is_approved', 0)
            ->where('is_spam', 0)
            ->countAllResults();
    }

    // Count pending student applications
    if ($db->tableExists('student_applications')) {
        $count += $db->table('student_applications')
            ->where('status', 'pending')
            ->countAllResults();
    }

    return $count;
}
```

**Returns**: Total count of pending items

#### 2. **get_recent_notifications()**
Location: `app/Helpers/auth_helper.php`

```php
function get_recent_notifications($limit = 5)
{
    // Returns array of notification objects:
    [
        'id' => int,
        'type' => 'comment' | 'application',
        'title' => string,
        'message' => string,
        'author' => string,
        'post_title' => string,
        'url' => string,
        'icon' => string (FontAwesome class),
        'color' => string (Bootstrap color),
        'created_at' => datetime,
    ]
}
```

**Features**:
- ✅ Fetch pending comments with post titles
- ✅ Fetch pending applications
- ✅ Sort by created_at (newest first)
- ✅ Limit results
- ✅ Include icon & color for each type

### Notification Types:

| Type | Icon | Color | Title |
|------|------|-------|-------|
| **Comment** | `fas fa-comment` | info (blue) | New Comment |
| **Application** | `fas fa-user-plus` | success (green) | New Student Application |

### Navbar Implementation:

```html
<!-- Notification Dropdown -->
<li class="nav-item dropdown">
    <a class="nav-link" data-bs-toggle="dropdown" href="#">
        <i class="fas fa-bell"></i>
        <?php if ($notificationsCount > 0): ?>
            <span class="navbar-badge badge badge-danger"><?= $notificationsCount ?></span>
        <?php endif; ?>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
        <span class="dropdown-header"><?= $notificationsCount ?> Notifications</span>
        
        <?php foreach ($recentNotifications as $notification): ?>
            <a href="<?= $notification->url ?>" class="dropdown-item">
                <i class="<?= $notification->icon ?> mr-2 text-<?= $notification->color ?>"></i>
                <span>
                    <strong><?= esc($notification->title) ?></strong><br>
                    <small><?= esc($notification->message) ?></small>
                </span>
            </a>
        <?php endforeach; ?>
        
        <a href="..." class="dropdown-item dropdown-footer">See All Notifications</a>
    </div>
</li>
```

### CSS Styling:

```css
/* Notification Badge */
.navbar-badge {
    position: absolute;
    top: 5px;
    right: 5px;
    font-size: 0.625rem;
    font-weight: 700;
    padding: 2px 5px;
    border-radius: 10px;
    background-color: #dc3545;
    color: white;
}

/* Notification Dropdown */
.dropdown-menu-lg {
    min-width: 350px;
    max-width: 400px;
}

.dropdown-item {
    padding: 0.75rem 1rem;
    transition: background-color 0.2s;
}

.dropdown-item:hover {
    background-color: rgba(0, 0, 0, 0.05);
}

.dropdown-header {
    font-weight: 600;
    padding: 0.75rem 1rem;
    background-color: rgba(0, 0, 0, 0.03);
}

.dropdown-footer {
    text-align: center;
    font-weight: 600;
    padding: 0.75rem;
    background-color: rgba(0, 0, 0, 0.03);
}
```

---

## 🎨 Visual Design

### Navbar Layout:

```
┌─────────────────────────────────────────────────────────────────┐
│ [☰] View Site          [🔔 2]  [Avatar] Username [▼]            │
└─────────────────────────────────────────────────────────────────┘
```

### Notification Dropdown:

```
┌───────────────────────────────────┐
│  2 Notifications                  │
├───────────────────────────────────┤
│  💬 New Comment                   │
│     Comment on Post Title...      │
├───────────────────────────────────┤
│  ➕ New Student Application       │
│     Application from John Doe     │
├───────────────────────────────────┤
│  See All Notifications            │
└───────────────────────────────────┘
```

### User Dropdown:

```
┌───────────────────────────────────┐
│         [Avatar Image]            │
│        John Doe                   │
│          admin                    │
├───────────────────────────────────┤
│  [Profile]        [Sign out]      │
└───────────────────────────────────┘
```

---

## 📊 Avatar Display Logic

### Flow Chart:

```
┌─────────────────────────┐
│  get_user_avatar()      │
└───────────┬─────────────┘
            │
            ▼
    ┌───────────────┐
    │ User logged?  │
    └───┬───────────┘
        │ No
        ▼
    [ Generate Guest Avatar ]
        │ Yes
        ▼
    ┌───────────────────┐
    │ Photo in DB?      │
    └───┬───────────────┘
        │ No
        ▼
    [ Generate Name Avatar ]
        │ Yes
        ▼
    ┌───────────────────┐
    │ File exists?      │
    └───┬───────────────┘
        │ No
        ▼
    [ Generate Name Avatar ]
        │ Yes
        ▼
    [ Display Uploaded Photo ]
```

---

## 🔄 Notification Update Flow

### Real-time Update:

```
User Action → Database Update → Next Page Load → Count Updated
```

**Example**:
1. New comment posted → `comments.is_approved = 0`
2. Admin refreshes page
3. `get_notifications_count()` queries database
4. Badge shows new count
5. Admin clicks bell icon
6. `get_recent_notifications()` fetches items
7. Dropdown displays list

**Note**: Currently updates on page refresh. Can be enhanced with:
- AJAX polling
- WebSocket
- Server-Sent Events (SSE)

---

## 🧪 Testing

### Avatar Testing:

| Scenario | Expected Result |
|----------|-----------------|
| User has photo in DB, file exists | ✅ Show uploaded photo |
| User has photo in DB, file missing | ✅ Show generated avatar |
| User has no photo | ✅ Show generated avatar |
| User not logged in | ✅ Show guest avatar |
| Photo path is NULL | ✅ Show generated avatar |

### Notification Testing:

| Scenario | Expected Result |
|----------|-----------------|
| No pending items | Badge hidden, "No new notifications" shown |
| 1 pending comment | Badge shows "1", dropdown shows comment |
| Multiple pending items | Badge shows count, dropdown shows recent 5 |
| Click notification | Navigate to relevant page |
| Approve comment | Count decreases on next refresh |
| New application | Count increases on next refresh |

---

## 📝 Files Modified

### Controllers:
1. **app/Controllers/Auth.php**
   - Updated `attemptLogin()` to include photo in session

### Helpers:
2. **app/Helpers/auth_helper.php**
   - Updated `current_user()` to include photo
   - Added `get_user_avatar()`
   - Added `get_notifications_count()`
   - Added `get_recent_notifications()`

### Views:
3. **app/Views/layout/admin_header.php**
   - Replaced hardcoded avatar with `get_user_avatar()`
   - Added notification dropdown
   - Added notification badge
   - Added CSS styling

---

## 🚀 Usage Examples

### Get User Avatar:

```php
// Current user's avatar
<?= get_user_avatar() ?>

// Specific user's avatar
<?php
$user = $userModel->find($userId);
$avatarUrl = get_user_avatar($user);
?>
<img src="<?= $avatarUrl ?>" alt="Avatar">
```

### Get Notification Count:

```php
<?php
$count = get_notifications_count();
echo "You have {$count} pending notifications";
?>
```

### Get Recent Notifications:

```php
<?php
$notifications = get_recent_notifications(10);
foreach ($notifications as $notification) {
    echo "<a href='{$notification->url}'>";
    echo "<i class='{$notification->icon}'></i>";
    echo "{$notification->title}: {$notification->message}";
    echo "</a>";
}
?>
```

---

## 🎯 Features Summary

### Avatar System:
- ✅ Display user photo from database
- ✅ Fallback to generated avatar
- ✅ Support for missing files
- ✅ Guest user support
- ✅ Circular avatar with shadow
- ✅ Responsive sizing (32px navbar, 80px dropdown)

### Notification System:
- ✅ Real-time count (on page load)
- ✅ Badge indicator with count
- ✅ Dropdown with recent items
- ✅ Multiple notification types
- ✅ Color-coded by type
- ✅ Icons for each type
- ✅ Direct links to manage
- ✅ "See all" link to activity logs

---

## 🔧 Customization Options

### Add New Notification Type:

```php
// In get_recent_notifications() function

// Example: Pending Galleries
if ($db->tableExists('galleries')) {
    $galleries = $db->table('galleries')
        ->where('status', 'pending')
        ->orderBy('created_at', 'DESC')
        ->limit($limit)
        ->get()
        ->getResult();

    foreach ($galleries as $gallery) {
        $notifications[] = (object)[
            'id' => $gallery->id,
            'type' => 'gallery',
            'title' => 'Pending Gallery',
            'message' => 'Gallery: ' . $gallery->title,
            'author' => $gallery->author_name,
            'post_title' => '',
            'url' => base_url('dashboard/galleries'),
            'icon' => 'fas fa-images',
            'color' => 'warning',
            'created_at' => $gallery->created_at,
        ];
    }
}
```

### Change Avatar Size:

```css
/* Navbar avatar */
.user-image {
    width: 40px;  /* Larger */
    height: 40px;
}

/* Dropdown avatar */
.user-header img {
    width: 100px;  /* Larger */
    height: 100px;
}
```

### Change Badge Color:

```css
.navbar-badge {
    background-color: #ff6b6b;  /* Custom red */
    /* Or use Bootstrap variables */
    background-color: var(--bs-warning);  /* Yellow */
}
```

---

## 🐛 Troubleshooting

### Issue: Avatar not showing from database
**Solutions**:
1. Check if photo column has value in database
2. Verify file exists in `public/uploads/users/`
3. Check file permissions (644 or 755)
4. Verify `base_url()` is correct
5. Check session has photo value (`session('photo')`)

### Issue: Always shows generated avatar
**Solutions**:
1. Re-login to refresh session data
2. Check `Auth.php` includes photo in session
3. Verify `current_user()` includes photo
4. Check file path format (should be relative: `uploads/users/file.jpg`)

### Issue: Notification count is 0 but there are pending items
**Solutions**:
1. Check table names are correct
2. Verify database connection
3. Check column names match (`is_approved`, `status`)
4. Run SQL query manually to verify data
5. Check for PHP errors in console

### Issue: Notification dropdown not showing
**Solutions**:
1. Check Bootstrap JS is loaded
2. Verify dropdown attribute: `data-bs-toggle="dropdown"`
3. Check CSS is applied
4. Inspect browser console for errors
5. Verify helper functions are loaded: `helper('auth')`

---

## 📖 References

- [CodeIgniter Sessions](https://codeigniter.com/user_guide/libraries/sessions.html)
- [UI Avatars API](https://ui-avatars.com/)
- [Bootstrap Dropdowns](https://getbootstrap.com/docs/5.3/components/dropdowns/)
- [FontAwesome Icons](https://fontawesome.com/icons)

---

## ✅ Checklist

Implementation completed:
- [x] Update Auth controller to store photo in session
- [x] Update current_user() helper to include photo
- [x] Create get_user_avatar() helper function
- [x] Create get_notifications_count() helper
- [x] Create get_recent_notifications() helper
- [x] Update navbar to use avatar from database
- [x] Add notification bell icon with badge
- [x] Add notification dropdown with items
- [x] Add CSS styling for notifications
- [x] Test avatar display
- [x] Test notification count
- [x] Test notification dropdown

Optional enhancements:
- [ ] AJAX real-time notifications (polling)
- [ ] Mark notification as read
- [ ] Notification preferences
- [ ] Email notifications
- [ ] Push notifications
- [ ] Notification history page
- [ ] Delete notifications
- [ ] Notification sound/alert

---

**Status**: ✅ COMPLETED  
**Avatar**: Dynamic from database with fallback  
**Notifications**: Real-time count with dropdown  
**Last Updated**: November 27, 2025
