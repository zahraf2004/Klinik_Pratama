# Chatify Header - Styling Klinik

## Design Overview

### Header Layout
```
┌─────────────────────────────────────────────┐
│  [Logo]  Klinik Pratama     [🔄] [⚙️] [✕]  │
│          Dokter Yanti                       │
└─────────────────────────────────────────────┘
```

## Styling Details

### 1. Logo Klinik
- **File**: `logo1_copy.png`
- **Size**: 35px height (auto width)
- **Effect**: Drop shadow + hover scale & rotate
- **Hover**: Scale 1.08 + rotate 2deg

### 2. Title Text

#### Main Title: "Klinik Pratama"
- **Font Size**: 15px
- **Font Weight**: 700 (Bold)
- **Color**: Gradient (Blue → Green)
  - Start: `#4a83d3` (Blue)
  - End: `#2ecc71` (Green)
- **Effect**: Gradient text dengan `-webkit-background-clip`

#### Sub Title: "Dokter Yanti"
- **Font Size**: 11px
- **Font Weight**: 500 (Medium)
- **Color**: `#7f8c8d` (Gray)
- **Hover**: Berubah ke primary blue

### 3. Container
- **Layout**: Flexbox (horizontal)
- **Gap**: 12px antara logo dan text
- **Padding**: 5px 10px
- **Border Radius**: 8px
- **Hover Effect**: 
  - Background: Light blue (rgba)
  - Transform: Slide right 2px

## CSS Classes

### `.chatify-header-link`
Container utama untuk logo + text
```css
display: flex;
align-items: center;
gap: 12px;
padding: 5px 10px;
border-radius: 8px;
```

### `.chatify-logo`
Styling untuk logo image
```css
height: 35px;
filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
```

### `.chatify-title-wrapper`
Container untuk text (2 baris)
```css
display: flex;
flex-direction: column;
gap: 2px;
```

### `.chatify-title-main`
Text "Klinik Pratama" dengan gradient
```css
font-size: 15px;
font-weight: 700;
background: linear-gradient(135deg, #4a83d3 0%, #2ecc71 100%);
-webkit-background-clip: text;
-webkit-text-fill-color: transparent;
```

### `.chatify-title-sub`
Text "Dokter Yanti" dengan warna abu
```css
font-size: 11px;
font-weight: 500;
color: #7f8c8d;
```

## Responsive Design

### Mobile (max-width: 480px)
- Logo: 28px (dari 35px)
- Main title: 13px (dari 15px)
- Sub title: 10px (dari 11px)

## Hover Effects

### Logo
```
Normal → Hover
Scale: 1.0 → 1.08
Rotate: 0deg → 2deg
```

### Container
```
Normal → Hover
Background: transparent → rgba(74, 131, 211, 0.05)
Transform: translateX(0) → translateX(2px)
```

### Sub Title
```
Normal → Hover
Color: #7f8c8d → #4a83d3
```

## Color Palette

| Element | Normal | Hover |
|---------|--------|-------|
| Main Title | Gradient (Blue→Green) | - |
| Sub Title | `#7f8c8d` | `#4a83d3` |
| Background | Transparent | `rgba(74, 131, 211, 0.05)` |
| Logo Shadow | `rgba(0, 0, 0, 0.1)` | - |

## Route Behavior

### Dokter
Click header → `/nakes/dashboard`

### Pasien
Click header → `/konsultasi`

## File Modified

1. **`resources/views/vendor/Chatify/pages/app.blade.php`**
   - Update struktur HTML dengan class baru
   - Tambah wrapper untuk title 2 baris

2. **`public/css/chatify/custom.css`**
   - Tambah styling untuk `.chatify-header-link`
   - Tambah styling untuk `.chatify-logo`
   - Tambah styling untuk `.chatify-title-wrapper`
   - Tambah styling untuk `.chatify-title-main` (gradient)
   - Tambah styling untuk `.chatify-title-sub`
   - Tambah responsive breakpoint

## Preview

### Desktop View
```
┌──────────────────────────────────────┐
│  [🏥]  Klinik Pratama    [🔄][⚙️][✕] │
│        Dokter Yanti                  │
│  ────────────────────────────────    │
│  [Search box...]                     │
└──────────────────────────────────────┘
```

### Mobile View
```
┌─────────────────────────┐
│ [🏥] Klinik Pratama [✕] │
│      Dokter Yanti       │
│ ─────────────────────   │
│ [Search...]             │
└─────────────────────────┘
```

## Tips

### Ganti Nama Klinik
Edit di `app.blade.php`:
```blade
<span class="chatify-title-main">Nama Klinik Anda</span>
<span class="chatify-title-sub">Subtitle/Tagline</span>
```

### Ganti Warna Gradient
Edit di `custom.css`:
```css
.chatify-title-main {
    background: linear-gradient(135deg, #warna1 0%, #warna2 100%);
}
```

### Ganti Ukuran Logo
Edit di `custom.css`:
```css
.chatify-logo {
    height: 40px; /* Ubah sesuai kebutuhan */
}
```
