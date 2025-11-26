# WYSIWYG Editor Integration (Summernote)

## Overview
The post create and edit forms now use **Summernote** - a Bootstrap-based WYSIWYG editor that comes integrated with AdminLTE. This provides a rich text editing experience with HTML output.

## Features

### Toolbar Options
- **Style** - Paragraph, H1, H2, H3, etc.
- **Font** - Bold, Italic, Underline, Clear formatting
- **Fontname** - Different fonts
- **Fontsize** - Different font sizes
- **Color** - Text and background colors
- **Paragraph** - Lists (ordered and unordered), Alignment
- **Height** - Line height
- **Table** - Insert and edit tables
- **Insert** - Links, Images, Video
- **View** - Fullscreen, Codeview, Help

### Responsive Design
- Works on all device sizes
- Touch-optimized interface for mobile devices

### Image Handling
- Drag and drop images
- Upload images directly
- Insert images from URL

## HTML Output Examples

Summernote generates clean HTML like:
```html
<h1>Heading 1</h1>
<p><strong>Bold text</strong> and <em>italic text</em></p>
<ul>
  <li>Bullet point 1</li>
  <li>Bullet point 2</li>
</ul>
<ol>
  <li>Numbered item 1</li>
  <li>Numbered item 2</li>
</ol>
<p><a href="https://example.com">Link text</a></p>
<img src="image-url.jpg" alt="Image alt text">
<blockquote>Blockquote</blockquote>
<table>
  <thead>
    <tr>
      <th>Column 1</th>
      <th>Column 2</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Data 1</td>
      <td>Data 2</td>
    </tr>
  </tbody>
</table>
```

## Storage
- Content is saved as **HTML** in the database
- HTML content is stored directly in the `content` field
- Content is rendered as HTML on the frontend

## CDN Resources
- CSS: `https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.css`
- JS: `https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.js`

## Configuration
The editor is configured in both `create.php` and `edit.php` with:
- Height: 400px
- Placeholder: "Write your content here..."
- Comprehensive toolbar with all necessary options
- Image upload callback for custom handling