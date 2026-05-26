import 'dart:io';
import 'package:dio/dio.dart';

class ApiErrorHandler {
  static String getErrorMessage(dynamic error) {
    if (error is DioException) {
      switch (error.type) {
        case DioExceptionType.connectionTimeout:
          return 'Connection timeout. Please try again.';
        case DioExceptionType.sendTimeout:
          return 'Send timeout. Please try again.';
        case DioExceptionType.receiveTimeout:
          return 'Receive timeout. Please try again.';
        case DioExceptionType.badResponse:
          return _handleStatusCode(error.response?.statusCode);
        case DioExceptionType.cancel:
          return 'Request cancelled.';
        default:
          return 'Network error. Please check your connection.';
      }
    } else if (error is SocketException) {
      return 'No internet connection.';
    } else {
      return 'An unexpected error occurred.';
    }
  }

  static String _handleStatusCode(int? statusCode) {
    switch (statusCode) {
      case 400: return 'Bad request.';
      case 401: return 'Unauthorized. Please login again.';
      case 403: return 'Forbidden. You do not have permission.';
      case 404: return 'Resource not found.';
      case 409: return 'Conflict with current state.';
      case 422: return 'Validation failed.';
      case 429: return 'Too many requests. Please try later.';
      case 500: return 'Internal server error.';
      default: return 'An error occurred. Please try again.';
    }
  }
}