// Đường dẫn: mobile_flutter/test/mocks/mock_api_service.dart

import 'package:mockito/annotations.dart';
import 'package:mockito/mockito.dart';
import 'package:security_evaluation_app/data/datasources/remote/api_service.dart';

@GenerateMocks([ApiService])

class MockApiService extends Mock implements ApiService {
  static MockApiService create() {
    final mock = MockApiService();
    
    when(mock.get(any)).thenAnswer((_) async => {'success': true, 'data': {}});
    when(mock.post(any)).thenAnswer((_) async => {'success': true, 'data': {}});
    when(mock.put(any)).thenAnswer((_) async => {'success': true, 'data': {}});
    when(mock.delete(any)).thenAnswer((_) async => {'success': true});
    
    return mock;
  }
}