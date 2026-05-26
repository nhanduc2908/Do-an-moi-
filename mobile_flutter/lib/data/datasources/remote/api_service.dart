import 'dart:convert';
import 'package:dio/dio.dart';
import 'api_client.dart';
import '../../../core/constants/api_constants.dart';
import '../../../core/utils/logger.dart';

class ApiService {
  final Dio _dio;

  ApiService(this._dio);

  Future<Map<String, dynamic>> get(String endpoint, {Map<String, dynamic>? params}) async {
    try {
      final response = await _dio.get(endpoint, queryParameters: params);
      return _handleResponse(response);
    } on DioException catch (e) {
      return _handleError(e);
    }
  }

  Future<Map<String, dynamic>> post(String endpoint, {dynamic data, Map<String, dynamic>? params}) async {
    try {
      final response = await _dio.post(endpoint, data: data, queryParameters: params);
      return _handleResponse(response);
    } on DioException catch (e) {
      return _handleError(e);
    }
  }

  Future<Map<String, dynamic>> put(String endpoint, {dynamic data, Map<String, dynamic>? params}) async {
    try {
      final response = await _dio.put(endpoint, data: data, queryParameters: params);
      return _handleResponse(response);
    } on DioException catch (e) {
      return _handleError(e);
    }
  }

  Future<Map<String, dynamic>> patch(String endpoint, {dynamic data, Map<String, dynamic>? params}) async {
    try {
      final response = await _dio.patch(endpoint, data: data, queryParameters: params);
      return _handleResponse(response);
    } on DioException catch (e) {
      return _handleError(e);
    }
  }

  Future<Map<String, dynamic>> delete(String endpoint, {Map<String, dynamic>? params}) async {
    try {
      final response = await _dio.delete(endpoint, queryParameters: params);
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