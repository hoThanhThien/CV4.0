# 🌟 Hồ Thành Thiện

<p align="center">
  <a href="https://hothanhthien.io.vn">
    <img src="https://hothanhthien.io.vn/images/og-image.webp" alt="Hồ Thành Thiện - Full-Stack Developer" width="100%" style="max-width: 820px; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.15);" />
  </a>
</p>

<p align="center">
  <strong>Nền tảng Portfolio cá nhân & Hệ thống Quản trị Nội dung (CMS) cao cấp dành cho Kỹ sư Phần mềm Full-Stack.</strong>
</p>

<p align="center">
  <a href="https://hothanhthien.io.vn"><img src="https://img.shields.io/badge/Live_Demo-hothanhthien.io.vn-6366f1?style=for-the-badge&logo=google-chrome&logoColor=white" alt="Live Website"></a>
  <a href="https://github.com/hoThanhThien/CV4.0"><img src="https://img.shields.io/badge/GitHub-Repository-24292e?style=for-the-badge&logo=github&logoColor=white" alt="GitHub Repo"></a>
  <img src="https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Laravel-10.x%2F11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/License-MIT-success?style=for-the-badge" alt="License MIT">
</p>

---

## 📌 Giới thiệu dự án (Project Overview)

**Personal Portfolio & CMS** là hệ thống web portfolio kết hợp CMS quản trị nội dung hoàn chỉnh được xây dựng dựa trên framework **Laravel** và kiến trúc **MVC**, phục vụ mục đích giới thiệu năng lực chuyên môn, kinh nghiệm thực chiến, các dự án tiêu biểu, bài viết kỹ thuật và tài liệu hồ sơ năng lực (Brochure / Bằng cấp / Giấy khen) của **Hồ Thanh Thiện**.

Hệ thống được thiết kế theo tiêu chuẩn hiện đại: giao diện tối ưu trải nghiệm người dùng (UX/UI) với phong cách kính mờ (Glassmorphism), tốc độ tải trang nhanh (LCP/Core Web Vitals), chuẩn SEO với Schema.org JSON-LD và hỗ trợ **song ngữ toàn diện (Tiếng Việt & Tiếng Anh)**.

> 🌐 **Website chính thức:** [https://hothanhthien.io.vn](https://hothanhthien.io.vn)

---

## ✨ Tính năng nổi bật (Key Features)

### 1. Giao diện Người dùng (Public Frontend)
- 🌐 **Hỗ trợ Song ngữ (Bilingual i18n):** Chuyển đổi ngôn ngữ Tiếng Việt 🇻🇳 và Tiếng Anh 🇬🇧 chỉ với 1 click, tự động lưu session và giữ nguyên ngữ cảnh trang.
- 📄 **Quản lý & Trưng bày Brochure / Giấy khen (`/certificates`):**
  - Chuyên trang trưng bày hồ sơ năng lực, bằng cấp chính quy, chứng chỉ chuyên môn và giải thưởng.
  - Bộ lọc phân loại tức thì (`Tất cả`, `Brochure`, `Bằng cấp`, `Giấy khen`, `Giải thưởng`).
  - **Trình xem tài liệu trực tiếp (Interactive Modal Viewer):** Xem file PDF hoặc ảnh chụp ngay trên web không cần tải lại trang.
  - **Hỗ trợ đính kèm nhiều file PDF (Multi-PDF attachments):** Mỗi mục có thể đính kèm nhiều file PDF (Brochure tiếng Anh, Bảng điểm, Phụ lục đề tài...) kèm nút tải xuống từng file độc lập.
  - Nút **Tải về (Download)** thiết kế dạng SVG sắc nét, đồng bộ và thẩm mỹ cao.
- 🚀 **Trình diễn Dự án Tiêu biểu (`/projects`):**
  - Lọc dự án theo công nghệ (Laravel, Spring Boot, ReactJS, Python/RAG, Docker...).
  - Trang chi tiết từng dự án với gallery ảnh, mô tả tính năng, bài toán kỹ thuật, link Demo trực tiếp và mã nguồn GitHub.
- 💼 **Dòng thời gian Sự nghiệp & Kỹ năng (`/about`):**
  - Timeline kinh nghiệm làm việc chuyên nghiệp (Odoo Developer Intern tại Enmasys, Fullstack Developer tại AIPower...).
  - Ma trận kỹ năng chuyên sâu (Languages, Frontend, Backend, Database, API, Tools & Practices).
  - Khối thành tích danh dự: GPA 4.0/4.0, Tốt nghiệp Xuất sắc, Giải Nhất cuộc thi Sáng tác Logo Khoa CNTT.
- ✍️ **Blog & Chia sẻ Kiến thức (`/blog`):**
  - Hệ thống bài viết công nghệ chuẩn SEO với đường dẫn thân thiện (slugs), thời gian đọc và phân mục.
- 📬 **Form Liên hệ & Chống Spam (`/contact`):**
  - Tích hợp Captcha bảo vệ chống bot tự động, kiểm tra định dạng email và xác thực dữ liệu chặt chẽ.
- ⚡ **SEO & Performance Optimization:**
  - Tự động tạo `sitemap.xml` động đa ngôn ngữ (`xhtml:link alternate`).
  - Đầy đủ thẻ OpenGraph, Twitter Cards, Canonical URLs và Schema.org JSON-LD (Person, WebSite, BreadcrumbList).

---

### 2. Bảng điều khiển Quản trị (Admin CMS Dashboard)
Hệ thống quản trị bảo mật cao qua route `/admin`, cung cấp đầy đủ các thao tác CRUD:
- 📊 **Dashboard Thống kê:** Tổng quan số lượng Dự án, Bài viết blog, Tin nhắn liên hệ, Kỹ năng và Tài liệu/Brochure.
- 📁 **Quản lý Brochure & Giấy khen:**
  - Tải lên file PDF chính hoặc ảnh giấy khen (hỗ trợ file dung lượng lớn đến 25MB).
  - Tải lên nhiều file PDF đính kèm bổ sung cùng lúc (`<input type="file" multiple>`).
  - Tùy chọn ảnh bìa hoặc file PDF xem trước, ghim nổi bật lên trang chủ.
  - Tự động dọn dẹp file rác trên ổ đĩa khi cập nhật hoặc xóa bản ghi.
- 💻 **Quản lý Dự án:** Thêm/sửa dự án, liên kết công nghệ sử dụng, quản lý link demo & repo.
- 📈 **Quản lý Kinh nghiệm & Kỹ năng:** Cập nhật vị trí công tác, thời gian làm việc, mức độ thành thạo và icon công nghệ.
- 📝 **Quản lý Bài viết Blog:** Soạn thảo, lưu nháp, xuất bản và tối ưu SEO tiêu đề/trích đoạn.
- 💬 **Hộp thư Liên hệ:** Xem danh sách phản hồi từ nhà tuyển dụng và đối tác.

---

## 🛠️ Công nghệ sử dụng (Tech Stack)

| Lớp (Layer) | Công nghệ / Thư viện | Mô tả |
| :--- | :--- | :--- |
| **Backend** | **PHP 8.1+ / Laravel** | Kiến trúc MVC, Eloquent ORM, Service Layer, Middleware |
| **Frontend** | **Blade Templates & Vanilla CSS/JS** | Custom Design System, Glassmorphism, Micro-animations, SVG Icons |
| **Database** | **MySQL / SQLite** | MySQL trên môi trường production, SQLite linh hoạt ở môi trường local |
| **Storage** | **Laravel Storage & Symlink** | Quản lý file PDF, tài liệu và hình ảnh với fallback route trực tiếp |
| **SEO & i18n** | **Schema.org, OpenGraph, JSON i18n** | Hỗ trợ song ngữ Tiếng Việt & Tiếng Anh, Dynamic XML Sitemap |
| **DevOps & Hosting** | **Docker, Nginx, cPanel** | Docker Compose phục vụ môi trường containerized, zip script deploy cPanel |

---

## 📂 Cấu trúc thư mục (Project Structure)

```text
CV4.0/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                # Controllers quản trị (Projects, Certificates, Blog...)
│   │   │   │   ├── CertificateController.php
│   │   │   │   ├── DashboardController.php
│   │   │   │   └── ProjectController.php
│   │   │   ├── HomeController.php    # Trang chủ & Featured showcase
│   │   │   ├── PageController.php    # Trang About, Certificates
│   │   │   ├── BlogController.php    # Quản lý Blog
│   │   │   └── ContactController.php # Xử lý gửi tin nhắn & Captcha
│   ├── Models/                       # Eloquent Models (Certificate, Project, Skill...)
│   └── Providers/
├── database/
│   ├── migrations/                   # Lịch sử lược đồ CSDL
│   └── seeders/                      # Dữ liệu mẫu (DatabaseSeeder, CertificateSeeder...)
├── lang/
│   ├── vi.json                       # Bản dịch Tiếng Việt
│   └── en.json                       # Bản dịch Tiếng Anh
├── public/
│   ├── assets/docs/                  # File Brochure PDF sao lưu an toàn
│   ├── css/                          # CSS tối ưu hóa LCP & FontAwesome purged
│   └── images/                       # Logo, avatar & open graph preview
├── resources/views/
│   ├── admin/                        # Giao diện quản trị (CRUD views)
│   │   └── certificates/             # Quản lý Brochure & Giấy khen (Index, Create, Edit)
│   ├── layouts/                      # Layouts chính (app.blade.php, admin.blade.php)
│   ├── pages/                        # Trang chuyên mục (certificates, about, contact)
│   └── welcome.blade.php             # Trang chủ Portfolio
├── routes/
│   └── web.php                       # Định tuyến web & API fallback
├── storage/app/public/certificates/  # Lưu trữ file PDF và ảnh chứng chỉ thực tế
├── zip_script.php                    # Script tự động đóng gói file triển khai cPanel
└── docker-compose.yml                # Cấu hình containerization Docker
```

---

## 🚀 Hướng dẫn cài đặt cục bộ (Local Setup)

### Yêu cầu hệ thống:
- PHP >= 8.1 (với các extensions: `pdo_sqlite`, `pdo_mysql`, `mbstring`, `fileinfo`, `gd`, `zip`)
- Composer >= 2.0
- Node.js & NPM (tùy chọn)

### Các bước cài đặt:

1. **Clone repository về máy:**
   ```bash
   git clone https://github.com/hoThanhThien/CV4.0.git
   cd CV4.0
   ```

2. **Cài đặt các thư viện phụ thuộc:**
   ```bash
   composer install
   ```

3. **Thiết lập file cấu hình môi trường `.env`:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Cấu hình Cơ sở dữ liệu:**
   - Với **SQLite** (khuyên dùng khi chạy test cục bộ):
     Tạo file `database/database.sqlite` trống và cập nhật `.env`:
     ```env
     DB_CONNECTION=sqlite
     # DB_DATABASE=/đường_dẫn_tuyệt_đối/database/database.sqlite
     ```
   - Với **MySQL**: Khai báo thông tin database trong `.env`.

5. **Chạy Migration & Nạp dữ liệu mẫu:**
   ```bash
   php artisan migrate --seed
   ```

6. **Tạo liên kết tượng trưng cho thư mục lưu trữ (Storage Symlink):**
   ```bash
   php artisan storage:link
   ```

7. **Khởi động Local Development Server:**
   ```bash
   php artisan serve
   ```
   Truy cập trình duyệt tại: `http://localhost:8000`  
   Đăng nhập trang quản trị tại: `http://localhost:8000/admin/login`

---

## 📦 Triển khai máy chủ (Deployment)

Hệ thống hỗ trợ script đóng gói tự động phục vụ triển khai lên hosting cPanel / VPS:

```bash
php zip_script.php
```

Script sẽ tự động tạo ra:
- `laravel_core.zip`: Chứa toàn bộ source code ứng dụng đã loại trừ các file rác và bộ nhớ đệm.
- `public_html.zip`: Chứa các tài nguyên công khai đặt tại web root.

Sau khi upload, truy cập route tiện ích để hoàn tất liên kết:
- Đồng bộ CSDL & Storage: `https://your-domain.com/setup-db`

---

## 👤 Tác giả & Liên hệ (Author)

**Hồ Thành Thiện (Thien Ho)**  
*Full-Stack Software Engineer & Problem Solver*

- 🌐 **Website:** [hothanhthien.io.vn](https://hothanhthien.io.vn)
- 🐙 **GitHub:** [@hoThanhThien](https://github.com/hoThanhThien)
- 💼 **LinkedIn:** [linkedin.com/in/httblackhat](https://www.linkedin.com/in/httblackhat)
- 📧 **Email:** [hothanhthien1804@gmail.com](mailto:hothanhthien1804@gmail.com)
- 📱 **Hotline:** 0375.227.764

---

## 📄 Bản quyền (License)

Dự án được phân phối dưới giấy phép **[MIT License](LICENSE)**.
