<?php

return [
    'password_policy' => [
        'min_length' => 'Mật khẩu phải có ít nhất :min ký tự.',
        'require_uppercase' => 'Mật khẩu phải chứa ít nhất một chữ hoa.',
        'require_lowercase' => 'Mật khẩu phải chứa ít nhất một chữ thường.',
        'require_numbers' => 'Mật khẩu phải chứa ít nhất một số.',
        'require_special' => 'Mật khẩu phải chứa ít nhất một ký tự đặc biệt.',
        'expired' => 'Mật khẩu của bạn đã hết hạn. Vui lòng đổi mật khẩu.',
        'recently_used' => 'Bạn đã sử dụng mật khẩu này gần đây. Vui lòng chọn mật khẩu mới.',
    ],
    'mfa' => [
        'setup_title' => 'Thiết lập Xác thực Hai Yếu tố',
        'scan_qr' => 'Quét mã QR bằng ứng dụng xác thực của bạn',
        'manual_code' => 'Hoặc nhập mã này thủ công:',
        'verify_title' => 'Xác minh Mã Xác thực',
        'backup_codes' => 'Lưu mã dự phòng của bạn',
        'backup_codes_warning' => 'Lưu trữ các mã dự phòng này ở nơi an toàn. Mỗi mã chỉ có thể sử dụng một lần.',
    ],
    'session' => [
        'expired' => 'Phiên của bạn đã hết hạn do không hoạt động.',
        'multiple_devices' => 'Bạn đang đăng nhập trên nhiều thiết bị.',
        'logout_all' => 'Đăng xuất khỏi tất cả thiết bị',
    ],
    'encryption' => [
        'key_rotated' => 'Khóa mã hóa đã được xoay thành công.',
        'key_revoked' => 'Khóa mã hóa đã bị thu hồi.',
        'decryption_failed' => 'Giải mã dữ liệu thất bại. Khóa có thể không hợp lệ hoặc bị hỏng.',
    ],
];