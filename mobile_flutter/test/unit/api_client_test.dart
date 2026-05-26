// Đường dẫn: mobile_flutter/test/unit/api_client_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:mockito/mockito.dart';
import 'package:dio/dio.dart';
import 'package:security_evaluation_app/data/datasources/remote/api_client.dart';
import '../helpers/mock_factories.dart';

void main() {
  late ApiClient apiClient;

  setUp(() {
    apiClient = ApiClient();
  });

  group('ApiClient Tests', () {
    test('get method returns response', () async {
      final response = await apiClient.get('/test');
      
      expect(response, isNotNull);
    });

    test('post method returns response', () async {
      final response = await apiClient.post('/test', data: {'key': 'value'});
      
      expect(response, isNotNull);
    });

    test('put method returns response', () async {
      final response = await apiClient.put('/test', data: {'key': 'value'});
      
      expect(response, isNotNull);
    });

    test('delete method returns response', () async {
      final response = await apiClient.delete('/test');
      
      expect(response, isNotNull);
    });

    test('patch method returns response', () async {
      final response = await apiClient.patch('/test', data: {'key': 'value'});
      
      expect(response, isNotNull);
    });
  });
}