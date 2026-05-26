import 'package:dio/dio.dart';
import '../../../../core/utils/logger.dart';

class RetryInterceptor extends Interceptor {
  final int maxRetries = 3;
  final Duration retryDelay = Duration(seconds: 1);

  @override
  Future<void> onError(DioException err, ErrorInterceptorHandler handler) async {
    final requestOptions = err.requestOptions;
    final shouldRetry = _shouldRetry(err);

    if (shouldRetry && requestOptions.extra['retryCount'] != null) {
      final retryCount = requestOptions.extra['retryCount'] as int;
      if (retryCount < maxRetries) {
        Logger.debug('Retrying request: ${requestOptions.path}, attempt ${retryCount + 1}');
        await Future.delayed(retryDelay * (retryCount + 1));
        
        final newRequest = await _retry(requestOptions, retryCount + 1);
        return handler.resolve(newRequest);
      }
    }
    
    return handler.next(err);
  }

  bool _shouldRetry(DioException err) {
    return err.type == DioExceptionType.connectionTimeout ||
        err.type == DioExceptionType.receiveTimeout ||
        err.type == DioExceptionType.sendTimeout ||
        (err.response?.statusCode != null && err.response!.statusCode! >= 500);
  }

  Future<Response> _retry(RequestOptions requestOptions, int retryCount) async {
    final clone = requestOptions.copyWith(
      extra: {'retryCount': retryCount},
    );
    return Dio().fetch(clone);
  }
}