import 'package:json_annotation/json_annotation.dart';
import 'package:freezed_annotation/freezed_annotation.dart';

part 'api_response_model.freezed.dart';
part 'api_response_model.g.dart';

@freezed
class ApiResponseModel<T> with _$ApiResponseModel<T> {
  const factory ApiResponseModel({
    bool? success,
    String? message,
    T? data,
    dynamic errors,
    @JsonKey(name: 'status_code') int? statusCode,
  }) = _ApiResponseModel<T>;

  factory ApiResponseModel.fromJson(
    Map<String, dynamic> json,
    T Function(Object? json) fromJsonT,
  ) => _$ApiResponseModelFromJson(json, fromJsonT);
}

extension ApiResponseModelX<T> on ApiResponseModel<T> {
  bool get isSuccess => success == true && statusCode != null && statusCode! >= 200 && statusCode! < 300;
  bool get isError => success == false || (statusCode != null && statusCode! >= 400);
  
  String get errorMessage {
    if (message != null && message!.isNotEmpty) return message!;
    if (errors != null) {
      if (errors is String) return errors;
      if (errors is Map) return errors.values.join(', ');
    }
    return 'Đã xảy ra lỗi';
  }
}