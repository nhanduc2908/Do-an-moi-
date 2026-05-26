import 'package:dio/dio.dart';
import '../../../../core/utils/logger.dart';

class LoggingInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    Logger.api('REQUEST: ${options.method} ${options.path}');
    Logger.api('Headers: ${options.headers}');
    if (options.data != null) {
      Logger.api('Body: ${options.data}');
    }
    return handler.next(options);
  }

  @override
  void onResponse(Response response, ResponseInterceptorHandler handler) {
    Logger.api('RESPONSE: ${response.statusCode} ${response.requestOptions.path}');
    if (response.data != null) {
      Logger.api('Data: ${response.data}');
    }
    return handler.next(response);
  }

  @override
  void onError(DioException err, ErrorInterceptorHandler handler) {
    Logger.error('ERROR: ${err.message}', err);
    if (err.response != null) {
      Logger.error('Response: ${err.response?.statusCode} ${err.response?.data}');
    }
    return handler.next(err);
  }
}