import 'package:dio/dio.dart';
import '../../../../core/constants/api_constants.dart';
import '../../../../core/utils/secure_storage.dart';
import '../../../../core/constants/storage_keys.dart';
import '../../../../core/utils/logger.dart';

class AuthInterceptor extends Interceptor {
  @override
  Future<void> onRequest(RequestOptions options, RequestInterceptorHandler handler) async {
    final token = await SecureStorage.read(StorageKeys.accessToken);
    
    if (token != null && token.isNotEmpty) {
      options.headers[ApiConstants.authorization] = '${ApiConstants.bearer} $token';
      Logger.api('Added auth token to request: ${options.path}');
    }
    
    return handler.next(options);
  }

  @override
  Future<void> onError(DioException err, ErrorInterceptorHandler handler) async {
    if (err.response?.statusCode == 401) {
      Logger.warning('Token expired or invalid, attempting refresh...');
      
      final refreshToken = await SecureStorage.read(StorageKeys.refreshToken);
      if (refreshToken != null) {
        // Attempt to refresh token
        final success = await _refreshToken(refreshToken);
        if (success) {
          // Retry original request with new token
          final newToken = await SecureStorage.read(StorageKeys.accessToken);
          if (newToken != null) {
            err.requestOptions.headers[ApiConstants.authorization] = '${ApiConstants.bearer} $newToken';
            final cloneReq = await _dio.fetch(err.requestOptions);
            return handler.resolve(cloneReq);
          }
        }
      }
      
      // Clear stored tokens and redirect to login
      await SecureStorage.deleteAll();
    }
    
    return handler.next(err);
  }

  Future<bool> _refreshToken(String refreshToken) async {
    try {
      final dio = Dio();
      final response = await dio.post(
        '${ApiConstants.baseUrl}${ApiConstants.refreshToken}',
        data: {'refresh_token': refreshToken},
      );
      
      if (response.statusCode == 200 && response.data['success'] == true