# Ứng dụng Tính Thuế Thu Nhập Cá Nhân (TNCN)

Đây là một ứng dụng web được xây dựng bằng Laravel, giúp cá nhân quản lý thu nhập, người phụ thuộc và tự động tính toán thuế thu nhập cá nhân theo quy định của pháp luật Việt Nam. Hệ thống cũng cung cấp một bảng điều khiển quản trị mạnh mẽ để quản lý người dùng và cấu hình các tham số thuế.

---

## 1. Tính năng chính

### 1.1. Dành cho Người dùng (Cá nhân nộp thuế)

* **Quản lý Tài khoản:** Đăng ký, đăng nhập, cập nhật thông tin cá nhân (profile).
* **Quản lý Nguồn Thu nhập:** Thêm, xem, sửa, xóa các khoản thu nhập. Mỗi nguồn thu nhập có thể được khai báo theo **tần suất hàng tháng**.
* **Quản lý Người phụ thuộc:** Thêm, xem, sửa, xóa thông tin người phụ thuộc để tính giảm trừ gia cảnh.
* **Tính toán Thuế TNCN theo tháng:** Tự động tổng hợp thu nhập và người phụ thuộc, áp dụng giảm trừ gia cảnh và biểu thuế lũy tiến để tính số thuế phải nộp trong tháng.
* **Lịch sử Khai báo:** Lưu trữ và xem lại các lần tính toán thuế đã thực hiện.
* **Thống kê Thuế cá nhân:** Xem tổng quan thu nhập và thuế đã nộp theo từng năm.

### 1.2. Dành cho Quản trị viên (Admin)

* **Admin Dashboard:** Tổng quan hệ thống với các số liệu thống kê và danh sách người dùng gần đây.
* **Quản lý Người dùng:** Xem, chỉnh sửa (bao gồm cấp/thu hồi quyền Admin, đặt lại mật khẩu), xóa tài khoản người dùng.
* **Cài đặt Tham số Thuế:**
    * **Quản lý Giảm trừ Gia cảnh:** Cập nhật mức giảm trừ bản thân và mức giảm trừ cho mỗi người phụ thuộc.
    * **Quản lý Biểu thuế Lũy tiến:** Xem, thêm, sửa, xóa các bậc thuế thu nhập (mức thu nhập tối thiểu/tối đa, tỷ lệ thuế).

---

## 2. Công nghệ sử dụng

* **Framework:** Laravel (PHP)
* **Frontend:** Blade Templates, Tailwind CSS, Alpine.js (Thông qua Laravel Breeze)
* **Cơ sở dữ liệu:** MySQL (hoặc bất kỳ DB nào Laravel hỗ trợ)

---

## 3. Cài đặt và Chạy ứng dụng

Làm theo các bước dưới đây để thiết lập và chạy ứng dụng trên máy local của bạn.

### 3.1. Yêu cầu hệ thống

* PHP >= 8.2
* Composer
* Node.js & npm (hoặc Yarn)
* MySQL (hoặc cơ sở dữ liệu khác)

### 3.2. Các bước cài đặt

1.  **Clone Repository:**
    ```bash
    git clone <URL_REPOSITORY_CỦA_BẠN>
    cd ten_thu_muc_du_an
    ```

2.  **Cài đặt các dependency của Composer:**
    ```bash
    composer install
    ```

3.  **Cài đặt các dependency của Node.js và build tài nguyên frontend:**
    ```bash
    npm install
    npm run dev # Hoặc npm run build cho môi trường production
    ```

4.  **Tạo và cấu hình file `.env`:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    Mở file `.env` và cấu hình thông tin kết nối cơ sở dữ liệu của bạn:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=laravel_tax_app # Đổi tên database của bạn
    DB_USERNAME=root # Tên người dùng database của bạn
    DB_PASSWORD= # Mật khẩu database của bạn
    ```

5.  **Chạy Migrations và Seed dữ liệu mặc định:**
    ```bash
    php artisan migrate
    ```
    *Lưu ý:* Các migration đã bao gồm dữ liệu mặc định cho `tax_settings` và `tax_brackets`.

6.  **Tạo tài khoản Admin:**
    Để tạo tài khoản Admin, bạn có thể đăng ký một tài khoản bất kỳ trên ứng dụng, sau đó truy cập database và chỉnh sửa cột `is_admin` của tài khoản đó thành `1`.

    * Cách 1 (Thủ công qua PhpMyAdmin/MySQL Workbench): Tìm bảng `users`, tìm tài khoản của bạn và sửa `is_admin` từ `0` thành `1`.
    * Cách 2 (Sử dụng Artisan Tinker):
        ```bash
        php artisan tinker
        >>> $user = App\Models\User::where('email', 'your_email@example.com')->first();
        >>> $user->is_admin = 1;
        >>> $user->save();
        >>> exit;
        ```

7.  **Chạy ứng dụng:**
    ```bash
    php artisan serve
    ```
    Ứng dụng sẽ khả dụng tại `http://127.0.0.1:8000`.

---

## 4. Hướng dẫn sử dụng

### 4.1. Dành cho Người dùng thông thường

1.  **Đăng ký và Đăng nhập:** Truy cập `http://127.0.0.1:8000/register` để tạo tài khoản hoặc `http://127.0.0.1:8000/login` để đăng nhập.
2.  **Dashboard:** Sau khi đăng nhập, bạn sẽ được chuyển hướng đến Dashboard cá nhân, nơi hiển thị tổng quan thu nhập và thuế.
3.  **Quản lý Nguồn Thu nhập:**
    * Vào mục "Nguồn Thu nhập".
    * Nhấn "Thêm mới" để khai báo các khoản thu nhập của bạn.
    * **Lưu ý:** Nhập số tiền thu nhập **hàng tháng** cho từng nguồn.
4.  **Quản lý Người phụ thuộc:**
    * Vào mục "Người Phụ thuộc".
    * Nhấn "Thêm mới" để khai báo thông tin của những người bạn đang nuôi dưỡng.
5.  **Tính Thuế TNCN:**
    * Vào mục "Tính Thuế TNCN".
    * Hệ thống sẽ tự động tổng hợp thu nhập hàng tháng từ các nguồn bạn đã khai báo và số lượng người phụ thuộc.
    * Nhấn "Tính thuế" để xem số thuế TNCN phải nộp trong tháng.
    * Bạn có thể chọn "Lưu khai báo" để ghi lại kết quả này.
6.  **Lịch sử Khai báo & Thống kê Thuế:** Truy cập các mục tương ứng trên thanh điều hướng để xem lại các khai báo đã lưu và biểu đồ thống kê.

### 4.2. Dành cho Quản trị viên (Admin)

1.  **Đăng nhập:** Đăng nhập bằng tài khoản Admin của bạn (`http://127.0.0.1:8000/login`). Hệ thống sẽ tự động chuyển hướng bạn đến Admin Dashboard.
2.  **Admin Dashboard:** Cung cấp cái nhìn tổng quan về trạng thái hệ thống.
3.  **Quản lý Người dùng:**
    * Vào mục "Quản lý Người dùng" trên thanh điều hướng Admin.
    * Tại đây, bạn có thể xem danh sách tất cả người dùng, chỉnh sửa thông tin của họ (bao gồm cả việc cấp hoặc thu hồi quyền Admin) hoặc xóa tài khoản.
4.  **Cài đặt Thuế:**
    * Vào mục "Cài đặt Thuế" trên thanh điều hướng Admin.
    * **Giảm trừ Gia cảnh:** Thay đổi mức giảm trừ bản thân và người phụ thuộc theo quy định mới nhất.
    * **Các Bậc thuế Lũy tiến:** Xem, thêm, sửa hoặc xóa các bậc thuế hiện hành. Mọi thay đổi tại đây sẽ ảnh hưởng trực tiếp đến công thức tính thuế cho toàn bộ người dùng.

---

## 5. Cần trợ giúp?

Nếu bạn gặp bất kỳ vấn đề nào trong quá trình cài đặt hoặc sử dụng, vui lòng kiểm tra tài liệu Laravel hoặc liên hệ với người phát triển.
