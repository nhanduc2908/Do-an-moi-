import 'package:dio/dio.dart';
import '../../../../core/utils/logger.dart';

class TimeoutInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    options.connectTimeout = const Duration(seconds: 30);
    options.receiveTimeout = const Duration(seconds: 30);
    options.sendTimeout = const Duration(seconds: 30);
    
    Logger.api('Request timeout set: ${options.path}');
    return handler.next(options);
  }

  @override
  void onError(DioException err, ErrorInterceptorHandler handler) {
    if (err.type == DioExceptionType.connectionTimeout ||
        err.type == DioExceptionType.receiveTimeout ||
        err.type == DioExceptionType.sendTimeout) {
      Logger.error('Request timeout: ${err.requestOptions.path}', err);
    }
    return handler.next(err);
  }
}