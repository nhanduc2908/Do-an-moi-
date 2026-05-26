import 'package:dio/dio.dart';
import '../../../../core/constants/storage_keys.dart';
import '../../../../core/utils/secure_storage.dart';

class LanguageInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) async {
    final language = await SecureStorage.read(StorageKeys.language);
    if (language != null) {
      options.headers['Accept-Language'] = language;
    }
    return handler.next(options);
  }
}