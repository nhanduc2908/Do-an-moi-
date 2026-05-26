class ApiResponse<T> {
  final bool success;
  final T? data;
  final String? message;
  final int? statusCode;
  final Map<String, dynamic>? errors;

  ApiResponse({
    required this.success,
    this.data,
    this.message,
    this.statusCode,
    this.errors,
  });

  factory ApiResponse.fromJson(Map<String, dynamic> json, T Function(dynamic) fromJson) {
    return ApiResponse(
      success: json['success'] ?? false,
      data: json['data'] != null ? fromJson(json['data']) : null,
      message: json['message'],
      statusCode: json['status_code'],
      errors: json['errors'],
    );
  }

  bool get hasData => data != null;
  bool get hasError => !success;
  String get errorMessage => message ?? 'An error occurred';
}