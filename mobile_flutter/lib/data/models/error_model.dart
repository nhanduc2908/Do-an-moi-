import 'package:freezed_annotation/freezed_annotation.dart';

part 'error_model.freezed.dart';
part 'error_model.g.dart';

@freezed
class ErrorModel with _$ErrorModel {
  const factory ErrorModel({
    String? code,
    String? message,
    String? details,
    @JsonKey(name: 'status_code') int? statusCode,
    dynamic errors,
    String? traceId,
  }) = _ErrorModel;

  factory ErrorModel.fromJson(Map<String, dynamic> json) => _$ErrorModelFromJson(json);
  
  factory ErrorModel.fromException(Exception e) {
    return ErrorModel(
      message: e.toString(),
      code: 'EXCEPTION',
    );
  }
  
  factory ErrorModel.unknown() {
    return ErrorModel(
      message: 'An unknown error occurred',
      code: 'UNKNOWN_ERROR',
    );
  }
  
  factory ErrorModel.network() {
    return ErrorModel(
      message: 'Network connection error. Please check your internet.',
      code: 'NETWORK_ERROR',
    );
  }
  
  factory ErrorModel.unauthorized() {
    return ErrorModel(
      message: 'Unauthorized. Please login again.',
      code: 'UNAUTHORIZED',
      statusCode: 401,
    );
  }
  
  factory ErrorModel.forbidden() {
    return ErrorModel(
      message: 'You do not have permission to access this resource.',
      code: 'FORBIDDEN',
      statusCode: 403,
    );
  }
  
  factory ErrorModel.notFound() {
    return ErrorModel(
      message: 'Resource not found.',
      code: 'NOT_FOUND',
      statusCode: 404,
    );
  }
  
  factory ErrorModel.serverError() {
    return ErrorModel(
      message: 'Server error. Please try again later.',
      code: 'SERVER_ERROR',
      statusCode: 500,
    );
  }
  
  factory ErrorModel.timeout() {
    return ErrorModel(
      message: 'Request timeout. Please try again.',
      code: 'TIMEOUT_ERROR',
    );
  }
}