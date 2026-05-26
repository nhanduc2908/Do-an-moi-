import 'dart:typed_data';
import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart';

class CompressInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) async {
    if (options.data != null && options.data is Map) {
      // Compress request data if needed
      options.headers['Accept-Encoding'] = 'gzip, deflate';
    }
    return handler.next(options);
  }

  @override
  void onResponse(Response response, ResponseInterceptorHandler handler) async {
    if (response.headers.value('content-encoding') == 'gzip') {
      // Decompress if needed
    }
    return handler.next(response);
  }
}