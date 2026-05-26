import 'dart:convert';
import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart';
import '../../../core/constants/api_constants.dart';
import '../../../core/utils/logger.dart';
import 'interceptors/auth_interceptor.dart';
import 'interceptors/logging_interceptor.dart';
import 'interceptors/retry_interceptor.dart';
import 'interceptors/timeout_interceptor.dart';

class ApiClient {
  late Dio _dio;
  
  ApiClient() {
    _dio = Dio(BaseOptions(
      baseUrl: ApiConstants.baseUrl,
      connectTimeout: Duration(milliseconds: ApiConstants.connectTimeout),
      receiveTimeout: Duration(milliseconds: ApiConstants.receiveTimeout),
      headers: {
        ApiConstants.contentType: ApiConstants.applicationJson,
        ApiConstants.accept: ApiConstants.applicationJson,
      },
    ));
    
    _dio.interceptors.addAll([
      AuthInterceptor(),
      LoggingInterceptor(),
      RetryInterceptor(),
      TimeoutInterceptor(),
    ]);
  }
  
  Dio get dio => _dio;
  
  Future<Map<String, dynamic>> get(String path, {Map<String, dynamic>? queryParams}) async {
    try {
      final response = await _dio.get(path, queryParameters: queryParams);
      return _handleResponse(response);
    } on DioException catch (e) {
      return _handleError(e);
    }
  }
  
  Future<Map<String, dynamic>> post(String path, {dynamic data, Map<String, dynamic>? queryParams}) async {
    try {
      final response = await _dio.post(path, data: data, queryParameters: queryParams);
      return _handleResponse(response);
    } on DioException catch (e) {
      return _handleError(e);
    }
  }
  
  Future<Map<String, dynamic>> put(String path, {dynamic data, Map<String, dynamic>? queryParams}) async {
    try {
      final response = await _dio.put(path, data: data, queryParameters: queryParams);
      return _handleResponse(response);
    } on DioException catch (e) {
      return _handleError(e);
    }
  }
  
  Future<Map<String, dynamic>> delete(String path, {Map<String, dynamic>? queryParams}) async {
    try {
      final response = await _dio.delete(path, queryParameters: queryParams);
      return _handleResponse(response);
    } on DioException catch (e) {
      return _handleError(e);
    }
  }
  
  Future<Map<String, dynamic>> patch(String path, {dynamic data, Map<String, dynamic>? queryParams}) async {
    try {
      final response = await _dio.patch(path, data: data, queryParameters: queryParams);
      return _handleResponse(response);
    } on DioException catch (e) {
      return _handleError(e);
    }
  }
  
  Future<Response> download(String url, String savePath, {ProgressCallback? onReceiveProgress}) async {
    return await _dio.download(url, savePath, onReceiveProgress: onReceiveProgress);
  }
  
  Map<String, dynamic> _handleResponse(Response response) {
    if (response.statusCode != null && response.statusCode! >= 200 && response.statusCode! < 300) {
      if (response.data is Map) {
        return response.data;
      } else if (response.data is String) {
        return jsonDecode(response.data);
      }
      return response.data ?? {};
    }
    throw DioException(
      requestOptions: response.requestOptions,
      response: response,
      type: DioExceptionType.badResponse,
    );
  }
  
  Map<String, dynamic> _handleError(DioException error) {
    Logger.api('API Error: ${error.message}');
    
    if (error.response != null) {
      return {
        'success': false,
        'message': error.response?.data?['message'] ?? error.message,
        'status_code': error.response?.statusCode,
        'errors': error.response?.data?['errors'],
      };
    }
    
    return {
      'success': false,
      'message': error.message,
      'status_code': 500,
    };
  }
}