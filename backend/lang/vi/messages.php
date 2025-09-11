<?php

use App\Enums\ResponseCode;

return [
    ResponseCode::INTERNAL_SERVER_ERROR => 'Đã xảy ra lỗi, vui lòng liên hệ quản lý hệ thống.',
    ResponseCode::METHOD_NOT_ALLOWED => 'Phương thức yêu cầu không được phép.',
    ResponseCode::NOT_FOUND => 'Không tìm thấy yêu cầu.',
    ResponseCode::BAD_REQUEST => 'Yêu cầu không hợp lệ.',
    ResponseCode::SUCCESS => 'Xử lý thành công.',
    ResponseCode::UNAUTHORIZED => 'Chưa xác thực hoặc mã xác thực không chính xác.',
    'register_fail' => 'Đăng ký không thành công.',
    'login_fail' => 'Đăng nhập không thành công.',
    'refresh_token_fail' => 'Làm mới mã xác thực không thành công.'
];