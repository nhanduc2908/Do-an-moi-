import 'dart:convert';
import '../../core/constants/api_constants.dart';

class ApiRequestBuilder {
  final Map<String, String> _headers = {};
  dynamic _body;
  Map<String, dynamic>? _queryParams;

  ApiRequestBuilder() {
    _headers[ApiConstants.contentType] = ApiConstants.applicationJson;
  }

  ApiRequestBuilder addAuthToken(String token) {
    _headers[ApiConstants.authorization] = '${ApiConstants.bearer} $token';
    return this;
  }

  ApiRequestBuilder addHeader(String key, String value) {
    _headers[key] = value;
    return this;
  }

  ApiRequestBuilder setBody(dynamic body) {
    _body = body;
    return this;
  }

  ApiRequestBuilder addQueryParam(String key, dynamic value) {
    _queryParams ??= {};
    _queryParams![key] = value;
    return this;
  }

  ApiRequestBuilder addQueryParams(Map<String, dynamic> params) {
    _queryParams ??= {};
    _queryParams!.addAll(params);
    return this;
  }

  Map<String, String> buildHeaders() => _headers;
  dynamic buildBody() => _body;
  Map<String, dynamic>? buildQueryParams() => _queryParams;
}