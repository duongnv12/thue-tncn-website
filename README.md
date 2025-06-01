# Hệ thống Quản lý và Tính toán Thuế Thu nhập Cá nhân (TNCN) Toàn diện

Đây là một ứng dụng web hiện đại được xây dựng trên nền tảng Laravel, cung cấp một giải pháp tự động và thông minh để quản lý thu nhập, người phụ thuộc và tính toán nghĩa vụ Thuế Thu nhập Cá nhân (TNCN) tại Việt Nam. Mục tiêu của hệ thống là đơn giản hóa quy trình khai báo thuế vốn phức tạp, mang lại sự minh bạch và dễ hiểu cho từng khoản thu nhập và nghĩa vụ thuế của cá nhân.

---

## Các Tính năng nổi bật

### **1. Tính toán Thuế TNCN tự động và Minh bạch**
* **Đồng bộ Thu nhập (Gross) tức thì:** Hệ thống tự động tổng hợp tất cả các khoản thu nhập (Gross) mà người dùng đã khai báo từ các nguồn khác nhau. Ngay lập tức, bạn sẽ thấy kết quả tính toán thuế mà không cần bất kỳ thao tác thủ công phức tạp nào.
* **Phân tích chi tiết các khoản giảm trừ:**
    * **Bảo hiểm bắt buộc:** Tự động tính toán các khoản đóng Bảo hiểm xã hội (BHXH), Bảo hiểm y tế (BHYT), Bảo hiểm thất nghiệp (BHTN) theo tỷ lệ quy định hiện hành, dựa trên mức thu nhập của bạn hoặc mức trần bảo hiểm.
    * **Giảm trừ gia cảnh:** Áp dụng mức giảm trừ bản thân và giảm trừ cho người phụ thuộc theo quy định của pháp luật Việt Nam, giúp bạn tối ưu hóa số thuế phải nộp.
* **Biểu thuế lũy tiến rõ ràng:** Hệ thống sẽ phân tích thu nhập tính thuế của bạn và áp dụng đúng biểu thuế lũy tiến từng phần, hiển thị chi tiết số thuế phải nộp cho từng bậc, đảm bảo bạn hiểu rõ cách tính toán cuối cùng.
* **Hiển thị Lương Net:** Ngoài số thuế phải nộp, ứng dụng còn tính toán và hiển thị ước tính lương thực nhận (Net) của bạn sau khi đã trừ đi các khoản bảo hiểm và thuế TNCN, giúp bạn có cái nhìn toàn diện về tài chính cá nhân.

### **2. Quản lý dữ liệu tài chính cá nhân hiệu quả**
* **Quản lý Nguồn thu nhập linh hoạt:** Dễ dàng thêm mới, chỉnh sửa hoặc xóa các nguồn thu nhập của bạn (ví dụ: lương chính, thu nhập phụ, tiền thưởng,...). Điều này giúp hệ thống luôn cập nhật thông tin tài chính cá nhân chính xác nhất.
* **Quản lý Người phụ thuộc dễ dàng:** Khai báo và theo dõi thông tin chi tiết của người phụ thuộc (con cái, cha mẹ, vợ/chồng không có khả năng lao động,...). Mỗi người phụ thuộc hợp lệ sẽ được tính giảm trừ gia cảnh, trực tiếp ảnh hưởng đến số thuế bạn phải nộp.

### **3. Lưu trữ và Tra cứu Lịch sử khai báo**
* **Lưu trữ khai báo tháng:** Mọi kết quả tính toán thuế TNCN hàng tháng đều có thể được lưu lại vào lịch sử cá nhân của bạn, giúp bạn dễ dàng theo dõi sự biến động thu nhập và nghĩa vụ thuế qua thời gian.
* **Xem chi tiết mọi lúc, mọi nơi:** Truy cập và xem lại bất kỳ bản khai báo thuế nào đã lưu với đầy đủ các thông số tính toán, chi tiết các khoản giảm trừ và bậc thuế áp dụng tại thời điểm khai báo.
* **Xuất PDF chuyên nghiệp:** Chức năng xuất file PDF cho phép bạn tạo ra các bản báo cáo khai báo thuế chính thức, dễ dàng in ấn, lưu trữ offline hoặc gửi cho các bên liên quan khi cần thiết.

### **4. Thống kê và Báo cáo Trực quan**
* **Tổng quan tài chính hàng năm:** Cung cấp cái nhìn tổng hợp về tổng thu nhập và tổng số thuế TNCN đã nộp trong từng năm.
* **Phân tích xu hướng hàng tháng:** Biểu đồ và bảng thống kê chi tiết theo từng tháng giúp bạn dễ dàng nhận diện xu hướng thu nhập và nghĩa vụ thuế của mình trong một năm cụ thể.
* **Chọn năm linh hoạt:** Dễ dàng chuyển đổi giữa các năm để xem thống kê, giúp bạn so sánh và phân tích dữ liệu lịch sử một cách thuận tiện.

---

## Công nghệ sử dụng

* **Framework chính:** Laravel (PHP 10+)
* **Cơ sở dữ liệu:** MySQL (hỗ trợ các hệ quản trị CSDL khác tương thích với Laravel Eloquent)
* **Giao diện người dùng:** Blade Templates kết hợp với Tailwind CSS cho thiết kế hiện đại và responsive.
* **Thư viện tạo PDF:** Barryvdh/laravel-dompdf, đảm bảo xuất file PDF chất lượng cao.
* **Quản lý Dependencies:** Composer (cho PHP) và npm/Yarn (cho JavaScript/CSS).

---

## Hướng dẫn cài đặt và khởi chạy

Để cài đặt và trải nghiệm ứng dụng trên môi trường phát triển cục bộ, bạn chỉ cần thực hiện theo các bước sau:

1.  **Clone Repository:** Tải mã nguồn về máy tính của bạn.
    ```bash
    git clone <đường_dẫn_đến_repository_của_bạn>
    cd <tên_thư_mục_dự_án>
    ```

2.  **Cài đặt Composer Dependencies:**
    ```bash
    composer install
    ```

3.  **Cài đặt Node.js Dependencies (cho Tailwind CSS):**
    ```bash
    npm install
    # Hoặc nếu bạn dùng Yarn
    yarn install
    ```

4.  **Tạo và Cấu hình file `.env`:**
    Sao chép file cấu hình mẫu và cập nhật các thông số cần thiết như thông tin kết nối cơ sở dữ liệu (`DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`), và URL ứng dụng (`APP_URL`).
    ```bash
    cp .env.example .env
    ```

5.  **Tạo App Key:**
    ```bash
    php artisan key:generate
    ```

6.  **Chuẩn bị Database:**
    Đảm bảo bạn đã tạo một cơ sở dữ liệu trống trên MySQL (hoặc CSDL bạn lựa chọn) và thông tin đăng nhập đã được cập nhật chính xác trong file `.env`.

7.  **Chạy Migrations và Seeder (nếu có):**
    Thao tác này sẽ tạo cấu trúc bảng cần thiết trong cơ sở dữ liệu và điền các dữ liệu mặc định ban đầu (ví dụ: các cài đặt thuế cơ bản, thông tin người dùng mẫu nếu có).
    ```bash
    php artisan migrate --seed
    ```
    *Lưu ý:* Nếu bạn không có seeder dữ liệu mẫu, chỉ cần chạy `php artisan migrate`.

8.  **Compile Assets (CSS/JS):**
    Biên dịch các file CSS và JavaScript. Sử dụng `npm run watch` trong quá trình phát triển để tự động cập nhật khi có thay đổi.
    ```bash
    npm run dev
    # Hoặc để theo dõi thay đổi tự động
    npm run watch
    # Hoặc để build cho môi trường sản phẩm
    npm run build
    ```

9.  **Khởi chạy Ứng dụng Laravel:**
    ```bash
    php artisan serve
    ```
    Ứng dụng của bạn giờ đây sẽ có thể truy cập qua trình duyệt tại địa chỉ `http://127.0.0.1:8000` (hoặc một cổng khác tùy thuộc vào cấu hình của bạn).

---

## Các Đường dẫn (Routes) chính

* `/dashboard`: Trang tổng quan sau khi đăng nhập, hiển thị thông tin chung và điều hướng.
* `/income-sources`: Quản lý và theo dõi tất cả các nguồn thu nhập của bạn.
* `/dependents`: Khai báo và quản lý thông tin người phụ thuộc.
* `/tax-calculation`: Công cụ tính toán thuế TNCN tự động theo thời gian thực.
* `/tax-declarations`: Xem lịch sử đầy đủ các bản khai báo thuế đã lưu.
* `/tax-declarations/{id}`: Truy cập vào trang chi tiết của một bản khai báo thuế cụ thể.
* `/tax-declarations/{id}/pdf`: Tải xuống bản PDF của một khai báo thuế đã chọn.
* `/tax-declarations/statistics`: Trang thống kê tổng quan về thu nhập và thuế TNCN theo thời gian.

---

## Đóng góp và Hỗ trợ

Chúng tôi luôn hoan nghênh mọi ý kiến đóng góp để phát triển và cải thiện ứng dụng này. Nếu bạn phát hiện lỗi, có đề xuất tính năng mới hoặc muốn đóng góp mã nguồn, vui lòng:
* Tạo một **Issue** trên GitHub để báo cáo lỗi hoặc đề xuất.
* Gửi một **Pull Request** với những cải tiến hoặc tính năng mới của bạn.

---

## Giấy phép

Ứng dụng này được phát hành dưới Giấy phép MIT. Vui lòng xem file `LICENSE` để biết thêm chi tiết.

---
