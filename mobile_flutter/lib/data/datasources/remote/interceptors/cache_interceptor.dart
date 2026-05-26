import 'package:dio/dio.dart';
import '../../../datasources/local/cache_manager.dart';
import '../../../../core/utils/logger.dart';

class CacheInterceptor extends Interceptor {
  final CacheManager _cacheManager;

  CacheInterceptor(this._cacheManager);

  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) async {
    if (options.method == 'GET') {
      final cached = await _cacheManager.get(options.path);
      if (cached != null) {
        Logger.debug('Cache hit for: ${options.path}');
        return handler.resolve(
          Response(
            requestOptions: options,
            data: cached,
            statusCode: 200,
          ),
        );
      }
    }
    return handler.next(options);
  }

  @override
  void onResponse(Response response, ResponseInterceptorHandler handler) async {
    if (response.requestOptions.method == 'GET' && response.statusCode == 200) {
      await _cacheManager.set(response.requestOptions.path, response.data);
      Logger.debug('Cached response for: ${response.requestOptions.path}');
    }
    return handler.next(response);
  }
}